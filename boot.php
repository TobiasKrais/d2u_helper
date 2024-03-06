<?php

use FriendsOfRedaxo\D2UHelper\FrontendHelper;
use FriendsOfRedaxo\D2UHelper\FrontendNavigationMegaMenu;

if (\rex::isBackend() && is_object(\rex::getUser())) {
    // Correct name of rights
    rex_perm::register('d2u_helper[]', rex_i18n::msg('d2u_helper_rights_all'));
    rex_perm::register('d2u_helper[settings]', rex_i18n::msg('d2u_helper_rights_settings'), rex_perm::OPTIONS);
    rex_perm::register('d2u_helper[translation_helper]', rex_i18n::msg('d2u_helper_translations_rights'), rex_perm::OPTIONS);

    rex_view::addCssFile(rex_url::addonAssets('d2u_helper', 'd2u_helper_backend.css'));
    if(!rex_addon::get('mform')->isAvailable()) {
        rex_view::addCssFile(rex_url::addonAssets('d2u_helper', 'mform_imglist.css'));
        // load JS after mediapool JS to overwrite some of its functions
        rex_extension::register('PACKAGES_INCLUDED', static function () {
            rex_view::addJsFile(rex_url::addonAssets('d2u_helper', 'mform_imglist.js'));
        });
    }

    // translation helper
    if (1 === rex_clang::count()) {
        $page = $this->getProperty('page');
        unset($page['subpages']['translation_helper']);
        $this->setProperty('page', $page);
    }
}

if (rex::isBackend()) {
    rex_extension::register('ART_PRE_DELETED', 'rex_d2u_helper_article_is_in_use');
    rex_extension::register('CLANG_DELETED', 'rex_d2u_helper_clang_deleted');
    rex_extension::register('MEDIA_IS_IN_USE', 'rex_d2u_helper_media_is_in_use');
} else {
    rex_extension::register('PACKAGES_INCLUDED', static function ($params) {
        // If CSS or JS is requested
        if ('helper.css' === rex_request('d2u_helper', 'string')) {
            sendD2UHelperCSS();
        } elseif ('helper.js' === rex_request('d2u_helper', 'string')) {
            if ('head' === rex_request('position', 'string')) {
                sendD2UHelperJS('head');
            } else {
                sendD2UHelperJS('body');
            }
        } elseif ('template.css' === rex_request('d2u_helper', 'string')) {
            sendD2UHelperTemplateCSS(rex_request('template_id', 'string', ''));
        } elseif ('custom.css' === rex_request('d2u_helper', 'string')) {
            sendD2UHelperCustomCSS();
        }
    });

    // Only frontend call
    rex_extension::register('OUTPUT_FILTER', 'appendToPageD2UHelperFiles');

    // Replace privacy policy and impress links, esp. after sprog calls OUTPUT_FILTER
    if (rex_config::get('d2u_helper', 'article_id_privacy_policy', 0) > 0 || rex_config::get('d2u_helper', 'article_id_impress', 0) > 0) {
        rex_extension::register('PACKAGES_INCLUDED', static function () {
            rex_extension::register('OUTPUT_FILTER', 'replace_privacy_policy_links');
        }, rex_extension::LATE);
    }

    // show table of contents
    if (rex_addon::get('sprog')->isAvailable()) {
        rex_extension::register('PACKAGES_INCLUDED', static function () {
            rex_extension::register('OUTPUT_FILTER', 'addD2UHelperTOC');
        });
    }
}

rex_extension::register('PACKAGES_INCLUDED', static function ($params) {
    /** @deprecated starting with version 2, class alias will be removed */
    class_alias('FriendsOfRedaxo\D2UHelper\ACronJob', 'D2U_Helper\ACronJob');
    class_alias('FriendsOfRedaxo\D2UHelper\ALangHelper', 'D2U_Helper\ALangHelper');
    class_alias('FriendsOfRedaxo\D2UHelper\BackendHelper', 'd2u_addon_backend_helper');
    class_alias('FriendsOfRedaxo\D2UHelper\FrontendHelper', 'd2u_addon_frontend_helper');
    class_alias('FriendsOfRedaxo\D2UHelper\FrontendNavigationResponsiveMultiLevel', 'd2u_mobile_navi');
    class_alias('FriendsOfRedaxo\D2UHelper\FrontendNavigationMegaMenu', 'd2u_mobile_navi_mega_menu');
    class_alias('FriendsOfRedaxo\D2UHelper\FrontendNavigationSlickNav', 'd2u_mobile_navi_slicknav');
    class_alias('FriendsOfRedaxo\D2UHelper\FrontendNavigationSmartmenu', 'd2u_mobile_navi_smartmenus');
    class_alias('FriendsOfRedaxo\D2UHelper\ITranslationHelper', 'D2U_Helper\ITranslationHelper');
    class_alias('FriendsOfRedaxo\D2UHelper\LangHelper', 'd2u_helper_lang_helper');
    class_alias('FriendsOfRedaxo\D2UHelper\Module', 'D2UModule');
    class_alias('FriendsOfRedaxo\D2UHelper\ModuleManager', 'D2UModuleManager');
    class_alias('FriendsOfRedaxo\D2UHelper\Template', 'D2UTemplate');
    class_alias('FriendsOfRedaxo\D2UHelper\TemplateManager', 'D2UTemplateManager');
});

