<div class="row">
	<?php
        $d2u_helper = rex_addon::get('d2u_helper');

        // Logo footer
        $media_logo_footer = rex_media::get((string) ('' !== $d2u_helper->getConfig('footer_logo', '') ? $d2u_helper->getConfig('footer_logo', '') : $d2u_helper->getConfig('template_logo')));
        // Social links
        $social_footer = '';
        // Facebook
        if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
            $social_footer = '<a href="'. $d2u_helper->getConfig('footer_facebook_link') .'" target="_blank" title="'. \Sprog\Wildcard::get('d2u_helper_social_facebook') .'">'
                // fontawesome facebook icon
                .'<span class="fa-icon fa-2x fa-facebook pr-2"></span>'
                .'</a>';
        }
        // Google
        if ('' !== (string) $d2u_helper->getConfig('footer_google_link', '')) {
            $social_footer .= '<a href="'. $d2u_helper->getConfig('footer_google_link') .'" target="_blank" title="'. \Sprog\Wildcard::get('d2u_helper_social_google') .'">'
                // fontawesome google icon
                .'<span class="fa-icon fa-2x fa-google pr-2"></span>'
                .'</a>';
        }
        // Instagram
        if ('' !== (string) $d2u_helper->getConfig('footer_instagram_link', '')) {
            $social_footer .= '<a href="'. $d2u_helper->getConfig('footer_instagram_link') .'" target="_blank" title="'. \Sprog\Wildcard::get('d2u_helper_social_instagram') .'">'
                // fontawesome instagram icon
                .'<span class="fa-icon fa-2x fa-instagram pr-2"></span>'
                .'</a>';
        }
        // LinkedIn
        if ('' !== (string) $d2u_helper->getConfig('footer_linkedin_link', '')) {
            $social_footer .= '<a href="'. $d2u_helper->getConfig('footer_linkedin_link') .'" target="_blank" title="'. \Sprog\Wildcard::get('d2u_helper_social_linkedin') .'">'
                // fontawesome linkedin icon
                .'<span class="fa-icon fa-2x fa-linkedin pr-2"></span>'
                .'</a>';
        }
        // Youtube
        if ('' !== (string) $d2u_helper->getConfig('footer_youtube_link', '')) {
            $social_footer .= '<a href="'. $d2u_helper->getConfig('footer_youtube_link') .'" target="_blank" title="'. \Sprog\Wildcard::get('d2u_helper_social_youtube') .'">'
                // fontawesome youtube icon
                .'<span class="fa-icon fa-2x fa-youtube pr-2"></span>'
                .'</a>';
        }

        if ($media_logo_footer instanceof rex_media || '' !== $social_footer) {
            echo '<div class="col-12 col-sm-8 col-md-9">';
        } else {
            echo '<div class="col-12">';
        }
        $rex_articles = rex_article::getRootArticles(true);
        echo '<div class="row">';
        foreach ($rex_articles as $rex_article) {
            echo '<div class="col-md-6 col-lg-4">';
                echo '<div class="footerbox">';
                    echo '<a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a>';
                echo '</div>';
            echo '</div>';
        }
        if (rex_addon::get('consent_manager')->isAvailable()) {
            echo '<div class="col-sm-6 col-lg-4">';
                echo '<div class="footerbox">';
                    echo '<a class="consent_manager-show-box-reload">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a>';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';

        if ($media_logo_footer instanceof rex_media || '' !== $social_footer) {
            echo '<div class="col-12 col-sm-4 col-md-3">';

            if ($media_logo_footer instanceof rex_media && '' !== $social_footer) {
                echo '<div class="row">';
                echo '<div class="col-12 facebook-logo-div mb-3">';
            }

            // Facebook Logo
            if ('' !== $social_footer) {
                echo $social_footer;
            }

            if ($media_logo_footer instanceof rex_media && '' !== $social_footer) {
                echo '</div>';
                echo '<div class="col-12">';
            }

            // Logo
            if ($media_logo_footer instanceof rex_media) {
                echo '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
                echo '<img src="'. rex_url::media($media_logo_footer->getFileName()) .'?v='. $media_logo_footer->getUpdateDate() .'" alt="'. $media_logo_footer->getTitle() .'" title="'. $media_logo_footer->getTitle() .'" id="logo-footer" loading="lazy">';
                echo '</a>';
            }

            if ($media_logo_footer instanceof rex_media && '' !== $social_footer) {
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
    ?>
</div>