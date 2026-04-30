<?php
$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 8 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' me-lg-auto ms-lg-auto ' : ''; /** @phpstan-ignore-line */

// Number pics per row
$number_pics = (int) 'REX_VALUE[1]';
$pics_cols = 'col-6';
if (1 === $number_pics) { /** @phpstan-ignore-line */
    $pics_cols = 'col-12';
} elseif (2 === $number_pics) { /** @phpstan-ignore-line */
    $pics_cols .= ' col-sm-6';
} elseif (3 === $number_pics) { /** @phpstan-ignore-line */
    $pics_cols .= ' col-sm-4';
} elseif (4 === $number_pics) { /** @phpstan-ignore-line */
    $pics_cols .= ' col-sm-4 col-lg-3';
} elseif (6 === $number_pics) { /** @phpstan-ignore-line */
    $pics_cols .= ' col-sm-3 col-md-4 col-lg-2';
} else {
    // Backward compatibility
    if (12 === $cols_sm) { /** @phpstan-ignore-line */
        $pics_cols .= ' col-sm-4';
    }

    if (12 === $cols_md) { /** @phpstan-ignore-line */
        $pics_cols .= ' col-md-3';
    } elseif (8 === $cols_md) { /** @phpstan-ignore-line */
        $pics_cols .= ' col-md-4';
    }

    if ($cols_lg >= 10) { /** @phpstan-ignore-line */
        $pics_cols .= ' col-lg-2';
    } elseif (8 === $cols_lg) { /** @phpstan-ignore-line */
        $pics_cols .= ' col-lg-3';
    } elseif (6 === $cols_lg) { /** @phpstan-ignore-line */
        $pics_cols .= ' col-lg-4';
    }
}

$type_thumb = 'd2u_helper_gallery_thumb';
$type_detail = 'd2u_helper_gallery_detail';
$pics_unfiltered = preg_grep('/^\s*$/s', explode(',', 'REX_MEDIALIST[1]'), PREG_GREP_INVERT);
$pics = is_array($pics_unfiltered) ? $pics_unfiltered : [];

$lightbox_id = random_int(0, getrandmax());

?>
<div class="col-12 col-sm-<?= $cols_sm ?> col-md-<?= $cols_md ?> col-lg-<?= $cols_lg . $offset_lg ?>">
	<div class="row">
		<?php
            foreach ($pics as $pic) {
                $pic = (string) $pic;
                $media = rex_media::get($pic);
                if ($media instanceof rex_media) {
                    $title = (string) $media->getValue('title');
                    $detailUrl = 'index.php?rex_media_type='. $type_detail .'&rex_media_file='. $pic;
                    $thumbUrl = 1 === $number_pics ? rex_url::media($pic) : 'index.php?rex_media_type='. $type_thumb .'&rex_media_file='. $pic; /** @phpstan-ignore-line */
                    $galleryName = 'gallery-'. (int) $lightbox_id;
                    echo '<a href="'. rex_escape($detailUrl, 'html_attr') .'" data-d2u-gallery="'. rex_escape($galleryName, 'html_attr') .'" class="'. $pics_cols .'"'
                        .' data-title="'. rex_escape($title, 'html_attr') .'" onclick="event.preventDefault(); d2uLightboxOpen(\''. rex_escape($galleryName, 'js') .'\', this);">';
                    echo '<img src="'. rex_escape($thumbUrl, 'html_attr') .'" class="img-fluid gallery-pic-box"'
                        .' alt="'. rex_escape($title, 'html_attr') .'" title="'. rex_escape($title, 'html_attr') .'" loading="lazy">';
                    echo '</a>';
                }
            }
        ?>
	</div>
</div>
