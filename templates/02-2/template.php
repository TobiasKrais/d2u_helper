<?php
// SEO stuff
$current_domain = \rex::getServer();
if (rex_addon::get('yrewrite')->isAvailable()) {
    $yrewrite = new rex_yrewrite_seo();
    $current_domain = rex_yrewrite::getCurrentDomain()->getUrl();
}

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

        echo '<link rel="stylesheet" href="'. rex_url::frontendController(['template_id' => '02-2', 'd2u_helper' => 'template.css']) .'">';

        // Bootstrap 5 CSS (no jQuery needed)
        echo '<link rel="stylesheet" type="text/css" href="'. rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap5/css/bootstrap.min.css') .'" />';
    ?>
	<?php $fragment = new rex_fragment(); $fragment->setVar('position', 'head'); echo $fragment->parse('d2u_template_darkmode.php'); ?>
</head>

<body>
	<a id="top"></a>
	<header>
		<div class="container">
			<?php
                if ($d2u_helper->hasConfig('template_logo') && '' !== $d2u_helper->getConfig('template_logo')) {
            ?>
			<div class="row abstand" id="headerdiv">
				<div class="col-12">
					<a href="<?= rex_getUrl(rex_article::getSiteStartArticleId()) ?>">
						<?php
                        $media_logo = rex_media::get((string) $d2u_helper->getConfig('template_logo'));
                        if ($media_logo instanceof rex_media) {
                            echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('template_logo')) .'" alt="'. $media_logo->getTitle() .'" id="logo">';
                        }
                        ?>
					</a>
				</div>
			</div>
		</div>
	</header>
	<?php
        }
        // Navi if above header picture
        if ($d2u_helper->isAvailable() && 'top' === $d2u_helper->getConfig('template_navi_pos', 'bottom')) {
            echo $fragment->parse('d2u_template_bs5_nav.php');
        }
    ?>
	<header class="second-header">
		<div class="container">
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
                                echo '<img src="'. ('' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) $d2u_helper->getConfig('template_header_media_manager_type', ''), $header_image) : rex_url::media($header_image)) .'" alt="'. $media_header_pic->getTitle() .'" title="'. $media_header_pic->getTitle() .'" id="header" width="1200px">';
                            }
                        }
                    ?>
				</div>
			</div>
		</div>
	</header>
	<?php
        // Navi if below header picture
        if ($d2u_helper->isAvailable() && 'bottom' === $d2u_helper->getConfig('template_navi_pos', 'bottom')) {
            echo $fragment->parse('d2u_template_bs5_nav.php');
        }
    ?>
	<article>
        <div class="container-wrapper">
            <div class="container">
                <div class="row">
                    <?php
                        // Breadcrumbs
                        if ($d2u_helper->hasConfig('show_breadcrumbs') && (bool) $d2u_helper->getConfig('show_breadcrumbs')) {
                            echo '<div class="col-12">';
                            echo '<div id="breadcrumbs">';
                            echo TobiasKrais\D2UHelper\FrontendHelper::getBreadcrumbs();
                            echo '</div>';
                            echo '</div>';
                        }
                        if (rex_article::getCurrent() instanceof rex_article && $d2u_helper->hasConfig('subhead_include_articlename') && (bool) $d2u_helper->getConfig('subhead_include_articlename')) {
                            echo '<div class="col-12">';
                            echo '<h1 class="subhead">'. rex_article::getCurrent()->getName() .'</h1>';
                            echo '</div>';
                        } elseif ($d2u_helper->hasConfig('show_breadcrumbs') && (bool) $d2u_helper->getConfig('show_breadcrumbs')) {
                            echo '<div class="col-12 abstand"></div>';
                        }
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
	<?php $fragment = new rex_fragment(); $fragment->setVar('position', 'body'); echo $fragment->parse('d2u_template_darkmode.php'); ?>
</body>
</html>
