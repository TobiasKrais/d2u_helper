<!DOCTYPE html>

<html lang="<?php echo rex_clang::getCurrent()->getCode(); ?>">
<head>
    <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		print d2u_addon_frontend_helper::getMetaTags();
	?>
	<link rel="stylesheet" href="/index.php?template_id=06-1&amp;d2u_helper=template.css">
	<?php
		if(file_exists(rex_path::media('favicon.ico'))) {
			print '<link rel="icon" href="'. rex_url::media('favicon.ico') .'">';
		}
		if(rex_addon::get('consent_manager')->isAvailable()) {
			print 'REX_CONSENT_MANAGER[]';
		}
	?>
</head>

<body id="body">
	<div class="container">
		<div class="row" id="paper-sheet">
			<div class="col-12">
				<header>
					<div class="row">
						<div class="col-12">
							<?php
								if(rex_config::get("d2u_helper", "template_header_pic", "") != "") {
									$header_image = rex_media::get(rex_config::get("d2u_helper", "template_header_pic"));
									print '<img src="'. ($d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_url::media($header_image) : rex_media_manager::getUrl($d2u_helper->getConfig('template_header_media_manager_type', ''), $header_image)) .'" alt="'. $media_background->getTitle() .'" id="header-image">';
								}
								if(rex_config::get("d2u_helper", "template_logo", "") != "") {
									$media_logo = rex_media::get(rex_config::get("d2u_helper", "template_logo"));
									print '<a href="' . rex_getUrl(rex_article::getSiteStartArticleId()) . '">';
									print '<img src="'. rex_url::media($media_logo->getFileName()) .'" alt="'. $media_logo->getTitle() .'" id="logo-top">';
									print '</a>';
								}
							?>
						</div>
					</div>
				</header>
				<div class="row">
					<div class="col-12 col-lg-3">
						<div class="row">
							<div class="col-12">
								<?php
									// Languages
									$fragment = new rex_fragment();
									if(count(rex_clang::getAllIds(true)) > 1) {
										print '<div id="lang_chooser_div">';
										echo $fragment->parse('d2u_template_language_modal.php');
										print '</div>';
									}
									// Search icon
									if(rex_addon::get('search_it')->isAvailable() && rex_config::get('d2u_helper', 'article_id_search', 0) > 0) {
										print '<div id="search_icon_div">';
										echo $fragment->parse('d2u_template_search_icon.php');
										print '</div>';
									}
								?>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<navi>
									<?php
										if(rex_addon::get('d2u_helper')->isAvailable()) {
											d2u_mobile_navi_smartmenus::getMenu();
										}
									?>
								</navi>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-9">
						<article>
							<div class="row">
								<?php
									// Content follows
									print $this->getArticle();
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
								foreach($rex_articles as $rex_articles) {
									if($show_separator) {
										print "&nbsp;&nbsp;|&nbsp;&nbsp;";
									}
									print '<a href="'. $rex_articles->getUrl() .'">'. $rex_articles->getName() .'</a>';
									$show_separator = true;
								}
							?>
						</div>
						<div class="col-12 col-sm-4 col-lg-2" id="footer-right">
							<?php
								if(rex_config::get("d2u_helper", "template_logo", "") != "") {
									$media_logo = rex_media::get(rex_config::get("d2u_helper", "template_logo"));
									print '<a href="' . rex_getUrl(rex_article::getSiteStartArticleId()) . '">';
									print '<img src="'. rex_url::media($media_logo->getFileName()) .'" alt="'. $media_logo->getTitle() .'" id="logo-footer">';
									print '</a>';
								}
							?>
						</div>
					</div>
				</footer>
			</div>
		</div>
	</div>
	<?php
		echo $fragment->parse('d2u_template_cta_box.php');
	?>
</body>
</html>