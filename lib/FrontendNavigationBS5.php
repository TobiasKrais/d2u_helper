<?php

namespace TobiasKrais\D2UHelper;

use rex_addon;
use rex_article;
use rex_category;
use rex_clang;
use rex_config;
use rex_media;
use rex_ycom_auth;

/**
 * Class for Bootstrap 5 navigation (compatible with BS5 templates).
 *
 * @author Tobias Krais
 */
class FrontendNavigationBS5
{
    /**
     * Get combined CSS styles for menu.
     * @return string Combined CSS
     */
    public static function getAutoCSS()
    {
        return '';
    }

    /**
     * Get combined JavaScript for installed modules.
     * @return string Combined JavaScript
     */
    public static function getAutoJS()
    {
        return '';
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
     * Returns the menu <li> items for Bootstrap 5 templates.
     * Does not output the outer navbar wrapper, so the BS5 nav fragment
     * can control the overall structure.
     * @param int $cat_parent_id redaxo category ID, by default root categories are returned
     */
    public static function getMenuItems($cat_parent_id = 0): void
    {
        $use_articlename = (bool) rex_config::get('d2u_helper', 'submenu_use_articlename', false);
        foreach (self::getCategories($cat_parent_id) as $category) {
            // Check permissions if YCom is installed
            $category_start_article = $category->getStartArticle();
            if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($category_start_article)) {
                $is_active = rex_article::getCurrentId() === $category->getId() || (rex_article::getCurrent() instanceof rex_article && in_array($category->getId(), rex_article::getCurrent()->getPathAsArray(), true));
                $children = $category->getChildren(true);

                if (0 === count($children)) {
                    // Without submenu
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link' . ($is_active ? ' active' : '') . '" href="' . $category->getUrl() . '">' . $category->getName() . '</a>';
                    echo '</li>';
                } else {
                    // With submenu (dropdown)
                    echo '<li class="nav-item dropdown">';
                    echo '<a class="nav-link dropdown-toggle' . ($is_active ? ' active' : '') . '" href="' . $category->getUrl() . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
                    echo $category->getName();
                    echo '</a>';
                    echo '<ul class="dropdown-menu">';
                    foreach ($children as $child) {
                        $child_start_article = $child->getStartArticle();
                        if (false === rex_addon::get('ycom')->isAvailable() || rex_ycom_auth::articleIsPermitted($child_start_article)) {
                            $child_active = rex_article::getCurrentId() === $child->getId();
                            // Use article name for submenu if setting is enabled
                            $child_name = $use_articlename && $child_start_article instanceof rex_article ? $child_start_article->getName() : $child->getName();
                            echo '<li><a class="dropdown-item' . ($child_active ? ' active' : '') . '" href="' . $child->getUrl() . '">' . $child_name . '</a></li>';
                        }
                    }
                    echo '</ul>';
                    echo '</li>';
                }
            }
        }
    }

    /**
     * Returns the language switcher items as <li> elements.
     * Outputs either a dropdown or a modal trigger button, depending on
     * whether a language icon is configured in the addon settings.
     * @param string $menu_breakpoint Bootstrap breakpoint for spacing (e.g. 'lg', 'md')
     */
    public static function getLanguageSwitcher($menu_breakpoint = 'lg'): void
    {
        if (count(rex_clang::getAllIds(true)) <= 1) {
            return;
        }

        $current_clang = rex_clang::getCurrent();
        $alternate_urls = FrontendHelper::getAlternateURLs();
        $clangs = rex_clang::getAll(true);

        if ('' !== rex_config::get('d2u_helper', 'header_lang_icon', '')) {
            // Language icon + modal trigger
            echo '<li class="nav-item">';
            echo '<button class="btn nav-link" type="button" data-bs-toggle="modal" data-bs-target="#lang_chooser_modal">';
            echo '<img src="' . \rex_url::media((string) rex_config::get('d2u_helper', 'header_lang_icon', '')) . '" alt="" style="height: 1.25em;">';
            echo ' <span class="lang-code">' . strtoupper($current_clang->getCode()) . '</span>';
            echo '</button>';
            echo '</li>';
        } else {
            // Simple dropdown language switcher
            echo '<li class="nav-item dropdown">';
            echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
            echo strtoupper($current_clang->getCode());
            echo '</a>';
            echo '<ul class="dropdown-menu dropdown-menu-end">';
            foreach ($clangs as $clang) {
                $lang_url = $alternate_urls[$clang->getId()] ?? \rex_getUrl(rex_article::getCurrentId(), $clang->getId());
                echo '<li><a class="dropdown-item' . ($clang->getId() === $current_clang->getId() ? ' active' : '') . '" href="' . $lang_url . '">' . $clang->getName() . '</a></li>';
            }
            echo '</ul>';
            echo '</li>';
        }
    }

    /**
     * Returns the language modal HTML for Bootstrap 5.
     * Should be placed outside the <nav> element.
     */
    public static function getLanguageModal(): void
    {
        if (count(rex_clang::getAllIds(true)) <= 1 || '' === rex_config::get('d2u_helper', 'header_lang_icon', '')) {
            return;
        }

        $alternate_urls = FrontendHelper::getAlternateURLs();
        $clangs = rex_clang::getAll(true);

        echo '<div class="modal fade" id="lang_chooser_modal" tabindex="-1" aria-hidden="true">';
        echo '<div class="modal-dialog modal-lg">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
        echo '</div>';
        echo '<div class="modal-body">';
        echo '<ul id="langchooser">';
        foreach ($clangs as $rex_clang) {
            $link = $alternate_urls[$rex_clang->getId()] ?? \rex_getUrl(rex_article::getSiteStartArticleId(), $rex_clang->getId());
            echo '<li><a href="' . $link . '">'
                . '<img class="lang-chooser-flag" src="' . \rex_url::media((string) $rex_clang->getValue('clang_icon')) . '" loading="lazy" alt="' . $rex_clang->getName() . '">'
                . '<span class="lang-text">' . $rex_clang->getName() . '</span></a></li>';
        }
        echo '</ul>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Returns the dark mode toggle button as a <li> element.
     * @param string $menu_breakpoint Bootstrap breakpoint for spacing (e.g. 'lg', 'md')
     */
    public static function getDarkModeToggle($menu_breakpoint = 'lg'): void
    {
        echo '<li class="nav-item">';
        echo '<button class="btn btn-darkmode-toggle nav-link" type="button" id="darkModeToggle" aria-label="Toggle dark mode">';
        echo '<svg class="darkmode-icon-light" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>';
        echo '<svg class="darkmode-icon-dark" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
        echo '</button>';
        echo '</li>';
    }
}
