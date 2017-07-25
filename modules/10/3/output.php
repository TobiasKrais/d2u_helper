<?php
$sprog = rex_addon::get("sprog");
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');
	
$documents = explode(",", "REX_MEDIALIST[1]");
$ueberschrift = "REX_VALUE[1]";
?>
<div class="col-md-6 col-lg-4">
	<?php
		if($ueberschrift != "") {
			print '<h2>'. $ueberschrift .'</h2>';
		}
	?>
	<ul class="download-list">
	<?php
		foreach($documents as $document) {
			$rex_document = rex_media::get($document);
			if($rex_document instanceof rex_media) {
				$filesize = round(filesize(rex_path::media() .'/'. $document) / pow(1024, 2), 2);
				$filetype = strtoupper(pathinfo(rex_path::media() . PATH_SEPARATOR . $document, PATHINFO_EXTENSION));
				$title = $rex_document->getTitle() == "" ? $document : $rex_document->getTitle();
				
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
	?>
	</ul>
</div>