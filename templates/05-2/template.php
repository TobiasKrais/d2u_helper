<?php
// SEO stuff
$current_domain = \rex::getServer();
if (rex_addon::get('yrewrite')->isAvailable()) {
    $yrewrite = new rex_yrewrite_seo();
    $current_domain = rex_yrewrite::getCurrentDomain()->getUrl();
}

$current_article = rex_article::getCurrent();

// Get d2u_helper stuff
$d2u_helper = rex_addon::get('d2u_helper');
?>

<!DOCTYPE html>
<html lang="<?= rex_clang::getCurrent()->getCode() ?>">
<head>
	<?php
        $fragment = new rex_fragment();
        // <head></head>
        echo $fragment->parse('d2u_template_head.php');

        echo '<link rel="stylesheet" href="'. rex_url::frontendController(['template_id' => '05-2', 'd2u_helper' => 'template.css']) .'">';

        // Bootstrap 5 CSS (no jQuery needed)
        echo '<link rel="stylesheet" type="text/css" href="'. rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap5/css/bootstrap.min.css') .'" />';
    ?>
	<script>
		// Apply dark mode preference before page renders to prevent flash
		(function() {
			var stored = localStorage.getItem('d2u_theme');
			if (stored) {
				document.documentElement.setAttribute('data-bs-theme', stored);
			} else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				document.documentElement.setAttribute('data-bs-theme', 'dark');
			}
		})();
	</script>
</head>

