<?php

if (!rex_plugin::get('d2u_immo', 'window_advertising')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_templates_install_d2u_immo_window_advertising'));
    return false;
}
