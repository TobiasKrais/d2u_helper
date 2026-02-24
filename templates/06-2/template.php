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

        echo '<link rel="stylesheet" href="'. rex_url::frontendController(['template_id' => '06-2', 'd2u_helper' => 'template.css']) .'">';

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

<body id="body">
	<a id="top"></a>
	<div class="container">
		<div class="row" id="paper-sheet">
			<div class="col-12">
				<header>
					<div class="row">
						<div class="col-12">
							<?php
                                if ('' !== rex_config::get('d2u_helper', 'template_header_pic', '')) {
                                    $header_image_filename = (string) rex_config::get('d2u_helper', 'template_header_pic');
                                    $header_image = rex_media::get($header_image_filename);
                                    if ($header_image instanceof rex_media) {
                                        echo '<img src="'. ('' !== rex_config::get('d2u_helper', 'template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) rex_config::get('d2u_helper', 'template_header_media_manager_type', ''), $header_image_filename) : rex_url::media($header_image_filename)) .'" alt="'. $header_image->getTitle() .'" id="header-image">';
                                    }
                                }
                                if ('' !== rex_config::get('d2u_helper', 'template_logo', '')) {
                                    $media_logo = rex_media::get((string) rex_config::get('d2u_helper', 'template_logo'));
                                    if ($media_logo instanceof rex_media) {
                                        echo '<a href="' . rex_getUrl(rex_article::getSiteStartArticleId()) . '">';
                                        echo '<span class="logo-light">';
                                        echo '<img src="'. rex_url::media($media_logo->getFileName()) .'" alt="'. $media_logo->getTitle() .'" id="logo-top">';
                                        echo '</span>';
                                        // Dark mode logo
                                        $dark_logo = (string) $d2u_helper->getConfig('template_logo_dark', '');
                                        if ('' !== $dark_logo) {
                                            $media_logo_dark = rex_media::get($dark_logo);
                                            if ($media_logo_dark instanceof rex_media) {
                                                echo '<span class="logo-dark">';
                                                echo '<img src="'. rex_url::media($dark_logo) .'" alt="'. $media_logo_dark->getTitle() .'" id="logo-top">';
                                                echo '</span>';
                                            }
                                        }
                                        echo '</a>';
                                    }
                                }
                            ?>
						</div>
					</div>
				</header>
				<?php
                    // Navigation (Bootstrap 5 version) - displayed as top bar on mobile
                    echo $fragment->parse('d2u_template_bs5_nav.php');
                ?>
				<div class="row">
					<div class="col-12 col-lg-3 d-none d-lg-block">
						<div class="row">
							<div class="col-12">
								<?php
                                    // Languages
                                    if (count(rex_clang::getAllIds(true)) > 1) {
                                        echo '<div id="lang_chooser_div">';
                                        $lang_modal = new rex_fragment();
                                        echo $lang_modal->parse('d2u_template_language_icon.php');
                                        echo $lang_modal->parse('d2u_template_language_modal.php');
                                        echo '</div>';
                                    }
                                    // Search icon
                                    if (rex_addon::get('search_it')->isAvailable() && rex_config::get('d2u_helper', 'article_id_search', 0) > 0) {
                                        echo '<div id="search_icon_div">';
                                        echo $fragment->parse('d2u_template_search_icon.php');
                                        echo '</div>';
                                    }
                                ?>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
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
						</div>
					</div>
					<div class="col-12 col-lg-9">
						<article>
							<div class="row">
								<?php
                                    // Content follows
                                    echo $this->getArticle(); /** @phpstan-ignore-line */
                                ?>
							</div>
						</article>
					</div>
				</div>
				<footer>
					<div class="row">
						<div class="col-12 col-sm-8 col-lg-7 offset-lg-3" id="footer-left">
							<?php
                                $rex_articles = rex_article::getRootArticles(true);
                                $show_separator = false;
                                foreach ($rex_articles as $rex_article) {
                                    if ($show_separator) {
                                        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
                                    }
                                    echo '<a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a>';
                                    $show_separator = true;
                                }
                            ?>
						</div>
						<div class="col-12 col-sm-4 col-lg-2" id="footer-right">
							<?php
                                if ('' !== rex_config::get('d2u_helper', 'template_logo', '')) {
                                    $media_logo = rex_media::get((string) rex_config::get('d2u_helper', 'template_logo'));
                                    if ($media_logo instanceof rex_media) {
                                        echo '<a href="' . rex_getUrl(rex_article::getSiteStartArticleId()) . '">';
                                        echo '<span class="logo-light">';
                                        echo '<img src="'. rex_url::media($media_logo->getFileName()) .'" alt="'. $media_logo->getTitle() .'" id="logo-footer">';
                                        echo '</span>';
                                        // Dark mode logo in footer
                                        $dark_logo = (string) $d2u_helper->getConfig('template_logo_dark', '');
                                        if ('' !== $dark_logo) {
                                            $media_logo_dark = rex_media::get($dark_logo);
                                            if ($media_logo_dark instanceof rex_media) {
                                                echo '<span class="logo-dark">';
                                                echo '<img src="'. rex_url::media($dark_logo) .'" alt="'. $media_logo_dark->getTitle() .'" id="logo-footer">';
                                                echo '</span>';
                                            }
                                        }
                                        echo '</a>';
                                    }
                                }
                            ?>
						</div>
					</div>
				</footer>
			</div>
		</div>
	</div>
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
