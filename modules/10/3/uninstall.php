<?php

$sql = rex_sql::factory();
$sql->setQuery('SELECT id FROM '. \rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_module_d2u_10-3'");
if ($sql->getRows() > 0) {
    $id = $sql->getValue('id');
    $sql->setQuery('DELETE FROM '. \rex::getTablePrefix() .'media_manager_type_effect WHERE `type_id` = '. $id);
    $sql->setQuery('DELETE FROM '. \rex::getTablePrefix() .'media_manager_type WHERE `type_id` = '. $id);
}
