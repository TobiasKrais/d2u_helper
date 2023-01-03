<div class="row">
	<?php
		$d2u_helper = rex_addon::get('d2u_helper');
	
		print '<div class="col-12">';
		print '<ul id="footer-address">';
		if($d2u_helper instanceof rex_addon) {
			if(strval($d2u_helper->getConfig('footer_text_company', '')) !== '') {
				print '<li>'. $d2u_helper->getConfig('footer_text_company') .'</li>';
			}
			if(strval($d2u_helper->getConfig('footer_text_ceo', '')) !== '') {
				print '<li>'. \Sprog\Wildcard::get('d2u_helper_module_14_search_template_ceo') .': '. $d2u_helper->getConfig('footer_text_ceo') .'</li>';
			}
			if(strval($d2u_helper->getConfig('footer_text_street', '')) !== '') {
				print '<li>'. $d2u_helper->getConfig('footer_text_street') .'</li>';
			}
			if(strval($d2u_helper->getConfig('footer_text_zip_city', '')) !== '') {
				print '<li>'. $d2u_helper->getConfig('footer_text_zip_city') .'</li>';
			}
		}
		print '</ul>';
		print '</div>';

		print '<div class="col-12">';
		print '<ul id="footer-contact">';
		if($d2u_helper instanceof rex_addon) {
			if(strval($d2u_helper->getConfig('footer_text_phone', '')) !== '') {
				print '<li>'. $d2u_helper->getConfig('footer_text_phone') .'</li>';
			}
			if(strval($d2u_helper->getConfig('footer_text_mobile', '')) !== '') {
				print '<li>'. $d2u_helper->getConfig('footer_text_mobile') .'</li>';
			}
			if(strval($d2u_helper->getConfig('footer_text_fax', '')) !== '') {
				print '<li>'. $d2u_helper->getConfig('footer_text_fax') .'</li>';
			}
			if(strval($d2u_helper->getConfig('footer_facebook_link', '')) !== '') {
				print '<li><a href="'. $d2u_helper->getConfig("footer_facebook_link") .'" target="_blank">Facebook</a></li>';
			}
			if(strval($d2u_helper->getConfig('footer_text_email', '')) !== '') {
				print '<li><a href="mailto:'. $d2u_helper->getConfig('footer_text_email') .'">'. $d2u_helper->getConfig('footer_text_email') .'</a></li>';
			}
		}
		print '</ul>';
		print '</div>';

		$rex_articles = rex_article::getRootArticles(true);
		if(count($rex_articles) > 0) {
			print '<div class="col-12">';
			print '<ul id="footer-links">';
			foreach($rex_articles as $rex_article) {
				print '<li><a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a></li>';
			}
			if(rex_addon::get('consent_manager')->isAvailable()) {
				print '<li><<a class="consent_manager-show-box-reload">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a></li>';
			}
			print '</div>';
		}
	?>
</div>