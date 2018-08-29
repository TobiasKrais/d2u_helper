<?php
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
	$offset_lg_cols = intval("REX_VALUE[17]");
	$offset_lg = "";
	if($offset_lg_cols > 0) {
		$offset_lg = " mr-lg-auto ml-lg-auto ";
	}
	$picture = "REX_MEDIA[1]";
	$heading = "REX_VALUE[1]";
	$type = "REX_VALUE[3]";
	$position = "REX_VALUE[4]";
	$same_height = "REX_VALUE[5]" == 'true' ? 'same-height' : '';
	
	$position_container_classes = "col-12 col-md-6 col-lg-". $cols . $offset_lg;
	if($position == "left") {
		$position_container_classes = "col-12 col-lg-". $cols . $offset_lg;
	}
?>
<div class="<?php echo $position_container_classes; ?> abstand">
	<div class="<?php print $same_height; ?> module-box">
		<div class="row">
			<?php
				// Picture
				$html_picture = $position == "left" || $position == "right" ? '<div class="col-12 col-sm-6 col-md-4">' : '<div class="col-12">';

				if ("REX_MEDIA[1]" != '') {
					$media = rex_media::get("REX_MEDIA[1]");
					$html_picture .= '<img src="';
					if($type == "") {
						$html_picture .= rex_url::media($picture);
					}
					else {
						$html_picture .= 'index.php?rex_media_type='. $type .'&rex_media_file='. $picture;
					}
					$html_picture .= '" alt="'. $media->getValue('title') .'" title="'. $media->getValue('title') .'">';
				}
				
				$html_picture .= '<br><br></div>';
				if($position == "left") {
					print $html_picture;
				}

				// Heading and Text
				if($position == "left" || $position == "right") {
					print '<div class="col-12 col-sm-6 col-md-8">';
				}
				else {
					print '<div class="col-12">';
				}

				if ($heading != "") {
					print "<b>". $heading ."</b><br>";
				}
				if ('REX_VALUE[id=2 isset=1]') {
					if(rex_config::get('d2u_helper', 'editor', '') == 'markitup' && rex_addon::get('markitup')->isAvailable()) {
						print markitup::parseOutput ('markdown', 'REX_VALUE[id=2 output="html"]');
					}
					else if(rex_config::get('d2u_helper', 'editor', '') == 'markitup_textile' && rex_addon::get('markitup')->isAvailable()) {
						print markitup::parseOutput ('textile', 'REX_VALUE[id=2 output="html"]');
					}
					else {
						print 'REX_VALUE[id=2 output=html]';
					}
				}

				print '</div>';
				
				// Picture right
				if($position == "right" || $position == "bottom") {
					print $html_picture;
				}
			?>
		</div>
	</div>
</div>