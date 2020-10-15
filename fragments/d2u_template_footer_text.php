<?php
	$d2u_helper = rex_addon::get('d2u_helper');
	if($d2u_helper->hasConfig('footer_text') && $d2u_helper->getConfig('footer_text') != "") {
		print '<div class="row"><div class="col-12 footer-text justify-content-center">'. $d2u_helper->getConfig('footer_text') .'</div></div>';
	}