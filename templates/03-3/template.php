<?php
// Get d2u_helper stuff
$d2u_helper = rex_addon::get('d2u_helper');

$print = filter_input(INPUT_GET, 'print', FILTER_SANITIZE_SPECIAL_CHARS); // Remove when https://github.com/twbs/bootstrap/issues/22753 is solved
?>

<!DOCTYPE html>
<html lang="<?= rex_clang::getCurrent()->getCode() ?>">
<head>
	<?php
        $fragment = new rex_fragment();
        // <head></head>
        echo $fragment->parse('d2u_template_head.php');

        echo '<link rel="stylesheet" href="'. rex_url::frontendController(['template_id' => '03-3', 'd2u_helper' => 'template.css']) .'">';

        // Bootstrap 5 CSS (no jQuery needed)
        echo '<link rel="stylesheet" type="text/css" href="'. rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap5/css/bootstrap.min.css') .'" />';
    ?>
	<?php $fragment = new rex_fragment(); $fragment->setVar('position', 'head'); echo $fragment->parse('d2u_template_darkmode.php'); ?>
</head>

<body>
	<a id="top"></a>
	<div class="container">
		<header class="right-border" id="header_template">
			<div class="row">
				<div class="col-12">
					<?php
                        if ($d2u_helper->hasConfig('template_header_pic') && '' !== $d2u_helper->getConfig('template_header_pic')) {
                            $header_image = (string) $d2u_helper->getConfig('template_header_pic');
                            if ($this->hasValue('art_file') && '' !== $this->getValue('art_file') && null !== $this->getValue('art_file')) { /** @phpstan-ignore-line */
                                $header_image = $this->getValue('art_file'); /** @phpstan-ignore-line */
                            }
                            $media_header_pic = rex_media::get($header_image);
                            if ($media_header_pic instanceof rex_media) {
                                echo '<img src="'. ('' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) $d2u_helper->getConfig('template_header_media_manager_type', ''), $header_image) : rex_url::media($header_image)) .'" alt="'. $media_header_pic->getTitle() .'" class="d-print-none">';
                            }
                        }
                        if ($d2u_helper->hasConfig('template_print_header_pic') || '' !== $d2u_helper->getConfig('template_print_header_pic')) {
                            echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('template_print_header_pic')) .'" alt="" class="d-none d-print-block">';
                        }
                    ?>
				</div>
			</div>
		</header>
	</div>
	<?php
        if (null === $print) {
            // Navigation (Bootstrap 5 version)
            echo $fragment->parse('d2u_template_bs5_nav.php');
        }
    ?>
	<article>
		<div class="container">
			<div class="row">
				<?php
                    // Breadcrumbs
                    if (null === $print) {
                        if ($d2u_helper->hasConfig('show_breadcrumbs') && (bool) $d2u_helper->getConfig('show_breadcrumbs')) {
                            echo '<div class="col-12 d-print-none">';
                            echo '<div id="breadcrumbs">';
                            echo TobiasKrais\D2UHelper\FrontendHelper::getBreadcrumbs();
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                    if (rex_article::getCurrent() instanceof rex_article && $d2u_helper->hasConfig('subhead_include_articlename') && (bool) $d2u_helper->getConfig('subhead_include_articlename')) {
                        echo '<div class="col-12">';
                        echo '<h1 class="subhead">'. rex_article::getCurrent()->getName() .'</h1>';
                        echo '</div>';
                    } elseif ($d2u_helper->hasConfig('show_breadcrumbs') && (bool) $d2u_helper->getConfig('show_breadcrumbs')) {
                        // If not title, but breadcrumbs: show empty row
                        echo '<div class="col-12 abstand d-print-none"></div>';
                    }
                ?>
			</div>
			<?php
                if (null === $print) { // Remove when https://github.com/twbs/bootstrap/issues/22753 is solved
                    echo '<div class="row">';
                    echo '<div class="col-12 col-lg-9">';
                    echo '<div class="row">';
                }
                // Content follows
                echo $this->getArticle(1); /** @phpstan-ignore-line */
                if (null === $print) { // Remove when https://github.com/twbs/bootstrap/issues/22753 is solved
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="col-12 col-lg-3 d-print-none">';
                    echo '<div class="row">';
                    echo $this->getArticle(2); /** @phpstan-ignore-line */
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            ?>
		</div>
	</article>
	<footer class="d-print-none">
		<div class="container footer">
			<a href="#top" id="jump-top" title="<?= \Sprog\Wildcard::get('d2u_helper_template_jump_top') ?>"></a>
			<?= $fragment->parse('d2u_template_bs5_footer.php') ?>
		</div>
	</footer>
	<div class="container">
		<div class="row">
			<?php
                echo '<div class="col-12 d-none d-print-block">';
                if ($d2u_helper->hasConfig('template_print_footer_pic') || '' !== $d2u_helper->getConfig('template_print_footer_pic')) {
                    echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('template_print_footer_pic')) .'" alt="">';
                }
                echo '</div>';
            ?>
		</div>
	</div>
	<script>
		// Match height functionality (vanilla JS replacement for jQuery matchHeight)
		document.addEventListener('DOMContentLoaded', function() {
			document.querySelectorAll('[data-match-height]').forEach(function(container) {
				var items = container.querySelectorAll('[data-height-watch]');
				var maxH = 0;
				items.forEach(function(el) { if (el.offsetHeight > maxH) maxH = el.offsetHeight; });
				items.forEach(function(el) { el.style.minHeight = (maxH + 1) + 'px'; });
			});
			var sameHeightEls = document.querySelectorAll('.same-height');
			if (sameHeightEls.length > 0) {
				var maxH = 0;
				sameHeightEls.forEach(function(el) { if (el.offsetHeight > maxH) maxH = el.offsetHeight; });
				sameHeightEls.forEach(function(el) { el.style.minHeight = maxH + 'px'; });
			}
		});
	</script>
	<?= $fragment->parse('d2u_template_cta_box.php') ?>
	<script src="<?= rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap5/js/bootstrap.bundle.min.js') ?>"></script>
	<?php $fragment = new rex_fragment(); $fragment->setVar('position', 'body'); echo $fragment->parse('d2u_template_darkmode.php'); ?>
</body>
</html>
