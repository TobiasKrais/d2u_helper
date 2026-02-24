<?php

$d2u_helper = rex_addon::get('d2u_helper');
if ($d2u_helper instanceof rex_addon && ('' !== (string) $d2u_helper->getConfig('footer_text', '') || rex_addon::get('consent_manager')->isAvailable())) {
    echo '<div class="row">';
    if ('' !== (string) $d2u_helper->getConfig('footer_text', '')) {
        echo '<div class="col-12 footer-text text-center">'. $d2u_helper->getConfig('footer_text') .'</div>';
    }
    if (rex_addon::get('consent_manager')->isAvailable()) {
        echo '<div class="col-12 footer-text text-center"><a class="consent_manager-show-box-reload" role="button">'. \Sprog\Wildcard::get('d2u_helper_consent_manager_template_edit_cookiesettings') .'</a></div>';
    }
    // Facebook link
    if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
        echo '<div class="col-12 footer-text text-center"><span class="fa-icon fa-facebook footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_facebook') .'"></span> <a href="'. $d2u_helper->getConfig('footer_facebook_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_facebook') .'</a></div>';
    }
    // Google link
    if ('' !== (string) $d2u_helper->getConfig('footer_google_link', '')) {
        echo '<div class="col-12 footer-text text-center"><span class="fa-icon fa-google footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_google') .'"></span> <a href="'. $d2u_helper->getConfig('footer_google_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_google') .'</a></div>';
    }
    // Instagram link
    if ('' !== (string) $d2u_helper->getConfig('footer_instagram_link', '')) {
        echo '<div class="col-12 footer-text text-center"><span class="fa-icon fa-instagram footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_instagram') .'"></span> <a href="'. $d2u_helper->getConfig('footer_instagram_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_instagram') .'</a></div>';
    }
    // LinkedIn link
    if ('' !== (string) $d2u_helper->getConfig('footer_linkedin_link', '')) {
        echo '<div class="col-12 footer-text text-center"><span class="fa-icon fa-linkedin footer-icon" title="'. \Sprog\Wildcard::get('d2u_helper_social_linkedin') .'"></span> <a href="'. $d2u_helper->getConfig('footer_linkedin_link') .'" target="_blank" rel="noopener">'. \Sprog\Wildcard::get('d2u_helper_social_linkedin') .'</a></div>';
    }
    echo '</div>';
}
