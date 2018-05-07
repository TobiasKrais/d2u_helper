<?php
$return = TRUE;
if(!rex_config::get('d2u_helper', 'lang_replacements_install', FALSE)) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_error_settings_translations'));
	$return = $return ? FALSE : $return;
}
if(!rex_addon::get('sprog')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_error_sprog'));
	$return = $return ? FALSE : $return;
}
return $return;