<?php
	$offset_lg_cols = intval("REX_VALUE[17]");
	$offset_lg = "";
	if($offset_lg_cols > 0) {
		$offset_lg = " mr-lg-auto ml-lg-auto ";
	}
?>

<div class="col-sm-12 col-md-8 <?php print $offset_lg; ?>">
<?php
	$ask_address = "REX_VALUE[2]" == 'true' ? TRUE : FALSE;
	$show_gdpr_hint = "REX_VALUE[3]" == 'true' ? TRUE : FALSE;

	// Form
	$form_data = 'text|name|'. \Sprog\Wildcard::get('d2u_helper_module_11_name') .' *|||{"required":"required"}'. PHP_EOL;
	if($ask_address) {
		$form_data .= 'text|street|'. \Sprog\Wildcard::get('d2u_helper_module_11_street') .'
			text|zip|'. \Sprog\Wildcard::get('d2u_helper_module_11_zip') .'
			text|city|'. \Sprog\Wildcard::get('d2u_helper_module_11_city') . PHP_EOL;
	}
	$form_data .= 'text|phone|'. \Sprog\Wildcard::get('d2u_helper_module_11_phone') .' *|||{"required":"required"}
		text|email|'. \Sprog\Wildcard::get('d2u_helper_module_11_email') .' *|||{"required":"required"}
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

	$yform = new rex_yform();
	$yform->setFormData(trim($form_data));

	// Spam protection
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

	$yform->setObjectparams("form_action", rex_getUrl(rex_article::getCurrentId()));
	$yform->setObjectparams("Error-occured", \Sprog\Wildcard::get('d2u_helper_module_11_validate_title'));
	$yform->setObjectparams("real_field_names", TRUE);

	// action - showtext
	$yform->setActionField("showtext", [\Sprog\Wildcard::get('d2u_helper_module_11_thanks')]);
 
	$mail_from = '###email###';
    $mail_to = ('REX_VALUE[1]' != '') ? 'REX_VALUE[1]' : rex::getErrorEmail();
    $mail_subject = \Sprog\Wildcard::get('d2u_helper_module_11_contact_request') .' '. rex::getServer();
    $mail_body = str_replace('<br />', '', rex_yform::unhtmlentities(\Sprog\Wildcard::get('d2u_helper_module_11_contact_request_intro') .':
		'. \Sprog\Wildcard::get('d2u_helper_module_11_name') .': ###name###
		'. ($ask_address ? \Sprog\Wildcard::get('d2u_helper_module_11_street') .': ###street###'. PHP_EOL . \Sprog\Wildcard::get('d2u_helper_module_11_zip') .', '. \Sprog\Wildcard::get('d2u_helper_module_11_city') .': ###zip### ###city###' : '') .'
		'. \Sprog\Wildcard::get('d2u_helper_module_11_phone') .': ###phone###
		'. \Sprog\Wildcard::get('d2u_helper_module_11_email') .': ###email###

		'. \Sprog\Wildcard::get('d2u_helper_module_11_message') .':
		###message###'
	));
	$yform->setActionField('email', [$mail_from, $mail_to, $mail_subject, $mail_body]);
	
	echo $yform->getForm();
?>
</div>