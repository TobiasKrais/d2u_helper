<?php
if(!rex_addon::get('geolocation')->isAvailable() && !rex_addon::get('osmproxy')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_error_gelocation'));
	return false;
}