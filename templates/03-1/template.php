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

$print = filter_input(INPUT_GET, 'print', FILTER_SANITIZE_SPECIAL_CHARS); // Remove when https://github.com/twbs/bootstrap/issues/22753 is solved
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
	<link rel="stylesheet" href="index.php?template_id=03-1&d2u_helper=template.css">
	<link rel="icon" href="<?php print rex_url::media('favicon.ico') ?>">
	<style>
		.desktop-navi {
			width: <?php print (100 / count(rex_category::getRootCategories(TRUE)));?>% !important;
		}
	</style>
</head>

<body>
	<div class="container">
		<header class="right-border" id="header_template">
			<div class="row">
				<div class="col-12">
					<?php
						if(!$d2u_helper->hasConfig("template_header_pic") || $d2u_helper->getConfig("template_header_pic") == "") {
							print "<p style='font: 2em red bold;'>WARNING: Template settings are not complete.</p>";
						}
						else {
							$header_image = $d2u_helper->getConfig("template_header_pic");
							if($this->hasValue("art_file") && $this->getValue("art_file") != "") {
								$header_image = $this->getValue("art_file");
							}
							$media_header_pic = rex_media::get($header_image);
							if($media_header_pic instanceof rex_media) {
								print '<img src="'. rex_url::media($header_image) .'" alt="'. $media_header_pic->getTitle() .'" title="'. $media_header_pic->getTitle() .'" class="hidden-print">';
							}
						}
						if($d2u_helper->hasConfig("template_print_header_pic") || $d2u_helper->getConfig("template_print_header_pic") != "") {
							print '<img src="'. rex_url::media($d2u_helper->getConfig("template_print_header_pic")) .'" alt="" class="visible-print-block">';
						}
					?>
				</div>
			</div>
		</header>
		<?php
			if($print == "") {
		?>
		<div class="row hidden-print">
			<div class="col-12">
				<div class="right-border distance-bottom">
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
		</div>
		<?php
			}
		?>
		<article>
			<div class="row">
				<?php
					// Breadcrumbs
					if($print == "") {
						if($d2u_helper->hasConfig("show_breadcrumbs") && $d2u_helper->getConfig("show_breadcrumbs")) {
							print '<div class="col-12 hidden-print">';
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
							if(rex_Addon::get('d2u_immo')->isAvailable()) {
								foreach(d2u_immo_frontend_helper::getBreadcrumbs() as $breadcrumb) {
									echo ' &nbsp;»&nbsp;&nbsp;' . $breadcrumb;
								}
							}
							print '</div>';
							print '</div>';
						}
					}
					if($d2u_helper->hasConfig('subhead_include_articlename') && $d2u_helper->getConfig('subhead_include_articlename') == "true") {
						print '<div class="col-12">';
						print '<h1 class="subhead">'. $current_article->getName() .'</h1>';
						print '</div>';
					}
					else if($d2u_helper->hasConfig("show_breadcrumbs") && $d2u_helper->getConfig("show_breadcrumbs")) {
						// If not title, but breadcrumbs: show empty row
						print '<div class="col-12 abstand hidden-print"></div>';
					}
				?>
			</div>
			<?php
				if($print == "") { // Remove when https://github.com/twbs/bootstrap/issues/22753 is solved
					print '<div class="row">';
					print '<div class="col-12 col-lg-9">';
					print '<div class="row">';
				}
				// Content follows
				print $this->getArticle(1);
				if($print == "") { // Remove when https://github.com/twbs/bootstrap/issues/22753 is solved
					print '</div>';
					print '</div>';
					print '<div class="col-12 col-lg-3 hidden-print">';
					print '<div class="row">';
					print $this->getArticle(2);
					print '</div>';
					print '</div>';
					print '</div>';
				}
			?>
		</article>
		<div class="row">
			<div class="col-12 hidden-print">
				<footer id="footer_template">
					<div class="row">
						<?php
							print '<div class="col-12">';
							$rex_articles = rex_article::getRootArticles(true);
							foreach($rex_articles as $rex_articles) {
								print '<div class="footerbox">';
								print '<a href="'. $rex_articles->getUrl() .'">'. $rex_articles->getName() .'</a>';
								print '</div>';
							}
							print '</div>';
						?>
					</div>
				</footer>
			</div>
			<?php
				print '<div class="col-12 visible-print-block">';
				if($d2u_helper->hasConfig("template_print_footer_pic") || $d2u_helper->getConfig("template_print_footer_pic") != "") {
					print '<img src="'. rex_url::media($d2u_helper->getConfig("template_print_footer_pic")) .'" alt="">';
				}
				print '</div>';
			?>
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