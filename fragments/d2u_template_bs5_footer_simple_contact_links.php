<div class="row">
	<?php
        $d2u_helper = rex_addon::get('d2u_helper');

        // Address
        echo '<div class="col-12">';
        echo '<ul id="footer-address" class="list-unstyled text-center mb-2">';
        if ('' !== (string) $d2u_helper->getConfig('footer_text_company', '')) {
            echo '<li>'. $d2u_helper->getConfig('footer_text_company') .'</li>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_ceo', '')) {
            echo '<li>'. \Sprog\Wildcard::get('d2u_helper_module_14_search_template_ceo') .': '. $d2u_helper->getConfig('footer_text_ceo') .'</li>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_street', '')) {
            echo '<li>'. $d2u_helper->getConfig('footer_text_street') .'</li>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_zip_city', '')) {
            echo '<li>'. $d2u_helper->getConfig('footer_text_zip_city') .'</li>';
        }
        echo '</ul>';
        echo '</div>';

        // Contact
        echo '<div class="col-12">';
        echo '<ul id="footer-contact" class="list-unstyled d-md-flex justify-content-center gap-3 text-center mb-2">';
        if ('' !== (string) $d2u_helper->getConfig('footer_text_phone', '')) {
            echo '<li><span class="fa-icon fa-phone footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_module_form_phone') .'"></span> <a href="tel:'. $d2u_helper->getConfig('footer_text_phone') .'">'. $d2u_helper->getConfig('footer_text_phone') .'</a></li>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_mobile', '')) {
            echo '<li><span class="fa-icon fa-mobile-screen-button footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_module_form_phone') .'"></span> <a href="tel:'. $d2u_helper->getConfig('footer_text_mobile') .'">'. $d2u_helper->getConfig('footer_text_mobile') .'</a></li>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_fax', '')) {
            echo '<li><span class="fa-icon fa-fax footer-icon"></span> '. $d2u_helper->getConfig('footer_text_fax') .'</li>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_email', '')) {
            echo '<li><span class="fa-icon fa-envelope footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_module_form_email') .'"></span> <a href="mailto:'. $d2u_helper->getConfig('footer_text_email') .'">'. $d2u_helper->getConfig('footer_text_email') .'</a></li>';
        }
        // Social icons inline
        if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
            echo '<li><a href="'. $d2u_helper->getConfig('footer_facebook_link') .'" target="_blank" rel="noopener"><span class="fa-icon fa-facebook" title="'. \Sprog\Wildcard::get('d2u_helper_social_facebook') .'"></span></a></li>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_google_link', '')) {
            echo '<li><a href="'. $d2u_helper->getConfig('footer_google_link') .'" target="_blank" rel="noopener"><span class="fa-icon fa-google" title="'. \Sprog\Wildcard::get('d2u_helper_social_google') .'"></span></a></li>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_instagram_link', '')) {
            echo '<li><a href="'. $d2u_helper->getConfig('footer_instagram_link') .'" target="_blank" rel="noopener"><span class="fa-icon fa-instagram" title="'. \Sprog\Wildcard::get('d2u_helper_social_instagram') .'"></span></a></li>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_linkedin_link', '')) {
            echo '<li><a href="'. $d2u_helper->getConfig('footer_linkedin_link') .'" target="_blank" rel="noopener"><span class="fa-icon fa-linkedin" title="'. \Sprog\Wildcard::get('d2u_helper_social_linkedin') .'"></span></a></li>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_youtube_link', '')) {
            echo '<li><a href="'. $d2u_helper->getConfig('footer_youtube_link') .'" target="_blank" rel="noopener"><span class="fa-icon fa-youtube" title="'. \Sprog\Wildcard::get('d2u_helper_social_youtube') .'"></span></a></li>';
        }
        echo '</ul>';
        echo '</div>';

        // Article links
        $rex_articles = rex_article::getRootArticles(true);
        if (count($rex_articles) > 0) {
            echo '<div class="col-12">';
            echo '<ul id="footer-links" class="list-unstyled d-md-flex justify-content-center gap-3 text-center pt-3">';
            foreach ($rex_articles as $rex_article) {
                echo '<li><a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a></li>';
            }
            if (rex_addon::get('consent_manager')->isAvailable()) {
                echo '<li><a class="consent_manager-show-box-reload" role="button">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a></li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    ?>
</div>
