<nav class="d-print-none">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="navigation">
					<div class="row">
						<?php
							$fragment = new rex_fragment();
							$d2u_helper = rex_addon::get("d2u_helper");
							$clangs = rex_clang::getAll(TRUE);

							// Navi
							print '<div class="col align-self-center">';
							print '<div class="navi">';
							if(rex_addon::get('d2u_helper')->isAvailable()) {
								if(rex_config::get('d2u_helper', 'include_menu_smartmenu', FALSE)) {
									d2u_mobile_navi_smartmenus::getMenu();
								}
								else if (rex_config::get('d2u_helper', 'include_menu_multilevel', FALSE)) {
									d2u_mobile_navi::getResponsiveMultiLevelMobileMenu();
									d2u_mobile_navi::getResponsiveMultiLevelDesktopMenu();
								}
							}
							print '</div>';
							print '</div>';

							// Languages
							if(count(rex_clang::getAllIds()) > 1) {
								print '<div class="col-5 col-sm-3 col-md-2 align-self-center">';
								print '<div id="lang_chooser_div">';
								$fragment->setVar('showLangDropdown', true, false);
								echo $fragment->parse('d2u_template_language_modal.php');
								print '</div>';
								print '</div>';
							}

							// Search field
							if(rex_addon::get('search_it')->isAvailable() && rex_config::get('d2u_helper', 'article_id_search', 0) > 0) {
								if(rex_addon::get('yform_spam_protection')->isAvailable()) {
									print '<div class="col-12 col-sm-6 col-md-4 col-lg-2 align-self-center">';
									print '<div id="search_icon_div">';
									$fragment->setVar('showSearchField', true, false);
									echo $fragment->parse('d2u_template_search_icon.php');
									print '</div>';
									print '</div>';
								}
								else {
									print '<div class="col-2 col-md-1 align-self-center">';
									print '<div id="search_icon_div">';
									echo $fragment->parse('d2u_template_search_icon.php');
									print '</div>';						
									print '</div>';						
								}
							}

						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>