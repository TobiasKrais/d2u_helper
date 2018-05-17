<?php
	if(!function_exists('yform_validate_timer')) {
		/**
		 * Timer Spamprotection function
		 * @param string $label
		 * @param int $microtime
		 * @param int $seconds
		 * @return boolean
		 */
		function yform_validate_timer($label, $microtime, $seconds) {
			if (($microtime + $seconds) > microtime(true)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	$offset_lg_cols = intval("REX_VALUE[17]");
	$offset_lg = "";
	if($offset_lg_cols > 0) {
		$offset_lg = " mr-lg-auto ml-lg-auto ";
	}
?>

<div class="col-sm-12 col-md-8 <?php print $offset_lg; ?>">
<?php
	$ask_address = "REX_VALUE[2]" == 'true' ? TRUE : FALSE;

	// Form
	$form_data = 'text|name|'. \Sprog\Wildcard::get('d2u_helper_module_11_name') .' *'. PHP_EOL;
	if($ask_address) {
		$form_data .= 'text|street|'. \Sprog\Wildcard::get('d2u_helper_module_11_street') .'
			text|zip|'. \Sprog\Wildcard::get('d2u_helper_module_11_zip') .'
			text|city|'. \Sprog\Wildcard::get('d2u_helper_module_11_city') . PHP_EOL;
	}
	$form_data .= 'text|phone|'. \Sprog\Wildcard::get('d2u_helper_module_11_phone') .' *
		text|email|'. \Sprog\Wildcard::get('d2u_helper_module_11_email') .' *
		textarea|message|'. \Sprog\Wildcard::get('d2u_helper_module_11_message') .'
		checkbox|privacy_policy_accepted|'. \Sprog\Wildcard::get('d2u_helper_module_11_privacy_policy') .' *|no,yes|no

		html||<br>* '. \Sprog\Wildcard::get('d2u_helper_module_11_required') .'<br><br>
		php|validate_timer|Spamprotection|<input name="validate_timer" type="hidden" value="'. microtime(true) .'" />|
		captcha|'. \Sprog\Wildcard::get('d2u_helper_module_11_captcha') .'|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_captcha') .'|'. rex_getUrl(rex_article::getCurrentId()) .'

		submit|submit|'. \Sprog\Wildcard::get('d2u_helper_module_11_send') .'|no_db

		validate|empty|name|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_name') .'
		validate|empty|phone|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_phone') .'
		validate|empty|email|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_email') .'
		validate|email|email|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_email') .'
		validate|empty|message|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_message') .'
		validate|empty|privacy_policy_accepted|'. \Sprog\Wildcard::get('d2u_helper_module_11_validate_privacy_policy') .'
		validate|customfunction|validate_timer|yform_validate_timer|9|'.\Sprog\Wildcard::get('d2u_helper_module_11_validate_spambots') .'|';

	$yform = new rex_yform();
	$yform->setFormData(trim($form_data));
	$yform->setObjectparams("form_action", rex_getUrl(rex_article::getCurrentId()));
	$yform->setObjectparams("Error-occured", \Sprog\Wildcard::get('d2u_helper_module_11_validate_title'));

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