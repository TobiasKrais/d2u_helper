<div class="row align-items-center">
	<?php
		$d2u_helper = rex_addon::get('d2u_helper');

		// Article links
		print '<div class="col-12 col-md-6 col-lg-4">';
		$rex_articles = rex_article::getRootArticles(true);
		foreach($rex_articles as $rex_article) {
			print '<p><span class="fa-icon fa-link footer-icon"></span><a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a></p>';
		}
		if(rex_addon::get('consent_manager')->isAvailable()) {
			print '<p><span class="fa-icon fa-link footer-icon"></span><a class="consent_manager-show-box-reload">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a></p>';
		}
		print '</div>';

		// Logo large displays
		print '<div class="d-none d-lg-block col-lg-4 text-lg-center">';
		if(strval($d2u_helper->getConfig("footer_logo", "")) !== "") {
				print '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
				$media_logo = rex_media::get(strval($d2u_helper->getConfig("footer_logo")));
				if($media_logo instanceof rex_media) {
					print '<img src="'. rex_url::media(strval($d2u_helper->getConfig("footer_logo"))) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo-footer" loading="lazy">';
				}
				print '</a>';
		}
		print '</div>';

		// Custom field
		print '<div class="col-12 d-md-none">&nbsp;</div>';
		print '<div class="col-12 col-md-6 col-lg-4">';
		print '<p class="text-md-right">';
		if(strval($d2u_helper->getConfig('footer_text_company', '')) !== '') {
			print $d2u_helper->getConfig('footer_text_company') .'<br>';
		}
		if(strval($d2u_helper->getConfig('footer_text_ceo', '')) !== '') {
			print \Sprog\Wildcard::get('d2u_helper_module_14_search_template_ceo') .':<br>';
			print $d2u_helper->getConfig('footer_text_ceo') .'<br>';
		}
		if(strval($d2u_helper->getConfig('footer_text_street', '')) !== '') {
			print $d2u_helper->getConfig('footer_text_street') .'<br>';
		}
		if(strval($d2u_helper->getConfig('footer_text_zip_city', '')) !== '') {
			print $d2u_helper->getConfig('footer_text_zip_city') .'<br>';
		}
		print '<br>';
		if(strval($d2u_helper->getConfig('footer_text_phone', '')) !== '') {
			print '<span class="fa-icon fa-phone footer-icon"></span> '. $d2u_helper->getConfig('footer_text_phone') .'<br>';
		}
		if(strval($d2u_helper->getConfig('footer_text_mobile', '')) !== '') {
			print '<span class="fa-icon fa-mobile footer-icon"></span> '. $d2u_helper->getConfig('footer_text_mobile') .'<br>';
		}
		if(strval($d2u_helper->getConfig('footer_text_fax', '')) !== '') {
			print '<span class="fa-icon fa-fax footer-icon"></span> '. $d2u_helper->getConfig('footer_text_fax') .'<br>';
		}
		if(strval($d2u_helper->getConfig('footer_text_email', '')) !== '') {
			print '<span class="fa-icon fa-envelope footer-icon"></span> <a href="mailto:'. $d2u_helper->getConfig('footer_text_email') .'">'. $d2u_helper->getConfig('footer_text_email') .'</a><br>';
		}
		if(strval($d2u_helper->getConfig('footer_facebook_link', '')) !== '') {
			print '<span class="fa-icon fa-facebook footer-icon"></span> <a href="'. $d2u_helper->getConfig("footer_facebook_link") .'" target="_blank">Facebook</a>';
		}
		print '</p>';
		print '</div>';
		
		// Logo small displays
		print '<div class="col-12 col-lg-0 d-lg-none">&nbsp;</div>';
		print '<div class="col-12 d-lg-none text-center">';
		if(strval($d2u_helper->getConfig("footer_logo", "")) !== "") {
				print '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
				$media_logo = rex_media::get(strval($d2u_helper->getConfig("footer_logo")));
				if($media_logo instanceof rex_media) {
					print '<img src="'. rex_url::media(strval($d2u_helper->getConfig("footer_logo"))) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo-footer" loading="lazy">';
				}
				print '</a>';
		}
		print '</div>';

	?>
</div>