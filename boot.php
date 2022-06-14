<?php
if(\rex::isBackend() && is_object(\rex::getUser())) {
	// Correct name of rights
	rex_perm::register('d2u_helper[]', rex_i18n::msg('d2u_helper_rights_all'));
	rex_perm::register('d2u_helper[settings]', rex_i18n::msg('d2u_helper_rights_settings'), rex_perm::OPTIONS);

	rex_view::addCssFile($this->getAssetsUrl('d2u_helper_backend.css'));
	//rex_view::addJsFile($addon->getAssetsUrl('js/script.js'), [rex_view::JS_IMMUTABLE => true]);
}

if(!\rex::isBackend()) {
    rex_extension::register('PACKAGES_INCLUDED', function ($params) {
		// If CSS or JS is requested
		if (rex_request('d2u_helper', 'string') == 'helper.css') {
			sendD2UHelperCSS();
		}
		else if (rex_request('d2u_helper', 'string') == 'helper.js') {
			if(rex_request('position', 'string') == "head") {
				sendD2UHelperJS("head");
			}
			else {
				sendD2UHelperJS("body");
			}
		}
		else if (rex_request('d2u_helper', 'string') == 'template.css') {
			sendD2UHelperTemplateCSS(rex_request('template_id', 'string', ''));
		}
		else if (rex_request('d2u_helper', 'string') == 'custom.css') {
			sendD2UHelperCustomCSS();
		}
    });
	
	// Only frontend call
	rex_extension::register('OUTPUT_FILTER', 'appendToPageD2UHelperFiles');
	
	if(rex_config::get("d2u_helper", "article_id_privacy_policy", 0) > 0 || rex_config::get("d2u_helper", "article_id_impress", 0) > 0) {
		// Try to replace as last one, esp. after sprog calls OUTPUT_FILTER
		rex_extension::register('PACKAGES_INCLUDED', function () {
			rex_extension::register('OUTPUT_FILTER', 'replace_privacy_policy_links');
		}, rex_extension::LATE);
	}
}
else {
	rex_extension::register('ART_PRE_DELETED', 'rex_d2u_helper_article_is_in_use');
	rex_extension::register('CLANG_DELETED', 'rex_d2u_helper_clang_deleted');
	rex_extension::register('MEDIA_IS_IN_USE', 'rex_d2u_helper_media_is_in_use');
}

/**
 * Adds some style and script stuff to the header
 * @param rex_extension_point $ep Redaxo extension point
 */
function appendToPageD2UHelperFiles(rex_extension_point $ep) {
	$VERSION_BOOTSTRAP = '4.6.1';
	$addon = rex_addon::get("d2u_helper");
	
	// If insertion should be prevented, detect class "prevent_d2u_helper_styles"
	if(strpos($ep->getSubject(), 'prevent_d2u_helper_styles') !== FALSE) {
		return;
	}

	$insert_head = "";
	$insert_body = "";
	// Vor dem </head> einfügen
	if($addon->getConfig('include_jquery') == 'true') {
		// JQuery
		$file = 'jquery.min.js';
		$insert_head .= '<script src="'. rex_url::coreAssets($file) .'?buster='. filemtime(rex_path::coreAssets($file)) .'"></script>' . PHP_EOL;
	}
	if($addon->getConfig('include_bootstrap4') == 'true') {
		// Bootstrap CSS
		$insert_head .= '<link rel="stylesheet" type="text/css" href="'.  $addon->getAssetsUrl('bootstrap4/bootstrap.min.css') .'?v='. $VERSION_BOOTSTRAP .'" />' . PHP_EOL;
	}

	// Consider module css or menu css
	if(($addon->getConfig("include_module", "false") == "true" && d2u_addon_frontend_helper::getModulesCSS() != "")	|| $addon->getConfig("include_menu") != "none") {
		$insert_head .= '<link rel="stylesheet" type="text/css" href="/index.php?d2u_helper=helper.css" />' . PHP_EOL;
	}
		
	// Menu stuff in header
	if($addon->getConfig("include_menu") != "none") {
		$insert_head .= '<script src="/index.php?position=head&amp;d2u_helper=helper.js"></script>' . PHP_EOL;
	}

	$ep->setSubject(str_replace('</head>', $insert_head .'</head>', $ep->getSubject()));

	// Vor dem </body> einfügen
	if($addon->getConfig('include_bootstrap4') == 'true') {
		$insert_body .= '<script src="'. $addon->getAssetsUrl('bootstrap4/bootstrap.bundle.min.js') .'?v='. $VERSION_BOOTSTRAP .'"></script>' . PHP_EOL;
	}

	// Module stuff in body
	if($addon->hasConfig("include_module") && $addon->getConfig("include_module") == "true" && d2u_addon_frontend_helper::getModulesJS()) {
		$insert_body .= '<script src="/index.php?position=body&amp;d2u_helper=helper.js"></script>' . PHP_EOL;
	}
	$ep->setSubject(str_replace('</body>', $insert_body .'</body>', $ep->getSubject()));
}

