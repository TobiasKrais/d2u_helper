<div class="row">
	<?php
		$d2u_helper = rex_addon::get('d2u_helper');
	
		print '<div class="col-12 col-md-4">';
		$rex_articles = rex_article::getRootArticles(true);
		print '<div class="row">';
		print '<div class="col-12">';
		print '<div id="footer-address">';
		print '<div class="footer-title">'. \Sprog\Wildcard::get('d2u_helper_module_14_search_template_links') .'</div>';
		foreach($rex_articles as $rex_article) {
			print '<a href="'. $rex_article->getUrl() .'"><span class="fa-icon fa-link footer-icon"></span>'. $rex_article->getName() .'</a><br>';
		}
		if(rex_addon::get('consent_manager')->isAvailable()) {
			print '<p><span class="fa-icon fa-link footer-icon"></span><a class="consent_manager-show-box-reload">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a></p>';
		}
		print '</div>';
		print '</div>';
		print '</div>';
		print '</div>';

		print '<div class="col-12 col-md-4">';
		print '<div id="footer-address">';
		print '<div class="footer-title">'. \Sprog\Wildcard::get('d2u_helper_module_14_search_template_address') .'</div>';
		if(strval($d2u_helper->getConfig('footer_text_company', '')) !== '') {
			print '<span class="fa-icon fa-user footer-icon"></span>'. $d2u_helper->getConfig('footer_text_company') .'<br>';
		}
		if(strval($d2u_helper->getConfig('footer_text_ceo', '')) !== '') {
			print '<span class="fa-icon footer-icon"></span>'. \Sprog\Wildcard::get('d2u_helper_module_14_search_template_ceo') .':<br>';
			print '<span class="fa-icon footer-icon"></span>'. $d2u_helper->getConfig('footer_text_ceo') .'<br>';
		}
		if(strval($d2u_helper->getConfig('footer_text_street', '')) !== '') {
			print '<span class="fa-icon fa-home footer-icon"></span>'. $d2u_helper->getConfig('footer_text_street') .'<br>';
		}
		if(strval($d2u_helper->getConfig('footer_text_zip_city', '')) !== '') {
			print '<span class="fa-icon footer-icon"></span>'. $d2u_helper->getConfig('footer_text_zip_city') .'<br>';
		}
		print '</div>';
		print '</div>';

		print '<div class="col-12 col-md-4">';
		print '<div id="footer-address">';
		print '<div class="footer-title">'. \Sprog\Wildcard::get('d2u_helper_module_14_search_template_contact') .'</div>';
		if(strval($d2u_helper->getConfig('footer_text_phone', '')) !== '') {
			print '<span class="fa-icon fa-phone footer-icon"></span><a href="tel:'. $d2u_helper->getConfig('footer_text_phone') .'">'. $d2u_helper->getConfig('footer_text_phone') .'</a><br>';
		}
		if(strval($d2u_helper->getConfig('footer_text_mobile', '')) !== '') {
			print '<span class="fa-icon fa-mobile footer-icon"></span><a href="tel:'. $d2u_helper->getConfig('footer_text_mobile') .'">'. $d2u_helper->getConfig('footer_text_mobile') .'</a><br>';
		}
		if(strval($d2u_helper->getConfig('footer_text_fax', '')) !== '') {
			print '<span class="fa-icon fa-fax footer-icon"></span>'. $d2u_helper->getConfig('footer_text_fax') .'<br>';
		}
		if(strval($d2u_helper->getConfig('footer_facebook_link', '')) !== '') {
			print '<span class="fa-icon fa-facebook footer-icon"></span><a href="'. $d2u_helper->getConfig("footer_facebook_link") .'" target="_blank">Facebook</a><br>';
		}
		if(strval($d2u_helper->getConfig('footer_text_email', '')) !== '') {
			print '<span class="fa-icon fa-envelope footer-icon"></span><a href="mailto:'. $d2u_helper->getConfig('footer_text_email') .'">'. $d2u_helper->getConfig('footer_text_email') .'</a><br>';
		}
		print '</div>';
		print '</div>';

		print '<div class="col-12">&nbsp;</div>';

		// Logo footer
		$media_logo_footer = rex_media::get(strval($d2u_helper->getConfig("footer_logo", "")) !== '' ? strval($d2u_helper->getConfig("template_logo")) : '');
		if($media_logo_footer instanceof rex_media) {
			print '<div class="col-12 col-md-4 offset-md-4 footer-logo-col">';
			print '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">'
				.'<img src="'. rex_url::media($media_logo_footer->getFileName()) .'" alt="'. $media_logo_footer->getTitle() .'" title="'. $media_logo_footer->getTitle() .'" id="logo-footer" loading="lazy">'
				.'</a>';
			print '</div>';
		}
	?>
</div>