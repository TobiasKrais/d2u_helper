<?php
$return = TRUE;
if(!rex_config::get('d2u_helper', 'lang_replacements_install', FALSE)) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_error_settings_translations'));
	$return = $return ? FALSE : $return;
}
if(!rex_addon::get('sprog')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_error_sprog'));
	$return = $return ? FALSE : $return;
}
if(!rex_addon::get('yform')->isAvailable() || !rex_plugin::get('yform', 'email')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_error_yform'));
	$return = $return ? FALSE : $return;
}
else {
	// YForm e-mail template
	$sql = rex_sql::factory();
	$sql->setQuery("SELECT * FROM ". \rex::getTablePrefix() ."yform_email_template WHERE name = 'd2u_helper_module_11_1'");
	if($sql->getRows() == 0) {
		$sql->setQuery("INSERT INTO ". \rex::getTablePrefix() ."yform_email_template (`name`, `mail_from`, `mail_from_name`, `mail_reply_to`, `mail_reply_to_name`, `subject`, `body`, `body_html`, `attachments`) VALUES
			('d2u_helper_module_11_1', '', '', 'REX_YFORM_DATA[field=\"email\"]', 'REX_YFORM_DATA[field=\"name\"]', '<?php print \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_11_contact_request'') .'' ''. (\\\\rex_addon::get(''yrewrite'')->isAvailable() ? \\\\rex_yrewrite::getCurrentDomain()->getUrl() : \\\\rex::getServer()); ', '', '<?php\r\nprint \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_11_contact_request_intro'') .''<br><br>'';\r\nprint ''<b>''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_11_name'') .''</b>: REX_YFORM_DATA[field=\"name\"]<br>'';\r\nif (''REX_YFORM_DATA[field=\"street\"]'' != \"\") {\r\n    print ''<b>''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_11_street'') .''</b>: REX_YFORM_DATA[field=\"street\"]<br>'';\r\n}\r\nif (''REX_YFORM_DATA[field=\"zip\"]'' != \"\" || ''REX_YFORM_DATA[field=\"city\"]'' != \"\") {\r\n    print ''<b>''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_11_zip'') .'', ''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_11_city'') .''</b>: REX_YFORM_DATA[field=\"zip\"] REX_YFORM_DATA[field=\"city\"]<br>'';\r\n}\r\nif (''REX_YFORM_DATA[field=\"phone\"]'' != \"\") {\r\n    print ''<b>''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_11_phone'') .''</b>: REX_YFORM_DATA[field=\"phone\"]<br>'';\r\n}\r\nprint ''<b>''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_11_email'') .''</b>: REX_YFORM_DATA[field=\"email\"]<br><br>'';\r\nprint ''<b>''. \\\\Sprog\\\\Wildcard::get(''d2u_helper_module_11_message'') .'':</b><br>''. nl2br(''REX_YFORM_DATA[field=\"message\"]'') .''<br><br>'';\r\n?>', '');");
	}
}
if(!rex_config::get('d2u_helper', 'lang_replacements_install', FALSE)) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_error_sprog_replacements'));
	$return = $return ? FALSE : $return;	
}

return $return;