/**
 * Replaces +++LINK_PRIVACY_POLICY+++ and +++LINK_IMPRESS+++ with URLs
 * for privacy policy an impress. The article ids are set in D2U Helper settings.
 * @param rex_extension_point $ep Redaxo extension point
 */
function replace_privacy_policy_links(rex_extension_point $ep) {
	$content = $ep->getSubject();

	if(rex_config::get("d2u_helper", "article_id_privacy_policy", 0) > 0) {
		$content = str_replace('+++LINK_PRIVACY_POLICY+++', rex_getUrl(rex_config::get("d2u_helper", "article_id_privacy_policy")), $content);
	}
	if(rex_config::get("d2u_helper", "article_id_impress", 0) > 0) {
		$content = str_replace('+++LINK_IMPRESS+++', rex_getUrl(rex_config::get("d2u_helper", "article_id_impress")), $content);
	}

	$ep->setSubject($content);
}

/**
 * Checks if article is used by this addon
 * @param rex_extension_point $ep Redaxo extension point
 * @return string Warning message as array
 * @throws rex_api_exception If article is used
 */
function rex_d2u_helper_article_is_in_use(rex_extension_point $ep) {
	$warning = [];
	$params = $ep->getParams();
	$article_id = $params['id'];
	
	// Settings
	$addon = rex_addon::get("d2u_helper");
	if(($addon->hasConfig("article_id_privacy_policy") && $addon->getConfig("article_id_privacy_policy") == $article_id) ||
		($addon->hasConfig("article_id_impress") && $addon->getConfig("article_id_impress") == $article_id) ||
		in_array($article_id, is_array(explode(',', $addon->getConfig("cta_box_article_ids"))) ? explode(',', $addon->getConfig("cta_box_article_ids")) : []) ) {
		$message = '<a href="index.php?page=d2u_helper/settings">'.
			 rex_i18n::msg('d2u_helper_rights_all') ." - ". rex_i18n::msg('d2u_helper_settings') . '</a>';
		if(!in_array($message, $warning)) {
			$warning[] = $message .'<br>';
		}
	}

	if(count($warning) > 0) {
		throw new rex_api_exception(rex_i18n::msg('d2u_helper_rex_article_cannot_delete')."<ul><li>". implode("</li><li>", $warning) ."</li></ul>");
	}
	else {
		return "";
	}
}

/**
 * Deletes language specific configurations and objects
 * @param rex_extension_point $ep Redaxo extension point
 * @return string[] Warning message as array
 */
function rex_d2u_helper_clang_deleted(rex_extension_point $ep) {
	$warning = $ep->getSubject();
	$params = $ep->getParams();
	$clang_id = $params['id'];

	// Correct settings
	if(rex_config::get('d2u_helper', 'default_lang', 0) == $clang_id) {
		rex_config::set('d2u_helper', 'default_lang', rex_clang::getStartId());
	}

	return $warning;
}

