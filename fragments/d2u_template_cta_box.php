<?php
$show_cta_box = (bool) rex_config::get('d2u_helper', 'show_cta_box', false);

if ($show_cta_box) {
    $d2u_helper = rex_addon::get('d2u_helper');

?>
<div id="cta_icon_box">
	<div id="cta_icon_box_content">
		<ul>
		<?php
            echo '<li><span class="cta_box_toggler fa-icon fa-left footer-icon"></span></li>';
            if ('' !== (string) $d2u_helper->getConfig('footer_text_phone', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-phone footer-icon"></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_mobile', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-mobile footer-icon"></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_fax', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-fax footer-icon"></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('cta_box_article_ids', '')) {
                foreach (explode(',', (string) $d2u_helper->getConfig('cta_box_article_ids', '')) as $article_id) {
                    $article = rex_article::get((int) $article_id);
                    if ($article instanceof rex_article) {
                        echo '<li><span class="cta_box_toggler fa-icon fa-link footer-icon"></span></li>';
                    }
                }
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-facebook footer-icon"></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_email', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-envelope footer-icon"></span></li>';
            }
        ?>
		</ul>
	</div>
</div>
<div id="cta_box">
	<div id="cta_box_content">
		<ul>
		<?php
            echo '<li><span class="cta_box_toggler fa-icon fa-right footer-icon"></span><span class="cta_box_content">'. \Sprog\Wildcard::get('d2u_helper_template_cta_box') .'</span></li>';
            if ('' !== (string) $d2u_helper->getConfig('footer_text_phone', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-phone footer-icon"></span><span class="cta_box_content"><a href="tel:'. $d2u_helper->getConfig('footer_text_phone') .'">'. $d2u_helper->getConfig('footer_text_phone') .'</a></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_mobile', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-mobile footer-icon"></span><span class="cta_box_content"><a href="tel:'. $d2u_helper->getConfig('footer_text_mobile') .'">'. $d2u_helper->getConfig('footer_text_mobile') .'</a></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_fax', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-fax footer-icon"></span><span class="cta_box_content">'. $d2u_helper->getConfig('footer_text_fax') .'</span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('cta_box_article_ids', '')) {
                foreach (explode(',', (string) $d2u_helper->getConfig('cta_box_article_ids', '')) as $article_id) {
                    $article = rex_article::get((int) $article_id);
                    if ($article instanceof rex_article) {
                        echo '<li><span class="cta_box_toggler fa-icon fa-link footer-icon"></span><span class="cta_box_content"><a href="'. rex_getUrl($article_id) .'">'. $article->getName() .'</a></span></li>';
                    }
                }
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_facebook_link', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-facebook footer-icon"></span><span class="cta_box_content"><a href="'. $d2u_helper->getConfig('footer_facebook_link') .'" target="_blank">Facebook</a></span></li>';
            }
            if ('' !== (string) $d2u_helper->getConfig('footer_text_email', '')) {
                echo '<li><span class="cta_box_toggler fa-icon fa-envelope footer-icon"></span>'
                    . '<span class="cta_box_content"><a href="mailto:'. $d2u_helper->getConfig('footer_text_email') .'">'. $d2u_helper->getConfig('footer_text_email') .'</a></span></li>';
            }
        ?>
		</ul>
	</div>
</div>

<script>
	// On change
	$('.cta_box_toggler').on('click', function() {
		if($('#cta_box_content').is(':hidden')) {
			$('#cta_box_content').animate( { width: 'toggle' }, 1000);
		}
		else {
			$('#cta_box_content').animate( { width: 'toggle' }, 1000);
		}
	});
</script>
<?php
}
