<?php
$cols_sm = "REX_VALUE[20]";
if($cols_sm == "") {
	$cols_sm = 12;
}
$cols_md = "REX_VALUE[19]";
if($cols_md == "") {
	$cols_md = 6;
}
$cols_lg = "REX_VALUE[18]";
if($cols_lg == "") {
	$cols_lg = 4;
}
$offset_lg_cols = intval("REX_VALUE[17]");
$offset_lg = "";
if($offset_lg_cols > 0) {
	$offset_lg = " mr-lg-auto ml-lg-auto ";
}

print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';

$sprog = rex_addon::get("sprog");
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');

$documents = explode(",", "REX_MEDIALIST[1]");
$ueberschrift = "REX_VALUE[1]";

// Output
if($ueberschrift != "") {
	print '<h2>'. $ueberschrift .'</h2>';
}
print '<ul class="download-list">';
foreach($documents as $document) {
	$rex_document = rex_media::get($document);
	if($rex_document instanceof rex_media) {
		$filesize = round(filesize(rex_path::media() .'/'. $document) / pow(1024, 2), 2);
		$filetype = strtoupper(pathinfo(rex_path::media($document), PATHINFO_EXTENSION));
		$title = $rex_document->getTitle() == "" ? $document : $rex_document->getTitle();

		// Check permissions
		$has_permission = TRUE;
		if(rex_plugin::get('ycom', 'media_auth')->isAvailable()) {
			$has_permission = rex_ycom_media_auth::checkPerm(rex_media_manager::create("", $document));
		}
		if($has_permission) {
			print '<li>';
			if($filetype == 'pdf') {
				print '<span class="icon pdf"></span>&nbsp;&nbsp;';
			}
			else {
				print '<span class="icon file"></span>&nbsp;&nbsp;';
			}
			print '<a href="'. rex_url::media($document) .'" target="_blank">'.
					$title .' <span>('. $filetype .', '. $filesize .' MB)</span></a></li>';
		}
	}
}
print '</ul>';
print '</div>';