<?php

$return = true;
if (!rex_addon::get('plyr')->isAvailable()) {
    echo rex_view::error(rex_i18n::msg('d2u_helper_modules_error_playr'));
    $return = false;
}
return $return;
