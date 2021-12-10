<?php 
	$d2u_helper = rex_addon::get("d2u_helper");
	
	$slider_pics = preg_grep('/^\s*$/s', explode(",", $d2u_helper->getConfig('template_04_header_slider_pics_clang_'. rex_clang::getCurrentId())), PREG_GREP_INVERT);
	if(count($slider_pics) > 0) {
?>
<header>
	<?php 
		if($d2u_helper->getConfig("template_04_header_slider_pics_full_width", FALSE) == FALSE) {
			// START Only if slider background slider is shown
	?>
	<div id="background">
		<?php
			if(count($slider_pics) == 1) {
				print '<img src="'. rex_url::media($slider_pics[0]) .'" alt="" id="background-single-image">';
			}
			else {
				// Slider
				print '<div id="headerCarouselbg" class="carousel carousel-fade slide carousel-sync" data-pause="false">';

				// Wrapper for slides
				print '<div class="carousel-inner">';
				for($i = 0; $i < count($slider_pics); $i++) {
					$rex_media_slider_pic = rex_media::get($slider_pics[$i]);
					if($rex_media_slider_pic instanceof rex_media) {
						print '<div class="carousel-item'. ($i == 0 ? ' active' : '') .'">';
						print '<img class="d-block w-100" src="'. ($d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_url::media($slider_pics[$i]) : rex_media_manager::getUrl($d2u_helper->getConfig('template_header_media_manager_type', ''), $slider_pics[$i]))
							.'" alt="'. $rex_media_slider_pic->getTitle() .'"'. ($i > 0 ? ' loading="lazy"' : '') .'>';
						print '</div>';
					}
				}
				print '</div>';
				print '</div>';
			}
		?>			
	</div>
	<div class="container">
		<div class="row d-print-none">
			<div class="col-12">
				<?php
		}  // END Only if slider background slider is shown
					if(count($slider_pics) == 1) {
						print '<img src="'. rex_url::media($slider_pics[0]) .'" alt="" style="max-width:100%;">';
					}
					else {
						// Slider
						print '<div id="headerCarousel" class="carousel carousel-fade slide carousel-sync" data-ride="carousel" data-pause="false">';

						// Slider indicators
						print '<ol class="carousel-indicators">';
						for($j = 0; $j < count($slider_pics); $j++) {
							print '<li data-target="#headerCarousel" data-slide-to="'. $j .'"'. ($j == 0 ? ' class="active"' : '') .'></li>';
						}
						print '</ol>';

						// Wrapper for slides
						print '<div class="carousel-inner">';
						for($k = 0; $k < count($slider_pics); $k++) {
							$slider_pic = rex_media::get($slider_pics[$k]);
							if($slider_pic instanceof rex_media) {
								print '<div class="carousel-item'. ($k == 0 ? ' active' : '') .'">';
								// Image
								$ratio = $slider_pic->getWidth() / $slider_pic->getHeight();
								$ratio_min_style = $d2u_helper->getConfig('template_header_media_manager_type', '') ? '' : ' style="min-height: 250px; min-width:'. round(250 * $ratio).'px;"';
								print '<img class="d-block w-100" src="'. ($d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_url::media($slider_pics[$k]) : rex_media_manager::getUrl($d2u_helper->getConfig('template_header_media_manager_type', ''), $slider_pics[$k]))
									.'" alt="'. $slider_pic->getTitle() .'"'. $ratio_min_style . ($k > 0 ? ' loading="lazy"' : '') .'>';
								// Slogan
								$article = rex_article::getCurrent();
								$slogan_text = $article->getValue('art_slogan') != "" ? $article->getValue('art_slogan') : $d2u_helper->getConfig('template_04_1_slider_slogan_clang_'. rex_clang::getCurrentId());
								$slogan = '<span class="slogan-text-row">'. str_replace('<br>', '</span><span class="slogan-text-row">', nl2br($slogan_text, FALSE)) .'</span>';
								if($slogan_text != "") {
									print '<div class="slogan"><div class="container"><span class="slogan-text">'. $slogan .'</span></div></div>';
								}
								print '</div>';
							}
						}
						print '</div>';

						// Left and right controls
						if($d2u_helper->getConfig("template_04_header_slider_pics_full_width", FALSE)) {
							print '<a class="carousel-control-prev" href="#headerCarousel" role="button" data-slide="prev">';
							print '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
							print '<span class="sr-only">Previous</span>';
							print '</a>';
							print '<a class="carousel-control-next" href="#headerCarousel" role="button" data-slide="next">';
							print '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
							print '<span class="sr-only">Next</span>';
							print '</a>';
						}
						print '</div>';
					}
		if($d2u_helper->getConfig("template_04_header_slider_pics_full_width", FALSE) == FALSE) {
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