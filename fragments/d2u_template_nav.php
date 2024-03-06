<?php
    $fragment = new rex_fragment();
    $d2u_helper = rex_addon::get('d2u_helper');
    $clangs = rex_clang::getAll(true);
?>
<nav class="d-print-none<?= 'top' === $d2u_helper->getConfig('template_navi_pos', 'bottom') ? ' top' : '' ?>">
	<div class="container">
		<div class="navigation">
			<div class="row">
				<div class="col-12">
					<?php
                        // Navi
                        echo '<div class="navi">';
                        if (rex_addon::get('d2u_helper')->isAvailable()) {
                            if ('smartmenu' === rex_config::get('d2u_helper', 'include_menu')) {
                                \FriendsOfRedaxo\D2UHelper\FrontendNavigationSmartmenu::getMenu();
                            } elseif ('multilevel' === rex_config::get('d2u_helper', 'include_menu')) {
                                \FriendsOfRedaxo\D2UHelper\FrontendNavigationResponsiveMultiLevel::getResponsiveMultiLevelMobileMenu();
                                \FriendsOfRedaxo\D2UHelper\FrontendNavigationResponsiveMultiLevel::getResponsiveMultiLevelDesktopMenu();
                            } elseif ('megamenu' === rex_config::get('d2u_helper', 'include_menu')) {
                                \FriendsOfRedaxo\D2UHelper\FrontendNavigationMegaMenu::getMenu();
                            }
                        }
                        echo '</div>';

                        // Search field
                        if (rex_addon::get('search_it')->isAvailable() && rex_config::get('d2u_helper', 'article_id_search', 0) > 0) {
                            if (rex_addon::get('yform_spam_protection')->isAvailable()) {
                                echo '<div id="search_icon_div">';
                                $fragment->setVar('showSearchField', true, false);
                                echo $fragment->parse('d2u_template_search_icon.php');
                                echo '</div>';
                            } else {
                                echo '<div id="search_icon_div">';
                                echo $fragment->parse('d2u_template_search_icon.php');
                                echo '</div>';
                            }
                        }

                        // Languages
                        if (count(rex_clang::getAllIds(true)) > 1) {
                            echo '<div id="lang_chooser_div">';
                            $fragment->setVar('showLangDropdown', true, false);
                            echo $fragment->parse('d2u_template_language_icon.php');
                            echo '</div>';
                        }

                    ?>
				</div>
			</div>
		</div>
	</div>
</nav>
<?= $fragment->parse('d2u_template_language_modal.php');
