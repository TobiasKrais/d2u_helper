<div class="row g-3">
	<?php
        $rex_articles = rex_article::getRootArticles(true);
        foreach ($rex_articles as $rex_article) {
            echo '<div class="col-sm-6 col-md-4 col-lg-3">';
                echo '<div class="footerbox">';
                    echo '<a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a>';
                echo '</div>';
            echo '</div>';
        }
        if (rex_addon::get('consent_manager')->isAvailable()) {
            echo '<div class="col-sm-6 col-md-4 col-lg-3">';
                echo '<div class="footerbox">';
                    echo '<a class="consent_manager-show-box-reload" role="button">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a>';
                echo '</div>';
            echo '</div>';
        }
        $d2u_helper = rex_addon::get('d2u_helper');
        // Facebook link
        if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
            echo '<div class="col-sm-6 col-md-4 col-lg-3">';
                echo '<div class="footerbox">';
                    echo '<span class="fa-icon fa-facebook footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_facebook') .'"></span> ';
                    echo '<a href="'. $d2u_helper->getConfig('footer_facebook_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_facebook') .'</a>';
                echo '</div>';
            echo '</div>';
        }
        // Google link
        if ('' !== (string) $d2u_helper->getConfig('footer_google_link', '')) {
            echo '<div class="col-sm-6 col-md-4 col-lg-3">';
                echo '<div class="footerbox">';
                    echo '<span class="fa-icon fa-google footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_google') .'"></span> ';
                    echo '<a href="'. $d2u_helper->getConfig('footer_google_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_google') .'</a>';
                echo '</div>';
            echo '</div>';
        }
        // Instagram link
        if ('' !== (string) $d2u_helper->getConfig('footer_instagram_link', '')) {
            echo '<div class="col-sm-6 col-md-4 col-lg-3">';
                echo '<div class="footerbox">';
                    echo '<span class="fa-icon fa-instagram footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_instagram') .'"></span> ';
                    echo '<a href="'. $d2u_helper->getConfig('footer_instagram_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_instagram') .'</a>';
                echo '</div>';
            echo '</div>';
        }
        // LinkedIn link
        if ('' !== (string) $d2u_helper->getConfig('footer_linkedin_link', '')) {
            echo '<div class="col-sm-6 col-md-4 col-lg-3">';
                echo '<div class="footerbox">';
                    echo '<span class="fa-icon fa-linkedin footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_linkedin') .'"></span> ';
                    echo '<a href="'. $d2u_helper->getConfig('footer_linkedin_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_linkedin') .'</a>';
                echo '</div>';
            echo '</div>';
        }
        // Youtube link
        if ('' !== (string) $d2u_helper->getConfig('footer_youtube_link', '')) {
            echo '<div class="col-sm-6 col-md-4 col-lg-3">';
                echo '<div class="footerbox">';
                    echo '<span class="fa-icon fa-youtube footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_youtube') .'"></span> ';
                    echo '<a href="'. $d2u_helper->getConfig('footer_youtube_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_youtube') .'</a>';
                echo '</div>';
            echo '</div>';
        }
    ?>
</div>