<body>
	<a id="top"></a>
	<header>
		<div class="container">
			<div class="row">
				<div class="col-6 col-xl-2">
					<div id="logo-left">
						<?php
                            if ('' !== $d2u_helper->getConfig('template_logo', '')) {
                                echo '<a href="'. (\rex_addon::get('yrewrite')->isAvailable() ? \rex_yrewrite::getCurrentDomain()->getUrl() : \rex::getServer()) .'">';
                                $media_logo = rex_media::get((string) $d2u_helper->getConfig('template_logo'));
                                if ($media_logo instanceof rex_media) {
                                    echo '<span class="logo-light">';
                                    echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('template_logo')) .'" alt="'. $media_logo->getTitle() .'" id="logo">';
                                    echo '</span>';
                                    // Dark mode logo
                                    $dark_logo = (string) $d2u_helper->getConfig('template_logo_dark', '');
                                    if ('' !== $dark_logo) {
                                        $media_logo_dark = rex_media::get($dark_logo);
                                        if ($media_logo_dark instanceof rex_media) {
                                            echo '<span class="logo-dark">';
                                            echo '<img src="'. rex_url::media($dark_logo) .'" alt="'. $media_logo_dark->getTitle() .'" id="logo">';
                                            echo '</span>';
                                        }
                                    }
                                }
                                echo '</a>';
                            }
                        ?>
					</div>
				</div>
				<div class="col-6 d-block d-xl-none">
					<div id="logo-right">
						<?php
                            if ('' !== $d2u_helper->getConfig('template_logo_2', '')) {
                                if ('' !== $d2u_helper->getConfig('template_logo_2_link', '')) {
                                    echo '<a href="'. $d2u_helper->getConfig('template_logo_2_link', '') .'">';
                                }
                                $media_logo = rex_media::get((string) $d2u_helper->getConfig('template_logo_2'));
                                if ($media_logo instanceof rex_media) {
                                    echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('template_logo_2')) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo">';
                                }
                                if ('' !== $d2u_helper->getConfig('template_logo_2_link', '')) {
                                    echo '</a>';
                                }
                            }
                        ?>
					</div>
				</div>
				<?php
                    $header_pic_style = '';
                    // Calculate header image
                    $header_image = (string) rex_config::get('d2u_helper', 'template_header_pic', '');

                    if ($this->hasValue('art_file') && '' !== $this->getValue('art_file') && null !== $this->getValue('art_file')) { /** @phpstan-ignore-line */
                        $header_image = $this->getValue('art_file'); /** @phpstan-ignore-line */
                    }
                    $titelbild = rex_media::get($header_image);
                    if ($titelbild instanceof rex_media) {
                        $header_pic_style = 'background: url('.
                                ('' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) $d2u_helper->getConfig('template_header_media_manager_type', ''), $header_image) : rex_url::media($header_image)) .') center center; background-size: cover;';
                    }
                ?>
				<div class="col-12 col-xl-8" style="<?= $header_pic_style ?>">
				</div>
				<div class="d-none d-xl-block col-xl-2">
					<div id="logo-right">
						<?php
                            if ('' !== $d2u_helper->getConfig('template_logo_2', '')) {
                                if ('' !== $d2u_helper->getConfig('template_logo_2_link', '')) {
                                    echo '<a href="'. $d2u_helper->getConfig('template_logo_2_link', '') .'">';
                                }
                                $media_logo = rex_media::get((string) $d2u_helper->getConfig('template_logo_2'));
                                if ($media_logo instanceof rex_media) {
                                    echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('template_logo_2')) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo">';
                                }
                                if ('' !== $d2u_helper->getConfig('template_logo_2_link', '')) {
                                    echo '</a>';
                                }
                            }
                        ?>
					</div>
				</div>
			</div>
		</div>
	</header>
	<?php
        // Navigation (Bootstrap 5 version)
        echo $fragment->parse('d2u_template_bs5_nav.php');
    ?>
	<article>
		<div class="container">
			<div class="row">
				<div class="col-12 col-xl-2" id="navi-inner-frame">
					<?php
                        // Languages
                        echo '<div id="lang_chooser_div">';
                        $lang_modal = new rex_fragment();
                        echo $lang_modal->parse('d2u_template_language_icon.php');
                        echo $lang_modal->parse('d2u_template_language_modal.php');
                        echo '</div>';
                        // Search icon
                        if (rex_addon::get('search_it')->isAvailable() && rex_config::get('d2u_helper', 'article_id_search', 0) > 0) {
                            echo '<div id="search_icon_div">';
                            echo $fragment->parse('d2u_template_search_icon.php');
                            echo '</div>';
                        }
                    ?>
					<nav id="sidebar-nav">
						<?php
                            if ($d2u_helper->isAvailable()) {
                                // Render sidebar navigation using BS5 vertical nav
                                $navHelper = new TobiasKrais\D2UHelper\FrontendNavigationBS5();
                                $menu_items = $navHelper->getMenuItems(0, true);
                                if ('' !== $menu_items) {
                                    echo '<ul class="nav flex-column">';
                                    echo $menu_items;
                                    echo '</ul>';
                                }
                            }
                        ?>
					</nav>
				</div>
				<div class="col-12 col-xl-8" id="article-content">
					<div class="row">
						<?php
                            // Breadcrumbs
                            if ((bool) $d2u_helper->getConfig('show_breadcrumbs', 'false')) {
                                echo '<div class="col-12 d-print-none" id="breadcrumbs">';
                                echo TobiasKrais\D2UHelper\FrontendHelper::getBreadcrumbs();
                                echo '</div>';
                            }

                            // Content follows
                            echo $this->getArticle(); /** @phpstan-ignore-line */

                            // Footer
                            echo '<div class="col-12" id="footer">';
                            echo $fragment->parse('d2u_template_bs5_footer.php');
                            echo '</div>';
                        ?>
					</div>
				</div>
				<div class="d-none d-xl-block col-xl-2" id="teaser-inner-frame">
					<?php
                        if ('' !== $d2u_helper->getConfig('template_05_1_info_text', '')) {
                            echo (string) $d2u_helper->getConfig('template_05_1_info_text');
                        }
                    ?>
				</div>
			</div>
		</div>
	</article>
	<?= $fragment->parse('d2u_template_cta_box.php') ?>
	<script src="<?= rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap5/js/bootstrap.bundle.min.js') ?>"></script>
	<script>
		// Dark mode toggle functionality
		(function() {
			var toggle = document.getElementById('darkModeToggle');
			if (!toggle) return;

			toggle.addEventListener('click', function() {
				var current = document.documentElement.getAttribute('data-bs-theme');
				var next = current === 'dark' ? 'light' : 'dark';
				document.documentElement.setAttribute('data-bs-theme', next);
				localStorage.setItem('d2u_theme', next);
			});

			// Listen for system preference changes when no manual override
			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
				if (!localStorage.getItem('d2u_theme')) {
					document.documentElement.setAttribute('data-bs-theme', e.matches ? 'dark' : 'light');
				}
			});
		})();
	</script>
</body>
</html>
