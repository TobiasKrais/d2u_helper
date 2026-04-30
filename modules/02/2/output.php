<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$id = random_int(0, getrandmax());

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' toggle_spacer">';

// Whitelist HTML tag for headline (defense-in-depth, input is a select)
$allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'b', 'p'];
$tag = in_array((string) 'REX_VALUE[2]', $allowed_tags, true) ? (string) 'REX_VALUE[2]' : 'h2'; /** @phpstan-ignore-line */
$headline = (string) 'REX_VALUE[1]'; /** @phpstan-ignore-line */

echo '<'. $tag .' onClick="toggle_view_'. $id .'()">'. rex_escape($headline) .'<span class="fa-icon h_toggle icon_down" id="'. $id .'_arrow"></span></'. $tag .'>';

echo '<div class="wysiwyg_content" id="'. $id .'_text">';
if ('REX_VALUE[id=3 isset=3]' !== '') { /** @phpstan-ignore-line */
    echo 'REX_VALUE[id=3 output=html]';
}
echo '</div>';

echo '</div>';

?>
<script>
	function toggle_view_<?= $id ?>() {
		$("#<?= $id ?>_text").fadeToggle();
		$("#<?= $id ?>_arrow").toggleClass("icon_down");
		$("#<?= $id ?>_arrow").toggleClass("icon_right");
	}

	// Hide on document load
	$(document).ready(function() {
		toggle_view_<?= $id ?>();
	});
</script>