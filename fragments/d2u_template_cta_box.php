<?php
$show_cta_box = (bool) rex_config::get('d2u_helper', 'show_cta_box', false);

if ($show_cta_box) {
    $d2u_helper = rex_addon::get('d2u_helper');

?>
<div id="cta_icon_box" class="d-print-none">
	<div id="cta_icon_box_content">
		<ul>
		<?php
            echo '<li><span class="cta_box_toggler fa-icon fa-left footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_consent_manager_text_toggle_details') .'"></span></li>';
            if ('' !== (string) $d2u_helper->getConfig('footer_text_phone', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-phone footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_module_form_phone') .'"></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_mobile', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-mobile-alt footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_module_form_phone') .'"></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_fax', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-fax footer-icon"></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('cta_box_article_ids', '')) {
                foreach (explode(',', (string) $d2u_helper->getConfig('cta_box_article_ids', '')) as $article_id) {
                    $article = rex_article::get((int) $article_id);
                    if ($article instanceof rex_article) {
                        echo '<li><span class="cta_box_toggler fa-icon fa-link footer-icon" title="'. $article->getName() .'"></span></li>';
                    }
                }
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-facebook footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_social_facebook') .'"></span></li>';
            }
            // Google link
            if ('' !== (string) $d2u_helper->getConfig('footer_google_link', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-google footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_social_google') .'"></span></li>';
            }
            // Instagram link
            if ('' !== (string) $d2u_helper->getConfig('footer_instagram_link', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-instagram footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_social_instagram') .'"></span></li>';
            }
            // LinkedIn link
            if ('' !== (string) $d2u_helper->getConfig('footer_linkedin_link', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-linkedin footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_social_linkedin') .'"></span></li>';
            }
            // Youtube link
            if ('' !== (string) $d2u_helper->getConfig('footer_youtube_link', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-youtube footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_social_youtube') .'"></span></li>';
            }
            // Email address
            if ('' !== (string) $d2u_helper->getConfig('footer_text_email', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-envelope footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_module_form_email') .'"></span></li>';
            }
        ?>
		</ul>
	</div>
</div>
<div id="cta_box" class="d-print-none">
	<div id="cta_box_content">
		<ul>
		<?php
            echo '<li><span class="cta_box_toggler fa-icon fa-right footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_consent_manager_text_toggle_details') .'"></span><span class="cta_box_content">'. \Sprog\Wildcard::get('d2u_helper_template_cta_box') .'</span></li>';
            if ('' !== (string) $d2u_helper->getConfig('footer_text_phone', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-phone footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_module_form_phone').'"></span><span class="cta_box_content"><a href="tel:'. $d2u_helper->getConfig('footer_text_phone') .'">'. $d2u_helper->getConfig('footer_text_phone') .'</a></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_mobile', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-mobile-alt footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_module_form_phone').'"></span><span class="cta_box_content"><a href="tel:'. $d2u_helper->getConfig('footer_text_mobile') .'">'. $d2u_helper->getConfig('footer_text_mobile') .'</a></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_fax', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-fax footer-icon"></span><span class="cta_box_content">'. $d2u_helper->getConfig('footer_text_fax') .'</span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('cta_box_article_ids', '')) {
                foreach (explode(',', (string) $d2u_helper->getConfig('cta_box_article_ids', '')) as $article_id) {
                    $article = rex_article::get((int) $article_id);
                    if ($article instanceof rex_article) {
                        echo '<li><span class="cta_box_toggler fa-icon fa-link footer-icon" title="'. $article->getName() .'"></span><span class="cta_box_content"><a href="'. rex_getUrl($article_id) .'">'. $article->getName() .'</a></span></li>';
                    }
                }
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-facebook footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_social_facebook') .'"></span><span class="cta_box_content"><a href="'. $d2u_helper->getConfig('footer_facebook_link') .'" target="_blank">'. Sprog\Wildcard::get('d2u_helper_social_facebook') .'</a></span></li>';
            }
            // Google link
            if ('' !== (string) $d2u_helper->getConfig('footer_google_link', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-google footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_social_google') .'"></span><span class="cta_box_content"><a href="'. $d2u_helper->getConfig('footer_google_link') .'" target="_blank">'. Sprog\Wildcard::get('d2u_helper_social_google') .'</a></span></li>';
            }
            // Instagram link
            if ('' !== (string) $d2u_helper->getConfig('footer_instagram_link', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-instagram footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_social_instagram') .'"></span><span class="cta_box_content"><a href="'. $d2u_helper->getConfig('footer_instagram_link') .'" target="_blank">'. Sprog\Wildcard::get('d2u_helper_social_instagram') .'</a></span></li>';
            }
            // LinkedIn link
            if ('' !== (string) $d2u_helper->getConfig('footer_linkedin_link', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-linkedin footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_social_linkedin') .'"></span><span class="cta_box_content"><a href="'. $d2u_helper->getConfig('footer_linkedin_link') .'" target="_blank">'. Sprog\Wildcard::get('d2u_helper_social_linkedin') .'</a></span></li>';
            }
            // Youtube link
            if ('' !== (string) $d2u_helper->getConfig('footer_youtube_link')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-youtube footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_social_youtube') .'"></span><span class="cta_box_content"><a href="'. $d2u_helper->getConfig('footer_youtube_link') .'" target="_blank">'. Sprog\Wildcard::get('d2u_helper_social_youtube') .'</a></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_email', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-envelope footer-icon" title="'. Sprog\Wildcard::get('d2u_helper_module_form_email') .'"></span>'
                    . '<span class="cta_box_content"><a href="mailto:'. $d2u_helper->getConfig('footer_text_email') .'">'. $d2u_helper->getConfig('footer_text_email') .'</a></span></li>';
            }
        ?>
		</ul>
	</div>
</div>

<script>
	// CTA box toggle functionality (works with and without jQuery)
	(function() {
		var togglers = document.querySelectorAll('.cta_box_toggler');
		var ctaBoxContent = document.getElementById('cta_box_content');
		if (!ctaBoxContent || togglers.length === 0) return;

		togglers.forEach(function(toggler) {
			toggler.addEventListener('click', function() {
				if (ctaBoxContent.style.display === 'none' || ctaBoxContent.style.display === '') {
					// Measure target width first with element visible at auto width
					ctaBoxContent.style.overflow = 'hidden';
					ctaBoxContent.style.width = 'auto';
					ctaBoxContent.style.display = 'block';
					var targetWidth = ctaBoxContent.scrollWidth;
					ctaBoxContent.style.width = '0';
					var start = null;
					function expand(timestamp) {
						if (!start) start = timestamp;
						var progress = Math.min((timestamp - start) / 500, 1);
						ctaBoxContent.style.width = (targetWidth * progress) + 'px';
						if (progress < 1) {
							requestAnimationFrame(expand);
						} else {
							ctaBoxContent.style.width = '';
							ctaBoxContent.style.overflow = '';
						}
					}
					requestAnimationFrame(expand);
				} else {
					var currentWidth = ctaBoxContent.offsetWidth;
					ctaBoxContent.style.overflow = 'hidden';
					ctaBoxContent.style.width = currentWidth + 'px';
					var start2 = null;
					function collapse(timestamp) {
						if (!start2) start2 = timestamp;
						var progress = Math.min((timestamp - start2) / 500, 1);
						ctaBoxContent.style.width = (currentWidth * (1 - progress)) + 'px';
						if (progress < 1) {
							requestAnimationFrame(collapse);
						} else {
							ctaBoxContent.style.display = 'none';
							ctaBoxContent.style.width = '';
							ctaBoxContent.style.overflow = '';
						}
					}
					requestAnimationFrame(collapse);
				}
			});
		});
	})();
</script>
<?php
}
