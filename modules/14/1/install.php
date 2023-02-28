<?php

$return = true;
if (!rex_addon::get('search_it')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_modules_error_search_it'));
    $return = false;
}
if (!rex_addon::get('sprog')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_modules_error_sprog'));
    $return = false;
}
if (!rex_addon::get('yform_spam_protection')->isAvailable()) {
    echo rex_view::warning(rex_i18n::msg('d2u_helper_modules_error_yform_spam_protection'));
}
if (!(bool) rex_config::get('d2u_helper', 'lang_replacements_install', false)) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_modules_error_sprog_replacements'));
    $return = false;
}
return $return;
