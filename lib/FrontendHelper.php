<?php

namespace TobiasKrais\D2UHelper;

use rex_addon;
use rex_article;
use rex_category;
use rex_clang;
use rex_config;
use rex_dir;
use rex_extension;
use rex_extension_point;
use rex_finder;
use rex_path;
use rex_version;
use rex_yrewrite_seo;

/**
 * @api
 * Offers methods for Redaxo frontend. Mostly for Addons published by
 * www.design-to-use.de.
 */
class FrontendHelper
{
    /** @var int Parameter id from URL addon */
    private static int $url_id = 0;

    /** @var string Parameter namespace from URL addon */
    private static string $url_namespace = '';

    /**
     * Apply colors from settings.
     * @param string $css CSS string
     * @return string replaced CSS
     */
    private static function applySettingsToCSS($css)
    {
        $d2u_helper = rex_addon::get('d2u_helper');

        // Apply template color settings
        $colors = ['navi_color_bg', 'navi_color_font', 'navi_color_hover_bg', 'navi_color_hover_font',
            'subhead_color_bg', 'subhead_color_font',
            'article_color_bg', 'article_color_h', 'article_color_box',
            'footer_color_bg', 'footer_color_box', 'footer_color_font'];
        foreach ($colors as $color) {
            if ($d2u_helper->hasConfig($color)) {
                $css = str_replace($color, (string) $d2u_helper->getConfig($color), $css);
            }
        }

        // Apply width
        if ($d2u_helper->hasConfig('include_menu_show')) {
            $size = '576px';
            switch ($d2u_helper->getConfig('include_menu_show')) {
                case 'xs':
                    $size = '576px';
                    break;
                case 'sm':
                    $size = '768px';
                    break;
                case 'md':
                    $size = '992px';
                    break;
                case 'lg':
                    $size = '1200px';
                    break;
                case 'xl':
                    $size = '';
                    break;
                default:
                    $size = '992px';
            }
            $css = str_replace('navi-min-width', $size, $css);
        }

        return $css;
    }

