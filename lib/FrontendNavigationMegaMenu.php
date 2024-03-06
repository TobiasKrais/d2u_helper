<?php

namespace FriendsOfRedaxo\D2UHelper;

use rex_addon;
use rex_article;
use rex_category;
use rex_config;
use rex_media;
use rex_ycom_auth;

/**
 * Class for Smartmenus Menu (https://codepen.io/JakubHonisek/pen/xXaYqg).
 *
 * @author Tobias Krais
 */
class FrontendNavigationMegaMenu
{
    /**
     * Get combined CSS styles for menu.
     * @return string Combined CSS
     */
    public static function getAutoCSS()
    {
        $addon = rex_addon::get('d2u_helper');
        $css = '';
        if (file_exists($addon->getAssetsPath('megamenu/megamenu.css'))) {
            $css .= file_get_contents($addon->getAssetsPath('megamenu/megamenu.css'));
        }
        if (file_exists($addon->getAssetsPath('megamenu/custom.css'))) {
            $css .= file_get_contents($addon->getAssetsPath('megamenu/custom.css'));
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
        if (file_exists($addon->getAssetsPath('megamenu/megamenu.js'))) {
            $js .= file_get_contents($addon->getAssetsPath('megamenu/megamenu.js'));
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
     * Returns menu.
     * @param int $cat_parent_id redaxo category ID, by default root categories are returned
     */
    public static function getMenu($cat_parent_id = 0): void
    {
        echo '<div class="navbar navbar-expand-'. rex_config::get('d2u_helper', 'include_menu_show') .' navbar-light">';

        // Mobile menu toggle button (hamburger/x icon)
        echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">';
        echo '<span class="navbar-toggler-icon"></span>';
        echo '</button>';

        // Navigation
        echo '<div class="collapse navbar-collapse" id="navbar">';
        echo '<ul class="navbar-nav mr-auto">';
        foreach (self::getCategories($cat_parent_id) as $category) {
            // Check permissions if YCom ist installed
            $category_start_article = $category->getStartArticle();
            if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($category_start_article)) {
                // $has_machine_submenu = (rex_addon::get('d2u_machinery')->isAvailable() && rex_config::get('d2u_machinery', 'show_categories_navi', 'hide') == 'show' && rex_config::get('d2u_machinery', 'article_id', 0) == $category->getId());
                echo '<li class="nav-item dropdown megamenu-li">';

                if (0 === count($category->getChildren(true)) /* && !$has_machine_submenu */) {
                    // Ohne Untermenü
                    echo '<a class="nav-link'. (rex_article::getCurrentId() === $category->getId() || (rex_article::getCurrent() instanceof rex_article && in_array($category->getId(), rex_article::getCurrent()->getPathAsArray(), true)) ? ' current' : '') .'" href="'. $category->getUrl() .'" id="dropdown'. $category->getId() .'">'. $category->getName() .'</a>';
                } else {
                    echo '<a class="nav-link dropdown-toggle'. (rex_article::getCurrentId() === $category->getId() || (rex_article::getCurrent() instanceof rex_article && in_array($category->getId(), rex_article::getCurrent()->getPathAsArray(), true)) ? ' current' : '') .'" href="'. $category->getUrl() .'" id="dropdown'. $category->getId() .'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'. $category->getName() .'</a>';

                    echo '<div class="dropdown-menu megamenu" aria-labelledby="dropdown'. $category->getId() .'">';
                    echo '<div class="row">';
                    $lev1_icon = '';
                    if ('' !== (string) $category->getValue('cat_d2u_helper_icon')) {
                        $lev1_icon_media = rex_media::get((string) $category->getValue('cat_d2u_helper_icon'));
                        if ($lev1_icon_media instanceof rex_media) {
                            $lev1_icon = '<img src="'. $lev1_icon_media->getUrl() .'" alt="'. $category->getName() .'" title="'. $category->getName() .'" class="megamenu_lev1_icon"> ';
                        }
                    }
                    echo '<div class="col-12"><h4><a href="'. $category->getUrl() .'" title="'. $category->getName() .'">'. $lev1_icon . $category->getName() .'</a></h4></div>';

                    foreach ($category->getChildren(true) as $lev2) {
                        $lev2_start_article = $lev2->getStartArticle();
                        if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($lev2_start_article)) {
                            echo '<div class="col-sm-6 col-lg-4 megamenu_itemlist">';
                            $lev2_icon = '';
                            if ('' !== $lev2->getValue('cat_d2u_helper_icon')) {
                                $lev2_icon_media = rex_media::get((string) $lev2->getValue('cat_d2u_helper_icon'));
                                if ($lev2_icon_media instanceof rex_media) {
                                    $lev2_icon = '<img src="'. $lev2_icon_media->getUrl() .'" alt="'. $lev2->getName() .'" title="'. $lev2->getName() .'" class="megamenu_lev2_icon"> ';
                                }
                            }
                            echo '<div class="megamenu_itemlist_header"><b><a href="'. $lev2->getUrl() .'" title="'. $lev2->getName() .'">'. $lev2_icon . $lev2->getName() .'</a></b></div>';
                            foreach ($lev2->getChildren(true) as $lev3) {
                                $lev3_start_article = $lev3->getStartArticle();
                                if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($lev3_start_article)) {
                                    echo '<a class="dropdown-item'. (rex_article::getCurrentId() === $lev3->getId() || (rex_article::getCurrent() instanceof rex_article && in_array($lev3->getId(), rex_article::getCurrent()->getPathAsArray(), true)) ? ' current' : '') .'" href="'. $lev3->getUrl() .'" title="'. $lev3->getName() .'">'. $lev3->getName() .'</a>';
                                }
                            }
                            echo '</div>';
                        }
                    }
                    echo '</div>';
                    echo '</div>';
                }
                echo '</li>';
            }
        }
        echo '</ul>';
        echo '</div>';

        echo '</div>';
    }
}
