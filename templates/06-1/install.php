<?php
if(!rex_addon::get('metainfo')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_templates_install_metainfo'));
	return FALSE;
}

// Add art_file as Metainfo
rex_metainfo_add_field('translate:d2u_helper_metainfo_headerpic', 'art_file', 1, '', rex_metainfo_table_manager::FIELD_REX_MEDIA_WIDGET, '', 'types="gif,jpg,png,webp,svg" preview="1"');