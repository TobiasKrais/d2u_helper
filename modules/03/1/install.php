<?php

$sql = rex_sql::factory();

// Update module values when upgrade to version >= 11
$module_key = 'd2u_03-1';
$sql->setQuery('SELECT id, revision FROM '. \rex::getTablePrefix() ."module WHERE `key` = '". $module_key ."'");
if ($sql->getRows() > 0 && $sql->getValue('revision') < 12) {
    $sql_update = rex_sql::factory();
    $sql_update->setQuery('UPDATE '. \rex::getTablePrefix() ."article_slice SET value18 = value20, value20 = '12' WHERE module_id = ". $sql->getValue('id'));
}