    /**
     * Compresses string containing CSS.
     * @param string $css CSS string
     * @return string compressed CSS
     */
    private static function compressCSS($css)
    {
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        if (null === $css) {
            return '';
        }
        $css = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $css);
        $css = str_replace('{ ', '{', $css);
        $css = str_replace(' }', '}', $css);
        $css = str_replace('; ', ';', $css);
        $css = str_replace(', ', ',', $css);
        $css = str_replace(' {', '{', $css);
        $css = str_replace('} ', '}', $css);
        $css = str_replace(': ', ':', $css);
        $css = str_replace(' ,', ',', $css);
        $css = str_replace(' ;', ';', $css);
        $css = str_replace(';}', '}', $css);
        return $css;
    }

    /**
     * Delete addon cache.
     */
    public static function deleteCache(): void
    {
        if (is_dir(rex_path::addonCache('d2u_helper'))) {
            $finder = rex_finder::factory(rex_path::addonCache('d2u_helper'))
                ->filesOnly();
            rex_dir::deleteIterator($finder);
        }
    }

    /**
     * Returns alternate URLs for Redaxo articles and D2U Addons. Key is Redaxo
     * language id, value is URL.
     * @return string[] alternate URLs
     */
    public static function getAlternateURLs()
    {
        /**
         * Extension point for translation list.
         * @param array $subject List of alternate URLs
         * @param int $params['url_namespace'] Namespace des URL Addons
         * @param int $params['url_id'] ID des URL Addons
         * @return array List of addons and their pages with translation status. Example:
         * [
         *      'redaxo_clang_id' => 'alternative url'
         * ]
         */
        $alternate_URLs = rex_extension::registerPoint(new rex_extension_point(name: 'D2U_HELPER_ALTERNATE_URLS', params: ['url_namespace' => self::getUrlNamespace(), 'url_id' => self::getUrlId()]));
        if (is_array($alternate_URLs) && count($alternate_URLs) > 0) {
            return $alternate_URLs;
        }
        /** @deprecated remove other addons in version 2, use EP instead */
        if (rex_addon::get('d2u_immo')->isAvailable() && count(\d2u_immo_frontend_helper::getAlternateURLs()) > 0) {
            $alternate_URLs = \d2u_immo_frontend_helper::getAlternateURLs();
        } elseif (rex_addon::get('d2u_machinery')->isAvailable() && count(\d2u_machinery_frontend_helper::getAlternateURLs()) > 0) {
            $alternate_URLs = \d2u_machinery_frontend_helper::getAlternateURLs();
        } else {
            foreach (rex_clang::getAllIds(true) as $clang_id) {
                $article = rex_article::getCurrent($clang_id);
                if ($article instanceof rex_article && $article->isOnline()) {
                    $alternate_URLs[$clang_id] = $article->getUrl();
                }
            }
        }

        return $alternate_URLs;
    }

    /**
     * Returns breadcrumbs for Redaxo articles and all D2U Addons. If start
     * article is current article, and no addons are used, nothing is returned.
     * @return string Breadcrumb elements
     */
    public static function getBreadcrumbs()
    {
        $startarticle = rex_article::get(rex_article::getSiteStartArticleId());
        $breadcrumb_start_only = true;
        $breadcrumbs = '';
        if ($startarticle instanceof rex_article) {
            $breadcrumbs = '<a href="' . $startarticle->getUrl() . '"><span class="fa-icon fa-home"></span></a>';
        }
        $current_article = rex_article::getCurrent();
        if ($current_article instanceof rex_article) {
            $path = $current_article->getPathAsArray();
            // Categories
            foreach ($path as $id) {
                $article = rex_category::get($id);
                if ($article instanceof rex_category && $id !== rex_article::getSiteStartArticleId()) {
                    $breadcrumb_start_only = false;
                    $breadcrumbs .= ' &nbsp;»&nbsp;&nbsp;<a href="' . $article->getUrl() . '">' . $article->getName() . '</a>';
                } elseif ($startarticle instanceof rex_article) {
                    $breadcrumb_start_only = true;
                    $breadcrumbs = '<a href="' . $startarticle->getUrl() . '"><span class="fa-icon fa-home"></span></a>';
                }
            }
            // Articles
            if (!$current_article->isStartArticle() && !$current_article->isSiteStartArticle()) {
                $breadcrumbs .= ' &nbsp;»&nbsp;&nbsp;<a href="' . $current_article->getUrl() . '">' . $current_article->getName() . '</a>';
                $breadcrumb_start_only = false;
            }
        }

        /**
         * Extension point for breadcrumbs.
         * @param array $subject List of breadcrumbs in html format. The <a> tags are included.
         * @param int $params['url_namespace'] Namespace des URL Addons
         * @param int $params['url_id'] ID des URL Addons
         * @return array List of addons and their pages with translation status. Example:
         * [
         *      'redaxo_clang_id' => 'alternative url'
         * ]
         */
        $ep_breadcrumbs = rex_extension::registerPoint(new rex_extension_point(name: 'D2U_HELPER_BREADCRUMBS', params: ['url_namespace' => self::getUrlNamespace(), 'url_id' => self::getUrlId()]));
        if(is_array($ep_breadcrumbs)) {
            foreach ($ep_breadcrumbs as $ep_breadcrumb) {
                $breadcrumbs .= ' &nbsp;»&nbsp;&nbsp;' . $ep_breadcrumb;
                $breadcrumb_start_only = false;
            }
        }

        /** @deprecated remove other addons in version 2, use EP instead */
        // Addons
        if (rex_addon::get('d2u_immo')->isAvailable()) {
            foreach (\d2u_immo_frontend_helper::getBreadcrumbs() as $breadcrumb) {
                $breadcrumbs .= ' &nbsp;»&nbsp;&nbsp;' . $breadcrumb;
                $breadcrumb_start_only = false;
            }
        }
        if (rex_addon::get('d2u_machinery')->isAvailable()) {
            foreach (\d2u_machinery_frontend_helper::getBreadcrumbs() as $breadcrumb) {
                $breadcrumbs .= ' &nbsp;»&nbsp;&nbsp;' . $breadcrumb;
                $breadcrumb_start_only = false;
            }
        }
        return $breadcrumb_start_only ? '' : $breadcrumbs;
    }

    /**
     * Returns Meta Tags for Redaxo articles and D2U Addons.
     * @return string meta Tags
     */
    public static function getMetaTags()
    {
        $meta_tags = '';

        if (rex_addon::get('yrewrite')->isAvailable()) {
            $yrewrite = new rex_yrewrite_seo();
            $meta_tags = $yrewrite->getTags() . PHP_EOL;
        }

        return $meta_tags;
    }

    /**
     * Get CSS stuff from modules as one string.
     * @param string $addon_key If set, only CSS for modules of this addon are returned
     * @return string CSS
     */
    public static function getModulesCSS($addon_key = '')
    {
        if (file_exists(rex_path::addonCache('d2u_helper', 'modules.css'))) {
            // Read from cache
            $contents = file_get_contents(rex_path::addonCache('d2u_helper', 'modules.css'));
            return false !== $contents ? $contents : '';
        }

        // Generate contents
        $installed_modules = ModuleManager::getModulePairs();

        $css = '';
        foreach ($installed_modules as $installed_module) {
            if ('' !== $installed_module['addon_key']) {
                $module_css_file = rex_path::addon($installed_module['addon_key'], ModuleManager::MODULE_FOLDER . str_replace('-', '/', $installed_module['d2u_id']) .'/'. Module::MODULE_CSS_FILE);
                if (file_exists($module_css_file)) {
                    $css .= file_get_contents($module_css_file);
                }
            }
        }

        // Write to cache
        if (!is_dir(rex_path::addonCache('d2u_helper'))) {
            rex_dir::create(rex_path::addonCache('d2u_helper'));
        }
        file_put_contents(rex_path::addonCache('d2u_helper', 'modules.css'), self::prepareCSS($css));

        return self::prepareCSS($css);

    }

    /**
     * Get JavaScript stuff from modules as one string.
     * @return string JS
     */
    public static function getModulesJS()
    {
        if (file_exists(rex_path::addonCache('d2u_helper', 'modules.js'))) {
            // Read from cache
            $contents = file_get_contents(rex_path::addonCache('d2u_helper', 'modules.js'));
            return false !== $contents ? $contents : '';
        }

        $installed_modules = ModuleManager::getModulePairs();

        $js = '';
        foreach ($installed_modules as $installed_module) {
            if ('' !== $installed_module['addon_key']) {
                $module_js_file = rex_path::addon($installed_module['addon_key'], ModuleManager::MODULE_FOLDER . str_replace('-', '/', $installed_module['d2u_id']) .'/'. Module::MODULE_JS_FILE);
                if (file_exists($module_js_file)) {
                    $js .= file_get_contents($module_js_file);
                }
            }
        }

        // Write to cache
        if (!is_dir(rex_path::addonCache('d2u_helper'))) {
            rex_dir::create(rex_path::addonCache('d2u_helper'));
        }
        file_put_contents(rex_path::addonCache('d2u_helper', 'modules.js'), $js);

        return $js;

    }

    /**
     * Get URL addon id if available. If id is not available, "0" is returned.
     * @return int URL addon dataset id
     */
    public static function getUrlId()
    {
        if (0 === self::$url_id && rex_addon::get('url')->isAvailable() && rex_version::compare(\rex_addon::get('url')->getVersion(), '2.0', '>=')) {
            // URL Addon 2.x
            $manager = \Url\Url::resolveCurrent();
            if ($manager instanceof \Url\UrlManager) {
                self::$url_id = (int) $manager->getDatasetId();
            }
        }
        return self::$url_id;
    }

    /**
     * Get URL addon namespace (former param_key) if available. If id is not available, an empty string is returned.
     * @return string URL addon namespace
     */
    public static function getUrlNamespace()
    {
        if ('' === self::$url_namespace && rex_addon::get('url')->isAvailable() && rex_version::compare(\rex_addon::get('url')->getVersion(), '2.0', '>=')) {
            // URL Addon 2.x
            $manager = \Url\Url::resolveCurrent();
            if ($manager instanceof \Url\UrlManager && $manager->getProfile() instanceof \Url\Profile) {
                self::$url_namespace = $manager->getProfile()->getNamespace();
            }
        }
        return self::$url_namespace;
    }

    /**
     * Prepares CSS. D2U Helper Colors are relaced.
     * @param string $css CSS string
     * @return string compressed CSS
     */
    public static function prepareCSS($css)
    {
        // Replace colors
        $css = self::applySettingsToCSS($css);

        // Compress
        $css = self::compressCSS($css);

        return $css;
    }

    /**
     * Formats a string for Frontend: redaxo:// URLs are replaced und if needed
     * MarkItUp Editor formats are added.
     * @param string $html string formatted by editor chosen in D2U Helper settings
     * @return string Correctly formatted HTML String
     */
    public static function prepareEditorField($html)
    {
        // Convert redaxo://123 to URL
        $final_html = preg_replace_callback(
            '@redaxo://(\d+)(?:-(\d+))?/?@i',
            static function ($matches) {
                return rex_getUrl($matches[1], $matches[2] ?? '');
            },
            $html,
        );

        return $final_html;
    }

    /**
     * Timer Spamprotection function.
     * @param string $label Field label
     * @param int $microtime Microtime value defined in field
     * @param int $seconds to wait
     * @return bool true if user took long enougth to fill out fields
     */
    public static function yform_validate_timer($label, $microtime, $seconds)
    {
        if (($microtime + $seconds) > microtime(true)) {
            return true;
        }
        return false;

    }
}
