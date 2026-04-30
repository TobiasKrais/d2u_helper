<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$show_text_side = 'REX_VALUE[5]' === 'true' ? true : false; /** @phpstan-ignore-line */

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
echo '<div class="row">';

echo '<div class="col-12'. ($show_text_side ? ' col-md-6' : '') .'">'; /** @phpstan-ignore-line */

// Whitelist HTML tag for headline (defense-in-depth, input is a select)
$allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'b', 'p'];
$tag = in_array((string) 'REX_VALUE[2]', $allowed_tags, true) ? (string) 'REX_VALUE[2]' : 'h2'; /** @phpstan-ignore-line */
$headline = (string) 'REX_VALUE[1]'; /** @phpstan-ignore-line */

echo '<'. $tag .' class="helper-02-3-title">'. rex_escape($headline) .'</'. $tag .'>';
echo '<p class="helper-02-3-subtitle">REX_VALUE[id=3 output=html]</p>';
echo '</div>';

$text = 'REX_VALUE[id=4 output="html"]';
if ('' !== strip_tags($text)) { /** @phpstan-ignore-line */
    echo '<div class="col-12'. ($show_text_side ? ' col-md-6' : '') .'">'; /** @phpstan-ignore-line */
    echo '<div class="helper-02-3-text">';
    if ('REX_VALUE[id=4 isset=4]' !== '') { /** @phpstan-ignore-line */
        echo $text;
    }
    echo '</div>';
    echo '</div>';
}

echo '</div>';
echo '</div>';
