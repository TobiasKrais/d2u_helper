<?php
/**
 * Offers methods for Redaxo frontend. Mostly for Addons published by
 * www.design-to-use.de.
 */
class d2u_addon_frontend_helper {
	/**
	 * Apply colors from settings
	 * @param string $css CSS string
	 * @return string replaced CSS
	 */
	private static function applyColorToCSS($css) {
		$d2u_helper = rex_addon::get('d2u_helper');

		// Apply template color settings
		$colors = ['navi_color_bg', 'navi_color_font', 'navi_color_hover_bg', 'navi_color_hover_font',
			'subhead_color_bg', 'subhead_color_font',
			'article_color_bg', 'article_color_h', 'article_color_box',
			'footer_color_bg', 'footer_color_box'];
		foreach($colors as $color) {
			if($d2u_helper->hasConfig($color)) {
				$css = str_replace($color, $d2u_helper->getConfig($color), $css);
			}
		}

		return $css;
	}

	/**
	 * Compresses string containing CSS
	 * @param string $css CSS string
	 * @return string compressed CSS
	 */
	private static function compressCSS($css) {
		$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
		$css = str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),'',$css);
		$css = str_replace('{ ', '{', $css);
		$css = str_replace(' }', '}', $css);
		$css = str_replace('; ', ';', $css);
		$css = str_replace(', ', ',', $css);
		$css = str_replace(' {', '{', $css);
		$css = str_replace('} ', '}', $css);
		$css = str_replace(': ', ':', $css);
		$css = str_replace(' ,', ',', $css);
		$css = str_replace(' ;', ';', $css);
		$css = str_replace(';}', '}', $css);
		return $css;
	}

	/**
	 * Get CSS stuff from modules as one string
	 * @param string $addon_key If set, only CSS for modules of this addon are returned
	 * @return string CSS
	 */
	public static function getModulesCSS($addon_key = "") {
		$installed_modules = D2UModuleManager::getModulePairs($addon_key);
		
		$css = "";
		foreach($installed_modules as $installed_module) {
			$module_css_file = rex_path::addon($installed_module['addon_key'], D2UModuleManager::MODULE_FOLDER . str_replace('-', '/', $installed_module['d2u_id']) .'/'. D2UModule::MODULE_CSS_FILE);
			if(file_exists($module_css_file)) {
				$css .= file_get_contents($module_css_file);
			}	
		}
		
		return $css;
	}

	/**
	 * Get JavaScript stuff from modules as one string
	 * @param string $addon_key If set, only JS for modules of this addon are returned
	 * @return string JS
	 */
	public static function getModulesJS($addon_key = "") {
		$installed_modules = D2UModuleManager::getModulePairs($addon_key);
		
		$js = "";
		foreach($installed_modules as $installed_module) {
			$module_js_file = rex_path::addon($installed_module['addon_key'], D2UModuleManager::MODULE_FOLDER . str_replace('-', '/', $installed_module['d2u_id']) .'/'. D2UModule::MODULE_JS_FILE);
			if(file_exists($module_js_file)) {
				$js .= file_get_contents($module_js_file);
			}	
		}
		
		return $js;
	}
	
	/**
	 * Prepares CSS. D2U Helper Colors are relaced
	 * @param string $css CSS string
	 * @return string compressed CSS
	 */
	public static function prepareCSS($css) {
		// Replace colors
		$css = d2u_addon_frontend_helper::applyColorToCSS($css);
		
		// Compress
		$css = d2u_addon_frontend_helper::compressCSS($css);

		return $css;
	}
	
	/**
	 * Formats a string for Frontend: redaxo:// URLs are replaced und if needed
	 * MarkItUp Editor formats are added.
	 * @param string $html string formatted by editor chosen in D2U Helper settings
	 * @return string Correctly formatted HTML String
	 */
	public static function prepareEditorField($html) {
		if(rex_config::get('d2u_helper', 'editor', '') == 'markitup' && rex_addon::get('markitup')->isAvailable()) {
			$html = markitup::parseOutput ('markdown', $html);
		}
		else if(rex_config::get('d2u_helper', 'editor', '') == 'markitup_textile' && rex_addon::get('markitup')->isAvailable()) {
			$html = markitup::parseOutput ('textile', $html);
		}

		// Convert redaxo://123 to URL
		$final_html = preg_replace_callback(
					'@redaxo://(\d+)(?:-(\d+))?/?@i',
					create_function(
							'$matches',
							'return rex_getUrl($matches[1], isset($matches[2]) ? $matches[2] : "");'
					),
					$html
			);
		
		return $final_html;
	}
}
