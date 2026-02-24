<?php

if (!rex_addon::get('metainfo')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_templates_install_metainfo'));
    return false;
}

if (!rex_addon::get('yrewrite')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_templates_install_yrewrite'));
    return false;
}

if (!rex_addon::get('d2u_news')->isAvailable()) {
    echo rex_view::warning(rex_i18n::msg('d2u_helper_templates_install_d2u_news'));
}

// Add clang_icon as Metainfo
rex_metainfo_add_field('translate:d2u_helper_icon', 'clang_icon', 1, '', rex_metainfo_table_manager::FIELD_REX_MEDIA_WIDGET, '', 'types="gif,jpg,png,webp,svg" preview="1"');

// Enable news column for existing installations (one-time migration)
if (!rex_addon::get('d2u_helper')->hasConfig('template_show_news_column')) {
    rex_addon::get('d2u_helper')->setConfig('template_show_news_column', true);
}

// Add slogan as Metainfo
rex_metainfo_add_field('translate:d2u_helper_settings_template_04_1_slogan', 'art_slogan', 1, '', rex_metainfo_table_manager::FIELD_TEXTAREA, '');
