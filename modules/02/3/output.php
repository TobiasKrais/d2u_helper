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

$show_text_side = 'REX_VALUE[5]' == 'true' ? true : false;

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
echo '<div class="row">';

echo '<div class="col-12'. ($show_text_side ? ' col-md-6' : '') .'">';
echo '<REX_VALUE[2] class="REX_VALUE[2] helper-02-3-title">REX_VALUE[1]</REX_VALUE[2]>';
echo '<p class="helper-02-3-subtitle">REX_VALUE[id=3 output=html]</p>';
echo '</div>';

$text = 'REX_VALUE[id=4 output="html"]';
if ('' != strip_tags($text)) {
    echo '<div class="col-12'. ($show_text_side ? ' col-md-6' : '') .'">';
    echo '<div class="helper-02-3-text">';
    if ('REX_VALUE[id=4 isset=4]') {
        if ('markitup' == rex_config::get('d2u_helper', 'editor', '') && rex_addon::get('markitup')->isAvailable()) {
            echo markitup::parseOutput('markdown', $text);
        } elseif ('markitup_textile' == rex_config::get('d2u_helper', 'editor', '') && rex_addon::get('markitup')->isAvailable()) {
            echo markitup::parseOutput('textile', $text);
        } else {
            echo $text;
        }
    }
    echo '</div>';
    echo '</div>';
}

echo '</div>';
echo '</div>';
