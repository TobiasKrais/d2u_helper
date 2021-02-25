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

if(rex::isFrontend()) {
	print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
	$link_type = "REX_VALUE[1]";
	if ($link_type == 'media' && "REX_MEDIA[1]" != '') {
		$file = "REX_MEDIA[1]";
		print '<script src="'. rex_url::media($file) .'?buster='. filemtime(rex_path::media($file)) .'"></script>'. PHP_EOL;
	}
	else if ($link_type == 'link' && "REX_VALUE[2]" != '') {
		print '<script src="REX_VALUE[2]"></script>'. PHP_EOL;
	}

	if ('REX_VALUE[id=3 isset=1]') {
		print htmlspecialchars_decode('REX_VALUE[id=3 output=html]');
	}
	print '</div>';
}
else {
	print "Ausgabe nur im Frontend.";
}