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

$media_filenames = preg_grep('/^\s*$/s', explode(",", REX_MEDIALIST[1]), PREG_GREP_INVERT);

print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
print '<div class="plyr-container">';
print '<div id="player">';
$plyr_media = rex_plyr::outputMedia($media_filenames[0], 'play-large,play,progress,current-time,duration,restart,volume,mute,pip,fullscreen' /*, '/media/cover/REX_MEDIA[2]'*/);
print $plyr_media;
print '</div>';
print '</div>';
print '</div>';

if(!function_exists('loadJsPlyr')) {
	function loadJsPlyr() {
		print '<script src="'. rex_url::base('assets/addons/plyr/vendor/plyr/dist/plyr.min.js') .'"></script>';
	}
	loadJsPlyr();
}
?>
<script>
var myPlaylist = [
<?php
	$first_element = true;
	foreach ($media_filenames as $media_filename) {
		$media = rex_media::get($media_filename);
		if ($media instanceof rex_media) {
			if ($first_element) {
				$first_element = false;
			} else {
				print ',';
			}

			print '{' . PHP_EOL;
			print 'type: "video/mp4",' . PHP_EOL;
			print 'title: "' . $media->getTitle() . '",' . PHP_EOL;
			print 'sources: [{ ' . PHP_EOL;
			print 'src: "' . $media->getUrl() . '",' . PHP_EOL;
			print 'type: "video/mp4"' . PHP_EOL;
			print '}],' . PHP_EOL;
			print 'src: "' . $media->getUrl() . '",' . PHP_EOL;
			//		poster: "https://img.youtube.com/vi/nfs8NYg7yQM/hqdefault.jpg"
			print '}' . PHP_EOL;
		}
	}
?>
	];

	var target = ".rex-plyr";
</script>