/**
 * Adds table of contents on pages.
 * @param rex_extension_point<string> $ep Redaxo extension point
 */
function addD2UHelperTOC(rex_extension_point $ep): void
{
    $content = $ep->getSubject();

    // is string not empty an can it be HTML
    if(false !== strpos($content, '<')) {
        // table of contents
        $toc_html = '<p onClick="toggle_toc()"><span class="fa-icon icon_toc"></span>'. \Sprog\Wildcard::get('d2u_helper_toc') .'<span class="fa-icon h_toggle icon_right" id="toc_arrow"></span></p>'. PHP_EOL;
        $toc_html .= '<ol id="toc_list"><li>';

        // load code in a DOM document
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        @$dom->loadHTML($content);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        $toc_element = $xpath->query('//div[@id="d2u_helper_toc"]');

        // is module
        if ($toc_element instanceof DOMNodeList && $toc_element->length > 0) {
            // find all headings in article
            $headings = $xpath->query('//article//h2 | //article//h3 | //article//h4 | //article//h5 | //article//h6');

            $last_level = 0;
            $highest_level = 2;
            // add ids to headings
            if ($headings instanceof DOMNodeList) {
                foreach ($headings as $heading) {
                    $level = (int) $heading->nodeName[1] < $highest_level ? $highest_level : (int) $heading->nodeName[1];

                    if (0 === $last_level) {
                        $highest_level = $level;
                    } elseif ($last_level < $level) {
                        $toc_html .= '<ol><li>';
                    } elseif ($last_level > $level) {
                        while ($last_level > $level) {
                            $toc_html .= '</li></ol>';
                            --$last_level;
                        }
                        $toc_html .= '</li>'. PHP_EOL .'<li>';
                    } else {
                        $toc_html .= '</li>'. PHP_EOL .'<li>';
                    }
                    $last_level = $level < $highest_level ? $highest_level : $level;

                    $id = 'heading-' . uniqid();
                    $anchor_node = $dom->createElement('a');
                    $anchor_node->setAttribute('name', $id);
                    $anchor_node->setAttribute('class', 'd2u_helper_toc_anchor');
                    $heading->insertBefore($anchor_node, $heading->firstChild);

                    $toc_html .= '<a href="#' . $id . '">' . $heading->nodeValue . '</a>';
                }
                while ($last_level >= $highest_level) {
                    $toc_html .= '</li></ol>';
                    --$last_level;
                }
            }

            // Element gefunden
            $toc_node = $dom->createDocumentFragment();
            $toc_node->appendXML($toc_html);
            if ($toc_element->item(0) instanceof DOMNode) {
                $toc_element->item(0)->appendChild($toc_node);
            }

            // update content
            $content = $dom->saveHTML();
        }
        
        if (false !== $content) {
            $ep->setSubject($content);
        }
    }
}

/**
 * Adds some style and script stuff to the header.
 * @param rex_extension_point<string> $ep Redaxo extension point
 */
