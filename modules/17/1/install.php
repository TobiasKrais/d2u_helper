<?php

$return = true;
$addon = rex_addon::get('googleplaces');
if (!$addon->isAvailable() || version_compare($addon->getVersion(), '3.0.0', '<')) {
    echo rex_view::error('Dieses Modul benötigt das Google Places Addon in Version 3 oder höher.');
    $return = false;
}
return $return;
