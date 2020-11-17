<?php
$return = TRUE;
if(!rex_addon::get('plyr')->isAvailable()) {
	print rex_view::error(rex_i18n::msg('d2u_helper_modules_error_playr'));
	$return = $return ? FALSE : $return;
}
return $return;