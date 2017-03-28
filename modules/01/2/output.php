<?php
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
	$type = "REX_VALUE[3]";
?>
<div class="col-sm-12 col-md-6 col-lg-<?php echo $cols; ?> abstand">
	<div class="same-height module-box">
		<?php
			if ("REX_MEDIA[1]" != '') {
				$media = rex_media::get("REX_MEDIA[1]");
				print '<img src="index.php?rex_media_type='. $type .'&rex_media_file=REX_MEDIA[1]" alt="'. 
					$media->getValue('title') .'" title="'. $media->getValue('title') .'">';
			}
		?>
		<div><br><b>REX_VALUE[1]</b><br>
		<?php
			if ('REX_VALUE[id=2 isset=1]') {
				echo "REX_VALUE[id=2 output=html]";
			}
		?>
		</div>
	</div>
</div>