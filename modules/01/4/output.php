<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' me-lg-auto ms-lg-auto ' : ''; /** @phpstan-ignore-line */

$text_1 = 'REX_VALUE[id=1 output="html"]';
$show_text_2 = 'REX_VALUE[2]' === 'true' ? true : false; /** @phpstan-ignore-line */
$text_2 = 'REX_VALUE[id=3 output="html"]';

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
if ('' !== $text_1) { /** @phpstan-ignore-line */
    echo '<div class="wysiwyg_content">';
    echo TobiasKrais\D2UHelper\FrontendHelper::prepareEditorField($text_1);
    echo '</div>';
}
if ($show_text_2 && '' !== $text_2) { /** @phpstan-ignore-line */
    $id = random_int(0, getrandmax());
    echo '<div class="wysiwyg_content">';
    echo '<button id="button_'. $id .'" class="text-toggler angle-down" onclick="toggle_text_'. $id .'()">'. \Sprog\Wildcard::get('d2u_helper_modules_show_more') .'</button>';
    echo '<div id="second_text_'. $id .'" class="hide-text">';
    echo TobiasKrais\D2UHelper\FrontendHelper::prepareEditorField($text_2);
    echo '</div>';
    echo '</div>';

    echo '<script>';
    echo 'function toggle_text_'. $id .'() {'. PHP_EOL;
    echo 'var secondText = document.getElementById("second_text_'. $id .'");'. PHP_EOL;
    echo 'secondText.classList.toggle("hide-text");'. PHP_EOL;
    echo 'var btn = document.getElementById("button_'. $id .'");'. PHP_EOL;
    echo 'if(btn.classList.contains("angle-down")) {'. PHP_EOL;
    echo 'btn.style.opacity = "0";'. PHP_EOL;
    echo 'setTimeout(function() {'. PHP_EOL;
    echo 'btn.textContent = "'. addslashes(\Sprog\Wildcard::get('d2u_helper_modules_show_less')) .'";'. PHP_EOL;
    echo 'btn.classList.remove("angle-down");'. PHP_EOL;
    echo 'btn.classList.add("angle-up");'. PHP_EOL;
    echo 'btn.style.opacity = "1";'. PHP_EOL;
    echo '}, 500);'. PHP_EOL;
    echo '}'. PHP_EOL;
    echo 'else {'. PHP_EOL;
    echo 'btn.style.opacity = "0";'. PHP_EOL;
    echo 'setTimeout(function() {'. PHP_EOL;
    echo 'btn.textContent = "'. addslashes(\Sprog\Wildcard::get('d2u_helper_modules_show_more')) .'";'. PHP_EOL;
    echo 'btn.classList.remove("angle-up");'. PHP_EOL;
    echo 'btn.classList.add("angle-down");'. PHP_EOL;
    echo 'btn.style.opacity = "1";'. PHP_EOL;
    echo '}, 500);'. PHP_EOL;
    echo '}'. PHP_EOL;
    echo '}'. PHP_EOL;
    echo '</script>';
}
echo '</div>';
