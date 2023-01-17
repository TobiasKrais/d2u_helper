<?php
$cols_sm = intval("REX_VALUE[20]") === 0 ? 12 : intval("REX_VALUE[20]"); /** @phpstan-ignore-line */
$cols_md = intval("REX_VALUE[19]") === 0 ? 12 : intval("REX_VALUE[19]"); /** @phpstan-ignore-line */
$cols_lg = intval("REX_VALUE[18]") === 0 ? 12 : intval("REX_VALUE[18]"); /** @phpstan-ignore-line */
$offset_lg_cols = intval("REX_VALUE[17]");
$offset_lg = "";
if($offset_lg_cols > 0) { /** @phpstan-ignore-line */
	$offset_lg = " mr-lg-auto ml-lg-auto ";
}

if ("REX_VALUE[1]" != "") {
	print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' abstand">';
	$source = "REX_VALUE[1]";
	$max_width = "REX_VALUE[2]";
	$max_height = "REX_VALUE[3]";
	$overflow = "REX_VALUE[4]";
	
	$frame_id = "frame_". rand();
	
	print '<iframe src="'. $source .'" width="'. $max_width .'" height="'. $max_height .'" style="overflow: '. $overflow .';" class="d2u_iframe" id="'. $frame_id .'">';
?>
	  <p>Ihr Browser kann leider keine eingebetteten Frames anzeigen: 
		  Sie k&ouml;nnen die eingebettete Seite &uuml;ber den folgenden 
		  Verweis aufrufen: <a href="REX_VALUE[1]">REX_VALUE[1]</a></p>
	  <p>Your browser does not support embedded Frames: 
		 You can open the Link here: <a href="REX_VALUE[1]">REX_VALUE[1]</a></p>
<?php
	print '</iframe>';
	print '</div>';
}