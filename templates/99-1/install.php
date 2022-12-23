<?php
if(!rex_addon::get('yrewrite')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_templates_install_yrewrite'));
	return false;
}