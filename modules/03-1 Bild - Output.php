<?php
/**
 * Version 1.0
 */
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
	$type = "REX_VALUE[1]";
?>
<div class="col-xs-12 col-sm-<?php echo $cols; ?>">
<?php
	if ("REX_MEDIA[1]" != '') {
		$media = rex_media::get("REX_MEDIA[1]");
		print '<img src="index.php?rex_media_type='. $type .'&rex_media_file=REX_MEDIA[1]" alt='. 
			$media->getValue('title') .'>';
	}
?>
</div>