function appendToPageD2UHelperFiles(rex_extension_point $ep): void
{
    $VERSION_BOOTSTRAP = '4.6.2';
    $addon = rex_addon::get('d2u_helper');

    // If insertion should be prevented, detect class "prevent_d2u_helper_styles"
    if (str_contains($ep->getSubject(), 'prevent_d2u_helper_styles')) {
        return;
    }

    $insert_head = '';
    $insert_body = '';
    // Vor dem </head> einfügen
    if ((bool) $addon->getConfig('include_jquery', false)) {
        // JQuery
        $file = 'jquery.min.js';
        $insert_head .= '<script src="'. rex_url::coreAssets($file) .'?buster='. filemtime(rex_path::coreAssets($file)) .'"></script>' . PHP_EOL;
    }
    if ((bool) $addon->getConfig('include_bootstrap4', false)) {
        // Bootstrap CSS
        $insert_head .= '<link rel="stylesheet" type="text/css" href="'.  $addon->getAssetsUrl('bootstrap4/bootstrap.min.css') .'?v='. $VERSION_BOOTSTRAP .'" />' . PHP_EOL;
    }

    // Consider module css or menu css
    if (((bool) $addon->getConfig('include_module', false) && '' !== FrontendHelper::getModulesCSS()) || 'none' !== (string) $addon->getConfig('include_menu')) {
        $insert_head .= '<link rel="stylesheet" type="text/css" href="'. rex_url::frontendController(['d2u_helper' => 'helper.css']) .'" />' . PHP_EOL;
    }

    // Menu stuff in header
    if ('none' !== (string) $addon->getConfig('include_menu')) {
        $insert_head .= '<script src="'. rex_url::frontendController(['position' => 'head', 'd2u_helper' => 'helper.js']) .'"></script>' . PHP_EOL;
    }

    $ep->setSubject(str_replace('</head>', $insert_head .'</head>', $ep->getSubject()));

    // Vor dem </body> einfügen
    if ((bool) $addon->getConfig('include_bootstrap4', false)) {
        $insert_body .= '<script src="'. $addon->getAssetsUrl('bootstrap4/bootstrap.bundle.min.js') .'?v='. $VERSION_BOOTSTRAP .'"></script>' . PHP_EOL;
    }

    // Module stuff in body
    if ((bool) $addon->getConfig('include_module', false) && '' !== FrontendHelper::getModulesJS()) {
        $insert_body .= '<script src="'. rex_url::frontendController(['position' => 'body', 'd2u_helper' => 'helper.js']) .'"></script>' . PHP_EOL;
    }
    $ep->setSubject(str_replace('</body>', $insert_body .'</body>', $ep->getSubject()));
}

/**
 * Replaces +++LINK_PRIVACY_POLICY+++ and +++LINK_IMPRESS+++ with URLs
 * for privacy policy an impress. The article ids are set in D2U Helper settings.
 * @param rex_extension_point<string> $ep Redaxo extension point
 */
function replace_privacy_policy_links(rex_extension_point $ep): void
{
    $content = $ep->getSubject();

    if (rex_config::get('d2u_helper', 'article_id_privacy_policy', 0) > 0) {
        $content = str_replace('+++LINK_PRIVACY_POLICY+++', rex_getUrl((string) rex_config::get('d2u_helper', 'article_id_privacy_policy')), $content);
    }
    if (rex_config::get('d2u_helper', 'article_id_impress', 0) > 0) {
        $content = str_replace('+++LINK_IMPRESS+++', rex_getUrl((string) rex_config::get('d2u_helper', 'article_id_impress')), $content);
    }

    $ep->setSubject($content);
}

/**
 * Checks if article is used by this addon.
 * @param rex_extension_point<string> $ep Redaxo extension point
 * @throws rex_api_exception If article is used
 * @return string Warning message as array
 */
function rex_d2u_helper_article_is_in_use(rex_extension_point $ep)
{
    $warning = [];
    $params = $ep->getParams();
    $article_id = (int) $params['id'];

    // Settings
    $addon = rex_addon::get('d2u_helper');
    if (($addon->hasConfig('article_id_privacy_policy') && (int) $addon->getConfig('article_id_privacy_policy') === $article_id) ||
        ($addon->hasConfig('article_id_impress') && (int) $addon->getConfig('article_id_impress') === $article_id) ||
        in_array($article_id, array_map('intval', explode(',', (string) $addon->getConfig('cta_box_article_ids'))), true)) {
        $warning[] = '<a href="index.php?page=d2u_helper/settings">'.
             rex_i18n::msg('d2u_helper_rights_all') .' - '. rex_i18n::msg('d2u_helper_settings') . '</a><br>';
    }

    if (count($warning) > 0) {
        throw new rex_api_exception(rex_i18n::msg('d2u_helper_rex_article_cannot_delete').'<ul><li>'. implode('</li><li>', $warning) .'</li></ul>');
    }

    return '';

}

