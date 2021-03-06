<?php
	$cols = "REX_VALUE[20]" == "" ? 8 : "REX_VALUE[20]";
	$offset_lg_cols = intval("REX_VALUE[17]");
	$offset_lg = "";
	if($offset_lg_cols > 0) {
		$offset_lg = " mr-lg-auto ml-lg-auto ";
	}
	$heading = "REX_VALUE[1]";
	$same_height = "REX_VALUE[5]" == 'true' ? 'same-height ' : '';
	$show_title = ("REX_VALUE[9]" == 'true');

	// Link
	$link_type = "REX_VALUE[7]";
	$link_url = "";
	if($link_type == "link") {
		$link_url = "REX_VALUE[8]";
	}
	else if($link_type == "download") {
		$link_url = rex_url::media("REX_MEDIA[2]");
	}
	else if($link_type == "article") {
		$article_id = "REX_LINK[1]";
		if($article_id > 0 && rex_article::get($article_id) instanceof rex_article) {
			$link_url = rex_getUrl($article_id);
		}
	}
	
	// Picture
	$picture = "REX_MEDIA[1]";
	$picture_cols = "REX_VALUE[6]" == "" ? 4 : "REX_VALUE[6]";
	$picture_type = "REX_VALUE[3]";
	$picture_position = "REX_VALUE[4]";
	
	$container_classes = "col-12 col-lg-". $cols . $offset_lg;
	if($picture_position == "left") {
		$container_classes = "col-12 col-lg-". $cols . $offset_lg;
	}
?>
<div class="<?php echo $container_classes; ?> abstand">
	<div class="<?php print $same_height; ?>module-box wysiwyg_content">
		<div class="row">
			<?php
				// Picture
				$html_picture = $picture_position == "left" || $picture_position == "right" ? '<div class="col-12 col-sm-'. ($picture_cols == 2 ? 4 : 6) .' col-md-'. $picture_cols .'">' : '<div class="col-12">';

				if ("REX_MEDIA[1]" != '') {
					if($link_url != "") {
						$html_picture .= '<a href="'. $link_url .'">';
					}
					$media = rex_media::get("REX_MEDIA[1]");
					$html_picture .= '<figure>';
					$html_picture .= '<img src="';
					if($picture_type == "") {
						$html_picture .= rex_url::media($picture);
					}
					else {
						$html_picture .= 'index.php?rex_media_type='. $picture_type .'&rex_media_file='. $picture;
					}
					$html_picture .= '" alt="'. $media->getValue('title') .'" title="'. $media->getValue('title') .'">';
					if($show_title && ($media->getValue('title') != "" || ($media->hasValue("med_title_". rex_clang::getCurrentId()) && $media->getValue("med_title_". rex_clang::getCurrentId()) != ""))) {
						$med_title = ($media->hasValue("med_title_". rex_clang::getCurrentId()) && $media->getValue("med_title_". rex_clang::getCurrentId()) != "") ? $media->getValue("med_title_". rex_clang::getCurrentId()) : $media->getValue('title');
						$html_picture .= '<figcaption class="d2u_figcaption">'. $med_title .'</figcaption>';
					}
					$html_picture .= '</figure>';
					if($link_url != "") {
						$html_picture .= '</a>';
					}
				}
				
				$html_picture .= '</div>';
				if($picture_position == "left" || $picture_position == "top") {
					print $html_picture;
				}

				// Heading and Text
				if($picture_position == "left" || $picture_position == "right") {
					print '<div class="col-12 col-sm-'. ($picture_cols == 2 ? 8 : 6) .' col-md-'. (12 - $picture_cols) .'">';
				}
				else {
					print '<div class="col-12">';
				}

				if ($heading != "") {
					if($link_url != "") {
						print '<a href="'. $link_url .'">';
					}
					print "<b>". $heading ."</b><br>";
					if($link_url != "") {
						print '</a>';
					}
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
				if($picture_position == "right" || $picture_position == "bottom") {
					print $html_picture;
				}
			?>
		</div>
	</div>
</div>