<!DOCTYPE html>

<html lang="<?= rex_clang::getCurrent()->getCode() ?>">
<head>
	<?php
        $fragment = new rex_fragment();
        // <head></head>
        echo $fragment->parse('d2u_template_head.php');

        echo '<link rel="stylesheet" href="'. rex_url::frontendController(['template_id' => '06-1', 'd2u_helper' => 'template.css']) .'">';
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
                                if ('' !== rex_config::get('d2u_helper', 'template_header_pic', '')) {
                                    $header_image_filename = (string) rex_config::get('d2u_helper', 'template_header_pic');
                                    $header_image = rex_media::get($header_image_filename);
                                    if ($header_image instanceof rex_media) {
                                        echo '<img src="'. ('' !== rex_config::get('d2u_helper', 'template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) rex_config::get('d2u_helper', 'template_header_media_manager_type', ''), $header_image_filename) : rex_url::media($header_image_filename)) .'" alt="'. $header_image->getTitle() .'" id="header-image">';
                                    }
                                }
                                if ('' !== rex_config::get('d2u_helper', 'template_logo', '')) {
                                    $media_logo = rex_media::get((string) rex_config::get('d2u_helper', 'template_logo'));
                                    if ($media_logo instanceof rex_media) {
                                        echo '<a href="' . rex_getUrl(rex_article::getSiteStartArticleId()) . '">';
                                        echo '<img src="'. rex_url::media($media_logo->getFileName()) .'" alt="'. $media_logo->getTitle() .'" id="logo-top">';
                                        echo '</a>';
                                    }
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
                                    if (count(rex_clang::getAllIds(true)) > 1) {
                                        echo '<div id="lang_chooser_div">';
                                        echo $fragment->parse('d2u_template_language_icon.php');
                                        echo $fragment->parse('d2u_template_language_modal.php');
                                        echo '</div>';
                                    }
                                    // Search icon
                                    if (rex_addon::get('search_it')->isAvailable() && rex_config::get('d2u_helper', 'article_id_search', 0) > 0) {
                                        echo '<div id="search_icon_div">';
                                        echo $fragment->parse('d2u_template_search_icon.php');
                                        echo '</div>';
                                    }
                                ?>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<nav>
									<?php
                                        if (rex_addon::get('d2u_helper')->isAvailable()) {
                                            \FriendsOfRedaxo\D2UHelper\FrontendNavigationSmartmenu::getMenu();
                                        }
                                    ?>
								</nav>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-9">
						<article>
							<div class="row">
								<?php
                                    // Content follows
                                    echo $this->getArticle(); /** @phpstan-ignore-line */
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
                                foreach ($rex_articles as $rex_article) {
                                    if ($show_separator) {
                                        echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
                                    }
                                    echo '<a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a>';
                                    $show_separator = true;
                                }
                            ?>
						</div>
						<div class="col-12 col-sm-4 col-lg-2" id="footer-right">
							<?php
                                if ('' !== rex_config::get('d2u_helper', 'template_logo', '')) {
                                    $media_logo = rex_media::get((string) rex_config::get('d2u_helper', 'template_logo'));
                                    if ($media_logo instanceof rex_media) {
                                        echo '<a href="' . rex_getUrl(rex_article::getSiteStartArticleId()) . '">';
                                        echo '<img src="'. rex_url::media($media_logo->getFileName()) .'" alt="'. $media_logo->getTitle() .'" id="logo-footer">';
                                        echo '</a>';
                                    }
                                }
                            ?>
						</div>
					</div>
				</footer>
			</div>
		</div>
	</div>
	<?= $fragment->parse('d2u_template_cta_box.php');
    ?>
</body>
</html>