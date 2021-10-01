<?php
/**
 * Offers methods for Redaxo frontend. Mostly for Addons published by
 * www.design-to-use.de.
 */
class d2u_addon_frontend_helper {
	
	/**
	 * @var int Parameter id from URL addon
	 */
	private static $url_id = 0;
	
	/**
	 * @var string Parameter namespace from URL addon 
	 */
	private static $url_namespace = "";

		/**
	 * Apply colors from settings
	 * @param string $css CSS string
	 * @return string replaced CSS
	 */
	private static function applySettingsToCSS($css) {
		$d2u_helper = rex_addon::get('d2u_helper');

		// Apply template color settings
		$colors = ['navi_color_bg', 'navi_color_font', 'navi_color_hover_bg', 'navi_color_hover_font',
			'subhead_color_bg', 'subhead_color_font',
			'article_color_bg', 'article_color_h', 'article_color_box',
			'footer_color_bg', 'footer_color_box', 'footer_color_font'];
		foreach($colors as $color) {
			if($d2u_helper->hasConfig($color)) {
				$css = str_replace($color, $d2u_helper->getConfig($color), $css);
			}
		}

		// Apply width
		if($d2u_helper->hasConfig('include_menu_show')) {
			$size = "576px";
			switch ($d2u_helper->getConfig('include_menu_show')) {
				case "xs":
					$size = "576px";
					break;
				case "sm":
					$size = "768px";
					break;
				case "md":
					$size = "992px";
					break;
				case "lg":
					$size = "1200px";
					break;
				case "xl":
					$size = "";
					break;
				default:
					$size = "992px";
			}
			$css = str_replace('navi-min-width', $size, $css);
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
	 * Delete addon cache.
	 */
	public static function deleteCache() {
		if(is_dir(rex_path::addonCache('d2u_helper'))) {
			$finder = rex_finder::factory(rex_path::addonCache('d2u_helper'))
				->filesOnly();
			rex_dir::deleteIterator($finder);
		}
	}

	/**
	 * Returns alternate URLs for Redaxo articles and D2U Addons. Key is Redaxo
	 * language id, value is URL
	 * @return string[] alternate URLs
	 */
	public static function getAlternateURLs() {
		$alternate_URLs = [];
		if(rex_addon::get('d2u_courses')->isAvailable() && count(d2u_courses_frontend_helper::getAlternateURLs()) > 0) {
			$alternate_URLs = d2u_courses_frontend_helper::getAlternateURLs();
		}
		else if(rex_addon::get('d2u_immo')->isAvailable() && count(d2u_immo_frontend_helper::getAlternateURLs()) > 0) {
			$alternate_URLs = d2u_immo_frontend_helper::getAlternateURLs();
		}
		else if(rex_addon::get('d2u_jobs')->isAvailable() && count(d2u_jobs_frontend_helper::getAlternateURLs()) > 0) {
			$alternate_URLs = d2u_jobs_frontend_helper::getAlternateURLs();
		}
		else if(rex_addon::get('d2u_machinery')->isAvailable() && count(d2u_machinery_frontend_helper::getAlternateURLs()) > 0) {
			$alternate_URLs = d2u_machinery_frontend_helper::getAlternateURLs();
		}
		else if(rex_addon::get('d2u_references')->isAvailable() && count(d2u_references_frontend_helper::getAlternateURLs()) > 0) {
			$alternate_URLs = d2u_references_frontend_helper::getAlternateURLs();
		}
		else {
			foreach (rex_clang::getAllIds(TRUE) as $clang_id) {
				$article = rex_article::getCurrent($clang_id);
				if($article->isOnline()) {
					$alternate_URLs[$clang_id] = $article->getUrl();
				}
			}
		}
		return $alternate_URLs;
	}
	
	/**
	 * Returns breadcrumbs for Redaxo articles and all D2U Addons. If start
	 * article is current article, and no addons are used, nothing is returned.
	 * @return string[] Breadcrumb elements
	 */
	public static function getBreadcrumbs() {
		$startarticle = rex_article::get(rex_article::getSiteStartArticleId());
		$breadcrumb_start_only = TRUE;
		$breadcrumbs = '<a href="' . $startarticle->getUrl() . '"><span class="fa-icon fa-home"></span></a>';
		$current_article = rex_article::getCurrent();
		$path = $current_article->getPathAsArray();
		// Categories
		foreach ($path as $id) {
			$article = rex_category::get($id);
			if($id != rex_article::getSiteStartArticleId()) {
				$breadcrumb_start_only = FALSE;
				$breadcrumbs .= ' &nbsp;»&nbsp;&nbsp;<a href="' . $article->getUrl() . '">' . $article->getName() . '</a>';
			}
			else {
				$breadcrumb_start_only = TRUE;
				$breadcrumbs = '<a href="' . $startarticle->getUrl() . '"><span class="fa-icon fa-home"></span></a>';
			}
		}
		// Articles
		if(!$current_article->isStartArticle() && !$current_article->isSiteStartArticle()) {
			$breadcrumbs .= ' &nbsp;»&nbsp;&nbsp;<a href="' . $current_article->getUrl() . '">' . $current_article->getName() . '</a>';
			$breadcrumb_start_only = FALSE;
		}
		// Addons
		if(rex_addon::get('d2u_courses')->isAvailable()) {
			foreach(d2u_courses_frontend_helper::getBreadcrumbs() as $breadcrumb) {
				$breadcrumbs .= ' &nbsp;»&nbsp;&nbsp;' . $breadcrumb;
				$breadcrumb_start_only = FALSE;
			}
		}
		if(rex_addon::get('d2u_immo')->isAvailable()) {
			foreach(d2u_immo_frontend_helper::getBreadcrumbs() as $breadcrumb) {
				$breadcrumbs .= ' &nbsp;»&nbsp;&nbsp;' . $breadcrumb;
				$breadcrumb_start_only = FALSE;
			}
		}
		if(rex_addon::get('d2u_jobs')->isAvailable()) {
			foreach(d2u_jobs_frontend_helper::getBreadcrumbs() as $breadcrumb) {
				$breadcrumbs .= ' &nbsp;»&nbsp;&nbsp;' . $breadcrumb;
				$breadcrumb_start_only = FALSE;
			}
		}
		if(rex_addon::get('d2u_machinery')->isAvailable()) {
			foreach(d2u_machinery_frontend_helper::getBreadcrumbs() as $breadcrumb) {
				$breadcrumbs .= ' &nbsp;»&nbsp;&nbsp;' . $breadcrumb;
				$breadcrumb_start_only = FALSE;
			}
		}
		if(rex_addon::get('d2u_references')->isAvailable()) {
			foreach(d2u_references_frontend_helper::getBreadcrumbs() as $breadcrumb) {
				$breadcrumbs .= ' &nbsp;»&nbsp;&nbsp;' . $breadcrumb;
				$breadcrumb_start_only = FALSE;
			}
		}
		return ($breadcrumb_start_only ? "" : $breadcrumbs);
	}

	/**
	 * Returns Meta Tags for Redaxo articles and D2U Addons
	 * @return string meta Tags
	 */
	public static function getMetaTags() {
		$meta_tags = "";
		
		if(rex_addon::get('url')->isAvailable()) {
			if(rex_version::compare(\rex_addon::get('url')->getVersion(), '1.5', '>=')) {
				// url addon 2.x
				$urlSeo = new Url\Seo();
				$meta_tags = $urlSeo->getTags();
			}
			else {
				// url addon 1.x
				$urlSeo = new Url\Seo();
				$meta_tags = $urlSeo->getTitleTag() . PHP_EOL;
				$meta_tags .= $urlSeo->getDescriptionTag() . PHP_EOL;
				$meta_tags .= $urlSeo->getHreflangTags() . PHP_EOL;
				$meta_tags .= $urlSeo->getCanonicalUrlTag() . PHP_EOL;
				$meta_tags .= $urlSeo->getRobotsTag();
			}
		}
		else if(rex_addon::get('yrewrite')->isAvailable()) {
			$yrewrite = new rex_yrewrite_seo();
			$meta_tags = $yrewrite->getTitleTag() . PHP_EOL;
			$meta_tags .= $yrewrite->getDescriptionTag() . PHP_EOL;
			$meta_tags .= $yrewrite->getHreflangTags() . PHP_EOL;
			$meta_tags .= $yrewrite->getCanonicalUrlTag() . PHP_EOL;
			$meta_tags .= $yrewrite->getRobotsTag();
		}
		
		return $meta_tags;
	}
	
	/**
	 * Get CSS stuff from modules as one string
	 * @param string $addon_key If set, only CSS for modules of this addon are returned
	 * @return string CSS
	 */
	public static function getModulesCSS($addon_key = "") {
		if(file_exists(rex_path::addonCache('d2u_helper', 'modules.css'))) {
			// Read from cache
			return file_get_contents(rex_path::addonCache('d2u_helper', 'modules.css'));
		}
		else {
			// Generate contents
			$installed_modules = D2UModuleManager::getModulePairs($addon_key);

			$css = "";
			foreach($installed_modules as $installed_module) {
				$module_css_file = rex_path::addon($installed_module['addon_key'], D2UModuleManager::MODULE_FOLDER . str_replace('-', '/', $installed_module['d2u_id']) .'/'. D2UModule::MODULE_CSS_FILE);
				if(file_exists($module_css_file)) {
					$css .= file_get_contents($module_css_file);
				}	
			}

			// Write to cache
			if(!is_dir(rex_path::addonCache('d2u_helper'))) {
				mkdir(rex_path::addonCache('d2u_helper'), 0755, TRUE);
			}
			file_put_contents(rex_path::addonCache('d2u_helper', 'modules.css'), self::prepareCSS($css));

			return self::prepareCSS($css);
		}
	}

	/**
	 * Get JavaScript stuff from modules as one string
	 * @return string JS
	 */
	public static function getModulesJS() {
		if(file_exists(rex_path::addonCache('d2u_helper', 'modules.js'))) {
			// Read from cache
			return file_get_contents(rex_path::addonCache('d2u_helper', 'modules.js'));
		}
		else {
			$installed_modules = D2UModuleManager::getModulePairs();

			$js = "";
			foreach($installed_modules as $installed_module) {
				$module_js_file = rex_path::addon($installed_module['addon_key'], D2UModuleManager::MODULE_FOLDER . str_replace('-', '/', $installed_module['d2u_id']) .'/'. D2UModule::MODULE_JS_FILE);
				if(file_exists($module_js_file)) {
					$js .= file_get_contents($module_js_file);
				}	
			}

			// Write to cache
			if(!is_dir(rex_path::addonCache('d2u_helper'))) {
				mkdir(rex_path::addonCache('d2u_helper'), 0755, TRUE);
			}
			file_put_contents(rex_path::addonCache('d2u_helper', 'modules.js'), $js);

			return $js;
		}
	}

	/**
	 * Get URL addon id if available. Works with all Versions of URL addon. If
	 * id is not available, "0" is returned.
	 * @return int URL addon dataset id
	 */
	public static function getUrlId() {
		if(self::$url_id == 0 && rex_addon::get("url")->isAvailable()) {
			if(rex_version::compare(\rex_addon::get('url')->getVersion(), '1.5', '>=')) {
				// URL Addon 2.x
				$manager = \Url\Url::resolveCurrent();
				if($manager) {
					self::$url_id = $manager->getDatasetId();
				}
			}
			else {
				// URL Addon 1.x
				self::$url_id = UrlGenerator::getId();
			}
		}
		return self::$url_id;
	}

	/**
	 * Get URL addon namespace (former param_key) if available. Works with all
	 * Versions of URL addon. If id is not available, an empty string is returned.
	 * @return string URL addon namespace
	 */
	public static function getUrlNamespace() {
		if(self::$url_namespace == "" && rex_addon::get("url")->isAvailable()) {
			if(rex_version::compare(\rex_addon::get('url')->getVersion(), '1.5', '>=')) {
				// URL Addon 2.x
				$manager = \Url\Url::resolveCurrent();
				if($manager && $manager->getProfile()) {
					self::$url_namespace = $manager->getProfile()->getNamespace();
				}
			}
			else {
				// URL Addon 1.x
				$url_data = UrlGenerator::getData();
				if(isset($url_data->urlParamKey)) {
					self::$url_namespace = $url_data->urlParamKey;
				}
			}
		}
		return self::$url_namespace;
	}
	
	/**
	 * Prepares CSS. D2U Helper Colors are relaced
	 * @param string $css CSS string
	 * @return string compressed CSS
	 */
	public static function prepareCSS($css) {
		// Replace colors
		$css = d2u_addon_frontend_helper::applySettingsToCSS($css);
		
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
					function($matches) {
						return rex_getUrl($matches[1], isset($matches[2]) ? $matches[2] : "");
					},
					$html
			);
		
		return $final_html;
	}
	
	/**
	 * Timer Spamprotection function
	 * @param string $label Field label
	 * @param int $microtime Microtime value defined in field
	 * @param int $seconds to wait
	 * @return bool TRUE if user took long enougth to fill out fields
	 */
	public static function yform_validate_timer($label, $microtime, $seconds) {
		if (($microtime + $seconds) > microtime(true)) {
			return true;
		} else {
			return false;
		}
	}
}
