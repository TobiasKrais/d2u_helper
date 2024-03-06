<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$text_1 = 'REX_VALUE[id=1 output="html"]';
$show_text_2 = 'REX_VALUE[2]' === 'true' ? true : false; /** @phpstan-ignore-line */
$text_2 = 'REX_VALUE[id=3 output="html"]';

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
if ('' !== $text_1) { /** @phpstan-ignore-line */
    echo '<div class="wysiwyg_content">';
    echo FriendsOfRedaxo\D2UHelper\FrontendHelper::prepareEditorField($text_1);
    echo '</div>';
}
if ($show_text_2 && '' !== $text_2) { /** @phpstan-ignore-line */
    $id = random_int(0, getrandmax());
    echo '<div class="wysiwyg_content">';
    echo '<button id="button_'. $id .'" class="text-toggler angle-down" onclick="toggle_text_'. $id .'()">'. \Sprog\Wildcard::get('d2u_helper_modules_show_more') .'</button>';
    echo '<div id="second_text_'. $id .'" class="hide-text">';
    echo FriendsOfRedaxo\D2UHelper\FrontendHelper::prepareEditorField($text_2);
    echo '</div>';
    echo '</div>';

    echo '<script>';
    echo 'function toggle_text_'. $id .'() {'. PHP_EOL;
    echo '$("#second_text_'. $id .'").slideToggle();'. PHP_EOL;
    echo 'if($("#button_'. $id .'").hasClass("angle-down")) {';
    echo '$("#button_'. $id .'").fadeOut(500, function() { $(this).html("'. addslashes(\Sprog\Wildcard::get('d2u_helper_modules_show_less')) .'").removeClass("angle-down").addClass("angle-up").fadeIn(500); });';
    echo '}'. PHP_EOL;
    echo 'else {';
    echo '$("#button_'. $id .'").fadeOut(500, function() { $(this).html("'. addslashes(\Sprog\Wildcard::get('d2u_helper_modules_show_more')) .'").removeClass("angle-up").addClass("angle-down").fadeIn(500); });';
    echo '}'. PHP_EOL;
    echo '}'. PHP_EOL;
    echo '</script>';
}
echo '</div>';
