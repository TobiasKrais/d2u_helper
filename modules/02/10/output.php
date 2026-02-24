<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' me-lg-auto ms-lg-auto ' : ''; /** @phpstan-ignore-line */

if (rex::isFrontend()) {
    echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
    echo '<div id="d2u_helper_toc">';
    // Content will be added in boot.php
    echo '</div>';
    echo '</div>';
} else {
    echo '<p>Ausgabe erfolgt nur im Frontend</p>';
}

?>
<script>
	function toggle_toc() {
		var tocList = document.getElementById("toc_list");
		tocList.style.display = tocList.style.display === "none" ? "" : "none";
		document.getElementById("toc_arrow").classList.toggle("icon_down");
		document.getElementById("toc_arrow").classList.toggle("icon_right");
	}
</script>
