<?php
	$fragment = new rex_fragment();
	$d2u_helper = rex_addon::get("d2u_helper");
	$clangs = rex_clang::getAll(true);
?>
<nav class="d-print-none<?= $d2u_helper->getConfig('template_navi_pos', 'bottom') === 'top' ? ' top' : '' ?>">
	<div class="container">
		<div class="navigation">
			<div class="row">
				<div class="col-12">
					<?php
						// Navi
						print '<div class="navi">';
						if(rex_addon::get('d2u_helper')->isAvailable()) {
							if(rex_config::get('d2u_helper', 'include_menu') === 'smartmenu') {
								d2u_mobile_navi_smartmenus::getMenu();
							}
							else if (rex_config::get('d2u_helper', 'include_menu') === 'multilevel') {
								d2u_mobile_navi::getResponsiveMultiLevelMobileMenu();
								d2u_mobile_navi::getResponsiveMultiLevelDesktopMenu();
							}
							else if (rex_config::get('d2u_helper', 'include_menu') === 'megamenu') {
								d2u_mobile_navi_mega_menu::getMenu();
							}
						}
						print '</div>';

						// Search field
						if(rex_addon::get('search_it')->isAvailable() && rex_config::get('d2u_helper', 'article_id_search', 0) > 0) {
							if(rex_addon::get('yform_spam_protection')->isAvailable()) {
								print '<div id="search_icon_div">';
								$fragment->setVar('showSearchField', true, false);
								echo $fragment->parse('d2u_template_search_icon.php');
								print '</div>';
							}
							else {
								print '<div id="search_icon_div">';
								echo $fragment->parse('d2u_template_search_icon.php');
								print '</div>';						
							}
						}

						// Languages
						if(count(rex_clang::getAllIds(true)) > 1) {
							print '<div id="lang_chooser_div">';
							$fragment->setVar('showLangDropdown', true, false);
							echo $fragment->parse('d2u_template_language_icon.php');
							print '</div>';
						}

					?>
				</div>
			</div>
		</div>
	</div>
</nav>
<?php
	echo $fragment->parse('d2u_template_language_modal.php');