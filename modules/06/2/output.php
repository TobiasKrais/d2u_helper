<div class="col-12 abstand">
<?php
if ("REX_VALUE[1]" != "") {
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
}
?>
</div>