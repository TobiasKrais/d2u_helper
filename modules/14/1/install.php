<?php
$return = TRUE;
if(!rex_addon::get('search_it')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_error_search_it'));
	$return = $return ? FALSE : $return;
}
if(!rex_addon::get('sprog')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_error_sprog'));
	$return = $return ? FALSE : $return;
}
if(!rex_addon::get('yform_spam_protection')->isAvailable()) {
	print rex_view::warning(rex_i18n::msg('d2u_helper_modules_error_yform_spam_protection'));
}
if(!rex_config::get('d2u_helper', 'lang_replacements_install', FALSE)) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_error_sprog_replacements'));
	$return = $return ? FALSE : $return;	
}
return $return;