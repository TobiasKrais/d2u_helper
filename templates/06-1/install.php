<?php

if (!rex_addon::get('metainfo')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_templates_install_metainfo'));
    return false;
}

if (!rex_addon::get('sprog')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_templates_install_sprog'));
    return false;
}
