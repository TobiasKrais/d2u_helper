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
	
	print '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
	print '<div class="row">';
	
	print '<div class="col-12 col-md-6">';
	print '<REX_VALUE[2] class="REX_VALUE[2] helper-02-3-title">REX_VALUE[1]</REX_VALUE[2]>';
	print '<p class="helper-02-3-subtitle">REX_VALUE[id=3 output=html]</p>';
	print '</div>';
	
	print '<div class="col-12 col-md-6">';
	print '<div class="helper-02-3-text">';
	if ('REX_VALUE[id=4 isset=4]') {
		if(rex_config::get('d2u_helper', 'editor', '') == 'markitup' && rex_addon::get('markitup')->isAvailable()) {
			print markitup::parseOutput ('markdown', 'REX_VALUE[id= output="html"]');
		}
		else if(rex_config::get('d2u_helper', 'editor', '') == 'markitup_textile' && rex_addon::get('markitup')->isAvailable()) {
			print markitup::parseOutput ('textile', 'REX_VALUE[id=4 output="html"]');
		}
		else {
			print 'REX_VALUE[id=4 output=html]';
		}
	}
	print '</div>';
	print '</div>';

	print '</div>';
	print '</div>';