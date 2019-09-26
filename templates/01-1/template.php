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
	<meta charset="utf-8">
	<base href="<?php echo $current_domain; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		print d2u_addon_frontend_helper::getMetaTags();
	?>
	<link rel="stylesheet" href="/index.php?template_id=01-1&d2u_helper=template.css">
	<?php
		if(file_exists(rex_path::media('favicon.ico'))) {
			print '<link rel="icon" href="'. rex_url::media('favicon.ico') .'">';
		}
	?>
</head>

<body>
	<header>
		<?php
			if($d2u_helper->hasConfig("template_logo") && $d2u_helper->getConfig("template_logo") != "") {
		?>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<a href="<?php echo rex_getUrl(rex_article::getSiteStartArticleId()); ?>">
						<?php
						$media_logo = rex_media::get($d2u_helper->getConfig("template_logo"));
						if($media_logo instanceof rex_media) {
							print '<img src="'. rex_url::media($d2u_helper->getConfig("template_logo")) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo">';
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
	<nav>
		<div class="container">
			<div class="row">
				<div class="col-12" data-match-height>
					<div class="navi">
						<?php
							// Navi
							if(rex_addon::get('d2u_helper')->isAvailable()) {
								d2u_mobile_navi_smartmenus::getMenu();
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</nav>
	<article>
		<div class="container">
			<div class="row">
				<div class="col-12 col-md-6">
					<?php
						if(!$d2u_helper->hasConfig("template_header_pic") || $d2u_helper->getConfig("template_header_pic") == "") {
							print "<p style='font: 2em red bold;'>WARNING: Template settings are not complete.</p>";
						}
						
						$header_image = $d2u_helper->getConfig("template_header_pic");
						if($this->hasValue("art_file") && $this->getValue("art_file") != "") {
							$header_image = $this->getValue("art_file");
						}
						$media_header_pic = rex_media::get($header_image);
						if($media_header_pic instanceof rex_media) {
							print '<img src="'. rex_url::media($header_image) .'" alt="'. $media_header_pic->getTitle() .'" title="'. $media_header_pic->getTitle() .'" id="logo">';
						}
					?>
				</div>
				<div class="col-12 col-md-6">
					<div class="row">
						<?php
							// Breadcrumbs
							if($d2u_helper->hasConfig("show_breadcrumbs") && $d2u_helper->getConfig("show_breadcrumbs")) {
								print '<div class="col-12">';
								print '<div id="breadcrumbs">';
								print d2u_addon_frontend_helper::getBreadcrumbs();
								print '</div>';
								print '</div>';
							}
							if($d2u_helper->hasConfig('subhead_include_articlename') && $d2u_helper->getConfig('subhead_include_articlename') == "true") {
								print '<div class="col-12">';
								print '<h1 class="subhead">'. rex_article::getCurrent()->getName() .'</h1>';
								print '</div>';
							}
							// Content follows
							print $this->getArticle();
						?>
					</div>
				</div>
			</div>
		</div>
	</article>
	<footer>
		<div class="container">
			<div class="row">
				<?php
					$rex_articles = rex_article::getRootArticles(true);
					foreach($rex_articles as $rex_articles) {
						print '<div class="col-sm-6 col-md-4 col-lg-3">';
						print '<div class="footerbox">';
						print '<a href="'. $rex_articles->getUrl() .'">'. $rex_articles->getName() .'</a>';
						print '</div>';
						print '</div>';
					}
				?>
			</div>
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
</body>
</html>