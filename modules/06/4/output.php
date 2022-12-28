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

$media_filenames_unfiltered = preg_grep('/^\s*$/s', explode(",", REX_MEDIALIST[1]), PREG_GREP_INVERT);
$media_filenames = is_array($media_filenames_unfiltered) ? $media_filenames_unfiltered : [];

print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
print rex_plyr::outputMediaPlaylist($media_filenames, 'play-large,play,progress,current-time,duration,restart,volume,mute,pip,fullscreen');
print '</div>';
if(!function_exists('loadJsPlyr')) {
	function loadJsPlyr() {
		print '<script src="'. rex_url::base('assets/addons/plyr/vendor/plyr/dist/plyr.min.js') .'"></script>';
	}
	loadJsPlyr();
}
print '<script src="'. rex_url::base('assets/addons/plyr/plyr_playlist.js') .'"></script>';