<?php
    $d2u_helper = rex_addon::get('d2u_helper');
?>
<!DOCTYPE html>
<html lang="<?= rex_clang::getCurrent()->getCode() ?>">
<head>
	<?php
        $fragment = new rex_fragment();
        // <head></head>
        echo $fragment->parse('d2u_template_head.php');
    ?>
	<link rel="stylesheet" href="/index.php?template_id=05-1&amp;d2u_helper=template.css">
</head>

<body>
	<?php
        $show_screen_size = 'screen == "lg" ||  screen == "xl"';
        $menu_icon_min_width = '';
        if ($d2u_helper->isAvailable()) {
            d2u_mobile_navi_slicknav::getMobileMenu();

            $include_menu_show = $d2u_helper->getConfig('include_menu_show', 'md');
            if ('xs' == $include_menu_show) {
                $show_screen_size = 'screen == "sm" || screen == "md" || screen == "lg" ||  screen == "xl"';
                $menu_icon_min_width = '576px';
            } elseif ('sm' == $include_menu_show) {
                $show_screen_size = 'screen == "md" || screen == "lg" ||  screen == "xl"';
                $menu_icon_min_width = '768px';
            } elseif ('md' == $include_menu_show) {
                $show_screen_size = 'screen == "lg" ||  screen == "xl"';
                $menu_icon_min_width = '992px';
            } elseif ('lg' == $include_menu_show) {
                $show_screen_size = 'screen == "xl"';
                $menu_icon_min_width = '1200px';
            } else {
                $show_screen_size = 'false';
            }
        }

        if ('' != $menu_icon_min_width) {
    ?>
  	<style>
  		@media screen and (min-width: <?= $menu_icon_min_width ?>) {
			#navi_desktop .slicknav_btn {
				display: none !important;
			}
		}
  	</style>
  	<?php
        }
    ?>
	<div id="device-size-detector">
		<div id="xs" class="d-block d-sm-none"></div>
		<div id="sm" class="d-none d-sm-block d-md-none"></div>
		<div id="md" class="d-none d-md-block d-lg-none"></div>
		<div id="lg" class="d-none d-lg-block d-xl-none"></div>
		<div id="xl" class="d-none d-xl-block"></div>
	</div>
	<header>
		<div class="container">
			<div class="row">
				<div class="col-6 col-xl-2">
					<div id="logo-left" align="right">
						<?php
                            if ('' != $d2u_helper->getConfig('template_logo', '')) {
                                echo '<a href="'. (\rex_addon::get('yrewrite')->isAvailable() ? \rex_yrewrite::getCurrentDomain()->getUrl() : \rex::getServer()) .'">';
                                $media_logo = rex_media::get($d2u_helper->getConfig('template_logo'));
                                if ($media_logo instanceof rex_media) {
                                    echo '<img src="'. rex_url::media($d2u_helper->getConfig('template_logo')) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo">';
                                }
                                echo '</a>';
                            }
                        ?>
					</div>
				</div>
				<div class="col-6 d-block d-xl-none">
					<div id="logo-right">
						<?php
                            if ('' != $d2u_helper->getConfig('template_logo_2', '')) {
                                if ('' != $d2u_helper->getConfig('template_logo_2_link', '')) {
                                    echo '<a href="'. $d2u_helper->getConfig('template_logo_2_link', '') .'">';
                                }
                                $media_logo = rex_media::get($d2u_helper->getConfig('template_logo_2'));
                                if ($media_logo instanceof rex_media) {
                                    echo '<img src="'. rex_url::media($d2u_helper->getConfig('template_logo_2')) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo">';
                                }
                                if ('' != $d2u_helper->getConfig('template_logo_2_link', '')) {
                                    echo '</a>';
                                }
                            }
                        ?>
					</div>
				</div>
				<?php
                    $header_pic_style = '';
                    // Vorschaubild berechnen
                    $header_image = rex_config::get('d2u_helper', 'template_header_pic', '');
                    if ($this->hasValue('art_file') && '' != $this->getValue('art_file')) {
                        $header_image = $this->getValue('art_file');
                    }
                    $titelbild = rex_media::get($header_image);
                    if ($titelbild instanceof rex_media) {
                        $header_pic_style = 'background: url('.
                                ($d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl($d2u_helper->getConfig('template_header_media_manager_type', ''), $header_image) : rex_url::media($header_image)) .') center center; background-size: cover;';
                    }
                ?>
				<div class="col-12 col-xl-8" style="<?= $header_pic_style ?>">
				</div>
				<div class="d-none d-xl-block col-xl-2">
					<div id="logo-right">
						<?php
                            if ('' != $d2u_helper->getConfig('template_logo_2', '')) {
                                if ('' != $d2u_helper->getConfig('template_logo_2_link', '')) {
                                    echo '<a href="'. $d2u_helper->getConfig('template_logo_2_link', '') .'">';
                                }
                                $media_logo = rex_media::get($d2u_helper->getConfig('template_logo_2'));
                                if ($media_logo instanceof rex_media) {
                                    echo '<img src="'. rex_url::media($d2u_helper->getConfig('template_logo_2')) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo">';
                                }
                                if ('' != $d2u_helper->getConfig('template_logo_2_link', '')) {
                                    echo '</a>';
                                }
                            }
                        ?>
					</div>
				</div>
			</div>
		</div>
	</header>
	<article>
		<div class="container">
			<div class="row">
				<div class="col-6 col-xl-2" id="navi-inner-frame">
					<?php
                        // Languages
                        echo '<div id="lang_chooser_div">';
                        echo $fragment->parse('d2u_template_language_icon.php');
                        echo $fragment->parse('d2u_template_language_modal.php');
                        echo '</div>';
                        // Search icon
                        if (rex_addon::get('search_it')->isAvailable() && rex_config::get('d2u_helper', 'article_id_search', 0) > 0) {
                            echo '<div id="search_icon_div">';
                            echo $fragment->parse('d2u_template_search_icon.php');
                            echo '</div>';
                        }
                    ?>
					<navi>
						<div id="navi_desktop"></div>
						<script>
							$(document).ready(function() {
								$('#slicknav-mobile-menu').slicknav({
									label: 'MENÜ',
									prependTo: '#navi_desktop'
								});

								function getBootstrapDeviceSize() {
									return $('#device-size-detector').find('div:visible').first().attr('id');
								}

								function checkMenu(){
									var screen = getBootstrapDeviceSize();
									if(<?= $show_screen_size ?>) {
										$('#slicknav-mobile-menu').slicknav('open');
									} else {
										$('#slicknav-mobile-menu').slicknav('close');
									}
								}
								checkMenu();
								$(window).on('resize', checkMenu);
							});
						</script>
					</navi>
				</div>
				<div class="col-12 col-xl-8" id="article-content">
					<div class="row">
						<?php
                            // Breadcrumbs
                            if ('true' == $d2u_helper->getConfig('show_breadcrumbs', 'false')) {
                                echo '<div class="col-12 d-print-none" id="breadcrumbs">';
                                echo d2u_addon_frontend_helper::getBreadcrumbs();
                                echo '</div>';
                            }

                            // Content follows
                            echo $this->getArticle();

                            // Footer
                            echo '<div class="col-12" id="footer">';
                            echo $fragment->parse('d2u_template_footer.php');
                            echo '</div>';
                        ?>
					</div>
				</div>
				<div class="d-none d-xl-block col-xl-2" id="teaser-inner-frame">
					<?php
                        if ('' !== $d2u_helper->getConfig('template_05_1_info_text', '')) {
                            echo $d2u_helper->getConfig('template_05_1_info_text');
                        }
                    ?>
				</div>
			</div>
		</div>
	</article>
	<?= $fragment->parse('d2u_template_cta_box.php');
    ?>
</body>
</html>