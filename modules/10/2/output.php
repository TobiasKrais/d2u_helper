<?php
	$picture_type = "REX_VALUE[3]";
	$same_height = "REX_VALUE[17]" == "true" ? " same-height" : "";
?>
<div class="col-12 col-sm-REX_VALUE[20] col-md-REX_VALUE[19] col-lg-REX_VALUE[18] abstand">
	<div class="module-box<?php echo $same_height; ?>">
		<?php
			if ("REX_MEDIA[1]" != '') {
				$media = rex_media::get("REX_MEDIA[1]");
				print '<img src="index.php?rex_media_type='. $picture_type .'&rex_media_file=REX_MEDIA[1]" alt="'. 
					$media->getValue('title') .'" title="'. $media->getValue('title') .'" class="module-box-pic">';
			}
			if ('REX_VALUE[id=1 isset=1]') {
				if(rex_config::get('d2u_helper', 'editor', '') == 'markitup' && rex_addon::get('markitup')->isAvailable()) {
					print markitup::parseOutput ('markdown', 'REX_VALUE[id=1 output="html"]');
				}
				else if(rex_config::get('d2u_helper', 'editor', '') == 'markitup_textile' && rex_addon::get('markitup')->isAvailable()) {
					print markitup::parseOutput ('textile', 'REX_VALUE[id=1 output="html"]');
				}
				else {
					print 'REX_VALUE[id=1 output=html]';
				}
			}
		?>
	</div>
</div>