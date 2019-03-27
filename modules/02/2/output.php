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
	
	$id = rand();
	
	print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' toggle_spacer">';

	print '<REX_VALUE[2] onClick="toggle_view_'. $id .'()">REX_VALUE[1]<span class="fa-icon h_toggle icon_up" id="'. $id .'_arrow"></span></REX_VALUE[2]>';
	
	print '<div class="wysiwyg_content" id="'. $id .'_text">';
	if ('REX_VALUE[id=3 isset=3]') {
		if(rex_config::get('d2u_helper', 'editor', '') == 'markitup' && rex_addon::get('markitup')->isAvailable()) {
			print markitup::parseOutput ('markdown', 'REX_VALUE[id=3 output="html"]');
		}
		else if(rex_config::get('d2u_helper', 'editor', '') == 'markitup_textile' && rex_addon::get('markitup')->isAvailable()) {
			print markitup::parseOutput ('textile', 'REX_VALUE[id=3 output="html"]');
		}
		else {
			print 'REX_VALUE[id=3 output=html]';
		}
	}
	print '</div>';
	
	print '</div>';
?>
<script>
	function toggle_view_<?php echo $id; ?>() {
		$("#<?php echo $id; ?>_text").fadeToggle();
		$("#<?php echo $id; ?>_arrow").toggleClass("icon_down");
		$("#<?php echo $id; ?>_arrow").toggleClass("icon_up");
	}

	// Hide on document load
	$(document).ready(function() {
		toggle_view_<?php echo $id; ?>();
	});
</script>