/**
 * Deletes language specific configurations and objects.
 * @param rex_extension_point<array<string>> $ep Redaxo extension point
 * @return array<string> Warning message as array
 */
function rex_d2u_helper_clang_deleted(rex_extension_point $ep)
{
    $warning = $ep->getSubject();
    $params = $ep->getParams();
    $clang_id = (int) $params['id'];

    // Correct settings
    if ((int) rex_config::get('d2u_helper', 'default_lang', 0) === $clang_id) {
        rex_config::set('d2u_helper', 'default_lang', rex_clang::getStartId());
    }

    return $warning;
}

/**
 * Checks if media is used by this addon.
 * @param rex_extension_point<array<string>> $ep Redaxo extension point
 * @return array<string> Warning message as array
 */
function rex_d2u_helper_media_is_in_use(rex_extension_point $ep)
{
    $warning = $ep->getSubject();
    $params = $ep->getParams();
    $filename = addslashes($params['filename']);

    if ('' !== $filename) {
        // Settings
        $addon = rex_addon::get('d2u_helper');
        $is_in_use = false;
        if (($addon->hasConfig('template_header_pic') && (string) $addon->getConfig('template_header_pic') === $filename) ||
                ($addon->hasConfig('template_logo') && (string) $addon->getConfig('template_logo') === $filename) ||
                ($addon->hasConfig('template_print_header_pic') && (string) $addon->getConfig('template_print_header_pic') === $filename) ||
                ($addon->hasConfig('template_print_footer_pic') && (string) $addon->getConfig('template_print_footer_pic') === $filename) ||
                ($addon->hasConfig('footer_logo') && (string) $addon->getConfig('footer_logo') === $filename) ||
                ($addon->hasConfig('template_03_2_header_pic') && (string) $addon->getConfig('template_03_2_header_pic') === $filename) ||
                ($addon->hasConfig('template_03_2_footer_pic') && (string) $addon->getConfig('template_03_2_footer_pic') === $filename) ||
                ($addon->hasConfig('footer_facebook_icon') && (string) $addon->getConfig('footer_facebook_icon') === $filename) ||
                ($addon->hasConfig('custom_css') && (string) $addon->getConfig('custom_css') === $filename)
        ) {
            $is_in_use = true;
        }
        foreach (rex_clang::getAllIds() as $clang_id) {
            if ($addon->hasConfig('template_04_header_slider_pics_clang_'. $clang_id) && str_contains((string) $addon->getConfig('template_04_header_slider_pics_clang_'. $clang_id), $filename)) {
                $is_in_use = true;
            }
        }
        if ($is_in_use) {
            $message = '<a href="javascript:openPage(\'index.php?page=d2u_helper/settings\')">'.
                 rex_i18n::msg('d2u_helper_meta_title') .' '. rex_i18n::msg('d2u_helper_settings') . '</a>';
            if (!in_array($message, $warning, true)) {
                $warning[] = $message.'<br>';
            }
        }

        // Templates
        if ('false' !== (string) rex_config::get('d2u_helper', 'check_media_template', 'false')) {
            $sql_template = \rex_sql::factory();
            $query = 'SELECT DISTINCT id, name FROM ' . rex::getTablePrefix() . 'template WHERE content REGEXP ' . $sql_template->escape('(^|[^[:alnum:]+_-])'. $filename);
            $sql_template->setQuery($query);

            // Prepare warnings for templates
            for ($i = 0; $i < $sql_template->getRows(); ++$i) {
                $message = '<a href="javascript:openPage(\''. rex_url::backendPage('templates', ['template_id' => $sql_template->getValue('id'), 'function' => 'edit']) .'\')">'. rex_i18n::msg('header_template') .': '. $sql_template->getValue('name') .'</a>';
                if (!in_array($message, $warning, true)) {
                    $warning[] = $message.'<br>';
                }
                $sql_template->next();
            }
        }
    }

    return $warning;
}

/**
 * Sends CSS file and exits PHP Script. The CSS file consists of module and menu
 * css.
 */
