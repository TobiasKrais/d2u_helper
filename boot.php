<?php
// Rewrite Scheme
if(rex_config::get('d2u_helper', 'activate_rewrite_scheme', 'false') == 'true') {
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
		else if (rex_request('d2u_helper', 'string') == 'template.css' && rex_request('template_id', 'string') != "") {
			sendD2UHelperTemplateCSS(rex_request('template_id', 'string'));
		}
    });
	
	// Only frontend call
	rex_extension::register('OUTPUT_FILTER', 'appendToPageD2UHelperFiles');
	rex_extension::register('OUTPUT_FILTER', 'appendGoogleAnalytics');
	rex_extension::register('OUTPUT_FILTER', 'appendWiredMindseMetrics');
}
else {
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
	$addon = rex_addon::get("d2u_helper");

	$insert_head = "";
	$insert_body = "";
	// Vor dem </head> einfügen
	if($addon->getConfig('include_bootstrap') == 'true') {
		// JavaScript
		$insert_head .= '<script type="text/javascript" src="'. $addon->getAssetsUrl('bootstrap4/jquery.min.js') .'?v=3.3.1"></script>' . PHP_EOL;
		$insert_head .= '<script type="text/javascript" src="'. $addon->getAssetsUrl('bootstrap4/popper.min.js') .'"></script>' . PHP_EOL;
		// Bootstrap CSS
		$insert_head .= '<link rel="stylesheet" type="text/css" href="'.  $addon->getAssetsUrl('bootstrap4/bootstrap.min.css') .'?v=4.1.0" />' . PHP_EOL;
	}

	// Consider module css or menu css
	if(($addon->hasConfig("include_module") && $addon->getConfig("include_module") == "true" && d2u_addon_frontend_helper::getModulesCSS() != "")
			|| ($addon->hasConfig("include_menu") && $addon->getConfig("include_menu") == "true")) {
		$insert_head .= '<link rel="stylesheet" type="text/css" href="index.php?d2u_helper=helper.css" />' . PHP_EOL;
	}
		
	// Menu stuff in header
	if($addon->hasConfig("include_menu") && $addon->getConfig("include_menu") == "true") {
		$insert_head .= '<script type="text/javascript" src="index.php?position=head&d2u_helper=helper.js"></script>' . PHP_EOL;
	}

	$ep->setSubject(str_replace('</head>', $insert_head .'</head>', $ep->getSubject()));

	// Vor dem </body> einfügen
	if($addon->getConfig('include_bootstrap') == 'true') {
		$insert_body .= '<script type="text/javascript" src="'. $addon->getAssetsUrl('bootstrap4/bootstrap.min.js') .'?v=4.1.0"></script>' . PHP_EOL;
	}

	// Module stuff in body
	if($addon->hasConfig("include_module") && $addon->getConfig("include_module") == "true" && d2u_addon_frontend_helper::getModulesJS()) {
		$insert_body .= '<script type="text/javascript" src="index.php?position=body&d2u_helper=helper.js"></script>' . PHP_EOL;
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
		<script type="text/javascript">
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
	if(($addon->hasConfig("template_header_pic") && $addon->getConfig("template_header_pic") == $filename) ||
			($addon->hasConfig("template_logo") && $addon->getConfig("template_logo") == $filename) ||
			($addon->hasConfig("template_print_header_pic") && $addon->getConfig("template_print_header_pic") == $filename) ||
			($addon->hasConfig("template_print_footer_pic") && $addon->getConfig("template_print_footer_pic") == $filename) ||
			($addon->hasConfig("template_03-2_header_pic") && $addon->getConfig("template_03-2_header_pic") == $filename) ||
			($addon->hasConfig("template_03-2_footer_pic") && $addon->getConfig("template_03-2_footer_pic") == $filename) ||
			($addon->hasConfig("custom_css") && $addon->getConfig("custom_css") == $filename)
		) {
		$message = '<a href="javascript:openPage(\'index.php?page=d2u_helper/settings\')">'.
			 rex_i18n::msg('d2u_helper_meta_title') ." ". rex_i18n::msg('d2u_helper_settings') . '</a>';
		if(!in_array($message, $warning)) {
			$warning[] = $message;
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
		
		// Menu CSS
		if($d2u_helper->hasConfig("include_menu") && $d2u_helper->getConfig("include_menu") == "true") {
			$css .= d2u_mobile_navi::getAutoCSS();
		}

		// Apply template settings and compress
		print d2u_addon_frontend_helper::prepareCSS($css);

		exit;	
}

/**
 * Sends JS file and exits PHP Script. The JS file consists of module js.
 * @param string $position JS position ("head" oder "body") 
 */
function sendD2UHelperJS($position = "head") {
		header('Content-type: text/javascript');
		$d2u_helper = rex_addon::get('d2u_helper');
		$js = "";
		if($position == "body") {
			// Module JS
			if($d2u_helper->hasConfig("include_module") && $d2u_helper->getConfig("include_module") == "true") {
				$js .= d2u_addon_frontend_helper::getModulesJS();
			}
		}
		else if($position == "head") {
			// Menu JS
			if($d2u_helper->hasConfig("include_menu") && $d2u_helper->getConfig("include_menu") == "true") {
				$js .= d2u_mobile_navi::getAutoJS();
			}
		}
		print $js;
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
			$css .= $current_template->getCSS();
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