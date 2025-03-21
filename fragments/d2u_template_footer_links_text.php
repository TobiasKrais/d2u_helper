<div class="row">
	<?php
        $d2u_helper = rex_addon::get('d2u_helper');
        if ('' !== (string) $d2u_helper->getConfig('footer_text', '')) {
            echo '<div class="col-12 col-md-5 col-lg-7">';
        } else {
            echo '<div class="col-12">';
        }
        $rex_articles = rex_article::getRootArticles(true);
        foreach ($rex_articles as $rex_article) {
            echo '<div class="footerbox">';
            echo '<a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a>';
            echo '</div>';
        }
        if (rex_addon::get('consent_manager')->isAvailable()) {
            echo '<div class="footerbox">';
            echo '<a class="consent_manager-show-box-reload">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a>';
            echo '</div>';
        }
        $d2u_helper = rex_addon::get('d2u_helper');
        // Facebook link
        if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
            echo '<div class="footerbox">';
                echo '<span class="fa-icon fa-facebook footer-icon"></span> ';
                echo '<a href="'. $d2u_helper->getConfig('footer_facebook_link') .'" target="_blank">Facebook</a>';
            echo '</div>';
        }
        // Google link
        if ('' !== (string) $d2u_helper->getConfig('footer_google_link', '')) {
            echo '<div class="footerbox">';
                echo '<span class="fa-icon fa-google footer-icon"></span> ';
                echo '<a href="'. $d2u_helper->getConfig('footer_google_link') .'" target="_blank">Google</a>';
            echo '</div>';
        }
        // Instagram link
        if ('' !== (string) $d2u_helper->getConfig('footer_instagram_link', '')) {
            echo '<div class="footerbox">';
                echo '<span class="fa-icon fa-instagram footer-icon"></span> ';
                echo '<a href="'. $d2u_helper->getConfig('footer_instagram_link') .'" target="_blank">Instagram</a>';
            echo '</div>';
        }
        // LinkedIn link
        if ('' !== (string) $d2u_helper->getConfig('footer_linkedin_link', '')) {
            echo '<div class="footerbox">';
                echo '<span class="fa-icon fa-linkedin footer-icon"></span> ';
                echo '<a href="'. $d2u_helper->getConfig('footer_linkedin_link') .'" target="_blank">LinkedIn</a>';
            echo '</div>';
        }
        echo '</div>';

        if ('' !== (string) $d2u_helper->getConfig('footer_text', '')) {
            echo '<div class="col-12 col-md-7 col-lg-5">';
            echo '<h2>'. $d2u_helper->getConfig('footer_text') .'</h2>';
            echo '</div>';
        }
    ?>
</div>