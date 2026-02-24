<div class="row g-3">
	<?php
        $d2u_helper = rex_addon::get('d2u_helper');

        // Logo footer
        $media_logo_footer = rex_media::get((string) ('' !== $d2u_helper->getConfig('footer_logo', '') ? $d2u_helper->getConfig('footer_logo', '') : $d2u_helper->getConfig('template_logo')));
        // Social links
        $social_footer = '';
        // Facebook
        if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
            $social_footer .= '<a href="'. $d2u_helper->getConfig('footer_facebook_link') .'" target="_blank" rel="noopener" title="'. \Sprog\Wildcard::get('d2u_helper_social_facebook') .'">'
                .'<span class="fa-icon fa-2x fa-facebook pe-2"></span>'
                .'</a>';
        }
        // Google
        if ('' !== (string) $d2u_helper->getConfig('footer_google_link', '')) {
            $social_footer .= '<a href="'. $d2u_helper->getConfig('footer_google_link') .'" target="_blank" rel="noopener" title="'. \Sprog\Wildcard::get('d2u_helper_social_google') .'">'
                .'<span class="fa-icon fa-2x fa-google pe-2"></span>'
                .'</a>';
        }
        // Instagram
        if ('' !== (string) $d2u_helper->getConfig('footer_instagram_link', '')) {
            $social_footer .= '<a href="'. $d2u_helper->getConfig('footer_instagram_link') .'" target="_blank" rel="noopener" title="'. \Sprog\Wildcard::get('d2u_helper_social_instagram') .'">'
                .'<span class="fa-icon fa-2x fa-instagram pe-2"></span>'
                .'</a>';
        }
        // LinkedIn
        if ('' !== (string) $d2u_helper->getConfig('footer_linkedin_link', '')) {
            $social_footer .= '<a href="'. $d2u_helper->getConfig('footer_linkedin_link') .'" target="_blank" rel="noopener" title="'. \Sprog\Wildcard::get('d2u_helper_social_linkedin') .'">'
                .'<span class="fa-icon fa-2x fa-linkedin pe-2"></span>'
                .'</a>';
        }
        // Youtube
        if ('' !== (string) $d2u_helper->getConfig('footer_youtube_link', '')) {
            $social_footer .= '<a href="'. $d2u_helper->getConfig('footer_youtube_link') .'" target="_blank" rel="noopener" title="'. \Sprog\Wildcard::get('d2u_helper_social_youtube') .'">'
                .'<span class="fa-icon fa-2x fa-youtube pe-2"></span>'
                .'</a>';
        }

        if ($media_logo_footer instanceof rex_media || '' !== $social_footer) {
            echo '<div class="col-12 col-sm-8 col-md-9">';
        } else {
            echo '<div class="col-12">';
        }
        $rex_articles = rex_article::getRootArticles(true);
        echo '<div class="row g-3">';
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
                    echo '<a class="consent_manager-show-box-reload" role="button">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a>';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';

        if ($media_logo_footer instanceof rex_media || '' !== $social_footer) {
            echo '<div class="col-12 col-sm-4 col-md-3">';

            if ($media_logo_footer instanceof rex_media && '' !== $social_footer) {
                echo '<div class="row">';
                echo '<div class="col-12 mb-3">';
            }

            // Social icons
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
                echo '<img src="'. rex_url::media($media_logo_footer->getFileName()) .'?v='. $media_logo_footer->getUpdateDate() .'" alt="'. $media_logo_footer->getTitle() .'" title="'. $media_logo_footer->getTitle() .'" id="logo-footer" class="img-fluid" loading="lazy">';
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
