<?php
// Get placeholder wildcard tags
$sprog = rex_addon::get("sprog");
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');

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

		echo '<link rel="stylesheet" href="'. rex_url::frontendController(['template_id' => '01-1', 'd2u_helper' => 'template.css']) .'">';
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
							print '<img src="'. rex_url::media((string) $d2u_helper->getConfig("template_logo")) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo">';
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
							print '<img src="'. ('' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) $d2u_helper->getConfig('template_header_media_manager_type', ''), $header_image) : rex_url::media($header_image)) .'" alt="'. $media_header_pic->getTitle() .'" title="'. $media_header_pic->getTitle() .'" id="logo">';
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
</body>
</html>