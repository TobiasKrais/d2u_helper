<?php

// Delete e-mail template
$sql = rex_sql::factory();
$sql->setQuery('DELETE FROM '. \rex::getTablePrefix() ."yform_email_template WHERE name = 'd2u_helper_module_11_1'");
