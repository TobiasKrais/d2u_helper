<?php

namespace TobiasKrais\D2UHelper;

use rex;
use rex_addon;
use rex_article;
use rex_category;
use rex_clang;
use rex_dir;
use rex_extension;
use rex_extension_point;
use rex_file;
use rex_finder;
use rex_media;
use rex_media_manager;
use rex_path;
use rex_sql;
use rex_url;
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
     * Generate CSS custom properties (:root block) from addon settings.
     * @return string CSS :root block with custom properties
     */
    public static function generateCSSVariables()
    {
        $d2u_helper = rex_addon::get('d2u_helper');

        $colors = ['navi_color_bg', 'navi_color_font', 'navi_color_hover_bg', 'navi_color_hover_font',
            'subhead_color_bg', 'subhead_color_font',
            'article_color_bg', 'article_color_h', 'article_color_box',
            'footer_color_bg', 'footer_color_box', 'footer_color_font'];

        // Light mode variables (:root)
        $vars = [];
        foreach ($colors as $color) {
            if ($d2u_helper->hasConfig($color)) {
                $sanitized = BackendHelper::sanitizeHexColor((string) $d2u_helper->getConfig($color));
                if ('' === $sanitized) {
                    continue;
                }
                $css_var_name = str_replace('_', '-', $color);
                $vars[] = '    --' . $css_var_name . ': ' . $sanitized . ';';
            }
        }

        // Generate 10% alpha variant of article_color_h (append hex alpha 1A ≈ 10%)
        if ($d2u_helper->hasConfig('article_color_h')) {
            $sanitized = BackendHelper::sanitizeHexColor((string) $d2u_helper->getConfig('article_color_h'));
            if ('' !== $sanitized) {
                $vars[] = '    --article-color-h10: ' . $sanitized . '1A;';
            }
        }

        // Generate 85% alpha variant of navi_color_font (append hex alpha D9 ≈ 85%)
        if ($d2u_helper->hasConfig('navi_color_font')) {
            $sanitized = BackendHelper::sanitizeHexColor((string) $d2u_helper->getConfig('navi_color_font'));
            if ('' !== $sanitized) {
                $vars[] = '    --navi-color-fontD9: ' . $sanitized . 'D9;';
            }
        }

        $css = '';
        if (count($vars) > 0) {
            $css .= ":root {\n" . implode("\n", $vars) . "\n}\n";
        }

        // Dark mode variables ([data-bs-theme="dark"])
        $dark_vars = [];
        foreach ($colors as $color) {
            $dark_key = 'dark_' . $color;
            if ($d2u_helper->hasConfig($dark_key) && '' !== (string) $d2u_helper->getConfig($dark_key)) {
                $sanitized = BackendHelper::sanitizeHexColor((string) $d2u_helper->getConfig($dark_key));
                if ('' === $sanitized) {
                    continue;
                }
                $css_var_name = str_replace('_', '-', $color);
                $dark_vars[] = '    --' . $css_var_name . ': ' . $sanitized . ';';
            }
        }

        // Generate dark mode alpha variants
        $dark_article_h = 'dark_article_color_h';
        if ($d2u_helper->hasConfig($dark_article_h) && '' !== (string) $d2u_helper->getConfig($dark_article_h)) {
            $sanitized = BackendHelper::sanitizeHexColor((string) $d2u_helper->getConfig($dark_article_h));
            if ('' !== $sanitized) {
                $dark_vars[] = '    --article-color-h10: ' . $sanitized . '1A;';
            }
        }
        $dark_navi_font = 'dark_navi_color_font';
        if ($d2u_helper->hasConfig($dark_navi_font) && '' !== (string) $d2u_helper->getConfig($dark_navi_font)) {
            $sanitized = BackendHelper::sanitizeHexColor((string) $d2u_helper->getConfig($dark_navi_font));
            if ('' !== $sanitized) {
                $dark_vars[] = '    --navi-color-fontD9: ' . $sanitized . 'D9;';
            }
        }

        if (count($dark_vars) > 0) {
            $css .= "[data-bs-theme=\"dark\"] {\n" . implode("\n", $dark_vars) . "\n}\n";
        }

        return $css;
    }

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
                $sanitized = BackendHelper::sanitizeHexColor((string) $d2u_helper->getConfig($color));
                if ('' === $sanitized) {
                    continue;
                }
                $css = str_replace($color, $sanitized, $css);
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
     * Returns an addon asset URL with cache buster.
     */
    public static function getAddonAssetUrl(string $asset): string
    {
        $addon = rex_addon::get('d2u_helper');
        return self::getBustedUrl($addon->getAssetsUrl($asset), $addon->getAssetsPath($asset));
    }

    /**
     * Returns the dynamic helper CSS content.
     */
    public static function getHelperCSSContent(): string
    {
        $d2u_helper = rex_addon::get('d2u_helper');
        $css = '';

        if ((bool) $d2u_helper->getConfig('include_module', false)) {
            $css .= self::getModulesCSS();
        }

        if ('megamenu' === (string) $d2u_helper->getConfig('include_menu')) {
            $css .= FrontendNavigationMegaMenu::getAutoCSS();
        } elseif ('bs5' === (string) $d2u_helper->getConfig('include_menu')) {
            $css .= FrontendNavigationBS5::getAutoCSS();
        } elseif ('multilevel' === (string) $d2u_helper->getConfig('include_menu')) {
            $css .= FrontendNavigationResponsiveMultiLevel::getAutoCSS();
        } elseif ('slicknav' === (string) $d2u_helper->getConfig('include_menu')) {
            $css .= FrontendNavigationSlickNav::getAutoCSS();
        } elseif ('smartmenu' === (string) $d2u_helper->getConfig('include_menu')) {
            $css .= FrontendNavigationSmartmenu::getAutoCSS();
        }

        return self::prepareCSS($css);
    }

    /**
     * Returns the dynamic helper CSS URL with cache buster.
     */
    public static function getHelperCSSUrl(): string
    {
        return self::appendBuster(
            rex_url::frontendController(['d2u_helper' => 'helper.css']),
            md5(self::getHelperCSSContent())
        );
    }

    /**
     * Returns the dynamic helper JS content.
     */
    public static function getHelperJSContent(string $position = 'head'): string
    {
        $d2u_helper = rex_addon::get('d2u_helper');
        $js = '';

        if ('body' === $position) {
            if ((bool) $d2u_helper->getConfig('include_module', false)) {
                $js .= self::getModulesJS();
            }
        } elseif ('head' === $position) {
            if ('multilevel' === (string) $d2u_helper->getConfig('include_menu')) {
                $js .= FrontendNavigationResponsiveMultiLevel::getAutoJS();
            }
            if ('slicknav' === (string) $d2u_helper->getConfig('include_menu')) {
                $js .= FrontendNavigationSlickNav::getAutoJS();
            }
            if ('smartmenu' === (string) $d2u_helper->getConfig('include_menu')) {
                $js .= FrontendNavigationSmartmenu::getAutoJS();
            }
            if ('megamenu' === (string) $d2u_helper->getConfig('include_menu')) {
                $js .= FrontendNavigationMegaMenu::getAutoJS();
            }
            if ('bs5' === (string) $d2u_helper->getConfig('include_menu')) {
                $js .= FrontendNavigationBS5::getAutoJS();
            }
        }

        return $js;
    }

    /**
     * Returns the dynamic helper JS URL with cache buster.
     */
    public static function getHelperJSUrl(string $position = 'head'): string
    {
        return self::appendBuster(
            rex_url::frontendController(['position' => $position, 'd2u_helper' => 'helper.js']),
            md5(self::getHelperJSContent($position))
        );
    }

    /**
     * Returns a media URL with cache buster.
     */
    public static function getMediaUrl(string $filename): string
    {
        return self::getBustedUrl(rex_url::media($filename), rex_path::media($filename));
    }

    /**
     * Returns the current custom CSS cache file path.
     */
    public static function getCustomCSSCachePath(): string
    {
        return rex_path::addonCache('d2u_helper', 'custom.css');
    }

    /**
     * Deletes the custom CSS cache file.
     */
    public static function deleteCustomCSSCache(): void
    {
        $cachePath = self::getCustomCSSCachePath();
        if (file_exists($cachePath)) {
            rex_file::delete($cachePath);
        }
    }

    /**
     * Returns the current custom CSS file path from addon settings.
     */
    public static function getCustomCSSFilePath(): string
    {
        $filename = (string) rex_addon::get('d2u_helper')->getConfig('custom_css', '');
        if ('' === $filename) {
            return '';
        }

        if ($filename !== basename($filename) || str_contains($filename, "\0")) {
            return '';
        }

        if (0 !== strcasecmp((string) pathinfo($filename, PATHINFO_EXTENSION), 'css')) {
            return '';
        }

        if (null === rex_media::get($filename)) {
            return '';
        }

        $path = rex_path::media($filename);
        $realPath = realpath($path);
        $mediaRoot = realpath(rex_path::media(''));
        if (false === $realPath || false === $mediaRoot || 0 !== strpos($realPath, $mediaRoot . DIRECTORY_SEPARATOR)) {
            return '';
        }

        return $realPath;
    }

    /**
     * Returns the source content of the configured custom CSS file.
     */
    public static function getCustomCSSSourceContent(): string
    {
        $filePath = self::getCustomCSSFilePath();
        if ('' === $filePath) {
            return '';
        }

        $content = file_get_contents($filePath);

        return false === $content ? '' : $content;
    }

    /**
     * Returns the prepared custom CSS content from cache.
     */
    public static function getCustomCSSContent(): string
    {
        $cachePath = self::getCustomCSSCachePath();
        if (file_exists($cachePath)) {
            $content = file_get_contents($cachePath);

            return false === $content ? '' : $content;
        }

        $content = self::prepareCSS(self::getCustomCSSSourceContent());

        if (!is_dir(rex_path::addonCache('d2u_helper'))) {
            rex_dir::create(rex_path::addonCache('d2u_helper'));
        }

        file_put_contents($cachePath, $content);

        return $content;
    }

    /**
     * Regenerates the custom CSS cache file.
     */
    public static function regenerateCustomCSSCache(): string
    {
        self::deleteCustomCSSCache();

        return self::getCustomCSSContent();
    }

    /**
     * Returns a cache buster for the configured custom CSS file.
     */
    public static function getCustomCSSBuster(): string
    {
        $filePath = self::getCustomCSSCachePath();
        if (!file_exists($filePath)) {
            self::getCustomCSSContent();
        }

        if ('' === $filePath || !file_exists($filePath)) {
            return '';
        }

        return (string) filemtime($filePath);
    }

    /**
     * Returns the dynamic custom CSS URL with cache buster.
     */
    public static function getCustomCSSUrl(): string
    {
        $url = rex_url::frontendController(['d2u_helper' => 'custom.css']);
        $buster = self::getCustomCSSBuster();

        return '' === $buster ? $url : self::appendBuster($url, $buster);
    }

    /**
     * Returns a template asset URL with cache buster.
     */
    public static function getTemplateAssetUrl(string $templateId, string $asset): string
    {
        $filePath = rex_path::addon('d2u_helper', 'templates/' . $templateId . '/' . $asset);
        if ('template.css' === $asset) {
            $stylePath = rex_path::addon('d2u_helper', 'templates/' . $templateId . '/styles.css');
            $styleBuster = file_exists($stylePath) ? (string) filemtime($stylePath) : '';
            $customCssBuster = self::getCustomCSSBuster();

            return self::appendBuster(
                rex_url::frontendController(['template_id' => $templateId, 'd2u_helper' => $asset]),
                md5($styleBuster . '|' . $customCssBuster)
            );
        }

        if ('custom.css' === $asset) {
            return self::getCustomCSSUrl();
        }

        return self::getBustedUrl(
            rex_url::frontendController(['template_id' => $templateId, 'd2u_helper' => $asset]),
            $filePath
        );
    }

    /**
     * Appends a cache buster based on filemtime to a URL.
     */
    public static function getBustedUrl(string $url, string $filePath): string
    {
        if (!file_exists($filePath)) {
            return $url;
        }

        return self::appendBuster($url, filemtime($filePath));
    }

    /**
     * Appends a cache buster to an URL.
     */
    public static function appendBuster(string $url, string|int $buster): string
    {
        $separator = str_contains($url, '?') ? '&' : '?';

        return $url . $separator . 'buster=' . rawurlencode((string) $buster);
    }

    /**
     * Checks whether a d2u_machinery extension is active in the new addon-based structure.
     */
    public static function isD2UMachineryExtensionActive(string $extensionKey): bool
    {
        $d2uMachinery = rex_addon::get('d2u_machinery');
        if (!$d2uMachinery->isAvailable()) {
            return false;
        }

        if (!class_exists(\TobiasKrais\D2UMachinery\Extension::class)) {
            return false;
        }

        return \TobiasKrais\D2UMachinery\Extension::isActive($extensionKey);
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

        // no alternate URLs from addons, so return Redaxo articles
        foreach (rex_clang::getAllIds(true) as $clang_id) {
            $article = rex_article::getCurrent($clang_id);
            if ($article instanceof rex_article && $article->isOnline()) {
                $alternate_URLs[$clang_id] = $article->getUrl();
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
            $breadcrumbs = '<a href="' . $startarticle->getUrl() . '" title="'. $startarticle->getName() .'"><span class="fa-icon fa-home"></span></a>';
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

    /**
     * Responsive srcset widths to check for corresponding Media Manager types.
     * If a Media Manager type exists with the base name plus one of these suffixes
     * (e.g. "header_480"), it will be included in the srcset attribute.
     * @var int[]
     */
    private static array $responsiveWidths = [480, 768, 1200, 1920];

    /**
     * Get responsive image srcset and sizes attributes for a header image.
     * Checks if Media Manager types with size suffixes (_480, _768, _1200, _1920)
     * exist in the database and builds the srcset string accordingly.
     *
     * @api
     * @param string $mediaManagerType Base Media Manager type name
     * @param string $filename Image filename
     * @param string $sizes CSS sizes attribute value (default: "100vw")
     * @return array{src: string, srcset_attr: string, sizes_attr: string} Array with src URL, srcset attribute string and sizes attribute string
     */
    public static function getResponsiveImageAttributes(string $mediaManagerType, string $filename, string $sizes = '100vw'): array
    {
        $result = [
            'src' => '' !== $mediaManagerType ? rex_media_manager::getUrl($mediaManagerType, $filename) : rex_url::media($filename),
            'srcset_attr' => '',
            'sizes_attr' => '',
        ];

        if ('' === $mediaManagerType || '' === $filename) {
            return $result;
        }

        // Check which responsive Media Manager types exist
        $srcset_parts = [];
        $sql = rex_sql::factory();
        foreach (self::$responsiveWidths as $width) {
            $responsive_type = $mediaManagerType . '_' . $width;
            $sql->setQuery('SELECT id FROM ' . rex::getTablePrefix() . 'media_manager_type WHERE name = :name', ['name' => $responsive_type]);
            if ($sql->getRows() > 0) {
                $srcset_parts[] = rex_media_manager::getUrl($responsive_type, $filename) . ' ' . $width . 'w';
            }
        }

        if (count($srcset_parts) > 0) {
            // Add the base type as largest fallback in srcset
            $srcset_parts[] = $result['src'] . ' 2560w';
            $result['srcset_attr'] = ' srcset="' . implode(', ', $srcset_parts) . '"';
            $result['sizes_attr'] = ' sizes="' . $sizes . '"';
        }

        return $result;
    }

    /**
     * Get breakpoint-specific header image URLs for art direction.
     *
    * Uses separate Media Manager types for mobile, tablet and desktop if
    * configured. The general header media manager type is used for the largest
    * breakpoint above desktop and as fallback for empty breakpoint fields.
     *
     * @api
     * @return array{mobile: string, tablet: string, desktop: string, xl: string}
     */
    public static function getHeaderImageUrls(string $filename): array
    {
        $d2u_helper = rex_addon::get('d2u_helper');
        $default_header_media_manager_type = (string) $d2u_helper->getConfig('template_header_media_manager_type', '');
        $desktop_header_media_manager_type = (string) $d2u_helper->getConfig('template_header_media_manager_type_desktop', '');
        if ('' === $desktop_header_media_manager_type) {
            $desktop_header_media_manager_type = $default_header_media_manager_type;
        }
        $tablet_header_media_manager_type = (string) $d2u_helper->getConfig('template_header_media_manager_type_tablet', '');
        if ('' === $tablet_header_media_manager_type) {
            $tablet_header_media_manager_type = $desktop_header_media_manager_type;
        }
        $mobile_header_media_manager_type = (string) $d2u_helper->getConfig('template_header_media_manager_type_mobile', '');
        if ('' === $mobile_header_media_manager_type) {
            $mobile_header_media_manager_type = $tablet_header_media_manager_type;
        }

        return [
            'mobile' => '' !== $mobile_header_media_manager_type ? rex_media_manager::getUrl($mobile_header_media_manager_type, $filename) : rex_url::media($filename),
            'tablet' => '' !== $tablet_header_media_manager_type ? rex_media_manager::getUrl($tablet_header_media_manager_type, $filename) : rex_url::media($filename),
            'desktop' => '' !== $desktop_header_media_manager_type ? rex_media_manager::getUrl($desktop_header_media_manager_type, $filename) : rex_url::media($filename),
            'xxl' => '' !== $default_header_media_manager_type ? rex_media_manager::getUrl($default_header_media_manager_type, $filename) : rex_url::media($filename),
        ];
    }

    /**
     * Render a breakpoint-specific header picture element.
     *
     * @api
     */
    public static function getHeaderPictureTag(string $filename, string $alt = '', string $title = '', string $imgAttributes = ''): string
    {
        if ('' === $filename) {
            return '';
        }

        $urls = self::getHeaderImageUrls($filename);
        $title_attr = '' !== $title ? ' title="'. rex_escape($title) .'"' : '';
        $img_attributes = '' !== trim($imgAttributes) ? ' '. trim($imgAttributes) : '';

        return '<picture class="d-print-none">'
            . '<source media="(max-width: 767.98px)" srcset="'. rex_escape($urls['mobile']) .'">'
            . '<source media="(max-width: 1199.98px)" srcset="'. rex_escape($urls['tablet']) .'">'
            . '<source media="(max-width: 1399.98px)" srcset="'. rex_escape($urls['desktop']) .'">'
            . '<img src="'. rex_escape($urls['xxl']) .'" alt="'. rex_escape($alt) .'"'. $title_attr . $img_attributes . '>'
            . '</picture>';
    }
}
