<div class="row">
	<?php
		$rex_articles = rex_article::getRootArticles(true);
		foreach($rex_articles as $rex_articles) {
			print '<div class="col-sm-6 col-md-4 col-lg-3">';
			print '<div class="footerbox">';
			print '<a href="'. $rex_articles->getUrl() .'">'. $rex_articles->getName() .'</a>';
			print '</div>';
			print '</div>';
		}
		if(rex_addon::get('consent_manager')->isAvailable()) {
			print '<div class="col-sm-6 col-md-4 col-lg-3">';
			print '<div class="footerbox">';
			print '<a class="consent_manager-show-box-reload">'. \Sprog\Wildcard::get('d2u_helper_cookie_manager_template_edit_cookiesettings') .'</a>';
			print '</div>';
			print '</div>';
		}
	?>
</div>