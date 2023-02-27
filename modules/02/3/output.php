<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$show_text_side = 'REX_VALUE[5]' === 'true' ? true : false; /** @phpstan-ignore-line */

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
echo '<div class="row">';

echo '<div class="col-12'. ($show_text_side ? ' col-md-6' : '') .'">'; /** @phpstan-ignore-line */
echo '<REX_VALUE[2] class="REX_VALUE[2] helper-02-3-title">REX_VALUE[1]</REX_VALUE[2]>';
echo '<p class="helper-02-3-subtitle">REX_VALUE[id=3 output=html]</p>';
echo '</div>';

$text = 'REX_VALUE[id=4 output="html"]';
if ('' !== strip_tags($text)) { /** @phpstan-ignore-line */
    echo '<div class="col-12'. ($show_text_side ? ' col-md-6' : '') .'">'; /** @phpstan-ignore-line */
    echo '<div class="helper-02-3-text">';
    if ('REX_VALUE[id=4 isset=4]' !== '') { /** @phpstan-ignore-line */
        if ('markitup' === (string) rex_config::get('d2u_helper', 'editor', '') && rex_addon::get('markitup')->isAvailable()) {
            echo markitup::parseOutput('markdown', $text);
        } elseif ('markitup_textile' === (string) rex_config::get('d2u_helper', 'editor', '') && rex_addon::get('markitup')->isAvailable()) {
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
