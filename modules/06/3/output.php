<?php
$cols_sm = "REX_VALUE[20]";
if($cols_sm == "") {
	$cols_sm = 12;
}
$cols_md = "REX_VALUE[19]";
if($cols_md == "") {
	$cols_md = 12;
}
$cols_lg = "REX_VALUE[18]";
if($cols_lg == "") {
	$cols_lg = 8;
}
$offset_lg_cols = intval("REX_VALUE[17]");
$offset_lg = "";
if($offset_lg_cols > 0) {
	$offset_lg = " mr-lg-auto ml-lg-auto ";
}

$media_filename = "REX_MEDIA[1]";
$media = rex_media::get($media_filename);
if($media instanceof rex_media) {
	print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
	$plyr_media = rex_plyr::outputMedia($media_filename, 'play-large,play,progress,current-time,duration,restart,volume,mute,pip,fullscreen' /*, '/media/cover/REX_MEDIA[2]'*/);
	print $plyr_media;
	print '</div>';
}
?>
<script>
	document.addEventListener("DOMContentLoaded", function(){
		const players = Plyr.setup('.rex-plyr',{
			youtube: { 
				noCookie: true
			}
		});
	});
</script>