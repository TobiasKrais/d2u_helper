<?php
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
?>
<div class="col-sm-12 col-md-<?php echo $cols; ?>">
<?php
if ('REX_VALUE[id=1 isset=1]') {
    echo "REX_VALUE[id=1 output=html]";
}
?>
</div>