<?php
if(!rex_addon::get('redactor2')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_install_redactor2'));
	return FALSE;
}