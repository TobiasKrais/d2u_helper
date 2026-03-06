<?php
    $d2u_helper = rex_addon::get('d2u_helper');
    $article = rex_article::getCurrent();

    $slider_pics_unfiltered = preg_grep('/^\s*$/s', explode(',', (string) $d2u_helper->getConfig('template_04_header_slider_pics_clang_'. rex_clang::getCurrentId())), PREG_GREP_INVERT);
    // get slider pics in case art_slider_pics is set
    $art_slider_pics_unfiltered = preg_grep('/^\s*$/s', explode(',', (string) $article->getValue('art_slider_pics')), PREG_GREP_INVERT);
    if (count($art_slider_pics_unfiltered) > 0) {
        $slider_pics_unfiltered = $art_slider_pics_unfiltered;
    }

    $slider_pics = is_array($slider_pics_unfiltered) ? $slider_pics_unfiltered : [];
    if (count($slider_pics) > 0) {
?>
<header>
	<?php
        $d2u_helper = rex_addon::get('d2u_helper');
        $media_manager_type = (string) $d2u_helper->getConfig('template_header_media_manager_type', '');

        if (false === $d2u_helper->getConfig('template_04_header_slider_pics_full_width', false)) {
            // START Only if slider background slider is shown
    ?>
	<div id="background">
		<?php
            if (1 === count($slider_pics)) {
                $responsive = TobiasKrais\D2UHelper\FrontendHelper::getResponsiveImageAttributes($media_manager_type, $slider_pics[0]);
                echo '<img src="'. $responsive['src'] .'"'. $responsive['srcset_attr'] . $responsive['sizes_attr'] .' alt="" id="background-single-image">';
            } else {
                // Slider
                echo '<div id="headerCarouselbg" class="carousel carousel-fade slide carousel-sync" data-pause="false">';

                // Wrapper for slides
                echo '<div class="carousel-inner">';
                for ($i = 0; $i < count($slider_pics); ++$i) {
                    $rex_media_slider_pic = rex_media::get($slider_pics[$i]);
                    if ($rex_media_slider_pic instanceof rex_media) {
                        echo '<div class="carousel-item'. (0 === $i ? ' active' : '') .'">';
                        $responsive = TobiasKrais\D2UHelper\FrontendHelper::getResponsiveImageAttributes($media_manager_type, $slider_pics[$i]);
                        echo '<img class="d-block w-100" src="'. $responsive['src'] .'"'. $responsive['srcset_attr'] . $responsive['sizes_attr']
                            .' alt="'. $rex_media_slider_pic->getTitle() .'"'. ($i > 0 ? ' loading="lazy"' : '') .'>';
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
                        $responsive = TobiasKrais\D2UHelper\FrontendHelper::getResponsiveImageAttributes($media_manager_type, $slider_pics[0]);
                        echo '<img src="'. $responsive['src'] .'"'. $responsive['srcset_attr'] . $responsive['sizes_attr']
                            .' alt="" class="header-slider-pic'. ((bool) rex_config::get('d2u_helper', 'template_04_header_slider_pics_full_width') ? ' header-slider-pic-full-width' : '') .'">';
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
                                $responsive = TobiasKrais\D2UHelper\FrontendHelper::getResponsiveImageAttributes($media_manager_type, $slider_pics[$k]);
                                echo '<img class="d-block w-100" src="'. $responsive['src'] .'"'. $responsive['srcset_attr'] . $responsive['sizes_attr']
                                    .' alt="'. $rex_media_slider_pic->getTitle() .'"'. $ratio_min_style . ($k > 0 ? ' loading="lazy"' : '') .' width="'. $slider_pic->getWidth() .'px"  height="'. $slider_pic->getHeight() .'px">';
                                // Slogan
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
