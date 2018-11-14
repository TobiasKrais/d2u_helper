<?php
// Delete Media Manager media types
$sql = rex_sql::factory();
$sql->setQuery("SELECT id FROM ". \rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_yfeed_small'");
if($sql->getRows() > 0) {
	$id = $sql->getValue("id");
	$sql->setQuery("DELETE FROM ". \rex::getTablePrefix() ."media_manager_type_effect WHERE `type_id` = ". $id);
	$sql->setQuery("DELETE FROM ". \rex::getTablePrefix() ."media_manager_type WHERE `type_id` = ". $id);
}
$sql->setQuery("SELECT id FROM ". \rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_yfeed_large'");
if($sql->getRows() > 0) {
	$id = $sql->getValue("id");
	$sql->setQuery("DELETE FROM ". \rex::getTablePrefix() ."media_manager_type_effect WHERE `type_id` = ". $id);
	$sql->setQuery("DELETE FROM ". \rex::getTablePrefix() ."media_manager_type WHERE `type_id` = ". $id);
}

// Delete language replacements
if(!class_exists('d2u_helper_lang_helper')) {
	// Load class in case addon is deactivated
	require_once 'lib/d2u_helper_lang_helper.php';
}
d2u_helper_lang_helper::factory()->uninstall();