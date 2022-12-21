<?php
/**
 * Class for Responsive MultiLevel Menu (http://tympanus.net/codrops/2013/04/19/responsive-multi-level-menu/)
 *
 * @author Tobias Krais
 */
class d2u_mobile_navi {
	/**
	 * Get combined CSS styles for menu.
	 * @return string Combined CSS
	 */
	public static function getAutoCSS() {
		$addon = rex_addon::get("d2u_helper");
		$css = "";
		if(file_exists($addon->getAssetsPath("responsive-multilevelmenu/component.css"))) {
			$css .= file_get_contents($addon->getAssetsPath("responsive-multilevelmenu/component.css"));
		}
		if(file_exists($addon->getAssetsPath("responsive-multilevelmenu/settings.css"))) {
			$css .= file_get_contents($addon->getAssetsPath("responsive-multilevelmenu/settings.css"));
		}
		return $css;
	}

	/**
	 * Get combined JavaScript for installed modules.
	 * @return string Combined JavaScript
	 */
	public static function getAutoJS() {
		$addon = rex_addon::get("d2u_helper");
		$js = "";
		// dlmenu JS must be called after menu is inserted
//		if(file_exists($addon->getAssetsPath("responsive-multilevelmenu/jquery.dlmenu.js"))){
//			$js .= file_get_contents($addon->getAssetsPath("responsive-multilevelmenu/jquery.dlmenu.js"));
//		}
		if(file_exists($addon->getAssetsPath("responsive-multilevelmenu/modernizr.custom.js"))){
			$js .= file_get_contents($addon->getAssetsPath("responsive-multilevelmenu/modernizr.custom.js"));
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
	 * Returns a Responsive MultiLevel menu for desktop view
	 * @param int $cat_parent_id Redaxo category ID, default root categories are returned.
	 */
	public static function getResponsiveMultiLevelDesktopMenu($cat_parent_id = 0) {
		$addon = rex_addon::get("d2u_helper");
		$show_class = "";
		if($addon->hasConfig('include_menu_show')) {
			$size = "xs";
			switch ($addon->getConfig('include_menu_show')) {
				case "xs":
					$size = "sm";
					break;
				case "sm":
					$size = "md";
					break;
				case "md":
					$size = "lg";
					break;
				case "lg":
					$size = "xl";
					break;
				default:
					$size = "md";
			}
			$show_class = ' class="d-none '. ($addon->getConfig('include_menu_show') == "xl" ? '' : 'd-'. $size .'-block') .'"';
		}
		print '<div id="desktop-menu"'. $show_class .'>';
		$is_first = TRUE;
		foreach(d2u_mobile_navi::getCategories($cat_parent_id) as $category) {
			// Check permissions if YCom ist installed
			if(!rex_addon::get('ycom')->isAvailable() || (rex_addon::get('ycom')->isAvailable() && rex_ycom_auth::articleIsPermitted($category->getStartArticle()))) {
				$has_machine_submenu = (rex_addon::get('d2u_machinery')->isAvailable() && rex_config::get('d2u_machinery', 'show_categories_navi', 'hide') == 'show' && rex_config::get('d2u_machinery', 'article_id', 0) == $category->getId());
				if(count($category->getChildren(true)) == 0 && !$has_machine_submenu) {
					// Ohne Untermenü
					print '<div class="desktop-navi'. (rex_article::getCurrentId() == $category->getId() ? ' current' : '') .'"><a href="'. $category->getURL() .'" title="'. $category->getName() .'"><div class="desktop-inner">'. $category->getName() .'</div></a></div>';
				}
				else {
					print '<div id="dl-menu-'. $category->getId() .'" class="dl-menuwrapper desktop-navi'.
						(rex_article::getCurrentId() == $category->getId() || in_array($category->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' current' : '') .'">';
					print '<div class="dl-trigger desktop-inner'. ($is_first ? ' first' : '') .'"><span class="has-children"></span>'. $category->getName() .'</div>';
					print '<ul class="dl-menu">';
					// Mit Untermenü
					$cat_name = rex_config::get('d2u_helper', 'submenu_use_articlename', FALSE) == TRUE ? rex_article::get($category->getId())->getName() : strtoupper($category->getName());
					print '<li><a href="'. $category->getURL() .'" title="'. $cat_name .'">'. $cat_name .'</a></li>';
					if($has_machine_submenu) {
						d2u_machinery_frontend_helper::getD2UMachineryResponsiveMultiLevelSubmenu();
					}
					foreach($category->getChildren(true) as $lev2) {
						if(count($lev2->getChildren(true)) == 0) {
							// Without Redaxo submenu
							print '<li'. (rex_article::getCurrentId() == $lev2->getId() || in_array($lev2->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $lev2->getUrl() .'" title="'. $lev2->getName() .'">'. $lev2->getName() .'</a></li>';
						}
						else {
							// Mit Untermenü
							d2u_mobile_navi::getSubmenu($lev2);
						}
					}
					print '</ul>';
					print '</div>'; // .dl-menuwrapper
					$is_first = FALSE;
				}
			}
		}
		print '<br style="clear: both">';
		print '</div>'; // desktop-menu
		
		// Nötige JS einfügen
		print '<script src="'. $addon->getAssetsUrl('responsive-multilevelmenu/jquery.dlmenu.js') .'"></script>' . PHP_EOL;
		print '<script>$(function() {';
		foreach(d2u_mobile_navi::getCategories($cat_parent_id) as $category) {
			print "$( '#dl-menu-". $category->getId() ."' ).dlmenu({ animationClasses : { classin : 'dl-animate-in-2', classout : 'dl-animate-out-2' } });";
		}
		print ' });</script>';
	}

	/**
	 * Returns a Responsive MultiLevel menu for mobile view
	 * @param int $cat_parent_id Redaxo category ID, default root categories are returned.
	 */
	public static function getResponsiveMultiLevelMobileMenu($cat_parent_id = 0) {
		$addon = rex_addon::get("d2u_helper");
		$show_class = "";
		if($addon->hasConfig('include_menu_show') && $addon->getConfig('include_menu_show') != "xl") {
			$size = "xs";
			switch ($addon->getConfig('include_menu_show')) {
				case "xs":
					$size = "sm";
					break;
				case "sm":
					$size = "md";
					break;
				case "md":
					$size = "lg";
					break;
				case "lg":
					$size = "xl";
					break;
				default:
					$size = "md";
			}
			$show_class = ' class="d-'. $size .'-none"';
		}
		print '<div id="mobile-menu"'. $show_class .'>';
		print '<div id="dl-menu" class="dl-menuwrapper">';
		print '<button class="dl-trigger">&nbsp;</button>';
		print '<ul class="dl-menu">';
		foreach(d2u_mobile_navi::getCategories($cat_parent_id) as $lev1) {
			// Check permissions if YCom ist installed
			if(!rex_addon::get('ycom')->isAvailable() || (rex_addon::get('ycom')->isAvailable() && rex_ycom_auth::articleIsPermitted($lev1->getStartArticle()))) {
				if(count($lev1->getChildren(true)) == 0) {
					// Without Redaxo submenu
					print '<li'. (rex_article::getCurrentId() == $lev1->getId() || in_array($lev1->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $lev1->getUrl() .'" title="'. $lev1->getName() .'">'. $lev1->getName() .'</a></li>';
				}
				else {
					// Mit Untermenü
					d2u_mobile_navi::getSubmenu($lev1);
				}
			}
		}
		print '</ul>';
		print '</div>'; // .dl-menuwrapper
		print '</div>'; // mobile-menu

		// Nötige JS einfügen
		print '<script src="'. $addon->getAssetsUrl('responsive-multilevelmenu/jquery.dlmenu.js') .'"></script>' . PHP_EOL;
		print '<script>$(function() {';
		print "$( '#dl-menu' ).dlmenu({ animationClasses : { classin : 'dl-animate-in-2', classout : 'dl-animate-out-2' } });";
		print ' });</script>';
	}
	
	/**
	 * Returns Mobile Responsive MultiLevel submenu
	 * @param rex_category $rex_category Redaxo category
	 */
	private static function getSubmenu($rex_category) {
		print '<li'. (rex_article::getCurrentId() == $rex_category->getId() || in_array($rex_category->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $rex_category->getUrl() .'" title="'. $rex_category->getName() .'">'. $rex_category->getName() .'</a>';
		print '<ul class="dl-submenu">';
		print '<li class="dl-back"><a href="#" title="">&nbsp;</a></li>';
		$cat_name = rex_config::get('d2u_helper', 'submenu_use_articlename', FALSE) == TRUE ? rex_article::get($rex_category->getId())->getName() : strtoupper($rex_category->getName());
		print '<li><a href="'. $rex_category->getUrl() .'" title="'. $cat_name .'">'. $cat_name .'</a></li>';
		if(rex_addon::get('d2u_machinery')->isAvailable() && rex_config::get('d2u_machinery', 'show_categories_navi', 'hide') == 'show' && rex_config::get('d2u_machinery', 'article_id', 0) == $rex_category->getId()) {
			d2u_machinery_frontend_helper::getD2UMachineryResponsiveMultiLevelSubmenu();
		}
		foreach($rex_category->getChildren(true) as $rex_subcategory) {
			// Check permissions if YCom ist installed
			if(!rex_addon::get('ycom')->isAvailable() || (rex_addon::get('ycom')->isAvailable() && rex_ycom_auth::articleIsPermitted($rex_subcategory->getStartArticle()))) {
				$has_machine_submenu = (rex_addon::get('d2u_machinery')->isAvailable() && rex_config::get('d2u_machinery', 'article_id', 0) == $rex_subcategory->getId());
				if(count($rex_subcategory->getChildren(true)) == 0 && !$has_machine_submenu) {
					// Without Redaxo submenu
					print '<li'. (rex_article::getCurrentId() == $rex_subcategory->getId() || in_array($rex_subcategory->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $rex_subcategory->getUrl() .'" title="'. $rex_subcategory->getName() .'">'. $rex_subcategory->getName() .'</a></li>';
				}
				else {
					// Mit Untermenü
					d2u_mobile_navi::getSubmenu($rex_subcategory);
				}
			}
		}
		print '</ul>';
		print '</li>';
	}
}