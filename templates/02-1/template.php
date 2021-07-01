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
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		print d2u_addon_frontend_helper::getMetaTags();
	?>
	<link rel="stylesheet" href="/index.php?template_id=02-1&amp;d2u_helper=template.css">
	<?php
		if(file_exists(rex_path::media('favicon.ico'))) {
			print '<link rel="icon" href="'. rex_url::media('favicon.ico') .'">';
		}
		if(rex_addon::get('consent_manager')->isAvailable()) {
			print 'REX_CONSENT_MANAGER[]';
		}
	?>
</head>

<body>
	<header>
		<div class="container">
			<?php
				if($d2u_helper->hasConfig("template_logo") && $d2u_helper->getConfig("template_logo") != "") {
			?>
			<div class="row abstand" id="headerdiv">
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
			<?php
				}
				$fragment = new rex_fragment();
				// Navi if above header picture
				if($d2u_helper->isAvailable() && $d2u_helper->getConfig('template_navi_pos', 'bottom') == 'top') {
					echo $fragment->parse('d2u_template_nav.php');
				}
			?>
			<div class="row">
				<div class="col-12">
					<?php
						if($d2u_helper->hasConfig("template_header_pic") && $d2u_helper->getConfig("template_header_pic") != "") {
							$header_image = $d2u_helper->getConfig("template_header_pic");
							if($this->hasValue("art_file") && $this->getValue("art_file") != "") {
								$header_image = $this->getValue("art_file");
							}
							$media_header_pic = rex_media::get($header_image);
							if($media_header_pic instanceof rex_media) {
								print '<img src="'. rex_url::media($header_image) .'" alt="'. $media_header_pic->getTitle() .'" title="'. $media_header_pic->getTitle() .'" id="header" width="1200px">';
							}
						}
					?>
				</div>
			</div>
			<?php
				// Navi if below header picture
				if($d2u_helper->isAvailable() && $d2u_helper->getConfig('template_navi_pos', 'bottom') == 'bottom') {
					echo $fragment->parse('d2u_template_nav.php');
				}
			?>
		</div>
	</header>
	<article>
		<div class="container">
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
						print '<h1 class="subhead">'. $current_article->getName() .'</h1>';
						print '</div>';
					}
					else if($d2u_helper->hasConfig("show_breadcrumbs") && $d2u_helper->getConfig("show_breadcrumbs")) {
						// If not title, but breadcrumbs: dhow empty row
						print '<div class="col-12 abstand"></div>';
					}
					// Content follows
					print $this->getArticle();
				?>
			</div>
		</div>
	</article>
	<div class="container">
		<div class="row abstand">
			<div class="col-12">
				<footer>
					<?php
						echo $fragment->parse('d2u_template_footer.php');
					?>
				</footer>
			</div>
		</div>
	</div>
	<?php
		echo $fragment->parse('d2u_template_cta_box.php');
	?>
</body>
</html>