<?php
/**
 * Funktionen rund um eine mobile Navigation.
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
		if(file_exists($addon->getAssetsPath("responsive-multilevelmenu/component.css"))){
			$css .= file_get_contents($addon->getAssetsPath("responsive-multilevelmenu/component.css"));
		}
		if(file_exists($addon->getAssetsPath("responsive-multilevelmenu/settings.css"))){
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
	 * Redaxo Kategorien.
	 * @param int $cat_parent_id ID der Elternkategorie. Wenn 0 werden die Redaxo
	 * Root Kategorien ausgegeben
	 * @return rex_category[] Array mit Redaxo Kategorien.
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
	 * Gibt ein Menü (http://tympanus.net/codrops/2013/04/19/responsive-multi-level-menu/) aus.
	 * @param int $cat_parent_id Wenn nicht die Root Kategorien verwendet ausgegeben
	 * werden sollen, muss diese Eltern Kategorie ID übergeben werden.
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
			$show_class = ' class="d-none d-'. $size .'-block"';
		}
		print '<div id="desktop-menu"'. $show_class .'>';
		$is_first = TRUE;
		foreach(d2u_mobile_navi::getCategories($cat_parent_id) as $category) {
			$has_machine_submenu = (rex_addon::get('d2u_machinery')->isAvailable() && rex_config::get('d2u_machinery', 'article_id', 0) == $category->getId());
			if(count($category->getChildren(true)) == 0 && !$has_machine_submenu) {
				// Ohne Untermenü
				print '<div class="desktop-navi"><a href="'. $category->getUrl() .'"><div class="desktop-inner">'. $category->getName() .'</div></a></div>';
			}
			else {
				print '<div id="dl-menu-'. $category->getId() .'" class="dl-menuwrapper desktop-navi">';
				print '<div class="dl-trigger desktop-inner'. ($is_first ? ' first' : '') .'"><span class="has-children"></span>'. $category->getName() .'</div>';
				print '<ul class="dl-menu">';
				// Mit Untermenü
				print '<li><a href="'. $category->getUrl() .'">'. strtoupper($category->getName()) .'</a></li>';
				if($has_machine_submenu) {
					d2u_machinery_frontend_helper::getD2UMachineryResponsiveMultiLevelSubmenu();
				}
				foreach($category->getChildren(true) as $lev2) {
					if(count($lev2->getChildren(true)) == 0) {
						// Without Redaxo submenu
						print '<li><a href="'. $lev2->getUrl() .'">'. $lev2->getName() .'</a></li>';
					}
					else {
						// Mit Untermenü
						d2u_mobile_navi::getResponsiveMultiLevelSubmenu($lev2);
					}
				}
				print '</ul>';
				print '</div>'; // .dl-menuwrapper
				$is_first = FALSE;
			}	
		}
		print '<br style="clear: both">';
		print '</div>'; // desktop-menu
		
		// Nötige JS einfügen
		print '<script type="text/javascript" src="'. $addon->getAssetsUrl('responsive-multilevelmenu/jquery.dlmenu.js') .'"></script>' . PHP_EOL;
		print '<script>$(function() {';
		foreach(d2u_mobile_navi::getCategories($cat_parent_id) as $category) {
			print "$( '#dl-menu-". $category->getId() ."' ).dlmenu({ animationClasses : { classin : 'dl-animate-in-2', classout : 'dl-animate-out-2' } });";
		}
		print ' });</script>';
	}

	/**
	 * Gibt ein mobiles Menü (http://tympanus.net/codrops/2013/04/19/responsive-multi-level-menu/) aus.
	 * @param int $cat_parent_id Wenn nicht die Root Kategorien verwendet ausgegeben
	 * werden sollen, muss diese Eltern Kategorie ID übergeben werden.
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
			if(count($lev1->getChildren(true)) == 0) {
				// Without Redaxo submenu
				print '<li><a href="'. $lev1->getUrl() .'">'. $lev1->getName() .'</a></li>';
			}
			else {
				// Mit Untermenü
				d2u_mobile_navi::getResponsiveMultiLevelSubmenu($lev1);
			}
		}
		print '</ul>';
		print '</div>'; // .dl-menuwrapper
		print '</div>'; // mobile-menu

		// Nötige JS einfügen
		print '<script type="text/javascript" src="'. $addon->getAssetsUrl('responsive-multilevelmenu/jquery.dlmenu.js') .'"></script>' . PHP_EOL;
		print '<script>$(function() {';
		print "$( '#dl-menu' ).dlmenu({ animationClasses : { classin : 'dl-animate-in-2', classout : 'dl-animate-out-2' } });";
		print ' });</script>';
	}
	
	/**
	 * Gibt das Unternemü für ein Mobile Responsive MultiLevel Menu zurück.
	 * @param rex_category $rex_category Redaxo Kategorie
	 */
	private static function getResponsiveMultiLevelSubmenu($rex_category) {
		print '<li><a href="'. $rex_category->getUrl() .'">'. $rex_category->getName() .'</a>';
		print '<ul class="dl-submenu">';
		print '<li class="dl-back"><a href="#">&nbsp;</a></li>';
		print '<li><a href="'. $rex_category->getUrl() .'">'. strtoupper($rex_category->getName()) .'</a></li>';
			if(rex_addon::get('d2u_machinery')->isAvailable() && rex_config::get('d2u_machinery', 'article_id', 0) == $rex_category->getId()) {
				d2u_machinery_frontend_helper::getD2UMachineryResponsiveMultiLevelSubmenu();
			}
		foreach($rex_category->getChildren(true) as $rex_subcategory) {
			$has_machine_submenu = (rex_addon::get('d2u_machinery')->isAvailable() && rex_config::get('d2u_machinery', 'article_id', 0) == $rex_subcategory->getId());
			if(count($rex_subcategory->getChildren(true)) == 0 && !$has_machine_submenu) {
				// Without Redaxo submenu
				print '<li><a href="'. $rex_subcategory->getUrl() .'">'. $rex_subcategory->getName() .'</a></li>';
			}
			else {
				// Mit Untermenü
				d2u_mobile_navi::getResponsiveMultiLevelSubmenu($rex_subcategory);
			}
		}
		print '</ul>';
		print '</li>';
	}
}