<div class="row">
	<?php
		$d2u_helper = rex_addon::get('d2u_helper');

		// Logo footer
		$media_logo_footer = rex_media::get($d2u_helper->getConfig("footer_logo", "") != "" ? $d2u_helper->getConfig("footer_logo", "") : $d2u_helper->getConfig("template_logo"));
		// Facebook logo and link
		$facebook_footer = "";
		if($d2u_helper->getConfig("footer_facebook_link", "") != "" && $d2u_helper->getConfig("footer_facebook_icon", "") != "") {
			$facebook_footer = '<a href="'. $d2u_helper->getConfig("footer_facebook_link") .'" target="_blank">'
				.'<img src="'. rex_url::media($d2u_helper->getConfig("footer_facebook_icon")) .'" alt="Facebook" id="facebook">'
				.'</a>';
		}

		if($media_logo_footer instanceof rex_media || $facebook_footer != "") {
			print '<div class="col-12 col-sm-8 col-md-9">';
		}
		else {
			print '<div class="col-12">';
		}
		$rex_articles = rex_article::getRootArticles(true);
		print '<div class="row">';
		foreach($rex_articles as $rex_article) {
			print '<div class="col-md-6 col-lg-4">';
			print '<div class="footerbox">';
			print '<a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a>';
			print '</div>';
			print '</div>';
		}
		print '</div>';
		print '</div>';

		if($media_logo_footer instanceof rex_media || $facebook_footer != "") {
			print '<div class="col-12 col-sm-4 col-md-3">';

			if($media_logo_footer instanceof rex_media && $facebook_footer != "") {
				print '<div class="row">';
				print '<div class="col-12 col-lg-6 facebook-logo-div">';
			}

			// Facebook Logo
			if($facebook_footer != "") {
				print $facebook_footer;
			}

			if($media_logo_footer instanceof rex_media && $facebook_footer != "") {
				print '</div>';
				print '<div class="d-block d-lg-none col-lg-6">&nbsp;</div>';
				print '<div class="col-12 col-lg-6">';
			}

			// Logo
			if($media_logo_footer instanceof rex_media) {
				print '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
				print '<img src="'. rex_url::media($media_logo_footer->getFileName()) .'" alt="'. $media_logo_footer->getTitle() .'" title="'. $media_logo_footer->getTitle() .'" id="logo-footer">';
				print '</a>';
			}

			if($media_logo_footer instanceof rex_media && $facebook_footer != "") {
				print '</div>';
				print '</div>';
			}
			print '</div>';
		}
	?>
</div>