/**
 * Checks if media is used by this addon
 * @param rex_extension_point $ep Redaxo extension point
 * @return string[] Warning message as array
 */
function rex_d2u_helper_media_is_in_use(rex_extension_point $ep) {
	$warning = $ep->getSubject();
	$params = $ep->getParams();
	$filename = addslashes($params['filename']);

	if($filename) {
		// Settings
		$addon = rex_addon::get("d2u_helper");
		$is_in_use = FALSE;
		if(($addon->hasConfig("template_header_pic") && $addon->getConfig("template_header_pic") == $filename) ||
				($addon->hasConfig("template_logo") && $addon->getConfig("template_logo") == $filename) ||
				($addon->hasConfig("template_print_header_pic") && $addon->getConfig("template_print_header_pic") == $filename) ||
				($addon->hasConfig("template_print_footer_pic") && $addon->getConfig("template_print_footer_pic") == $filename) ||
				($addon->hasConfig("footer_logo") && $addon->getConfig("footer_logo") == $filename) ||
				($addon->hasConfig("template_03_2_header_pic") && $addon->getConfig("template_03_2_header_pic") == $filename) ||
				($addon->hasConfig("template_03_2_footer_pic") && $addon->getConfig("template_03_2_footer_pic") == $filename) ||
				($addon->hasConfig("footer_facebook_icon") && $addon->getConfig("footer_facebook_icon") == $filename) ||
				($addon->hasConfig("custom_css") && $addon->getConfig("custom_css") == $filename)
			) {
				$is_in_use = TRUE;
		}
		foreach(rex_clang::getAllIds() as $clang_id) {
			if(($addon->hasConfig('template_04_header_slider_pics_clang_'. $clang_id) && strpos($addon->getConfig('template_04_header_slider_pics_clang_'. $clang_id), $filename) !== FALSE)) {
				$is_in_use = TRUE;
			}
		}
		if($is_in_use) {
			$message = '<a href="javascript:openPage(\'index.php?page=d2u_helper/settings\')">'.
				 rex_i18n::msg('d2u_helper_meta_title') ." ". rex_i18n::msg('d2u_helper_settings') . '</a>';
			if(!in_array($message, $warning)) {
				$warning[] = $message.'<br>';
			}
		}

		// Templates
		if(rex_config::get("d2u_helper", "check_media_template", FALSE)) {
			$sql_template = \rex_sql::factory();
			$query = 'SELECT DISTINCT id, name FROM ' . rex::getTablePrefix() . 'template WHERE content REGEXP ' . $sql_template->escape('(^|[^[:alnum:]+_-])'. $filename);
			$sql_template->setQuery($query);

			// Prepare warnings for templates
			for($i = 0; $i < $sql_template->getRows(); $i++) {
				$message = '<a href="javascript:openPage(\''. rex_url::backendPage('templates', ['template_id' => $sql_template->getValue('id'), 'function' => 'edit']) .'\')">'. rex_i18n::msg('header_template') .': '. $sql_template->getValue('name') .'</a>';
				if(!in_array($message, $warning)) {
					$warning[] = $message.'<br>';
				}
				$sql_template->next();
			}
		}
	}

	return $warning;
}

/**
 * Sends CSS file and exits PHP Script. The CSS file consists of module and menu
 * css.
 */
