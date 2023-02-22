<div class="row align-items-center">
	<?php
        $d2u_helper = rex_addon::get('d2u_helper');

        // Article links
        echo '<div class="col-12 col-md-6 col-lg-4">';
        $rex_articles = rex_article::getRootArticles(true);
        foreach ($rex_articles as $rex_article) {
            echo '<p><span class="fa-icon fa-link footer-icon"></span><a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a></p>';
        }
        if (rex_addon::get('consent_manager')->isAvailable()) {
            echo '<p><span class="fa-icon fa-link footer-icon"></span><a class="consent_manager-show-box-reload">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a></p>';
        }
        echo '</div>';

        // Logo large displays
        echo '<div class="d-none d-lg-block col-lg-4 text-lg-center">';
        if ('' !== (string) $d2u_helper->getConfig('footer_logo', '')) {
                echo '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
                $media_logo = rex_media::get((string) $d2u_helper->getConfig('footer_logo'));
                if ($media_logo instanceof rex_media) {
                    echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('footer_logo')) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo-footer" loading="lazy">';
                }
                echo '</a>';
        }
        echo '</div>';

        // Custom field
        echo '<div class="col-12 d-md-none">&nbsp;</div>';
        echo '<div class="col-12 col-md-6 col-lg-4">';
        echo '<p class="text-md-right">';
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
            echo '<span class="fa-icon fa-phone footer-icon"></span> <a href="tel:'. $d2u_helper->getConfig('footer_text_phone') .'">'. $d2u_helper->getConfig('footer_text_phone') .'</a><br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_mobile', '')) {
            echo '<span class="fa-icon fa-mobile footer-icon"></span> <a href="tel:'. $d2u_helper->getConfig('footer_text_mobile') .'">'. $d2u_helper->getConfig('footer_text_mobile') .'</a><br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_fax', '')) {
            echo '<span class="fa-icon fa-fax footer-icon"></span> '. $d2u_helper->getConfig('footer_text_fax') .'<br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_text_email', '')) {
            echo '<span class="fa-icon fa-envelope footer-icon"></span> <a href="mailto:'. $d2u_helper->getConfig('footer_text_email') .'">'. $d2u_helper->getConfig('footer_text_email') .'</a><br>';
        }
        if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
            echo '<span class="fa-icon fa-facebook footer-icon"></span> <a href="'. $d2u_helper->getConfig('footer_facebook_link') .'" target="_blank">Facebook</a>';
        }
        echo '</p>';
        echo '</div>';

        // Logo small displays
        echo '<div class="col-12 col-lg-0 d-lg-none">&nbsp;</div>';
        echo '<div class="col-12 d-lg-none text-center">';
        if ('' !== (string) $d2u_helper->getConfig('footer_logo', '')) {
                echo '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
                $media_logo = rex_media::get((string) $d2u_helper->getConfig('footer_logo'));
                if ($media_logo instanceof rex_media) {
                    echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('footer_logo')) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo-footer" loading="lazy">';
                }
                echo '</a>';
        }
        echo '</div>';

    ?>
</div>