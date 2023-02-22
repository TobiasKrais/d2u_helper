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
        echo '</div>';

        if ('' !== (string) $d2u_helper->getConfig('footer_text', '')) {
            echo '<div class="col-12 col-md-7 col-lg-5">';
            echo '<h2>'. $d2u_helper->getConfig('footer_text') .'</h2>';
            echo '</div>';
        }
    ?>
</div>