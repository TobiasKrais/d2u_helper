<?php

namespace FriendsOfRedaxo\D2UHelper;

use rex_addon;
use rex_article;
use rex_category;
use rex_config;
use rex_ycom_auth;

/**
 * Class for Responsive MultiLevel Menu (http://tympanus.net/codrops/2013/04/19/responsive-multi-level-menu/).
 *
 * @author Tobias Krais
 */
class FrontendNavigationResponsiveMultiLevel
{
    /**
     * Get combined CSS styles for menu.
     * @return string Combined CSS
     */
    public static function getAutoCSS()
    {
        $addon = rex_addon::get('d2u_helper');
        $css = '';
        if (file_exists($addon->getAssetsPath('responsive-multilevelmenu/component.css'))) {
            $css .= file_get_contents($addon->getAssetsPath('responsive-multilevelmenu/component.css'));
        }
        if (file_exists($addon->getAssetsPath('responsive-multilevelmenu/settings.css'))) {
            $css .= file_get_contents($addon->getAssetsPath('responsive-multilevelmenu/settings.css'));
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
        // dlmenu JS must be called after menu is inserted
        //		if(file_exists($addon->getAssetsPath("responsive-multilevelmenu/jquery.dlmenu.js"))){
        //			$js .= file_get_contents($addon->getAssetsPath("responsive-multilevelmenu/jquery.dlmenu.js"));
        //		}
        if (file_exists($addon->getAssetsPath('responsive-multilevelmenu/modernizr.custom.js'))) {
            $js .= file_get_contents($addon->getAssetsPath('responsive-multilevelmenu/modernizr.custom.js'));
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
            $category = rex_category::get($cat_parent_id);
            if ($category instanceof rex_category) {
                return $category->getChildren(true);
            }
        }

        return rex_category::getRootCategories(true);

    }

    /**
     * Returns a Responsive MultiLevel menu for desktop view.
     * @param int $cat_parent_id redaxo category ID, default root categories are returned
     */
    public static function getResponsiveMultiLevelDesktopMenu($cat_parent_id = 0): void
    {
        $addon = rex_addon::get('d2u_helper');
        $show_class = '';
        if ($addon->hasConfig('include_menu_show')) {
            $size = 'xs';
            switch ($addon->getConfig('include_menu_show')) {
                case 'xs':
                    $size = 'sm';
                    break;
                case 'sm':
                    $size = 'md';
                    break;
                case 'md':
                    $size = 'lg';
                    break;
                case 'lg':
                    $size = 'xl';
                    break;
                default:
                    $size = 'md';
            }
            $show_class = ' class="d-none '. ('xl' === (string) $addon->getConfig('include_menu_show') ? '' : 'd-'. $size .'-block') .'"';
        }
        echo '<div id="desktop-menu"'. $show_class .'>';
        $is_first = true;
        foreach (self::getCategories($cat_parent_id) as $category) {
            // Check permissions if YCom ist installed
            $startarticle = $category->getStartArticle();
            if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($startarticle)) {
                $has_machine_submenu = (rex_addon::get('d2u_machinery')->isAvailable() && 'show' === rex_config::get('d2u_machinery', 'show_categories_navi', 'hide') && (int) rex_config::get('d2u_machinery', 'article_id', 0) === $category->getId());
                if (0 === count($category->getChildren(true)) && !$has_machine_submenu) {
                    // Ohne Untermenü
                    echo '<div class="desktop-navi'. (rex_article::getCurrentId() === $category->getId() ? ' current' : '') .'"><a href="'. $category->getUrl() .'" title="'. $category->getName() .'"><div class="desktop-inner">'. $category->getName() .'</div></a></div>';
                } else {
                    echo '<div id="dl-menu-'. $category->getId() .'" class="dl-menuwrapper desktop-navi'.
                        (rex_article::getCurrentId() === $category->getId() || (rex_article::getCurrent() instanceof rex_article && in_array($category->getId(), rex_article::getCurrent()->getPathAsArray(), true)) ? ' current' : '') .'">';
                    echo '<div class="dl-trigger desktop-inner'. ($is_first ? ' first' : '') .'"><span class="has-children"></span>'. $category->getName() .'</div>';
                    echo '<ul class="dl-menu">';
                    // Mit Untermenü
                    $cat_name = strtoupper($category->getName());
                    if ((bool) rex_config::get('d2u_helper', 'submenu_use_articlename', false)) {
                        $category_article = rex_article::get($category->getId());
                        if ($category_article instanceof rex_article) {
                            $cat_name = $category_article->getName();
                        }
                    }
                    echo '<li><a href="'. $category->getUrl() .'" title="'. $cat_name .'">'. $cat_name .'</a></li>';
                    if ($has_machine_submenu) {
                        \d2u_machinery_frontend_helper::getD2UMachineryResponsiveMultiLevelSubmenu();
                    }
                    foreach ($category->getChildren(true) as $lev2) {
                        if (0 === count($lev2->getChildren(true))) {
                            // Without Redaxo submenu
                            echo '<li'. (rex_article::getCurrentId() === $lev2->getId() || (rex_article::getCurrent() instanceof rex_article && in_array($lev2->getId(), rex_article::getCurrent()->getPathAsArray(), true)) ? ' class="current"' : '') .'><a href="'. $lev2->getUrl() .'" title="'. $lev2->getName() .'">'. $lev2->getName() .'</a></li>';
                        } else {
                            // Mit Untermenü
                            self::getSubmenu($lev2);
                        }
                    }
                    echo '</ul>';
                    echo '</div>'; // .dl-menuwrapper
                    $is_first = false;
                }
            }
        }
        echo '<br style="clear: both">';
        echo '</div>'; // desktop-menu

        // Nötige JS einfügen
        echo '<script src="'. $addon->getAssetsUrl('responsive-multilevelmenu/jquery.dlmenu.js') .'"></script>' . PHP_EOL;
        echo '<script>$(function() {';
        foreach (self::getCategories($cat_parent_id) as $category) {
            echo "$( '#dl-menu-". $category->getId() ."' ).dlmenu({ animationClasses : { classin : 'dl-animate-in-2', classout : 'dl-animate-out-2' } });";
        }
        echo ' });</script>';
    }

