<?php

if (!rex_addon::get('d2u_immo')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_templates_install_d2u_immo'));
    return false;
}
