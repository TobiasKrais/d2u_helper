<?php
// Rewrite Scheme
if(rex_addon::get('yrewrite')->isAvailable() && rex_config::get('d2u_helper', 'activate_rewrite_scheme', 'false') == 'true') {
	rex_yrewrite::setScheme(new d2u_yrewrite_scheme());
}

// Correct name of rights
if(\rex::isBackend() && is_object(\rex::getUser())) {
	rex_perm::register('d2u_helper[]', rex_i18n::msg('d2u_helper_rights_all'));
	rex_perm::register('d2u_helper[settings]', rex_i18n::msg('d2u_helper_rights_settings'), rex_perm::OPTIONS);
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
	rex_extension::register('OUTPUT_FILTER', 'appendGoogleAnalytics');
	rex_extension::register('OUTPUT_FILTER', 'appendWiredMindseMetrics');
	
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
 * Adds Google Analytics stuff if Analytics ID is stored in settings
 * @param rex_extension_point $ep Redaxo extension point
 */
function appendGoogleAnalytics(rex_extension_point $ep) {
	$d2u_helper = rex_addon::get("d2u_helper");

	$insert_body = "";

	if($d2u_helper->hasConfig("google_analytics") && $d2u_helper->getConfig("google_analytics") !== "") {
		// Analytics stuff
		$insert_body = "<script>
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

				ga('create', '". $d2u_helper->getConfig("google_analytics") ."', 'auto');
				ga('set', 'anonymizeIp', true);
				ga('send', 'pageview');
			</script>";
	}
	$ep->setSubject(str_replace('</body>', $insert_body .'</body>', $ep->getSubject()));
}

/**
 * Adds some style and script stuff to the header
 * @param rex_extension_point $ep Redaxo extension point
 */
function appendToPageD2UHelperFiles(rex_extension_point $ep) {
	$VERSION_JQUERY = '3.3.1';
	$VERSION_BOOTSTRAP = '4.3.1';
	$VERSION_POPPER = '1.14.7';
	$addon = rex_addon::get("d2u_helper");
	
	// If insertion should be prevented, detect class "prevent_d2u_helper_styles"
	if(strpos($ep->getSubject(), 'prevent_d2u_helper_styles') !== FALSE) {
		return;
	}

	$insert_head = "";
	$insert_body = "";
	// Vor dem </head> einfügen
	if($addon->getConfig('include_jquery') == 'true') {
		// JavaScript
		$insert_head .= '<script src="'. $addon->getAssetsUrl('bootstrap4/jquery.min.js') .'?v='. $VERSION_JQUERY .'"></script>' . PHP_EOL;
	}
	if($addon->getConfig('include_bootstrap4') == 'true') {
		// Popper JavaScript
		$insert_head .= '<script src="'. $addon->getAssetsUrl('bootstrap4/popper.min.js') .'?v='. $VERSION_POPPER .'"></script>' . PHP_EOL;
		// Bootstrap CSS
		$insert_head .= '<link rel="stylesheet" type="text/css" href="'.  $addon->getAssetsUrl('bootstrap4/bootstrap.min.css') .'?v='. $VERSION_BOOTSTRAP .'" />' . PHP_EOL;
	}

	// Consider module css or menu css
	if(($addon->getConfig("include_module", "false") == "true" && d2u_addon_frontend_helper::getModulesCSS() != "")
			|| $addon->getConfig("include_menu_multilevel", "false") == "true" || $addon->getConfig("include_menu_slicknav", "false") == "true" || $addon->getConfig("include_menu_smartmenu", "false") == "true"
		) {
		$insert_head .= '<link rel="stylesheet" type="text/css" href="/index.php?d2u_helper=helper.css" />' . PHP_EOL;
	}
		
	// Menu stuff in header
	if($addon->getConfig("include_menu_multilevel", "false") == "true" || $addon->getConfig("include_menu_slicknav", "false") == "true" || $addon->getConfig("include_menu_smartmenu", "false") == "true") {
		$insert_head .= '<script src="/index.php?position=head&d2u_helper=helper.js"></script>' . PHP_EOL;
	}

	$ep->setSubject(str_replace('</head>', $insert_head .'</head>', $ep->getSubject()));

	// Vor dem </body> einfügen
	if($addon->getConfig('include_bootstrap4') == 'true') {
		$insert_body .= '<script src="'. $addon->getAssetsUrl('bootstrap4/bootstrap.min.js') .'?v='. $VERSION_BOOTSTRAP .'"></script>' . PHP_EOL;
	}

	// Module stuff in body
	if($addon->hasConfig("include_module") && $addon->getConfig("include_module") == "true" && d2u_addon_frontend_helper::getModulesJS()) {
		$insert_body .= '<script src="/index.php?position=body&d2u_helper=helper.js"></script>' . PHP_EOL;
	}
	$ep->setSubject(str_replace('</body>', $insert_body .'</body>', $ep->getSubject()));
}

/**
 * Adds WiredMinds eMetrics stuff if ID is stored in settings
 * @param rex_extension_point $ep Redaxo extension point
 */
function appendWiredMindseMetrics(rex_extension_point $ep) {
	$insert_body = "";

	if(rex_config::get("d2u_helper", "emetrics_customno", "") !== "") {
		// eMatrics stuff
		$insert_body = '
		<!-- WiredMinds eMetrics tracking with Enterprise Edition V5.9.2 START -->
		<script>
			var wiredminds = [];
			wiredminds.push(["setTrackParam", "wm_custnum", "'. rex_config::get("d2u_helper", "emetrics_customno", "") .'"]);
			// Begin own parameters.
			wiredminds.push(["setTrackParam", "wm_campaign_key", "wmc"]);
			wiredminds.push(["registerHeatmapEvent", "mousedown"]);
			wiredminds.push(["setTrackParam", "wm_content_width", ]);
			// End own parameters.
			wiredminds.push(["count"]);

			(function() {
				function wm_async_load() {
					var wm = document.createElement("script");
					wm.type = "text/javascript";
					wm.async = true;
					wm.src = "https://stats.vertriebsassistent.de/track/count.js";
					var el = document.getElementsByTagName(\'script\')[0];
					el.parentNode.insertBefore(wm, el);
				}

				if (window.addEventListener) {
					window.addEventListener(\'load\', wm_async_load, false);
				} else if (window.attachEvent){
					window.attachEvent(\'onload\', wm_async_load);
				}
			})();
		</script>

		<noscript>
			<div>
				<a href="https://www.wiredminds.de">
					<img src="https://stats.vertriebsassistent.de/track/ctin.php?wm_custnum='. rex_config::get("d2u_helper", "emetrics_customno", "") .'&nojs=1" alt="" style="border:0px;"/>
				</a>
			</div>
		</noscript>
		<!-- WiredMinds eMetrics tracking with Enterprise Edition V5.9.2 END -->';
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
		($addon->hasConfig("article_id_impress") && $addon->getConfig("article_id_impress") == $article_id)) {
		$message = '<a href="/index.php?page=d2u_helper/settings">'.
			 rex_i18n::msg('d2u_helper_rights_all') ." - ". rex_i18n::msg('d2u_helper_settings') . '</a>';
		if(!in_array($message, $warning)) {
			$warning[] = $message;
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

	// Settings
	$addon = rex_addon::get("d2u_helper");
    $is_in_use = FALSE;
	if(($addon->hasConfig("template_header_pic") && $addon->getConfig("template_header_pic") == $filename) ||
			($addon->hasConfig("template_logo") && $addon->getConfig("template_logo") == $filename) ||
			($addon->hasConfig("template_print_header_pic") && $addon->getConfig("template_print_header_pic") == $filename) ||
			($addon->hasConfig("template_print_footer_pic") && $addon->getConfig("template_print_footer_pic") == $filename) ||
			($addon->hasConfig("template_03_2_header_pic") && $addon->getConfig("template_03_2_header_pic") == $filename) ||
			($addon->hasConfig("template_03_2_footer_pic") && $addon->getConfig("template_03_2_footer_pic") == $filename) ||
			($addon->hasConfig("template_04_2_facebook_icon") && $addon->getConfig("template_04_2_facebook_icon") == $filename) ||
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
			$warning[] = $message;
		}    }

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

	// Multilevel Menu CSS
	if($d2u_helper->getConfig("include_menu_multilevel", "false") == "true") {
		$css .= d2u_addon_frontend_helper::prepareCSS(d2u_mobile_navi::getAutoCSS());
	}

	// Slicknav Menu CSS
	if($d2u_helper->getConfig("include_menu_slicknav", "false") == "true") {
		$css .= d2u_addon_frontend_helper::prepareCSS(d2u_mobile_navi_slicknav::getAutoCSS());
	}

	// Smartmenu Menu CSS
	if($d2u_helper->getConfig("include_menu_smartmenu", "false") == "true") {
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
		if($d2u_helper->getConfig("include_menu_multilevel", "false") == "true") {
			$js .= d2u_mobile_navi::getAutoJS();
		}
		// Slicknav menu JS
		if($d2u_helper->getConfig("include_menu_slicknav", "false") == "true") {
			$js .= d2u_mobile_navi_slicknav::getAutoJS();
		}
		// Smartmenu menu JS
		if($d2u_helper->getConfig("include_menu_smartmenu", "false") == "true") {
			$js .= d2u_mobile_navi_smartmenus::getAutoJS();
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
	print d2u_addon_frontend_helper::prepareCSS($css);

	exit;	
}