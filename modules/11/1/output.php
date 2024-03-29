<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' d2u_helper_module_11_1">';

$ask_address = 'REX_VALUE[2]' === 'true' ? true : false; /** @phpstan-ignore-line */
$show_gdpr_hint = 'REX_VALUE[3]' == 'true' ? true : false; /** @phpstan-ignore-line */

// Form
$yform = new rex_yform();

$form_data = 'text|name|'. \Sprog\Wildcard::get('d2u_helper_module_form_name') .' *|||{"required":"required"}'. PHP_EOL;
if ($ask_address) { /** @phpstan-ignore-line */
    $form_data .= 'text|street|'. \Sprog\Wildcard::get('d2u_helper_module_form_street') .'
        text|zip|'. \Sprog\Wildcard::get('d2u_helper_module_form_zip') .'
        text|city|'. \Sprog\Wildcard::get('d2u_helper_module_form_city') . PHP_EOL;
}
$form_data .= 'text|phone|'. \Sprog\Wildcard::get('d2u_helper_module_form_phone') .' *|||{"required":"required"}
    text|email|'. \Sprog\Wildcard::get('d2u_helper_module_form_email') .' *|||{"required":"required"}';

if (rex_addon::get('yform_spam_protection')->isAvailable()) {
    $form_data .= '
        spam_protection|honeypot|Bitte nicht ausfüllen|'. \Sprog\Wildcard::get('d2u_helper_module_form_validate_spam_detected') .'|0';
} else {
    // Spam protection
    $form_data .= '
        html|honeypot||<div class="hide-validation">
        text|mailvalidate|'. \Sprog\Wildcard::get('d2u_helper_module_form_email') .'||no_db
        validate|compare_value|mailvalidate||!=|'. \Sprog\Wildcard::get('d2u_helper_module_form_validate_spam_detected') .'|
        html|honeypot||</div>';
    $yform->setValueField('php', [
        'validate_timer',
        'spamschutz',
        '<?php echo \'<input name="validate_timer" type="hidden" value="' . microtime(true) . '" />\' ?>',
    ]);
    $yform->setValidateField('customfunction', [
        'validate_timer',
        'TobiasKrais\D2UHelper\FrontendHelper::yform_validate_timer',
        '5',
        \Sprog\Wildcard::get('d2u_helper_module_form_validate_spambots'),
    ]);
}
$form_data .= '
    textarea|message|'. \Sprog\Wildcard::get('d2u_helper_module_form_message') .' *|||{"required":"required"}'. PHP_EOL;
if ($show_gdpr_hint) { /** @phpstan-ignore-line */
    $form_data .= 'checkbox|privacy_policy_accepted|'. \Sprog\Wildcard::get('d2u_helper_module_form_privacy_policy') .' *|0,1|0' . PHP_EOL;
}
$form_data .= 'html||<br>* '. \Sprog\Wildcard::get('d2u_helper_module_form_required') .'<br><br>

    submit|submit|'. \Sprog\Wildcard::get('d2u_helper_module_form_send') .'|no_db

    validate|empty|name|'. \Sprog\Wildcard::get('d2u_helper_module_form_validate_name') .'
    validate|empty|phone|'. \Sprog\Wildcard::get('d2u_helper_module_form_validate_phone') .'
    validate|empty|email|'. \Sprog\Wildcard::get('d2u_helper_module_form_validate_email') .'
    validate|type|email|email|'. \Sprog\Wildcard::get('d2u_helper_module_form_validate_email') .'
    validate|empty|message|'. \Sprog\Wildcard::get('d2u_helper_module_form_validate_message') . PHP_EOL;
if ($show_gdpr_hint) { /** @phpstan-ignore-line */
    $form_data .= 'validate|empty|privacy_policy_accepted|'. \Sprog\Wildcard::get('d2u_helper_module_form_validate_privacy_policy') . PHP_EOL;
}
$mail_to = 'REX_VALUE[1]' !== '' ? 'REX_VALUE[1]' : rex::getErrorEmail(); /** @phpstan-ignore-line */
$form_data .= 'action|tpl2email|d2u_helper_module_11_1|'. $mail_to .PHP_EOL;
// Prevent setting a cookie
//	$form_data .= 'objparams|csrf_protection|0';

$yform->setFormData(trim($form_data));

$yform->setObjectparams('Error-occured', \Sprog\Wildcard::get('d2u_helper_module_form_validate_title'));
$yform->setObjectparams('form_action', rex_getUrl(rex_article::getCurrentId()));
$yform->setObjectparams('form_name', 'd2u_helper_module_11_1_'. $this->getCurrentSlice()->getId()); /** @phpstan-ignore-line */
$yform->setObjectparams('real_field_names', true);

// action - showtext
$yform->setActionField('showtext', [\Sprog\Wildcard::get('d2u_helper_module_form_thanks')]);

echo $yform->getForm();
?>
</div>