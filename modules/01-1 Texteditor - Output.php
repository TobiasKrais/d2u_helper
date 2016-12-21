<?php
/**
 * Version 1.0
 */
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
?>
<div class="col-xs-12 col-sm-<?php echo $cols; ?>">
<?php
if ('REX_VALUE[id=1 isset=1]') {
    echo "REX_VALUE[id=1 output=html]";
}
?>
</div>
