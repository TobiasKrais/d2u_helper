<?php
	$type = "REX_VALUE[3]";
	$same_height = "REX_VALUE[17]" == "true" ? " same-height" : "";
?>
<div class="col-12 col-sm-REX_VALUE[20] col-md-REX_VALUE[19] col-lg-REX_VALUE[18] abstand">
	<div class="module-box<?php echo $same_height; ?>">
		<REX_VALUE[2]>REX_VALUE[1]</REX_VALUE[2]>
		<?php
			if ("REX_MEDIA[1]" != '') {
				$media = rex_media::get("REX_MEDIA[1]");
				print '<img src="index.php?rex_media_type='. $type .'&rex_media_file=REX_MEDIA[1]" alt="'. 
					$media->getValue('title') .'" title="'. $media->getValue('title') .'">';
			}
		?>
	</div>
</div>