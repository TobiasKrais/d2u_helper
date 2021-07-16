<?php
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}

	$offset_lg_cols = intval("REX_VALUE[17]");
	$offset_lg = "";
	if($offset_lg_cols > 0) {
		$offset_lg = " mr-lg-auto ml-lg-auto ";
	}
	
	$center = "REX_VALUE[3]" == "true" ? true : false;
	$line = "REX_VALUE[4]" == "true" ? true : false;

?>
<div class="col-12 col-lg-<?php echo $cols . $offset_lg; ?>">
	<REX_VALUE[2] class="REX_VALUE[2]<?php echo ($center ? ' heading-02-1-center' : '') . ($line ? ' heading-02-1-line' : '') . ($center && $line ? ' heading-02-1-line-center' : ''); ?>">REX_VALUE[1]</REX_VALUE[2]>
</div>