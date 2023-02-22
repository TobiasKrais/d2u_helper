<?php
    $cols_sm = 'REX_VALUE[20]';
    if ('' == $cols_sm) {
        $cols_sm = 8;
    }
    $cols_md = 'REX_VALUE[19]';
    if ('' == $cols_md) {
        $cols_md = $cols_sm;
        $cols_sm = 12; // Backward compatibility
    }
    $cols_lg = 'REX_VALUE[18]';
    if ('' == $cols_lg) {
        $cols_lg = $cols_md;
    }
    $offset_lg_cols = (int) 'REX_VALUE[17]';
    $offset_lg = '';
    if ($offset_lg_cols > 0) { /** @phpstan-ignore-line */
        $offset_lg = ' mr-lg-auto ml-lg-auto ';
    }

    // Number pics per row
    $number_pics = 'REX_VALUE[1]';
    $pics_cols = 'col-6';
    if ('1' == $number_pics) {
        $pics_cols = 'col-12';
    } elseif ('2' == $number_pics) {
        $pics_cols .= ' col-sm-6';
    } elseif ('3' == $number_pics) {
        $pics_cols .= ' col-sm-4';
    } elseif ('4' == $number_pics) {
        $pics_cols .= ' col-sm-4 col-lg-3';
    } elseif ('6' == $number_pics) {
        $pics_cols .= ' col-sm-3 col-md-4 col-lg-2';
    } else {
        // Backward compatibility
        if (12 == $cols_sm) {
            $pics_cols .= ' col-sm-4';
        }

        if (12 == $cols_md) {
            $pics_cols .= ' col-md-3';
        } elseif (8 == $cols_md) {
            $pics_cols .= ' col-md-4';
        }

        if ($cols_lg >= 10) {
            $pics_cols .= ' col-lg-2';
        } elseif (8 == $cols_lg) {
            $pics_cols .= ' col-lg-3';
        } elseif (6 == $cols_lg) {
            $pics_cols .= ' col-lg-4';
        }
    }

    $type_thumb = 'd2u_helper_gallery_thumb';
    $type_detail = 'd2u_helper_gallery_detail';
    $pics_unfiltered = preg_grep('/^\s*$/s', explode(',', REX_MEDIALIST[1]), PREG_GREP_INVERT);
    $pics = is_array($pics_unfiltered) ? $pics_unfiltered : [];

    $lightbox_id = random_int(0, getrandmax());

?>
<div class="col-12 col-sm-<?= $cols_sm ?> col-md-<?= $cols_md ?> col-lg-<?= $cols_lg . $offset_lg ?>">
	<div class="row">
		<?php
            foreach ($pics as $pic) {
                $media = rex_media::get($pic);
                if ($media instanceof rex_media) {
                    echo '<a href="index.php?rex_media_type='. $type_detail .'&rex_media_file='. $pic .'" data-toggle="lightbox'. $lightbox_id .'" data-gallery="example-gallery'. $lightbox_id .'" class="'. $pics_cols .'"'
                        .' data-title="'. $media->getValue('title') .'">';
                    echo '<img src="'. (1 == $number_pics ? rex_url::media($pic) : 'index.php?rex_media_type='. $type_thumb .'&rex_media_file='. $pic) .'" class="img-fluid gallery-pic-box"'
                        .' alt="'. $media->getValue('title') .'" title="'. $media->getValue('title') .'" loading="lazy">';
                    echo '</a>';
                }
            }
        ?>
	</div>
</div>
<script>
	$(document).on('click', '[data-toggle="lightbox<?= $lightbox_id ?>"]', function(event) {
		event.preventDefault();
		$(this).ekkoLightbox({
			alwaysShowClose: true
		});
	});
</script>