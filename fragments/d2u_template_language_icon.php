<?php
$clangs = rex_clang::getAll(true);
if (count($clangs) > 1) {
?>
<button id="lang_chooser_button" data-toggle="modal" data-target="#lang_chooser_modal">
	<?php
        if ('' !== rex_config::get('d2u_helper', 'header_lang_icon', '')) {
            echo '<img src="'. TobiasKrais\D2UHelper\FrontendHelper::getMediaUrl((string) rex_config::get('d2u_helper', 'header_lang_icon', '')) .'">';
        }

        // Show language dropdown
        if ($this->getVar('showLangDropdown', false)) {
            echo '&nbsp;<span class="lang-code">'. strtoupper(rex_clang::getCurrent()->getCode()) .'&nbsp;<span class="fa-icon fa-dropdown"></span></span>';
        }
    ?>
</button>
<?php
}
