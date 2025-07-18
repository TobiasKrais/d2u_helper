<?php

$return = true;
if (!rex_addon::get('sprog')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_modules_error_sprog'));
    $return = false;
}
if (!rex_addon::get('yform')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_modules_error_yform'));
    $return = false;
} else {
    // YForm e-mail template
    $sql = rex_sql::factory();
    $sql->setQuery('SELECT * FROM '. \rex::getTablePrefix() ."yform_email_template WHERE name = 'd2u_helper_module_11_1'");
    if (0 === $sql->getRows()) {
        $sql->setQuery('INSERT INTO '. \rex::getTablePrefix() ."yform_email_template (`name`, `mail_from`, `mail_from_name`, `mail_reply_to`, `mail_reply_to_name`, `subject`, `body`, `body_html`, `attachments`) VALUES
			('d2u_helper_module_11_1', '', '', 'REX_YFORM_DATA[field=\"email\"]', 'REX_YFORM_DATA[field=\"name\"]', '<?= Sprog\\\\Wildcard::get(''d2u_helper_module_form_contact_request'') .'' ''. (rex_addon::get(''yrewrite'')->isAvailable() ? rex_yrewrite::getCurrentDomain()->getUrl() : rex::getServer());', '', '<?php\r\nprint \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_form_contact_request_intro'') .''<br><br>'';\r\nprint ''<b>''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_form_name'') .''</b>: REX_YFORM_DATA[field=\"name\"]<br>'';\r\nif (''REX_YFORM_DATA[field=\"street\"]'' != \"\") {\r\n    print ''<b>''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_form_street'') .''</b>: REX_YFORM_DATA[field=\"street\"]<br>'';\r\n}\r\nif (''REX_YFORM_DATA[field=\"zip\"]'' != \"\" || ''REX_YFORM_DATA[field=\"city\"]'' != \"\") {\r\n    print ''<b>''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_form_zip'') .'', ''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_form_city'') .''</b>: REX_YFORM_DATA[field=\"zip\"] REX_YFORM_DATA[field=\"city\"]<br>'';\r\n}\r\nif (''REX_YFORM_DATA[field=\"phone\"]'' != \"\") {\r\n    print ''<b>''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_form_phone'') .''</b>: REX_YFORM_DATA[field=\"phone\"]<br>'';\r\n}\r\nprint ''<b>''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_form_email'') .''</b>: REX_YFORM_DATA[field=\"email\"]<br><br>'';\r\nprint ''<b>''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_form_message'') .'':</b><br>''. nl2br(''REX_YFORM_DATA[field=\"message\"]'') .''<br><br>'';\r\n?>', '');");
    } else {
        $sql->setQuery('UPDATE '. \rex::getTablePrefix() ."yform_email_template SET `subject` = REPLACE(`subject`, '<?php print', '<?=') WHERE name = 'd2u_helper_module_11_1'");
        $sql->setQuery('UPDATE '. \rex::getTablePrefix() ."yform_email_template SET `subject` = REPLACE(`subject`, 'rex::getServer())', 'rex::getServer());') WHERE name = 'd2u_helper_module_11_1' AND `subject` LIKE '%rex::getServer())'");
    }
}
if (!(bool) rex_config::get('d2u_helper', 'lang_replacements_install', false)) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_modules_error_sprog_replacements'));
    $return = false;
}

return $return;
