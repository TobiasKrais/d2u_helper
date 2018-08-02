<?php
// Correct name of rights
if(\rex::isBackend() && is_object(\rex::getUser())) {
	rex_perm::register('d2u_helper[translation_helper]', rex_i18n::msg('d2u_helper_translations_rights'), rex_perm::OPTIONS);
}