function sendD2UHelperCSS() {
	header('Content-type: text/css');
	$d2u_helper = rex_addon::get('d2u_helper');
	$css = "";
	// Module CSS
	if($d2u_helper->hasConfig("include_module") && $d2u_helper->getConfig("include_module") == "true") {
		$css .= d2u_addon_frontend_helper::getModulesCSS();
	}

	// Include menu CSS
	if($d2u_helper->getConfig("include_menu") == "megamenu") {
		$css .= d2u_addon_frontend_helper::prepareCSS(d2u_mobile_navi_mega_menu::getAutoCSS());
	}
	else if($d2u_helper->getConfig("include_menu") == "multilevel") {
		$css .= d2u_addon_frontend_helper::prepareCSS(d2u_mobile_navi::getAutoCSS());
	}
	else if($d2u_helper->getConfig("include_menu") == "slicknav") {
		$css .= d2u_addon_frontend_helper::prepareCSS(d2u_mobile_navi_slicknav::getAutoCSS());
	}
	else if($d2u_helper->getConfig("include_menu") == "smartmenu") {
		$css .= d2u_addon_frontend_helper::prepareCSS(d2u_mobile_navi_smartmenus::getAutoCSS());
	}

	print $css;
	exit;
}

/**
 * Sends JS file and exits PHP Script. The JS file consists of module js.
 * @param string $position JS position ("head" oder "body") 
 */
function sendD2UHelperJS($position = "head") {
	header('Content-type: application/javascript');
	$d2u_helper = rex_addon::get('d2u_helper');
	$js = "";
	if($position == "body") {
		// Module JS
		if($d2u_helper->hasConfig("include_module") && $d2u_helper->getConfig("include_module") == "true") {
			$js .= d2u_addon_frontend_helper::getModulesJS();
		}
	}
	else if($position == "head") {
		// MultiLevel menu JS
		if($d2u_helper->getConfig("include_menu") == "multilevel") {
			$js .= d2u_mobile_navi::getAutoJS();
		}
		// Slicknav menu JS
		if($d2u_helper->getConfig("include_menu") == "slicknav") {
			$js .= d2u_mobile_navi_slicknav::getAutoJS();
		}
		// Smartmenu menu JS
		if($d2u_helper->getConfig("include_menu") == "smartmenu") {
			$js .= d2u_mobile_navi_smartmenus::getAutoJS();
		}
		// Mega menu JS
		if($d2u_helper->getConfig("include_menu") == "megamenu") {
			$js .= d2u_mobile_navi_mega_menu::getAutoJS();
		}
	}
	print $js;
	exit;
}

/**
 * Sends CustomCSS file and exits PHP Script.
 */
function sendD2UHelperCustomCSS() {
	header('Content-type: text/css');
	$css = "";

	// Custom CSS
	$d2u_helper = rex_addon::get('d2u_helper');
	if($d2u_helper->hasConfig("custom_css") && file_exists(rex_path::media($d2u_helper->getConfig("custom_css")))) {
			$css .= file_get_contents(rex_path::media($d2u_helper->getConfig("custom_css")));
	}		

	// Apply template settings and compress
	print d2u_addon_frontend_helper::prepareCSS($css);

	exit;
}

/**
 * Sends CSS file and exits PHP Script. The CSS file consists of template and
 * - if in settings checked - also module css.
 * @param string $d2u_template_id
 */
function sendD2UHelperTemplateCSS($d2u_template_id = "") {
	header('Content-type: text/css');
	$css = "";
	// Template CSS
	if($d2u_template_id != "") {
		$template_manager = new D2UTemplateManager(D2UTemplateManager::getD2UHelperTemplates());
		$current_template = $template_manager->getTemplate($d2u_template_id);
		if($current_template !== FALSE) {
			$css .= $current_template->getCSS();
		}
	}

	// Custom CSS
	$d2u_helper = rex_addon::get('d2u_helper');
	if($d2u_helper->hasConfig("custom_css") && file_exists(rex_path::media($d2u_helper->getConfig("custom_css")))) {
			$css .= file_get_contents(rex_path::media($d2u_helper->getConfig("custom_css")));
	}		

	// Apply template settings and compress
//	print d2u_addon_frontend_helper::prepareCSS($css);

	exit;	
}