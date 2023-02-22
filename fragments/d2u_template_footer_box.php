<div class="row">
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
            echo '<a class="consent_manager-show-box-reload">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a>';
            echo '</div>';
            echo '</div>';
        }
    ?>
</div>