<?php

$return = true;
if (!rex_addon::get('sprog')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_modules_error_sprog'));
    $return = false;
}
return $return;