function sendD2UHelperCSS(): void
{
    header('Content-type: text/css');
    $d2u_helper = rex_addon::get('d2u_helper');
    $css = '';
    // Module CSS
    if ((bool) $d2u_helper->getConfig('include_module', false)) {
        $css .= FrontendHelper::getModulesCSS();
    }

    // Include menu CSS
    if ('megamenu' === (string) $d2u_helper->getConfig('include_menu')) {
        $css .= \FriendsOfRedaxo\D2UHelper\FrontendNavigationMegaMenu::getAutoCSS();
    } elseif ('multilevel' === (string) $d2u_helper->getConfig('include_menu')) {
        $css .= \FriendsOfRedaxo\D2UHelper\FrontendNavigationResponsiveMultiLevel::getAutoCSS();
    } elseif ('slicknav' === (string) $d2u_helper->getConfig('include_menu')) {
        $css .= \FriendsOfRedaxo\D2UHelper\FrontendNavigationSlickNav::getAutoCSS();
    } elseif ('smartmenu' === (string) $d2u_helper->getConfig('include_menu')) {
        $css .= \FriendsOfRedaxo\D2UHelper\FrontendNavigationSmartmenu::getAutoCSS();
    }

    echo FrontendHelper::prepareCSS($css);
    exit;
}

/**
 * Sends JS file and exits PHP Script. The JS file consists of module js.
 * @param string $position JS position ("head" oder "body")
 */
function sendD2UHelperJS($position = 'head'): void
{
    header('Content-type: application/javascript');
    $d2u_helper = rex_addon::get('d2u_helper');
    $js = '';
    if ('body' === $position) {
        // Module JS
        if ((bool) $d2u_helper->getConfig('include_module', false)) {
            $js .= \FriendsOfRedaxo\D2UHelper\FrontendHelper::getModulesJS();
        }
    } elseif ('head' === $position) {
        // MultiLevel menu JS
        if ('multilevel' === (string) $d2u_helper->getConfig('include_menu')) {
            $js .= \FriendsOfRedaxo\D2UHelper\FrontendNavigationResponsiveMultiLevel::getAutoJS();
        }
        // Slicknav menu JS
        if ('slicknav' === (string) $d2u_helper->getConfig('include_menu')) {
            $js .= \FriendsOfRedaxo\D2UHelper\FrontendNavigationSlickNav::getAutoJS();
        }
        // Smartmenu menu JS
        if ('smartmenu' === (string) $d2u_helper->getConfig('include_menu')) {
            $js .= \FriendsOfRedaxo\D2UHelper\FrontendNavigationSmartmenu::getAutoJS();
        }
        // Mega menu JS
        if ('megamenu' === (string) $d2u_helper->getConfig('include_menu')) {
            $js .= \FriendsOfRedaxo\D2UHelper\FrontendNavigationMegaMenu::getAutoJS();
        }
    }
    echo $js;
    exit;
}

/**
 * Sends CustomCSS file and exits PHP Script.
 */
function sendD2UHelperCustomCSS(): void
{
    header('Content-type: text/css');
    $css = '';

    // Custom CSS
    $d2u_helper = rex_addon::get('d2u_helper');
    if ($d2u_helper->hasConfig('custom_css') && file_exists(rex_path::media((string) $d2u_helper->getConfig('custom_css')))) {
        $css .= file_get_contents(rex_path::media((string) $d2u_helper->getConfig('custom_css')));
    }

    // Apply template settings and compress
    echo \FriendsOfRedaxo\D2UHelper\FrontendHelper::prepareCSS($css);

    exit;
}

/**
 * Sends CSS file and exits PHP Script. The CSS file consists of template and
 * - if in settings checked - also module css.
 * @param string $d2u_template_id
 */
function sendD2UHelperTemplateCSS($d2u_template_id = ''): void
{
    header('Content-type: text/css');
    $css = '';
    // Template CSS
    if ('' !== $d2u_template_id) {
        $template_manager = new \FriendsOfRedaxo\D2UHelper\TemplateManager(\FriendsOfRedaxo\D2UHelper\TemplateManager::getD2UHelperTemplates());
        $current_template = $template_manager->getTemplate($d2u_template_id);
        if ($current_template instanceof \FriendsOfRedaxo\D2UHelper\Template) {
            $css .= $current_template->getCSS();
        }
    }

    // Custom CSS
    $d2u_helper = rex_addon::get('d2u_helper');
    if ($d2u_helper->hasConfig('custom_css') && file_exists(rex_path::media((string) $d2u_helper->getConfig('custom_css')))) {
        $css .= file_get_contents(rex_path::media((string) $d2u_helper->getConfig('custom_css')));
    }

    // Apply template settings and compress
    echo \FriendsOfRedaxo\D2UHelper\FrontendHelper::prepareCSS($css);

    exit;
}
