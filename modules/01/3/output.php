<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$type = 'REX_VALUE[1]';
$text = 'REX_VALUE[id=2 output="html"]';

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' d2u-module-01-3">';
if ('' !== $text) { /** @phpstan-ignore-line */
    echo '<div class="alert alert-'. $type .'" role="alert">';
    echo FriendsOfRedaxo\D2UHelper\FrontendHelper::prepareEditorField($text);
    echo '</div>';
}

echo '</div>';
