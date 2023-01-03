<?php
$d2u_helper = rex_addon::get('d2u_helper');
if($d2u_helper instanceof rex_addon && (strval($d2u_helper->getConfig('footer_text', "")) !== "" || rex_addon::get('consent_manager')->isAvailable())) {
	print '<div class="row">';
	if(strval($d2u_helper->getConfig('footer_text', "")) !== "") {
		print '<div class="col-12 footer-text justify-content-center">'. $d2u_helper->getConfig('footer_text') .'</div>';
	}
	if(rex_addon::get('consent_manager')->isAvailable()) {
		print '<div class="col-12 footer-text justify-content-center"><a class="consent_manager-show-box-reload">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a></div>';
	}
	print '</div>';
}