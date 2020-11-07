<?php
/**
 * Class for SlickNav menu (HTTPS://GITHUB.COM/COMPUTERWOLF/SLICKNAV)
 *
 * @author Tobias Krais
 */
class d2u_mobile_navi_slicknav {
	/**
	 * Get combined CSS styles for menu.
	 * @return string Combined CSS
	 */
	public static function getAutoCSS() {
		$addon = rex_addon::get("d2u_helper");
		$css = "";
		if(file_exists($addon->getAssetsPath("slicknav/slicknav.css"))){
			$css .= file_get_contents($addon->getAssetsPath("slicknav/slicknav.css"));
		}
		return $css;
	}

	/**
	 * Get combined JavaScript for installed modules.
	 * @return string Combined JavaScript
	 */
	public static function getAutoJS() {
		$addon = rex_addon::get("d2u_helper");
		$js = '';
		if(file_exists($addon->getAssetsPath("slicknav/jquery.slicknav.min.js"))) {
			$js .= file_get_contents($addon->getAssetsPath("slicknav/jquery.slicknav.min.js"));
		}
		return $js;
	}

	/**
	 * Redaxo categories.
	 * @param int $cat_parent_id Parent category ID. If 0, Redaxo root categories
	 * are returned
	 * @return rex_category[] Array containing Redaxo categories
	 */
	private static function getCategories($cat_parent_id = 0) {
		if($cat_parent_id > 0) {
			return rex_category::get($cat_parent_id)->getChildren(true);
		}
		else {
			return rex_category::getRootCategories(true);
		}
	}

	/**
	 * Returns a SlickNav menu for mobile view
	 * @param int $cat_parent_id Redaxo category ID, default root categories are returned.
	 */
	public static function getMobileMenu($cat_parent_id = 0) {
		print '<div id="mobile-menu">';
		print '<ul id="slicknav-mobile-menu">';
		foreach(d2u_mobile_navi_slicknav::getCategories($cat_parent_id) as $lev1) {
			// Check permissions if YCom ist installed
			if(!rex_addon::get('ycom')->isAvailable() || (rex_addon::get('ycom')->isAvailable() && rex_ycom_auth::articleIsPermitted($lev1->getStartArticle()))) {
				if(count($lev1->getChildren(TRUE)) == 0) {
					// Without Redaxo submenu
						print '<li'. (rex_article::getCurrentId() == $lev1->getId() || in_array($lev1->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $lev1->getUrl() .'" title="'. $lev1->getName() .'">'. $lev1->getName() .'</a></li>';
				}
				else {
					// With submenu
					d2u_mobile_navi_slicknav::getSubmenu($lev1);
				}
			}
		}
		print '</ul>';
		print '</div>';

		// JS to init has to be added in template
	}
	
	/**
	 * Returns slicknav submenu
	 * @param rex_category $rex_category Redaxo category
	 */
	private static function getSubmenu($rex_category) {
		print '<li><a href="'. $rex_category->getUrl() .'" title="'. $rex_category->getName() .'">'. $rex_category->getName() .'</a>';
		print '<ul>';
		$cat_name = rex_config::get('d2u_helper', 'submenu_use_articlename', FALSE) == TRUE ? rex_article::get($rex_category->getId())->getName() : strtoupper($rex_category->getName());
		print '<li'. (rex_article::getCurrentId() == $rex_category->getId() || in_array($rex_category->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $rex_category->getUrl() .'" title="'. $cat_name .'">'. $cat_name .'</a></li>';

		foreach($rex_category->getChildren(true) as $rex_subcategory) {
			// Check permissions if YCom ist installed
			if(!rex_addon::get('ycom')->isAvailable() || (rex_addon::get('ycom')->isAvailable() && rex_ycom_auth::articleIsPermitted($rex_subcategory->getStartArticle()))) {
				if(count($rex_subcategory->getChildren(true)) == 0) {
					// Without Redaxo submenu
					print '<li'. (rex_article::getCurrentId() == $rex_subcategory->getId() || in_array($rex_subcategory->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $rex_subcategory->getUrl() .'" title="'. $rex_subcategory->getName() .'">'. $rex_subcategory->getName() .'</a></li>';
				}
				else {
					// Mit Untermenü
					d2u_mobile_navi_slicknav::getSubmenu($rex_subcategory);
				}
			}
		}
		print '</ul>';
		print '</li>';
	}
}