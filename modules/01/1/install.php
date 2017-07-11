<?php
if(!rex_addon::get('redactor2')->isAvailable() && !rex_addon::get('tinymce4')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_install_wysiwyg'));
	return FALSE;
}