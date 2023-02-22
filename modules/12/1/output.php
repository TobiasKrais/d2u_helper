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
$pics_per_line = (int) 'REX_VALUE[4]';
$pics_cols = 'col-6';
if ($pics_per_line > 2) {
    $pics_cols .= ' col-sm-4';
}
if ($pics_per_line > 3) {
    $pics_cols .= ' col-md-3';
}
if ($pics_per_line > 4) {
    $pics_cols .= ' col-lg-2';
}

$link_type = 'REX_VALUE[3]';

if (rex_addon::get('feeds')->isAvailable()) {
    $stream = rex_feeds_stream::get('REX_VALUE[1]');
    $items = $stream->getPreloadedItems(6);
    $lightbox_id = random_int(0, getrandmax());
?>
<div class="col-12 col-sm-<?= $cols_sm ?> col-md-<?= $cols_md ?> col-lg-<?= $cols_lg . $offset_lg ?>">
	<div class="row">
		<?php
            foreach ($items as $item) {
                $link_url = 'feed' == $link_type ? $item->getUrl() : 'index.php?rex_media_type=d2u_helper_yfeed_large&rex_media_file='. $item->getId() .'.feeds';
                echo '<a href="'. $link_url .'" data-toggle="lightbox'. $lightbox_id .'" data-gallery="example-gallery'. $lightbox_id .'" class="'. $pics_cols .'" data-title="'. $stream->getTitle() .'" title="'. $stream->getTitle() .'" data-footer="<a href=\''. $item->getUrl() .'\' target=\'_blank\' title=\''. $item->getTitle() .'\'>'. $item->getTitle() .'</a>">';
                echo '<img src="index.php?rex_media_type='. ($pics_per_line > 2 ? 'd2u_helper_yfeed_small' : 'd2u_helper_yfeed_large') .'&rex_media_file='. $item->getId() .'.feeds" class="img-fluid gallery-pic-box" alt="'. implode(' ', array_slice(explode(' ', $item->getTitle()), 0, 5)) .'" title="'. implode(' ', array_slice(explode(' ', $item->getTitle()), 0, 5)) .'">';
                echo '</a>';
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
<?php
}
