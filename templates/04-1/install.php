<?php
if(!rex_addon::get('metainfo')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_templates_install_metainfo'));
	return false;
}

if(!rex_addon::get('yrewrite')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_templates_install_yrewrite'));
	return false;
}

// Add clang_icon as Metainfo
rex_metainfo_add_field('translate:d2u_helper_icon', 'clang_icon', 1, '', rex_metainfo_table_manager::FIELD_REX_MEDIA_WIDGET, '', 'types="gif,jpg,png,webp,svg" preview="1"');

// Add slogan as Metainfo
rex_metainfo_add_field('translate:d2u_helper_settings_template_04_1_slogan', 'art_slogan', 1, '', rex_metainfo_table_manager::FIELD_TEXTAREA, '');