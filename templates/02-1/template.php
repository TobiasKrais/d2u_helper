<?php
// SEO stuff
$alternate = ""; 
$canonical = "";
$current_domain = rex::getServer();
$description = "";
$robots = "";
$title = "";
if (rex_addon::get('yrewrite')->isAvailable()) {
	$yrewrite = new rex_yrewrite_seo();
	$alternate = $yrewrite->getHreflangTags();
	$canonical = $yrewrite->getCanonicalUrlTag();
	$current_domain = rex_yrewrite::getCurrentDomain()->getUrl();
	$description = $yrewrite->getDescriptionTag();
	$robots = $yrewrite->getRobotsTag();
	$title = $yrewrite->getTitleTag();
}

// Get d2u_helper stuff
$d2u_helper = rex_addon::get("d2u_helper");
?>

<!DOCTYPE html>
<html lang="<?php echo rex_clang::getCurrent()->getCode(); ?>">
<head>
	<meta charset="utf-8" />
	<base href="<?php echo $current_domain; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		echo $title.PHP_EOL;
		echo $description .PHP_EOL;
		echo $robots .PHP_EOL;
		echo $alternate .PHP_EOL;
		echo $canonical .PHP_EOL;
	?>
	<link href="https://fonts.googleapis.com/css?family=Istok+Web:400,700" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="index.php?template_id=02-1&d2u_helper=template.css">
	<link rel="icon" href="<?php print rex_url::media('favicon.ico') ?>">
</head>

<body>
	<div class="container">
		<div class="row abstand">
			<div class="col-4 col-sm-7">&nbsp;</div>
			<div class="col-8 col-sm-5">
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
		<div class="row">
			<div class="col-12">
				<?php
					if(!$d2u_helper->hasConfig("template_header_pic") || $d2u_helper->getConfig("template_header_pic") == "") {
						print "<p style='font: 2em red bold;'>WARNING: Template settings are not complete.</p>";
					}
					
					$header_image = $d2u_helper->getConfig("template_header_pic");
					if($this->hasValue("art_file") && $this->getValue("art_file") != "") {
						$header_image = $this->getValue("art_file");
					}
					$media_side_pic = rex_media::get($header_image);
					if($media_side_pic instanceof rex_media) {
						print '<img src="'. rex_url::media($header_image) .'" alt="'. $media_side_pic->getTitle() .'" title="'. $media_side_pic->getTitle() .'" id="logo" width="1200px">';
					}
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<nav>
					<div class="row">
						<div class="col-12">
								<?php
								if(rex_addon::get('d2u_helper')->isAvailable()) {
									d2u_mobile_navi::getResponsiveMultiLevelMobileMenu();
									d2u_mobile_navi::getResponsiveMultiLevelDesktopMenu();
								}
								?>
						</div>
					</div>
				</nav>
			</div>
		</div>
		<article>
			<div class="row">
				<?php
					// Breadcrumbs
					if($d2u_helper->hasConfig("show_breadcrumbs") && $d2u_helper->getConfig("show_breadcrumbs")) {
						print '<div class="col-12">';
						print '<div id="breadcrumbs">';
						$startarticle = rex_article::get(rex_article::getSiteStartArticleId());
						echo '<a href="' . $startarticle->getUrl() . '"><span class="fa-home"></span></a>';
						$current_article = rex_article::getCurrent();
						$path = $current_article->getPathAsArray();
						foreach ($path as $id) {
							$article = rex_category::get($id);
							echo ' &nbsp;»&nbsp;&nbsp;<a href="' . $article->getUrl() . '">' . $article->getName() . '</a>';
						}
						if(!$current_article->isStartArticle()) {
							echo ' &nbsp;»&nbsp;&nbsp;<a href="' . $current_article->getUrl() . '">' . $current_article->getName() . '</a>';
						}
						print '</div>';
						print '</div>';
					}
					if($d2u_helper->hasConfig('subhead_include_articlename') && $d2u_helper->getConfig('subhead_include_articlename') == "true") {
						print '<div class="col-12">';
						print '<h1 class="subhead">'. $current_article->getName() .'</h1>';
						print '</div>';
					}
					// Content follows
					print $this->getArticle();
				?>
			</div>
		</article>
		<div class="row abstand">
			<div class="col-12">
				<footer>
					<div class="row">
						<?php
							if($d2u_helper->hasConfig('template_02_1_footer_text') && $d2u_helper->getConfig('template_02_1_footer_text') != "") {
								print '<div class="col-12 col-md-5 col-lg-7">';
							}
							else {
								print '<div class="col-12">';
							}
							$rex_articles = rex_article::getRootArticles(true);
							foreach($rex_articles as $rex_articles) {
								print '<div class="footerbox">';
								print '<a href="'. $rex_articles->getUrl() .'">'. $rex_articles->getName() .'</a>';
								print '</div>';
							}
							print '</div>';

							if($d2u_helper->hasConfig('template_02_1_footer_text') && $d2u_helper->getConfig('template_02_1_footer_text') != "") {
								print '<div class="col-12 col-md-7 col-lg-5">';
								print '<h2>'. $d2u_helper->getConfig('template_02_1_footer_text') .'</h2>';
								print '</div>';
							}
						?>
					</div>
				</footer>
			</div>
		</div>
	</div>
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