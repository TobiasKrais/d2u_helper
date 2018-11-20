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

?>
<div class="col-12 col-lg-<?php echo $cols . $offset_lg; ?>">
	<REX_VALUE[2]>REX_VALUE[1]</REX_VALUE[2]>
</div>