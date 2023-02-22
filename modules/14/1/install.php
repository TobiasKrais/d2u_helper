<?php

$return = true;
if (!rex_addon::get('search_it')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_modules_error_search_it'));
    $return = $return ? false : $return;
}
if (!rex_addon::get('sprog')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_modules_error_sprog'));
    $return = $return ? false : $return;
}
if (!rex_addon::get('yform_spam_protection')->isAvailable()) {
    echo rex_view::warning(rex_i18n::msg('d2u_helper_modules_error_yform_spam_protection'));
}
if (!rex_config::get('d2u_helper', 'lang_replacements_install', false)) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_modules_error_sprog_replacements'));
    $return = $return ? false : $return;
}
return $return;
