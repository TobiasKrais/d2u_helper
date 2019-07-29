<?php
/**
 * Class for Smartmenus Menu (https://www.smartmenus.org/)
 *
 * @author Tobias Krais
 */
class d2u_mobile_navi_smartmenus {
	/**
	 * Get combined CSS styles for menu.
	 * @return string Combined CSS
	 */
	public static function getAutoCSS() {
		$addon = rex_addon::get("d2u_helper");
		$css = "";
		if(file_exists($addon->getAssetsPath("smartmenus/sm-core-css.css"))){
			$css .= file_get_contents($addon->getAssetsPath("smartmenus/sm-core-css.css"));
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
		if(file_exists($addon->getAssetsPath("smartmenus/jquery.smartmenus.min.js"))){
			$js .= file_get_contents($addon->getAssetsPath("smartmenus/jquery.smartmenus.min.js"));
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
	 * Returns menu
	 * @param int $cat_parent_id Redaxo category ID, by default root categories are returned.
	 */
	public static function getMenu($cat_parent_id = 0) {
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
		print '<ul id="main-menu" class="sm sm-simple">'. PHP_EOL;
	
		foreach(d2u_mobile_navi_smartmenus::getCategories($cat_parent_id) as $category) {
			$has_machine_submenu = (rex_addon::get('d2u_machinery')->isAvailable() && rex_config::get('d2u_machinery', 'show_categories_navi', 'hide') == 'show' && rex_config::get('d2u_machinery', 'article_id', 0) == $category->getId());
			if(count($category->getChildren(true)) == 0 && !$has_machine_submenu) {
				// Ohne Untermenü
				print '<li class="main'. (rex_article::getCurrentId() == $category->getId() ? ' current' : '') .'"><a href="'. $category->getUrl() .'" title="'. $category->getName() .'">'. $category->getName() .'</a></li>'. PHP_EOL;
			}
			else {
				print '<li class="main '. (rex_article::getCurrentId() == $category->getId() || in_array($category->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' current' : '') .'">'
					.'<a href="'. $category->getUrl() .'" title="'. $category->getName() .'">'. $category->getName() .'</a>'. PHP_EOL;
				print '<ul>'. PHP_EOL;
				// Mit Untermenü
				if($has_machine_submenu) {
					d2u_machinery_frontend_helper::getD2UMachineryResponsiveMultiLevelSubmenu();
				}
				foreach($category->getChildren(true) as $lev2) {
					if(count($lev2->getChildren(true)) == 0) {
						// Without Redaxo submenu
						print '<li'. (rex_article::getCurrentId() == $lev2->getId() || in_array($lev2->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $lev2->getUrl() .'" title="'. $lev2->getName() .'">'. $lev2->getName() .'</a></li>'. PHP_EOL;
					}
					else {
						// Mit Untermenü
						d2u_mobile_navi_smartmenus::getSubmenu($lev2);
					}
				}
				print '</ul>'. PHP_EOL;
				print '</li>'. PHP_EOL; // .dl-menuwrapper
			}	
		}
		
		print '<script type="text/javascript">
	$(function() {
		$("#main-menu").smartmenus({
			mainMenuSubOffsetX: -1,
			subMenusSubOffsetX: 10,
			subMenusSubOffsetY: 0
		});
	});
</script>';
	}
	
	/**
	 * Returns Mobile Responsive MultiLevel submenu
	 * @param rex_category $rex_category Redaxo category
	 */
	private static function getSubmenu($rex_category) {
		print '<li'. (rex_article::getCurrentId() == $rex_category->getId() || in_array($rex_category->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $rex_category->getUrl() .'" title="'. $rex_category->getName() .'">'. $rex_category->getName() .'</a>'. PHP_EOL;
		print '<ul>'. PHP_EOL;
		if(rex_addon::get('d2u_machinery')->isAvailable() && rex_config::get('d2u_machinery', 'show_categories_navi', 'hide') == 'show' && rex_config::get('d2u_machinery', 'article_id', 0) == $rex_category->getId()) {
			d2u_machinery_frontend_helper::getD2UMachineryResponsiveMultiLevelSubmenu();
		}
		foreach($rex_category->getChildren(true) as $rex_subcategory) {
			$has_machine_submenu = (rex_addon::get('d2u_machinery')->isAvailable() && rex_config::get('d2u_machinery', 'article_id', 0) == $rex_subcategory->getId());
			if(count($rex_subcategory->getChildren(true)) == 0 && !$has_machine_submenu) {
				// Without Redaxo submenu
				print '<li'. (rex_article::getCurrentId() == $rex_subcategory->getId() || in_array($rex_subcategory->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $rex_subcategory->getUrl() .'" title="'. $rex_subcategory->getName() .'">'. $rex_subcategory->getName() .'</a></li>';
			}
			else {
				// Mit Untermenü
				d2u_mobile_navi_smartmenus::getSubmenu($rex_subcategory);
			}
		}
		print '</ul>'. PHP_EOL;
		print '</li>'. PHP_EOL;
	}
}