<?php
/**
 * Class for Smartmenus Menu (https://www.smartmenus.org/).
 *
 * @author Tobias Krais
 */
class d2u_mobile_navi_smartmenus
{
    /**
     * Get combined CSS styles for menu.
     * @return string Combined CSS
     */
    public static function getAutoCSS()
    {
        $addon = rex_addon::get('d2u_helper');
        $css = '';
        if (file_exists($addon->getAssetsPath('smartmenus/sm-core-css.css'))) {
            $css .= file_get_contents($addon->getAssetsPath('smartmenus/sm-core-css.css'));
        }
        if (file_exists($addon->getAssetsPath('smartmenus/custom.css'))) {
            $css .= file_get_contents($addon->getAssetsPath('smartmenus/custom.css'));
        }
        return $css;
    }

    /**
     * Get combined JavaScript for installed modules.
     * @return string Combined JavaScript
     */
    public static function getAutoJS()
    {
        $addon = rex_addon::get('d2u_helper');
        $js = '';
        if (file_exists($addon->getAssetsPath('smartmenus/jquery.smartmenus.min.js'))) {
            $js .= file_get_contents($addon->getAssetsPath('smartmenus/jquery.smartmenus.min.js'));
        }
        if (file_exists($addon->getAssetsPath('smartmenus/menu-toggle.js'))) {
            $js .= file_get_contents($addon->getAssetsPath('smartmenus/menu-toggle.js'));
        }
        return $js;
    }

    /**
     * Redaxo categories.
     * @param int $cat_parent_id Parent category ID. If 0, Redaxo root categories
     * are returned
     * @return rex_category[] Array containing Redaxo categories
     */
    private static function getCategories($cat_parent_id = 0)
    {
        if ($cat_parent_id > 0) {
            return rex_category::get($cat_parent_id)->getChildren(true);
        }

        return rex_category::getRootCategories(true);

    }

    /**
     * Returns menu.
     * @param int $cat_parent_id redaxo category ID, by default root categories are returned
     */
    public static function getMenu($cat_parent_id = 0)
    {
        // Mobile menu toggle button (hamburger/x icon)
        echo '<input id="main-menu-state" type="checkbox" />';
        echo '<label class="main-menu-btn" for="main-menu-state">';
        echo '<span class="main-menu-btn-icon"></span>';
        echo '</label>';

        // Menu
        echo '<ul id="main-menu" class="sm sm-simple">'. PHP_EOL;

        foreach (self::getCategories($cat_parent_id) as $category) {
            // Check permissions if YCom ist installed
            if (!rex_addon::get('ycom')->isAvailable() || (rex_addon::get('ycom')->isAvailable() && rex_ycom_auth::articleIsPermitted($category->getStartArticle()))) {
                $has_machine_submenu = (rex_addon::get('d2u_machinery')->isAvailable() && 'show' == rex_config::get('d2u_machinery', 'show_categories_navi', 'hide') && rex_config::get('d2u_machinery', 'article_id', 0) == $category->getId());
                if (0 == count($category->getChildren(true)) && !$has_machine_submenu) {
                    // Ohne Untermenü
                    echo '<li class="main'. (rex_article::getCurrentId() == $category->getId() || in_array($category->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' current' : '') .'"><a href="'. $category->getURL() .'" title="'. $category->getName() .'">'. $category->getName() .'</a></li>'. PHP_EOL;
                } else {
                    echo '<li class="main'. (rex_article::getCurrentId() == $category->getId() || in_array($category->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' current' : '') .'">'
                        .'<a href="'. $category->getURL() .'" title="'. $category->getName() .'">'. $category->getName() .'</a>'. PHP_EOL;
                    echo '<ul>'. PHP_EOL;
                    // Mit Untermenü
                    if ($has_machine_submenu) {
                        if (method_exists('d2u_machinery_frontend_helper', 'getD2UMachinerySmartmenuSubmenu')) {
                            d2u_machinery_frontend_helper::getD2UMachinerySmartmenuSubmenu();
                        } else {
                            d2u_machinery_frontend_helper::getD2UMachineryResponsiveMultiLevelSubmenu();
                        }
                    }
                    foreach ($category->getChildren(true) as $lev2) {
                        if (0 == count($lev2->getChildren(true))) {
                            // Without Redaxo submenu
                            echo '<li'. (rex_article::getCurrentId() == $lev2->getId() || in_array($lev2->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $lev2->getUrl() .'" title="'. $lev2->getName() .'">'. $lev2->getName() .'</a></li>'. PHP_EOL;
                        } else {
                            // Mit Untermenü
                            self::getSubmenu($lev2);
                        }
                    }
                    echo '</ul>'. PHP_EOL;
                    echo '</li>'. PHP_EOL; // .dl-menuwrapper
                }
            }
        }

        echo '</ul>';
        echo '
<script type="text/javascript">
	$(function() {
		$("#main-menu").smartmenus({
			subMenusSubOffsetX: 15,
			subMenusSubOffsetY: 0
		});
	});
</script>';
    }

    /**
     * Returns Mobile Responsive MultiLevel submenu.
     * @param rex_category $rex_category Redaxo category
     */
    private static function getSubmenu($rex_category)
    {
        echo '<li'. (rex_article::getCurrentId() == $rex_category->getId() || in_array($rex_category->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $rex_category->getUrl() .'" title="'. $rex_category->getName() .'">'. $rex_category->getName() .'</a>'. PHP_EOL;
        echo '<ul>'. PHP_EOL;
        if (rex_addon::get('d2u_machinery')->isAvailable() && 'show' == rex_config::get('d2u_machinery', 'show_categories_navi', 'hide') && rex_config::get('d2u_machinery', 'article_id', 0) == $rex_category->getId()) {
            if (method_exists('d2u_machinery_frontend_helper', 'getD2UMachinerySmartmenuSubmenu')) {
                d2u_machinery_frontend_helper::getD2UMachinerySmartmenuSubmenu();
            } else {
                d2u_machinery_frontend_helper::getD2UMachineryResponsiveMultiLevelSubmenu();
            }
        }
        foreach ($rex_category->getChildren(true) as $rex_subcategory) {
            // Check permissions if YCom ist installed
            if (!rex_addon::get('ycom')->isAvailable() || (rex_addon::get('ycom')->isAvailable() && rex_ycom_auth::articleIsPermitted($rex_subcategory->getStartArticle()))) {
                $has_machine_submenu = (rex_addon::get('d2u_machinery')->isAvailable() && rex_config::get('d2u_machinery', 'article_id', 0) == $rex_subcategory->getId());
                if (0 == count($rex_subcategory->getChildren(true)) && !$has_machine_submenu) {
                    // Without Redaxo submenu
                    echo '<li'. (rex_article::getCurrentId() == $rex_subcategory->getId() || in_array($rex_subcategory->getId(), rex_article::getCurrent()->getPathAsArray()) ? ' class="current"' : '') .'><a href="'. $rex_subcategory->getUrl() .'" title="'. $rex_subcategory->getName() .'">'. $rex_subcategory->getName() .'</a></li>';
                } else {
                    // Mit Untermenü
                    self::getSubmenu($rex_subcategory);
                }
            }
        }
        echo '</ul>'. PHP_EOL;
        echo '</li>'. PHP_EOL;
    }
}