    /**
     * Returns a Responsive MultiLevel menu for mobile view.
     * @param int $cat_parent_id redaxo category ID, default root categories are returned
     */
    public static function getResponsiveMultiLevelMobileMenu($cat_parent_id = 0): void
    {
        $addon = rex_addon::get('d2u_helper');
        $show_class = '';
        if ($addon->hasConfig('include_menu_show') && 'xl' !== (string) $addon->getConfig('include_menu_show')) {
            $size = 'xs';
            switch ($addon->getConfig('include_menu_show')) {
                case 'xs':
                    $size = 'sm';
                    break;
                case 'sm':
                    $size = 'md';
                    break;
                case 'md':
                    $size = 'lg';
                    break;
                case 'lg':
                    $size = 'xl';
                    break;
                default:
                    $size = 'md';
            }
            $show_class = ' class="d-'. $size .'-none"';
        }
        echo '<div id="mobile-menu"'. $show_class .'>';
        echo '<div id="dl-menu" class="dl-menuwrapper">';
        echo '<button class="dl-trigger">&nbsp;</button>';
        echo '<ul class="dl-menu">';
        foreach (self::getCategories($cat_parent_id) as $lev1) {
            // Check permissions if YCom ist installed
            $lev1_start_article = $lev1->getStartArticle();
            if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($lev1_start_article)) {
                if (0 === count($lev1->getChildren(true))) {
                    // Without Redaxo submenu
                    echo '<li'. (rex_article::getCurrentId() === $lev1->getId() || (rex_article::getCurrent() instanceof rex_article && in_array($lev1->getId(), rex_article::getCurrent()->getPathAsArray(), true)) ? ' class="current"' : '') .'><a href="'. $lev1->getUrl() .'" title="'. $lev1->getName() .'">'. $lev1->getName() .'</a></li>';
                } else {
                    // Mit Untermenü
                    self::getSubmenu($lev1);
                }
            }
        }
        echo '</ul>';
        echo '</div>'; // .dl-menuwrapper
        echo '</div>'; // mobile-menu

        // Nötige JS einfügen
        echo '<script src="'. $addon->getAssetsUrl('responsive-multilevelmenu/jquery.dlmenu.js') .'"></script>' . PHP_EOL;
        echo '<script>$(function() {';
        echo "$( '#dl-menu' ).dlmenu({ animationClasses : { classin : 'dl-animate-in-2', classout : 'dl-animate-out-2' } });";
        echo ' });</script>';
    }

    /**
     * Returns Mobile Responsive MultiLevel submenu.
     * @param rex_category $rex_category Redaxo category
     */
    private static function getSubmenu($rex_category): void
    {
        echo '<li'. (rex_article::getCurrentId() === $rex_category->getId() || (rex_article::getCurrent() instanceof rex_article && in_array($rex_category->getId(), rex_article::getCurrent()->getPathAsArray(), true)) ? ' class="current"' : '') .'><a href="'. $rex_category->getUrl() .'" title="'. $rex_category->getName() .'">'. $rex_category->getName() .'</a>';
        echo '<ul class="dl-submenu">';
        echo '<li class="dl-back"><a href="#" title="">&nbsp;</a></li>';
        $rex_category_article = rex_article::get($rex_category->getId());
        $cat_name = (bool) rex_config::get('d2u_helper', 'submenu_use_articlename', false) && $rex_category_article instanceof rex_article ? $rex_category_article->getName() : strtoupper($rex_category->getName());
        echo '<li><a href="'. $rex_category->getUrl() .'" title="'. $cat_name .'">'. $cat_name .'</a></li>';
        if (rex_addon::get('d2u_machinery')->isAvailable() && 'show' === (string) rex_config::get('d2u_machinery', 'show_categories_navi', 'hide') && (int) rex_config::get('d2u_machinery', 'article_id', 0) === $rex_category->getId()) {
            \d2u_machinery_frontend_helper::getD2UMachineryResponsiveMultiLevelSubmenu();
        }
        foreach ($rex_category->getChildren(true) as $rex_subcategory) {
            // Check permissions if YCom ist installed
            $rex_subcategory_article = $rex_subcategory->getStartArticle();
            if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($rex_subcategory_article)) {
                $has_machine_submenu = (rex_addon::get('d2u_machinery')->isAvailable() && (int) rex_config::get('d2u_machinery', 'article_id', 0) === $rex_subcategory->getId());
                if (0 === count($rex_subcategory->getChildren(true)) && !$has_machine_submenu) {
                    // Without Redaxo submenu
                    echo '<li'. (rex_article::getCurrentId() === $rex_subcategory->getId() || (rex_article::getCurrent() instanceof rex_article && in_array($rex_subcategory->getId(), rex_article::getCurrent()->getPathAsArray(), true)) ? ' class="current"' : '') .'><a href="'. $rex_subcategory->getUrl() .'" title="'. $rex_subcategory->getName() .'">'. $rex_subcategory->getName() .'</a></li>';
                } else {
                    // Mit Untermenü
                    self::getSubmenu($rex_subcategory);
                }
            }
        }
        echo '</ul>';
        echo '</li>';
    }
}
