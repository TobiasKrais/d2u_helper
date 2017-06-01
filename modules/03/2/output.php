<?php
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
	$pics_cols = 4;
	if($cols >= 10) {
		$pics_cols = 3;
	}
	else if ($cols <= 6) {
		$pics_cols = 6;
	}
	$type_thumb = "d2u_helper_gallery_thumb";
	$type_detail = "d2u_helper_gallery_detail";
	$pics = preg_grep('/^\s*$/s', explode(",", REX_MEDIALIST[1]), PREG_GREP_INVERT);
	
	$lightbox_id = rand();
?>
<div class="col-12 col-md-<?php echo $cols; ?>">
	<div class="row">
		<?php
			foreach($pics as $pic) {
				$media = rex_media::get($pic);
				print '<a href="index.php?rex_media_type='. $type_detail .'&rex_media_file='. $pic .'" data-toggle="lightbox'. $lightbox_id .'" data-gallery="example-gallery'. $lightbox_id .'" class="col-4 col-lg-'. $pics_cols .'"';
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