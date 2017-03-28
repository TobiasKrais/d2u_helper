<?php
if(!rex_addon::get('metainfo')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_templates_install_metainfo'));
	return FALSE;
}

if(!rex_addon::get('yrewrite')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_templates_install_yrewrite'));
	return FALSE;
}

if(!rex_addon::get('emailobfuscator')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_templates_install_emailobfuscator'));
	return FALSE;
}

$query_metainfo = "SELECT name FROM ". rex::getTablePrefix() ."metainfo_field "
	."WHERE name = 'art_file'";
$result_metainfo = rex_sql::factory();
$result_metainfo->setQuery($query_metainfo);
if($result_metainfo->getRows() == 0) {
	$insertmod = rex_sql::factory();
	$insertmod->setTable(rex::getTablePrefix() . 'metainfo_field');
	$insertmod->setValue('title', 'translate:d2u_helper_metainfo_headerpic');
	$insertmod->setValue('name', 'art_file');
	$insertmod->setValue('priority', 1);
	$insertmod->setValue('type_id', 6);
	$insertmod->setValue('params', 'types="png,jpg" preview="1"');
	$insertmod->addGlobalCreateFields();
	$insertmod->insert();
}
