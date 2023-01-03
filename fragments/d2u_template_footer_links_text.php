<div class="row">
	<?php
		$d2u_helper = rex_addon::get('d2u_helper');
		if(strval($d2u_helper->getConfig('footer_text', '')) !== "") {
			print '<div class="col-12 col-md-5 col-lg-7">';
		}
		else {
			print '<div class="col-12">';
		}
		$rex_articles = rex_article::getRootArticles(true);
		foreach($rex_articles as $rex_article) {
			print '<div class="footerbox">';
			print '<a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a>';
			print '</div>';
		}
		if(rex_addon::get('consent_manager')->isAvailable()) {
			print '<div class="footerbox">';
			print '<a class="consent_manager-show-box-reload">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a>';
			print '</div>';
		}
		print '</div>';

		if(strval($d2u_helper->getConfig('footer_text', '')) !== "") {
			print '<div class="col-12 col-md-7 col-lg-5">';
			print '<h2>'. $d2u_helper->getConfig('footer_text') .'</h2>';
			print '</div>';
		}
	?>
</div>