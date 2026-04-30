<?php
$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 18 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? $cols_md : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' me-lg-auto ms-lg-auto ' : ''; /** @phpstan-ignore-line */

// Number pics per row
$pics_per_line = (int) 'REX_VALUE[4]';
$pics_cols = 'col-6';
if ($pics_per_line > 2) { /** @phpstan-ignore-line */
    $pics_cols .= ' col-sm-4';
}
if ($pics_per_line > 3) { /** @phpstan-ignore-line */
    $pics_cols .= ' col-md-3';
}
if ($pics_per_line > 4) { /** @phpstan-ignore-line */
    $pics_cols .= ' col-lg-2';
}

$link_type = 'REX_VALUE[3]';

if (rex_addon::get('feeds')->isAvailable()) {
    $stream = rex_feeds_stream::get((int) 'REX_VALUE[1]');
    if ($stream instanceof rex_feeds_stream_abstract) {
        $items = $stream->getPreloadedItems(6);
        $lightbox_id = random_int(0, getrandmax());
        $streamTitle = (string) $stream->getTitle();
?>
<div class="col-12 col-sm-<?= $cols_sm ?> col-md-<?= $cols_md ?> col-lg-<?= $cols_lg . $offset_lg ?>">
	<div class="row">
		<?php
            foreach ($items as $item) {
                $link_url = 'feed' === $link_type ? (string) $item->getUrl() : 'index.php?rex_media_type=d2u_helper_yfeed_large&rex_media_file='. (int) $item->getId() .'.feeds'; /** @phpstan-ignore-line */
                $itemTitle = (string) $item->getTitle();
                $itemUrl = (string) $item->getUrl();
                $altTitle = implode(' ', array_slice(explode(' ', $itemTitle), 0, 5));
                $imgSrc = 'index.php?rex_media_type='. ($pics_per_line > 2 ? 'd2u_helper_yfeed_small' : 'd2u_helper_yfeed_large') .'&rex_media_file='. (int) $item->getId() .'.feeds';
                echo '<a href="'. rex_escape($link_url, 'html_attr') .'" data-d2u-gallery="gallery-'. (int) $lightbox_id .'" class="'. rex_escape($pics_cols, 'html_attr') .'" data-title="'. rex_escape($streamTitle, 'html_attr') .'" title="'. rex_escape($streamTitle, 'html_attr') .'" data-footer-url="'. rex_escape($itemUrl, 'html_attr') .'" data-footer-text="'. rex_escape($itemTitle, 'html_attr') .'" onclick="event.preventDefault(); d2uLightboxOpen(\'gallery-'. (int) $lightbox_id .'\', this);">';
                echo '<img src="'. rex_escape($imgSrc, 'html_attr') .'" class="img-fluid gallery-pic-box" alt="'. rex_escape($altTitle, 'html_attr') .'" title="'. rex_escape($altTitle, 'html_attr') .'">';
                echo '</a>';
            }
        ?>
	</div>
</div>
<?php
    }
}
