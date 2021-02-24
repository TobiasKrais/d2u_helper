<?php
if(!rex_addon::get('metainfo')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_templates_install_metainfo'));
	return FALSE;
}

if(!rex_addon::get('yrewrite')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_templates_install_yrewrite'));
	return FALSE;
}

// Add clang_icon as Metainfo
$query_clang_icon = "SELECT name FROM ". \rex::getTablePrefix() ."metainfo_field "
	."WHERE name = 'clang_icon'";
$result_clang_icon = rex_sql::factory();
$result_clang_icon->setQuery($query_clang_icon);
if($result_clang_icon->getRows() == 0) {
	$insertmod = rex_sql::factory();
	$insertmod->setTable(\rex::getTablePrefix() . 'metainfo_field');
	$insertmod->setValue('title', 'translate:d2u_helper_lang_icon');
	$insertmod->setValue('name', 'clang_icon');
	$insertmod->setValue('priority', 1);
	$insertmod->setValue('type_id', 6);
	$insertmod->setValue('params', 'types="gif,jpg,png,svg,webp,GIF,JPG,PNG,SVG,WEBP" preview="1"');
	$insertmod->addGlobalCreateFields();
	$insertmod->insert();
}
// Add clang_icon as column
$query_art_clang_icon = "SHOW COLUMNS FROM ". \rex::getTablePrefix() ."clang LIKE 'clang_icon'";
$result_art_clang_icon = rex_sql::factory();
$result_art_clang_icon->setQuery($query_art_clang_icon);
if($result_art_clang_icon->getRows() == 0) {
	$query_add_clang = "ALTER TABLE ". \rex::getTablePrefix() ."clang "
		. "ADD clang_icon VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL";
	$result_add_clang = rex_sql::factory();
	$result_add_clang->setQuery($query_add_clang);
}