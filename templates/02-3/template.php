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

$url_namespace = TobiasKrais\D2UHelper\FrontendHelper::getUrlNamespace();
$url_id = TobiasKrais\D2UHelper\FrontendHelper::getUrlId();
?>

<!DOCTYPE html>
<html lang="<?= rex_clang::getCurrent()->getCode() ?>">
<head>
	<?php
        $fragment = new rex_fragment();
        // <head></head>
        echo $fragment->parse('d2u_template_head.php');

        echo '<link rel="stylesheet" href="'. rex_url::frontendController(['template_id' => '02-3', 'd2u_helper' => 'template.css']) .'">';

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
	<?php
        $has_header_image = false;
        $header_image = '';
        if ($d2u_helper->hasConfig('template_header_pic') && '' !== $d2u_helper->getConfig('template_header_pic')) {
            $header_image = (string) $d2u_helper->getConfig('template_header_pic');
            if ($this->hasValue('art_file') && '' !== $this->getValue('art_file') && null !== $this->getValue('art_file')) { /** @phpstan-ignore-line */
                $header_image = $this->getValue('art_file'); /** @phpstan-ignore-line */
            }
            $media_header_pic = rex_media::get($header_image);
            if ($media_header_pic instanceof rex_media) {
                $has_header_image = true;
            }
        }
    ?>
	<header<?= $has_header_image ? ' class="has-image"' : '' ?>>
		<?php if ($has_header_image && isset($media_header_pic) && $media_header_pic instanceof rex_media) { ?>
		<div class="header-image">
			<img src="<?= '' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) $d2u_helper->getConfig('template_header_media_manager_type', ''), $header_image) : rex_url::media($header_image) ?>" alt="<?= $media_header_pic->getTitle() ?>" title="<?= $media_header_pic->getTitle() ?>" id="header">
		</div>
		<?php } ?>
		<div class="header-overlay">
			<?php
                // Navigation (Bootstrap 5 version)
                echo $fragment->parse('d2u_template_bs5_nav.php');
            ?>
		</div>
	</header>
	<section id="breadcrumbs">
		<div class="container">
			<div class="row">
				<?php
                    if ((bool) $d2u_helper->getConfig('show_breadcrumbs', false)) {
                        echo '<div class="col-12 d-print-none">';
                        echo '<div id="breadcrumbs-inner"><small>';
                        echo TobiasKrais\D2UHelper\FrontendHelper::getBreadcrumbs();
                        echo '</small></div>';
                        echo '</div>';
                    }

                    if ($current_article instanceof rex_article && $d2u_helper->hasConfig('subhead_include_articlename') && true === (bool) $d2u_helper->getConfig('subhead_include_articlename')) {
                        echo '<div class="col-12 subhead-nav">';
                        echo '<h1 class="subhead">'. $current_article->getName() .'</h1>';
                        echo '</div>';
                    }
                ?>
			</div>
		</div>
	</section>
	<article>
        <div class="container-wrapper">
            <div class="container">
                <div class="row">
                    <?php
                        // Content follows
                        echo $this->getArticle(); /** @phpstan-ignore-line */
                    ?>
                </div>
            </div>
        </div>
	</article>
	<footer class="d-print-none">
		<div class="container footer">
			<a href="#top" id="jump-top" title="<?= \Sprog\Wildcard::get('d2u_helper_template_jump_top') ?>"></a>
			<?= $fragment->parse('d2u_template_bs5_footer.php') ?>
		</div>
	</footer>
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
