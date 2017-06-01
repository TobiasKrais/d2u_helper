<?php
$sql = rex_sql::factory();
// Delete Media Manager media types
$sql->setQuery("DELETE FROM ". rex::getTablePrefix() ."media_manager_type WHERE name LIKE 'd2u_helper%'");
$sql->setQuery("DELETE FROM ". rex::getTablePrefix() ."media_manager_type_effect WHERE createuser = 'd2u_helper'");