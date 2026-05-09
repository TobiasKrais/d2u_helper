<?php
// SEO stuff
$current_domain = \rex::getServer();
if (rex_addon::get('yrewrite')->isAvailable()) {
	$yrewrite = new rex_yrewrite_seo();
	$current_domain = rex_yrewrite::getCurrentDomain()->getUrl();
}

// Get d2u_helper stuff
$d2u_helper = rex_addon::get("d2u_helper");
?>

<!DOCTYPE html>
<html lang="<?php echo rex_clang::getCurrent()->getCode(); ?>">
<head>
	<?php
		$fragment = new rex_fragment();
		// <head></head>
		echo $fragment->parse('d2u_template_head.php');

		echo '<link rel="stylesheet" type="text/css" href="'. TobiasKrais\D2UHelper\FrontendHelper::getTemplateAssetUrl('01-1', 'template.css') .'">';

		$jquery_file = 'jquery.min.js';
		echo '<script src="'. rex_url::coreAssets($jquery_file) .'?buster='. filemtime(rex_path::coreAssets($jquery_file)) .'"></script>';
		echo '<link rel="stylesheet" type="text/css" href="'. TobiasKrais\D2UHelper\FrontendHelper::getAddonAssetUrl('bootstrap4/bootstrap.min.css') .'" />';
	?>
</head>

<body>
	<header>
		<?php
			if($d2u_helper->hasConfig("template_logo") && $d2u_helper->getConfig('template_logo') !== '') {
		?>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<a href="<?php echo rex_getUrl(rex_article::getSiteStartArticleId()); ?>">
						<?php
						$media_logo = rex_media::get((string) $d2u_helper->getConfig("template_logo"));
						if($media_logo instanceof rex_media) {
							print '<img src="'. TobiasKrais\D2UHelper\FrontendHelper::getMediaUrl((string) $d2u_helper->getConfig("template_logo")) .'" alt="'. $media_logo->getTitle() .'" id="logo">';
						}
						?>
					</a>
				</div>
			</div>
		</div>
		<?php
			}
		?>
	</header>
<?php
		// Navi
		echo $fragment->parse('d2u_template_nav.php');
?>
	<article>
		<div class="container">
			<div class="row">
				<div class="col-12 col-md-6">
					<?php
						$header_image = (string) $d2u_helper->getConfig("template_header_pic");
						if($this->hasValue("art_file") && '' !== $this->getValue('art_file') && null !== $this->getValue('art_file')) { /** @phpstan-ignore-line */
							$header_image = $this->getValue("art_file"); /** @phpstan-ignore-line */
						}
						$media_header_pic = rex_media::get($header_image);
						if($media_header_pic instanceof rex_media) {
							print TobiasKrais\D2UHelper\FrontendHelper::getHeaderPictureTag($header_image, $media_header_pic->getTitle(), $media_header_pic->getTitle(), 'id="logo"');
						}
					?>
				</div>
				<div class="col-12 col-md-6">
					<div class="row">
						<?php
							// Breadcrumbs
							if($d2u_helper->hasConfig("show_breadcrumbs") && (bool) $d2u_helper->getConfig("show_breadcrumbs")) {
								print '<div class="col-12">';
								print '<div id="breadcrumbs">';
								print TobiasKrais\D2UHelper\FrontendHelper::getBreadcrumbs();
								print '</div>';
								print '</div>';
							}
							if(rex_article::getCurrent() instanceof rex_article && $d2u_helper->hasConfig('subhead_include_articlename') && (bool) $d2u_helper->getConfig('subhead_include_articlename') === true) {
								print '<div class="col-12">';
								print '<h1 class="subhead">'. rex_article::getCurrent()->getName() .'</h1>';
								print '</div>';
							}
							// Content follows
							print $this->getArticle(); /** @phpstan-ignore-line */
						?>
					</div>
				</div>
			</div>
		</div>
	</article>
	<footer>
		<div class="container">
			<?php
				echo $fragment->parse('d2u_template_footer.php');
			?>
		</div>
	</footer>
	<script>
		$(window).on("load",
			function(e) {
				$("[data-match-height]").each(
					function() {
						var e=$(this),
							t=$(this).find("[data-height-watch]"),
							n=t.map(function() {
								return $(this).innerHeight();
							}).get(),
							i=Math.max.apply(Math,n);
						t.css("min-height", i+1);
					}
				);
			}
		);

		$(window).on("load", function() {
			var heights = $(".same-height").map(function() {
				return $(this).innerHeight();
			}).get(),

			maxHeight = Math.max.apply(null, heights);

			$(".same-height").css("min-height", maxHeight);
		});
	</script>
	<?php
		echo $fragment->parse('d2u_template_cta_box.php');
	?>
	<script src="<?= TobiasKrais\D2UHelper\FrontendHelper::getAddonAssetUrl('bootstrap4/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>