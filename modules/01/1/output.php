<?php
	$cols_sm = "REX_VALUE[20]";
	if($cols_sm == "") {
		$cols_sm = 8;
	}
	$cols_md = "REX_VALUE[19]";
	if($cols_md == "") {
		$cols_md = $cols_sm;
		$cols_sm = 12; // Backward compatibility
	}
	$cols_lg = "REX_VALUE[18]";
	if($cols_lg == "") {
		$cols_lg = $cols_md;
	}
	$offset_lg_cols = intval("REX_VALUE[17]");
	$offset_lg = "";
	if($offset_lg_cols > 0) {
		$offset_lg = " mr-lg-auto ml-lg-auto ";
	}
	
	print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
	if ('REX_VALUE[id=1 isset=1]') {
		if(rex_config::get('d2u_helper', 'editor', '') == 'markitup' && rex_addon::get('markitup')->isAvailable()) {
			print markitup::parseOutput ('markdown', 'REX_VALUE[id=1 output="html"]');
		}
		else if(rex_config::get('d2u_helper', 'editor', '') == 'markitup_textile' && rex_addon::get('markitup')->isAvailable()) {
			print markitup::parseOutput ('textile', 'REX_VALUE[id=1 output="html"]');
		}
		else {
			print 'REX_VALUE[id=1 output=html]';
		}
	}
	print '</div>';
?>