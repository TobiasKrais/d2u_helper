<div class="row g-3 align-items-center">
	<?php
        $d2u_helper = rex_addon::get('d2u_helper');

        // Article links
        echo '<div class="col-12 col-md-6 col-lg-4">';
        $rex_articles = rex_article::getRootArticles(true);
        foreach ($rex_articles as $rex_article) {
            echo '<p><span class="fa-icon fa-chevron-right footer-icon"></span><a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a></p>';
        }
        if (rex_addon::get('consent_manager')->isAvailable()) {
            echo '<p><span class="fa-icon fa-chevron-right footer-icon"></span><a class="consent_manager-show-box-reload" role="button">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a></p>';
        }
        echo '</div>';

        // Logo large displays
        echo '<div class="d-none d-lg-block col-lg-4 text-center">';
        if ('' !== (string) $d2u_helper->getConfig('footer_logo', '')) {
                echo '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
                $media_logo = rex_media::get((string) $d2u_helper->getConfig('footer_logo'));
                if ($media_logo instanceof rex_media) {
                    echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('footer_logo')) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo-footer" class="img-fluid" loading="lazy">';
                }
                echo '</a>';
        }
        echo '</div>';

        // Address and contact info
        echo '<div class="col-12 d-md-none">&nbsp;</div>';
        echo '<div class="col-12 col-md-6 col-lg-4">';
        echo '<p class="text-md-end">';
        if ('' !== (string) $d2u_helper->getConfig('footer_text_company', '')) {
            echo $d2u_helper->getConfig('footer_text_company') .'<br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_ceo', '')) {
            echo \Sprog\Wildcard::get('d2u_helper_module_14_search_template_ceo') .':<br>';
            echo $d2u_helper->getConfig('footer_text_ceo') .'<br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_street', '')) {
            echo $d2u_helper->getConfig('footer_text_street') .'<br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_zip_city', '')) {
            echo $d2u_helper->getConfig('footer_text_zip_city') .'<br>';
        }
        echo '<br>';
        if ('' !== (string) $d2u_helper->getConfig('footer_text_phone', '')) {
            echo '<span class="fa-icon fa-phone footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_module_form_phone') .'"></span> <a href="tel:'. $d2u_helper->getConfig('footer_text_phone') .'">'. $d2u_helper->getConfig('footer_text_phone') .'</a><br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_mobile', '')) {
            echo '<span class="fa-icon fa-mobile-screen-button footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_module_form_phone') .'"></span> <a href="tel:'. $d2u_helper->getConfig('footer_text_mobile') .'">'. $d2u_helper->getConfig('footer_text_mobile') .'</a><br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_fax', '')) {
            echo '<span class="fa-icon fa-fax footer-icon"></span> '. $d2u_helper->getConfig('footer_text_fax') .'<br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_email', '')) {
            echo '<span class="fa-icon fa-envelope footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_module_form_email') .'"></span> <a href="mailto:'. $d2u_helper->getConfig('footer_text_email') .'">'. $d2u_helper->getConfig('footer_text_email') .'</a><br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
            echo '<span class="fa-icon fa-facebook footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_facebook') .'"></span> <a href="'. $d2u_helper->getConfig('footer_facebook_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_facebook') .'</a><br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_google_link', '')) {
            echo '<span class="fa-icon fa-google footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_google') .'"></span> <a href="'. $d2u_helper->getConfig('footer_google_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_google') .'</a><br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_instagram_link', '')) {
            echo '<span class="fa-icon fa-instagram footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_instagram') .'"></span> <a href="'. $d2u_helper->getConfig('footer_instagram_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_instagram') .'</a><br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_linkedin_link', '')) {
            echo '<span class="fa-icon fa-linkedin footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_linkedin') .'"></span> <a href="'. $d2u_helper->getConfig('footer_linkedin_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_linkedin') .'</a><br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_youtube_link', '')) {
            echo '<span class="fa-icon fa-youtube footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_youtube') .'"></span> <a href="'. $d2u_helper->getConfig('footer_youtube_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_youtube') .'</a><br>';
        }
        echo '</p>';
        echo '</div>';

        // Logo small displays
        echo '<div class="col-12 d-lg-none text-center">';
        if ('' !== (string) $d2u_helper->getConfig('footer_logo', '')) {
                echo '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
                $media_logo = rex_media::get((string) $d2u_helper->getConfig('footer_logo'));
                if ($media_logo instanceof rex_media) {
                    echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('footer_logo')) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo-footer" class="img-fluid" loading="lazy">';
                }
                echo '</a>';
        }
        echo '</div>';
    ?>
</div>
