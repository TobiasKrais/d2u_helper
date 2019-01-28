<?php
if(!rex_addon::get('metainfo')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_templates_install_metainfo'));
	return FALSE;
}

// Add art_file as Metainfo
$query_metainfo = "SELECT name FROM ". \rex::getTablePrefix() ."metainfo_field "
	."WHERE name = 'art_file'";
$result_metainfo = rex_sql::factory();
$result_metainfo->setQuery($query_metainfo);
if($result_metainfo->getRows() == 0) {
	$insertmod = rex_sql::factory();
	$insertmod->setTable(\rex::getTablePrefix() . 'metainfo_field');
	$insertmod->setValue('title', 'translate:d2u_helper_metainfo_headerpic');
	$insertmod->setValue('name', 'art_file');
	$insertmod->setValue('priority', 1);
	$insertmod->setValue('type_id', 6);
	$insertmod->setValue('params', 'types="png,jpg,PNG,JPG" preview="1"');
	$insertmod->addGlobalCreateFields();
	$insertmod->insert();
}
// Add art_file as column
$query_art_file = "SHOW COLUMNS FROM ". \rex::getTablePrefix() ."article LIKE 'art_file'";
$result_art_file = rex_sql::factory();
$result_art_file->setQuery($query_art_file);
if($result_art_file->getRows() == 0) {
	$query_add_art_file = "ALTER TABLE ". \rex::getTablePrefix() ."article "
		. "ADD art_file VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
	$result_add_art_file = rex_sql::factory();
	$result_add_art_file->setQuery($query_add_art_file);
}