<?php
    $d2u_helper = rex_addon::get('d2u_helper');

    $slider_pics_unfiltered = preg_grep('/^\s*$/s', explode(',', (string) $d2u_helper->getConfig('template_04_header_slider_pics_clang_'. rex_clang::getCurrentId())), PREG_GREP_INVERT);
    $slider_pics = is_array($slider_pics_unfiltered) ? $slider_pics_unfiltered : [];
    if (count($slider_pics) > 0) {
?>
<header>
	<?php
        $d2u_helper = rex_addon::get('d2u_helper');

        $media_manager_webp_exists = false;
        $media_manager_webp_type_name = '';
        if ('' !== $d2u_helper->getConfig('template_header_media_manager_type')) {
            $sql = rex_sql::factory();
            $sql->setQuery('SELECT * FROM '. rex::getTablePrefix() .'media_manager_type WHERE name = "'. $d2u_helper->getConfig('template_header_media_manager_type', '') .'_webp"');
            if ($sql->getRows() > 0) {
                $media_manager_webp_exists = true;
                $media_manager_webp_type_name = $d2u_helper->getConfig('template_header_media_manager_type') .'_webp';
            }
        }

        if (false === $d2u_helper->getConfig('template_04_header_slider_pics_full_width', false)) {
            // START Only if slider background slider is shown
    ?>
	<div id="background">
		<?php
            if (1 === count($slider_pics)) {
                $srcset = $media_manager_webp_exists ? ' srcset="'. rex_media_manager::getUrl($media_manager_webp_type_name, $slider_pics[0]) .' 2000w"' : '';
                echo '<img'. $srcset .' src="'. rex_url::media($slider_pics[0]) .'" alt="" id="background-single-image">';
            } else {
                // Slider
                echo '<div id="headerCarouselbg" class="carousel carousel-fade slide carousel-sync" data-pause="false">';

                // Wrapper for slides
                echo '<div class="carousel-inner">';
                for ($i = 0; $i < count($slider_pics); ++$i) {
                    $rex_media_slider_pic = rex_media::get($slider_pics[$i]);
                    if ($rex_media_slider_pic instanceof rex_media) {
                        echo '<div class="carousel-item'. (0 === $i ? ' active' : '') .'">';
                        $srcset = $media_manager_webp_exists ? ' srcset="'. rex_media_manager::getUrl($media_manager_webp_type_name, $slider_pics[$i]) .' 2000w"' : '';
                        echo '<img class="d-block w-100"'. $srcset .' src="'. ('' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) $d2u_helper->getConfig('template_header_media_manager_type', ''), $slider_pics[$i]) : rex_url::media($slider_pics[$i]))
                            .'" alt="'. $rex_media_slider_pic->getTitle() .'"'. ($i > 0 ? ' loading="lazy"' : '') .'>';
                        echo '</div>';
                    }
                }
                echo '</div>';
                echo '</div>';
            }
        ?>
	</div>
	<div class="container">
		<div class="row d-print-none">
			<div class="col-12">
				<?php
        }  // END Only if slider background slider is shown
                    if (1 === count($slider_pics)) {
                        $srcset = $media_manager_webp_exists ? ' srcset="'. rex_media_manager::getUrl($media_manager_webp_type_name, $slider_pics[0]) .' 2000w"' : '';
                        echo '<img'. $srcset .' src="'. ('' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) $d2u_helper->getConfig('template_header_media_manager_type', ''), $slider_pics[0]) : rex_url::media($slider_pics[0]))
                            .'" alt="" style="max-width:100%;">';
                    } else {
                        // Slider
                        echo '<div id="headerCarousel" class="carousel carousel-fade slide carousel-sync" data-ride="carousel" data-pause="false">';

                        // Slider indicators
                        echo '<ol class="carousel-indicators">';
                        for ($j = 0; $j < count($slider_pics); ++$j) {
                            echo '<li data-target="#headerCarousel" data-slide-to="'. $j .'"'. (0 === $j ? ' class="active"' : '') .'></li>';
                        }
                        echo '</ol>';

                        // Wrapper for slides
                        echo '<div class="carousel-inner">';
                        for ($k = 0; $k < count($slider_pics); ++$k) {
                            $rex_media_slider_pic = rex_media::get($slider_pics[$k]);
                            $slider_pic = '' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::create((string) $d2u_helper->getConfig('template_header_media_manager_type'), $slider_pics[$k])->getMedia() : rex_media::get($slider_pics[$k]);
                            if ($rex_media_slider_pic instanceof rex_media && ($slider_pic instanceof rex_media || $slider_pic instanceof rex_managed_media)) {
                                echo '<div class="carousel-item'. (0 === $k ? ' active' : '') .'">';
                                // Image
                                $ratio = (int) $slider_pic->getWidth() / (int) $slider_pic->getHeight();
                                $min_height = (int) $slider_pic->getHeight() < 250 ? (int) ($slider_pic->getHeight()) : 250;
                                $ratio_min_style = ' style="min-height: '. $min_height .'px; min-width:'. round($min_height * $ratio).'px;"';
                                $srcset = $media_manager_webp_exists ? ' srcset="'. rex_media_manager::getUrl($media_manager_webp_type_name, $slider_pics[$k]) .' 2000w"' : '';
                                echo '<img class="d-block w-100"'. $srcset .'  src="'. ('' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) $d2u_helper->getConfig('template_header_media_manager_type'), $slider_pics[$k]) : rex_url::media($slider_pics[$k]))
                                    .'" alt="'. $rex_media_slider_pic->getTitle() .'"'. $ratio_min_style . ($k > 0 ? ' loading="lazy"' : '') .' width="'. $slider_pic->getWidth() .'px"  height="'. $slider_pic->getHeight() .'px">';
                                // Slogan
                                $article = rex_article::getCurrent();
                                $slogan_text = (string) ($article instanceof rex_article && '' !== $article->getValue('art_slogan') ? $article->getValue('art_slogan') : $d2u_helper->getConfig('template_04_1_slider_slogan_clang_'. rex_clang::getCurrentId()));
                                $slogan = '<span class="slogan-text-row">'. str_replace('<br>', '</span><span class="slogan-text-row">', nl2br($slogan_text, false)) .'</span>';
                                if ('' !== $slogan_text && 'slider' === $d2u_helper->getConfig('template_slogan_position', 'slider')) {
                                    echo '<div class="slogan"><div class="container"><span class="slogan-text">'. $slogan .'</span></div></div>';
                                }
                                echo '</div>';
                            }
                        }
                        echo '</div>';

                        // Left and right controls
                        if ((bool) $d2u_helper->getConfig('template_04_header_slider_pics_full_width', false)) {
                            echo '<a class="carousel-control-prev" href="#headerCarousel" role="button" data-slide="prev">';
                            echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                            echo '<span class="sr-only">Previous</span>';
                            echo '</a>';
                            echo '<a class="carousel-control-next" href="#headerCarousel" role="button" data-slide="next">';
                            echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                            echo '<span class="sr-only">Next</span>';
                            echo '</a>';
                        }
                        echo '</div>';
                    }
        if (false === (bool) $d2u_helper->getConfig('template_04_header_slider_pics_full_width', false)) {
            // START Only if slider background slider is shown
                ?>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function () {
			// Sync sliders
			jQuery('#headerCarousel').on('slide.bs.carousel', function() {
				jQuery('#headerCarouselbg').carousel('next');
			});

			// Sync events
			$('.carousel-sync').on('slide.bs.carousel', function(ev) {
				// get the direction, based on the event which occurs
				var dir = ev.direction === 'right' ? 'prev' : 'next';
				// get synchronized non-sliding carousels, and make'em sliding
				$('.carousel-sync').not('.sliding').addClass('sliding').carousel(dir);
			});
			$('.carousel-sync').on('slid.bs.carousel', function(ev) {
				// remove .sliding class, to allow the next move
				$('.carousel-sync').removeClass('sliding');
			});
		});
	</script>
	<?php
        } // END Only if slider background slider is shown
    ?>
</header>
<?php
    }
