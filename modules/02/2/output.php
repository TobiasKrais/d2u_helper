<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$id = random_int(0, getrandmax());

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' toggle_spacer">';

echo '<REX_VALUE[2] onClick="toggle_view_'. $id .'()" class="REX_VALUE[2]">REX_VALUE[1]<span class="fa-icon h_toggle icon_up" id="'. $id .'_arrow"></span></REX_VALUE[2]>';

echo '<div class="wysiwyg_content" id="'. $id .'_text">';
if ('REX_VALUE[id=3 isset=3]' !== '') { /** @phpstan-ignore-line */
    if ('markitup' === (string) rex_config::get('d2u_helper', 'editor', '') && rex_addon::get('markitup')->isAvailable()) {
        echo markitup::parseOutput('markdown', 'REX_VALUE[id=3 output="html"]');
    } elseif ('markitup_textile' === (string) rex_config::get('d2u_helper', 'editor', '') && rex_addon::get('markitup')->isAvailable()) {
        echo markitup::parseOutput('textile', 'REX_VALUE[id=3 output="html"]');
    } else {
        echo 'REX_VALUE[id=3 output=html]';
    }
}
echo '</div>';

echo '</div>';

?>
<script>
	function toggle_view_<?= $id ?>() {
		$("#<?= $id ?>_text").fadeToggle();
		$("#<?= $id ?>_arrow").toggleClass("icon_down");
		$("#<?= $id ?>_arrow").toggleClass("icon_up");
	}

	// Hide on document load
	$(document).ready(function() {
		toggle_view_<?= $id ?>();
	});
</script>