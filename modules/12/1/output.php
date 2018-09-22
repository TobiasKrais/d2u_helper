<?php
	$cols_sm = "REX_VALUE[20]";
	if($cols_sm == "") {
		$cols_sm = 8;
	}
	$cols_md = "REX_VALUE[19]";
	if($cols_md == "") {
		$cols_md = $cols_sm;
		$cols_sm = 12; // Backward compatibility
	}
	$cols_lg = "REX_VALUE[18]";
	if($cols_lg == "") {
		$cols_lg = $cols_md;
	}
	$offset_lg_cols = intval("REX_VALUE[17]");
	$offset_lg = "";
	if($offset_lg_cols > 0) {
		$offset_lg = " mr-lg-auto ml-lg-auto ";
	}
	
	// Number pics per row
	$pics_per_line = (int) "REX_VALUE[4]";
	$pics_cols = 'col-6';
	if($pics_per_line > 2) {
		$pics_cols .= ' col-sm-4';
	}
	if($pics_per_line > 3) {
		$pics_cols .= ' col-md-3';
	}
	if($pics_per_line > 4) {
		$pics_cols .= ' col-lg-2';
	}

	$link_type = "REX_VALUE[3]";
	
	$stream = rex_yfeed_stream::get("REX_VALUE[1]");
	$items = $stream->getPreloadedItems(6);
	$lightbox_id = rand();
?>
<div class="col-12 col-sm-<?php echo $cols_sm; ?> col-md-<?php echo $cols_md; ?> col-lg-<?php echo $cols_lg . $offset_lg; ?>">
	<div class="row">
		<?php
			foreach($items as $item) {
				$link_url = $link_type == 'feed' ? $item->getUrl() : 'index.php?rex_media_type=d2u_helper_yfeed_large&rex_media_file='. $item->getId() .'.yfeed';
				print '<a href="'. $link_url .'" data-toggle="lightbox'. $lightbox_id .'" data-gallery="example-gallery'. $lightbox_id .'" class="'. $pics_cols .'" data-title="'. $stream->getTitle() .'" data-footer="<a href=\''. $item->getUrl() .'\' target=\'_blank\'>'. $item->getTitle() .'</a>">';
                print '<img src="index.php?rex_media_type='. ($pics_per_line > 2 ? 'd2u_helper_yfeed_small' : 'd2u_helper_yfeed_large') .'&rex_media_file='. $item->getId() .'.yfeed" class="img-fluid gallery-pic-box" alt="'. implode(' ', array_slice(explode(' ', $item->getTitle()), 0, 5)) .'" title="'. implode(' ', array_slice(explode(' ', $item->getTitle()), 0, 5)) .'">';
				print '</a>';
			}
		?>
	</div>
</div>
<script>
	$(document).on('click', '[data-toggle="lightbox<?php print $lightbox_id; ?>"]', function(event) {
		event.preventDefault();
		$(this).ekkoLightbox({
			alwaysShowClose: true
		});
	});
</script>