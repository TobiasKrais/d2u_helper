<?php

if (\rex::isBackend() && is_object(\rex::getUser())) {
    // Correct name of rights
    rex_perm::register('d2u_helper[]', rex_i18n::msg('d2u_helper_rights_all'));
    rex_perm::register('d2u_helper[settings]', rex_i18n::msg('d2u_helper_rights_settings'), rex_perm::OPTIONS);

    $d2u_helper = rex_addon::get('d2u_helper');
    if ($d2u_helper instanceof rex_addon) {
        rex_view::addCssFile($d2u_helper->getAssetsUrl('d2u_helper_backend.css'));
    }
    // rex_view::addJsFile($addon->getAssetsUrl('js/script.js'), [rex_view::JS_IMMUTABLE => true]);
}

if (!\rex::isBackend()) {
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

    if (rex_config::get('d2u_helper', 'article_id_privacy_policy', 0) > 0 || rex_config::get('d2u_helper', 'article_id_impress', 0) > 0) {
        // Try to replace as last one, esp. after sprog calls OUTPUT_FILTER
        rex_extension::register('PACKAGES_INCLUDED', static function () {
            rex_extension::register('OUTPUT_FILTER', 'replace_privacy_policy_links');
        }, rex_extension::LATE);
    }
} else {
    rex_extension::register('ART_PRE_DELETED', 'rex_d2u_helper_article_is_in_use');
    rex_extension::register('CLANG_DELETED', 'rex_d2u_helper_clang_deleted');
    rex_extension::register('MEDIA_IS_IN_USE', 'rex_d2u_helper_media_is_in_use');
}

/**
 * Adds some style and script stuff to the header.
 * @param rex_extension_point<string> $ep Redaxo extension point
 */
function appendToPageD2UHelperFiles(rex_extension_point $ep): void
{
    $VERSION_BOOTSTRAP = '4.6.1';
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
    if (((bool) $addon->getConfig('include_module', false) && '' !== d2u_addon_frontend_helper::getModulesCSS()) || 'none' !== (string) $addon->getConfig('include_menu')) {
        $insert_head .= '<link rel="stylesheet" type="text/css" href="/index.php?d2u_helper=helper.css" />' . PHP_EOL;
    }

    // Menu stuff in header
    if ('none' !== (string) $addon->getConfig('include_menu')) {
        $insert_head .= '<script src="/index.php?position=head&amp;d2u_helper=helper.js"></script>' . PHP_EOL;
    }

    $ep->setSubject(str_replace('</head>', $insert_head .'</head>', $ep->getSubject()));

    // Vor dem </body> einfügen
    if ((bool) $addon->getConfig('include_bootstrap4', false)) {
        $insert_body .= '<script src="'. $addon->getAssetsUrl('bootstrap4/bootstrap.bundle.min.js') .'?v='. $VERSION_BOOTSTRAP .'"></script>' . PHP_EOL;
    }

    // Module stuff in body
    if ((bool) $addon->getConfig('include_module', false) && '' !== d2u_addon_frontend_helper::getModulesJS()) {
        $insert_body .= '<script src="/index.php?position=body&amp;d2u_helper=helper.js"></script>' . PHP_EOL;
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
 * @param rex_extension_point<string> $ep Redaxo extension point
 * @return array<string> Warning message as array
 */
function rex_d2u_helper_clang_deleted(rex_extension_point $ep)
{
    /** @var array<string> $warning */
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
 * @param rex_extension_point<string> $ep Redaxo extension point
 * @return array<string> Warning message as array
 */
function rex_d2u_helper_media_is_in_use(rex_extension_point $ep)
{
    /** @var array<string> $warning */
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
        $css .= d2u_addon_frontend_helper::getModulesCSS();
    }

    // Include menu CSS
    if ('megamenu' === (string) $d2u_helper->getConfig('include_menu')) {
        $css .= d2u_addon_frontend_helper::prepareCSS(d2u_mobile_navi_mega_menu::getAutoCSS());
    } elseif ('multilevel' === (string) $d2u_helper->getConfig('include_menu')) {
        $css .= d2u_addon_frontend_helper::prepareCSS(d2u_mobile_navi::getAutoCSS());
    } elseif ('slicknav' === (string) $d2u_helper->getConfig('include_menu')) {
        $css .= d2u_addon_frontend_helper::prepareCSS(d2u_mobile_navi_slicknav::getAutoCSS());
    } elseif ('smartmenu' === (string) $d2u_helper->getConfig('include_menu')) {
        $css .= d2u_addon_frontend_helper::prepareCSS(d2u_mobile_navi_smartmenus::getAutoCSS());
    }

    echo $css;
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
            $js .= d2u_addon_frontend_helper::getModulesJS();
        }
    } elseif ('head' === $position) {
        // MultiLevel menu JS
        if ('multilevel' === (string) $d2u_helper->getConfig('include_menu')) {
            $js .= d2u_mobile_navi::getAutoJS();
        }
        // Slicknav menu JS
        if ('slicknav' === (string) $d2u_helper->getConfig('include_menu')) {
            $js .= d2u_mobile_navi_slicknav::getAutoJS();
        }
        // Smartmenu menu JS
        if ('smartmenu' === (string) $d2u_helper->getConfig('include_menu')) {
            $js .= d2u_mobile_navi_smartmenus::getAutoJS();
        }
        // Mega menu JS
        if ('megamenu' === (string) $d2u_helper->getConfig('include_menu')) {
            $js .= d2u_mobile_navi_mega_menu::getAutoJS();
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
    echo d2u_addon_frontend_helper::prepareCSS($css);

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
        $template_manager = new D2UTemplateManager(D2UTemplateManager::getD2UHelperTemplates());
        $current_template = $template_manager->getTemplate($d2u_template_id);
        if ($current_template instanceof D2UTemplate) {
            $css .= $current_template->getCSS();
        }
    }

    // Custom CSS
    $d2u_helper = rex_addon::get('d2u_helper');
    if ($d2u_helper->hasConfig('custom_css') && file_exists(rex_path::media((string) $d2u_helper->getConfig('custom_css')))) {
        $css .= file_get_contents(rex_path::media((string) $d2u_helper->getConfig('custom_css')));
    }

    // Apply template settings and compress
    echo d2u_addon_frontend_helper::prepareCSS($css);

    exit;
}
