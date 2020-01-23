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
$picture_type = "REX_VALUE[1]";

if ("REX_MEDIA[1]" != '') {
	$media = rex_media::get("REX_MEDIA[1]");
	if($media instanceof rex_media) {
		print '<div class="col-12 col-sm-'. $cols . $offset_lg .'">';
		print '<img src="';
		if($picture_type == "") {
			print rex_url::media($picture);
		}
		else {
			print 'index.php?rex_media_type='. $picture_type .'&rex_media_file='. $picture;
		}
		print '" alt="'. $media->getValue('title') .'" title="'. $media->getValue('title') .'" class="module_3_1_image">';
		print '</div>';
	}
}