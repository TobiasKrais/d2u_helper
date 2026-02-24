<?php

if (!rex_addon::get('cke5')->isAvailable()
        && !rex_addon::get('redactor')->isAvailable()
        && !rex_addon::get('tinymce')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_modules_install_wysiwyg'));
    return false;
}
