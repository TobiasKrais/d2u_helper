<?php
	$cols_sm = "REX_VALUE[20]";
	if($cols_sm == "") {
		$cols_sm = 12;
	}
	$cols_md = "REX_VALUE[19]";
	if($cols_md == "") {
		$cols_md = 12;
	}
	$cols_lg = "REX_VALUE[18]";
	if($cols_lg == "") {
		$cols_lg = 8;
	}
	$offset_lg_cols = intval("REX_VALUE[17]");
	$offset_lg = "";
	if($offset_lg_cols > 0) {
		$offset_lg = " mr-lg-auto ml-lg-auto ";
	}
	
	print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
	
	$ask_address = "REX_VALUE[2]" == 'true' ? TRUE : FALSE;
	$show_gdpr_hint = "REX_VALUE[3]" == 'true' ? TRUE : FALSE;

	// Form
	$yform = new rex_yform();

	$form_data = 'text|name|'. \Sprog\Wildcard::get('d2u_helper_module_11_name') .' *|||{"required":"required"}'. PHP_EOL;
	if($ask_address) {
		$form_data .= 'text|street|'. \Sprog\Wildcard::get('d2u_helper_module_11_street') .'
			text|zip|'. \Sprog\Wildcard::get('d2u_helper_module_11_zip') .'
			text|city|'. \Sprog\Wildcard::get('d2u_helper_module_11_city') . PHP_EOL;
	}
	$form_data .= 'text|phone|'. \Sprog\Wildcard::get('d2u_helper_module_11_phone') .' *|||{"required":"required"}
		text|email|'. \Sprog\Wildcard::get('d2u_helper_module_11_email') .' *|||{"required":"required"}';
			
	if(rex_addon::get('yform_spam_protection')->isAvailable()) {
		$form_data .= '
			spam_protection|honeypot|Bitte nicht ausfüllen|'. $tag_open .'d2u_helper_module_11_validate_spam_detected'. $tag_close .'|0';					
	}
	else {
		// Spam protection
		$form_data .= '
			html|honeypot||<div class="hide-validation">
			text|mailvalidate|'. $tag_open .'d2u_helper_module_11_email'. $tag_close .'||no_db
			validate|compare_value|mailvalidate||!=|'. $tag_open .'d2u_helper_module_11_validate_spam_detected'. $tag_close .'|
			html|honeypot||</div>';
		$yform->setValueField('php', [
			'validate_timer',
			'spamschutz',
			'<?php echo \'<input name="validate_timer" type="hidden" value="' . microtime(true) . '" />\' ?>'
		]);
		$yform->setValidateField('customfunction', [
			'validate_timer',
			'd2u_addon_frontend_helper::yform_validate_timer',
			'5',
			\Sprog\Wildcard::get('d2u_helper_module_11_validate_spambots')
		]);
	}
	$form_data .= '
		textarea|message|'. \Sprog\Wildcard::get('d2u_helper_module_11_message') .' *|||{"required":"required"}'. PHP_EOL;
	if($show_gdpr_hint) {
		$form_data .= 'checkbox|privacy_policy_accepted|'. \Sprog\Wildcard::get('d2u_helper_module_11_privacy_policy') .' *|0,1|0' . PHP_EOL;
	}
	$form_data .= 'html||<br>* '. \Sprog\Wildcard::get('d2u_helper_module_11_required') .'<br><br>

		submit|submit|'. \Sprog\Wildcard::get('d2u_helper_module_11_send') .'|no_db

		validate|empty|name|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_name') .'
		validate|empty|phone|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_phone') .'
		validate|empty|email|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_email') .'
		validate|type|email|email|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_email') .'
		validate|empty|message|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_message') . PHP_EOL;
	if($show_gdpr_hint) {
		$form_data .= 'validate|empty|privacy_policy_accepted|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_privacy_policy') . PHP_EOL;
	}
    $mail_to = 'REX_VALUE[1]' ?? rex::getErrorEmail();
	$form_data .= 'action|tpl2email|d2u_helper_module_11_1|'. $mail_to .PHP_EOL;
	// Prevent setting a cookie
//	$form_data .= 'objparams|csrf_protection|0';

	$yform->setFormData(trim($form_data));

	$yform->setObjectparams("form_action", rex_getUrl(rex_article::getCurrentId()));
	$yform->setObjectparams("Error-occured", \Sprog\Wildcard::get('d2u_helper_module_11_validate_title'));
	$yform->setObjectparams("real_field_names", TRUE);

	// action - showtext
	$yform->setActionField("showtext", [\Sprog\Wildcard::get('d2u_helper_module_11_thanks')]);

	echo $yform->getForm();
?>
</div>