<div class="row">
	<?php
        $d2u_helper = rex_addon::get('d2u_helper');

        // Logo footer
        $media_logo_footer = rex_media::get((string) ('' !== $d2u_helper->getConfig('footer_logo', '') ? $d2u_helper->getConfig('footer_logo', '') : $d2u_helper->getConfig('template_logo')));
        // Facebook logo and link
        $facebook_footer = '';
        if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '') && '' !== (string) $d2u_helper->getConfig('footer_facebook_icon', '')) {
            $facebook_footer = '<a href="'. $d2u_helper->getConfig('footer_facebook_link') .'" target="_blank">'
                .'<img src="'. rex_url::media((string) $d2u_helper->getConfig('footer_facebook_icon')) .'" alt="Facebook" id="facebook" loading="lazy">'
                .'</a>';
        }

        if ($media_logo_footer instanceof rex_media || '' !== $facebook_footer) {
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

        if ($media_logo_footer instanceof rex_media || '' !== $facebook_footer) {
            echo '<div class="col-12 col-sm-4 col-md-3">';

            if ($media_logo_footer instanceof rex_media && '' !== $facebook_footer) {
                echo '<div class="row">';
                echo '<div class="col-12 col-lg-6 facebook-logo-div">';
            }

            // Facebook Logo
            if ('' !== $facebook_footer) {
                echo $facebook_footer;
            }

            if ($media_logo_footer instanceof rex_media && '' !== $facebook_footer) {
                echo '</div>';
                echo '<div class="d-block d-lg-none col-lg-6">&nbsp;</div>';
                echo '<div class="col-12 col-lg-6">';
            }

            // Logo
            if ($media_logo_footer instanceof rex_media) {
                echo '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
                echo '<img src="'. rex_url::media($media_logo_footer->getFileName()) .'" alt="'. $media_logo_footer->getTitle() .'" title="'. $media_logo_footer->getTitle() .'" id="logo-footer" loading="lazy">';
                echo '</a>';
            }

            if ($media_logo_footer instanceof rex_media && '' !== $facebook_footer) {
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
    ?>
</div>