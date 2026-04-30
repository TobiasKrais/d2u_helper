<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$allowed_types = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
$type = in_array((string) 'REX_VALUE[1]', $allowed_types, true) ? (string) 'REX_VALUE[1]' : 'info'; /** @phpstan-ignore-line */
$text = 'REX_VALUE[id=2 output="html"]';

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' d2u-module-01-3">';
if ('' !== $text) { /** @phpstan-ignore-line */
    echo '<div class="alert alert-'. $type .'" role="alert">';
    echo TobiasKrais\D2UHelper\FrontendHelper::prepareEditorField($text);
    echo '</div>';
}

echo '</div>';
