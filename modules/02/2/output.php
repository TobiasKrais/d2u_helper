<?php
    $cols_sm = 'REX_VALUE[20]';
    if ('' == $cols_sm) {
        $cols_sm = 12;
    }
    $cols_md = 'REX_VALUE[19]';
    if ('' == $cols_md) {
        $cols_md = 12;
    }
    $cols_lg = 'REX_VALUE[18]';
    if ('' == $cols_lg) {
        $cols_lg = 8;
    }
    $offset_lg_cols = (int) 'REX_VALUE[17]';
    $offset_lg = '';
    if ($offset_lg_cols > 0) { /** @phpstan-ignore-line */
        $offset_lg = ' mr-lg-auto ml-lg-auto ';
    }

    $id = random_int(0, getrandmax());

    echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' toggle_spacer">';

    echo '<REX_VALUE[2] onClick="toggle_view_'. $id .'()" class="REX_VALUE[2]">REX_VALUE[1]<span class="fa-icon h_toggle icon_up" id="'. $id .'_arrow"></span></REX_VALUE[2]>';

    echo '<div class="wysiwyg_content" id="'. $id .'_text">';
    if ('REX_VALUE[id=3 isset=3]') {
        if ('markitup' == rex_config::get('d2u_helper', 'editor', '') && rex_addon::get('markitup')->isAvailable()) {
            echo markitup::parseOutput('markdown', 'REX_VALUE[id=3 output="html"]');
        } elseif ('markitup_textile' == rex_config::get('d2u_helper', 'editor', '') && rex_addon::get('markitup')->isAvailable()) {
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