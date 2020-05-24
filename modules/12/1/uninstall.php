<?php
$sql = rex_sql::factory();
// Delete Media Manager media types
$sql->setQuery("SELECT id FROM ". \rex::getTablePrefix() ."media_manager_type WHERE name LIKE 'd2u_helper_feeds%'");
for($i = 0; $i < $sql->getRows(); $i++) {
	$cleanup_sql = rex_sql::factory();
	$cleanup_sql->setQuery("DELETE FROM ". \rex::getTablePrefix() ."media_manager_type_effect WHERE id = ". $sql->getValue('id'));
	$sql->next();
}
$sql->setQuery("DELETE FROM ". \rex::getTablePrefix() ."media_manager_type WHERE name LIKE 'd2u_helper_feeds%'");