<?php
    $d2u_helper = rex_addon::get('d2u_helper');
    $menu_type = (string) rex_config::get('d2u_helper', 'include_menu', 'none');
    $menu_breakpoint = (string) rex_config::get('d2u_helper', 'include_menu_show', 'lg');

    // Get logo
    $logo_html = '';
    if ('' !== (string) $d2u_helper->getConfig('template_logo', '')) {
        $media_logo = rex_media::get((string) $d2u_helper->getConfig('template_logo'));
        if ($media_logo instanceof rex_media) {
            $logo_html = '<img src="'. rex_url::media((string) $d2u_helper->getConfig('template_logo')) .'?v='. $media_logo->getUpdateDate() .'" alt="'. $media_logo->getTitle() .'" id="logo">';
        }
    }

    // Get dark mode logo
    $logo_dark_html = '';
    if ('' !== (string) $d2u_helper->getConfig('template_logo_dark', '')) {
        $media_logo_dark = rex_media::get((string) $d2u_helper->getConfig('template_logo_dark'));
        if ($media_logo_dark instanceof rex_media) {
            $logo_dark_html = '<img src="'. rex_url::media((string) $d2u_helper->getConfig('template_logo_dark')) .'?v='. $media_logo_dark->getUpdateDate() .'" alt="'. $media_logo_dark->getTitle() .'" id="logo-dark">';
        }
    }

    // Contact button article
    $contact_article = null;
    if ($d2u_helper->hasConfig('article_id_contact') && (int) $d2u_helper->getConfig('article_id_contact', 0) > 0) {
        $contact_article = rex_article::get((int) $d2u_helper->getConfig('article_id_contact'));
    }
?>
<?php if ('' !== $logo_html) { ?>
<div id="logo-container" class="d-print-none">
	<div class="container">
		<div class="d-flex justify-content-between align-items-center">
			<a href="<?= rex_getUrl(rex_article::getSiteStartArticleId()) ?>">
				<?php if ('' !== $logo_dark_html) { ?>
				<span class="logo-light"><?= $logo_html ?></span>
				<span class="logo-dark"><?= $logo_dark_html ?></span>
				<?php } else { ?>
				<?= $logo_html ?>
				<?php } ?>
			</a>
			<div class="d-flex align-items-center">
				<?php
                    // Search icon in logo bar
                    $article_id_search = (int) rex_config::get('d2u_helper', 'article_id_search', 0);
                    if (rex_addon::get('search_it')->isAvailable() && $article_id_search > 0 && $article_id_search !== rex_article::getCurrentId()) {
                        echo '<form method="get" action="'. rex_getUrl($article_id_search) .'#search-field" class="search_it-formfragment me-3">';
                        echo '<button id="search_icon" type="submit">';
                        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" style="width: 1.25em; height: 1.25em;">';
                        echo '<path fill="currentColor" d="M23.354 22.646l-5-5-.012-.007a8.532 8.532 0 10-.703.703l.007.012 5 5a.5.5 0 00.707-.707zM12 19.5a7.5 7.5 0 117.5-7.5 7.508 7.508 0 01-7.5 7.5z"></path>';
                        echo '</svg>';
                        echo '</button>';
                        echo '</form>';
                    }
                ?>
				<?php if ($contact_article instanceof rex_article) { ?>
				<a class="btn btn-contact-top" href="<?= $contact_article->getUrl() ?>"><span class="fa-icon fa-envelope"></span> <?= $contact_article->getName() ?></a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<nav class="d-print-none">
	<div class="container">
		<div class="navbar navbar-expand-<?= $menu_breakpoint ?>">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="mainNavbar">
				<ul class="navbar-nav me-auto align-items-<?= $menu_breakpoint ?>-center">
					<?php
                        if ('megamenu' === $menu_type) {
                            // MegaMenu with BS5 attributes
                            \TobiasKrais\D2UHelper\FrontendNavigationMegaMenu::getMenuItemsBS5();
                        } else {
                            // BS5 dropdown navigation
                            \TobiasKrais\D2UHelper\FrontendNavigationBS5::getMenuItems();
                        }
                    ?>
				</ul>
				<ul class="navbar-nav navbar-nav-utils ms-auto align-items-<?= $menu_breakpoint ?>-center">
					<?php
                        // Language switcher
                        \TobiasKrais\D2UHelper\FrontendNavigationBS5::getLanguageSwitcher($menu_breakpoint);

                        // Dark mode toggle
                        \TobiasKrais\D2UHelper\FrontendNavigationBS5::getDarkModeToggle($menu_breakpoint);
                    ?>
				</ul>
			</div>
		</div>
	</div>
</nav>
<?php
// BS5 Language Modal (when header_lang_icon is configured)
\TobiasKrais\D2UHelper\FrontendNavigationBS5::getLanguageModal();
?>
