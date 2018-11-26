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
	$number_pics = "REX_VALUE[1]";
	$pics_cols = 'col-6';
	if ($number_pics == "3") {
		$pics_cols .= ' col-sm-4';
	}
	else if ($number_pics == "4") {
		$pics_cols .= ' col-sm-4 col-lg-3';		
	}
	else {
		// Backward compatibility
		if($cols_sm == 12) {
			$pics_cols .= ' col-sm-4';
		}

		if($cols_md == 12) {
			$pics_cols .= ' col-md-3';
		}
		elseif($cols_md == 8) {
			$pics_cols .= ' col-md-4';
		}

		if($cols_lg == 12) {
			$pics_cols .= ' col-lg-2';
		}
		elseif($cols_lg == 8) {
			$pics_cols .= ' col-lg-3';
		}
		elseif($cols_lg == 6) {
			$pics_cols .= ' col-lg-4';
		}
	}
	
	$type_thumb = "d2u_helper_gallery_thumb";
	$type_detail = "d2u_helper_gallery_detail";
	$pics = preg_grep('/^\s*$/s', explode(",", REX_MEDIALIST[1]), PREG_GREP_INVERT);
	
	$lightbox_id = rand();

?>
<div class="col-12 col-sm-<?php echo $cols_sm; ?> col-md-<?php echo $cols_md; ?> col-lg-<?php echo $cols_lg . $offset_lg; ?>">
	<div class="row">
		<?php
			foreach($pics as $pic) {
				$media = rex_media::get($pic);
				print '<a href="index.php?rex_media_type='. $type_detail .'&rex_media_file='. $pic .'" data-toggle="lightbox'. $lightbox_id .'" data-gallery="example-gallery'. $lightbox_id .'" class="'. $pics_cols .'"';
				if($media instanceof rex_media) {
					print ' data-title="'. $media->getValue('title') .'"';
				}
				print '>';
                print '<img src="index.php?rex_media_type='. $type_thumb .'&rex_media_file='. $pic .'" class="img-fluid gallery-pic-box"';
				if($media instanceof rex_media) {
					print ' alt="'. $media->getValue('title') .'" title="'. $media->getValue('title') .'"';
				}
				print '>';
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