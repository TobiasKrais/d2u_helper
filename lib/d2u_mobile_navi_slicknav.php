<?php

/**
 * Class for SlickNav menu (HTTPS://GITHUB.COM/COMPUTERWOLF/SLICKNAV).
 *
 * @author Tobias Krais
 */
class d2u_mobile_navi_slicknav
{
    /**
     * Get combined CSS styles for menu.
     * @return string Combined CSS
     */
    public static function getAutoCSS()
    {
        $addon = rex_addon::get('d2u_helper');
        $css = '';
        if (file_exists($addon->getAssetsPath('slicknav/slicknav.css'))) {
            $css .= file_get_contents($addon->getAssetsPath('slicknav/slicknav.css'));
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
        if (file_exists($addon->getAssetsPath('slicknav/jquery.slicknav.min.js'))) {
            $js .= file_get_contents($addon->getAssetsPath('slicknav/jquery.slicknav.min.js'));
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
            $rex_category = rex_category::get($cat_parent_id);
            if ($rex_category instanceof rex_category) {
                return $rex_category->getChildren(true);
            }
        }

        return rex_category::getRootCategories(true);

    }

    /**
     * Returns a SlickNav menu for mobile view.
     * @param int $cat_parent_id redaxo category ID, default root categories are returned
     */
    public static function getMobileMenu($cat_parent_id = 0): void
    {
        echo '<div id="mobile-menu">';
        echo '<ul id="slicknav-mobile-menu">';
        foreach (self::getCategories($cat_parent_id) as $lev1) {
            // Check permissions if YCom ist installed
            $lev1_start_article = $lev1->getStartArticle();
            if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($lev1_start_article)) {
                if (0 === count($lev1->getChildren(true))) {
                    // Without Redaxo submenu
                    echo '<li'. (rex_article::getCurrentId() === $lev1->getId() || (rex_article::getCurrent() instanceof rex_article && in_array($lev1->getId(), rex_article::getCurrent()->getPathAsArray(), true)) ? ' class="current"' : '') .'><a href="'. $lev1->getUrl() .'" title="'. $lev1->getName() .'">'. $lev1->getName() .'</a></li>';
                } else {
                    // With submenu
                    self::getSubmenu($lev1);
                }
            }
        }
        echo '</ul>';
        echo '</div>';

        // JS to init has to be added in template
    }

    /**
     * Returns slicknav submenu.
     * @param rex_category $rex_category Redaxo category
     */
    private static function getSubmenu($rex_category):void
    {
        echo '<li><a href="'. $rex_category->getUrl() .'" title="'. $rex_category->getName() .'">'. $rex_category->getName() .'</a>';
        echo '<ul>';
        $cat_name = true === (bool) rex_config::get('d2u_helper', 'submenu_use_articlename', false) && rex_article::get($rex_category->getId()) instanceof rex_article ? rex_article::get($rex_category->getId())->getName() : strtoupper($rex_category->getName());
        echo '<li'. (rex_article::getCurrentId() === $rex_category->getId() || (rex_article::getCurrent() instanceof rex_article && in_array($rex_category->getId(), rex_article::getCurrent()->getPathAsArray(), true)) ? ' class="current"' : '') .'><a href="'. $rex_category->getUrl() .'" title="'. $cat_name .'">'. $cat_name .'</a></li>';

        foreach ($rex_category->getChildren(true) as $rex_subcategory) {
            // Check permissions if YCom ist installed
            if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($rex_subcategory->getStartArticle())) {
                if (0 === count($rex_subcategory->getChildren(true))) {
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
