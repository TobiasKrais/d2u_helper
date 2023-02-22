<div class="row">
	<?php
        $d2u_helper = rex_addon::get('d2u_helper');

        echo '<div class="col-12">';
        echo '<ul id="footer-address">';
        if ($d2u_helper instanceof rex_addon) {
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
        }
        echo '</ul>';
        echo '</div>';

        echo '<div class="col-12">';
        echo '<ul id="footer-contact">';
        if ($d2u_helper instanceof rex_addon) {
            if ('' !== (string) $d2u_helper->getConfig('footer_text_phone', '')) {
                echo '<li><span class="fa-icon fa-phone footer-icon"></span> <a href="tel:'. $d2u_helper->getConfig('footer_text_phone') .'">'. $d2u_helper->getConfig('footer_text_phone') .'</a></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_mobile', '')) {
                echo '<li>span class="fa-icon fa-mobile footer-icon"></span> <a href="tel:'. $d2u_helper->getConfig('footer_text_mobile') .'">'. $d2u_helper->getConfig('footer_text_mobile') .'</a></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_fax', '')) {
                echo '<li><span class="fa-icon fa-fax footer-icon"></span> '. $d2u_helper->getConfig('footer_text_fax') .'</li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
                echo '<li><a href="'. $d2u_helper->getConfig('footer_facebook_link') .'" target="_blank">Facebook</a></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_email', '')) {
                echo '<li><a href="mailto:'. $d2u_helper->getConfig('footer_text_email') .'">'. $d2u_helper->getConfig('footer_text_email') .'</a></li>';
            }
        }
        echo '</ul>';
        echo '</div>';

        $rex_articles = rex_article::getRootArticles(true);
        if (count($rex_articles) > 0) {
            echo '<div class="col-12">';
            echo '<ul id="footer-links">';
            foreach ($rex_articles as $rex_article) {
                echo '<li><a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a></li>';
            }
            if (rex_addon::get('consent_manager')->isAvailable()) {
                echo '<li><<a class="consent_manager-show-box-reload">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a></li>';
            }
            echo '</div>';
        }
    ?>
</div>