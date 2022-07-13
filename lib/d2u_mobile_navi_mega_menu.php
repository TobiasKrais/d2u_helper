<?php
/**
 * Class for Smartmenus Menu (https://codepen.io/JakubHonisek/pen/xXaYqg)
 *
 * @author Tobias Krais
 */
class d2u_mobile_navi_mega_menu {
	/**
	 * Get combined CSS styles for menu.
	 * @return string Combined CSS
	 */
	public static function getAutoCSS() {
		$addon = rex_addon::get("d2u_helper");
		$css = "";
		if(file_exists($addon->getAssetsPath("megamenu/megamenu.css"))){
			$css .= file_get_contents($addon->getAssetsPath("megamenu/megamenu.css"));
		}
		if(file_exists($addon->getAssetsPath("megamenu/custom.css"))){
			$css .= file_get_contents($addon->getAssetsPath("megamenu/custom.css"));
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
		if(file_exists($addon->getAssetsPath("megamenu/megamenu.js"))){
			$js .= file_get_contents($addon->getAssetsPath("megamenu/megamenu.js"));
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
		print '<div class="navbar navbar-expand-'. rex_config::get('d2u_helper', 'include_menu_show') .' navbar-light">';
		
		// Mobile menu toggle button (hamburger/x icon)
		print '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">';
		print '<span class="navbar-toggler-icon"></span>';
		print '</button>';
		
		// Navigation
		print '<div class="collapse navbar-collapse" id="navbar">';
		print '<ul class="navbar-nav mr-auto">';
		foreach(self::getCategories($cat_parent_id) as $category) {
			// Check permissions if YCom ist installed
			if(!rex_addon::get('ycom')->isAvailable() || (rex_addon::get('ycom')->isAvailable() && rex_ycom_auth::articleIsPermitted($category->getStartArticle()))) {
//				$has_machine_submenu = (rex_addon::get('d2u_machinery')->isAvailable() && rex_config::get('d2u_machinery', 'show_categories_navi', 'hide') == 'show' && rex_config::get('d2u_machinery', 'article_id', 0) == $category->getId());
				print '<li class="nav-item dropdown megamenu-li">';

				if(count($category->getChildren(true)) == 0 /*&& !$has_machine_submenu */) {
					// Ohne Untermenü
					print '<a class="nav-link'. (rex_article::getCurrentId() == $category->getId() || in_array($category->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' current' : '') .'" href="'. $category->getUrl() .'" id="dropdown'. $category->getId() .'">'. $category->getName() .'</a>';
				}
				else {
					print '<a class="nav-link dropdown-toggle'. (rex_article::getCurrentId() == $category->getId() || in_array($category->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' current' : '') .'" href="'. $category->getUrl() .'" id="dropdown'. $category->getId() .'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'. $category->getName() .'</a>';
					
					print '<div class="dropdown-menu megamenu" aria-labelledby="dropdown'. $category->getId() .'">';
					print '<div class="row">';
					$lev1_icon = '';
					if($category->getValue('cat_d2u_helper_icon')) {
						$lev1_icon_media =  rex_media::get($category->getValue('cat_d2u_helper_icon'));
						if($lev1_icon_media) {
							$lev1_icon = '<img src="'. $lev1_icon_media->getUrl() .'" alt="'. $category->getName() .'" title="'. $category->getName() .'" class="megamenu_lev1_icon"> ';
						}
					}
					print '<div class="col-12"><h4><a href="'. $category->getUrl() .'" title="'. $category->getName() .'">'. $lev1_icon . $category->getName() .'</a></h4></div>';
					
					foreach($category->getChildren(true) as $lev2) {
						if(!rex_addon::get('ycom')->isAvailable() || (rex_addon::get('ycom')->isAvailable() && rex_ycom_auth::articleIsPermitted($lev2->getStartArticle()))) {
							print '<div class="col-sm-6 col-lg-4 megamenu_itemlist">';
							$lev2_icon = '';
							if($lev2->getValue('cat_d2u_helper_icon')) {
								$lev2_icon_media =  rex_media::get($lev2->getValue('cat_d2u_helper_icon'));
								if($lev2_icon_media) {
									$lev2_icon = '<img src="'. $lev2_icon_media->getUrl() .'" alt="'. $lev2->getName() .'" title="'. $lev2->getName() .'" class="megamenu_lev2_icon"> ';
								}
							}
							print '<div class="megamenu_itemlist_header"><b><a href="'. $lev2->getUrl() .'" title="'. $lev2->getName() .'">'. $lev2_icon . $lev2->getName() .'</a></b></div>';
							foreach($lev2->getChildren(true) as $lev3) {
								if(!rex_addon::get('ycom')->isAvailable() || (rex_addon::get('ycom')->isAvailable() && rex_ycom_auth::articleIsPermitted($lev3->getStartArticle()))) {
									print '<a class="dropdown-item'. (rex_article::getCurrentId() == $lev3->getId() || in_array($lev3->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' current' : '') .'" href="'. $lev3->getUrl() .'" title="'. $lev3->getName() .'">'. $lev3->getName() .'</a>';
								}
							}
							print '</div>';
						}
					}		
					print '</div>';
					print '</div>';
				}
				print '</li>';
			}
		}
		print '</ul>';
		print '</div>';
		
		print '</div>';
	}
}