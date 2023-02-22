<?php

$line = 'REX_VALUE[1]' == 'line' ? true : false;
$container_new = 'REX_VALUE[2]' == 'true' ? true : false;
$container_fluid = 'REX_VALUE[3]' == 'true' ? true : false;

$current_template = new rex_template(rex_article::getCurrent()->getTemplateId());
$compatible_template = in_array(str_replace('d2u_', '', $current_template->getKey()), ['00-1', '02-1', '04-1', '04-2']);
if (\rex::isBackend()) {
    echo 'Umbruch '. ($line ? 'mit' : 'ohne') .' Linie';
    if ($container_new && $compatible_template) {
        echo 'Neuen Container beginnen: '. ($container_new ? 'Ja' : 'Nein') .'<br>';
        echo 'Neuen Container Fluid setzen (komplette Bildschirmbreite): '. ($container_fluid ? 'Ja' : 'Nein') .'<br>';
        echo 'CSS Klasse des neuen Containers: REX_VALUE[3]';
    }
} else {
    if ($container_new && $compatible_template) {
        echo '</div>';
        echo '</div>';
        echo '<div class="container'. ('true' == $container_fluid ? '-fluid' : '') . ('REX_VALUE[4]' != '' ? ' REX_VALUE[4]' : '') .'">';
        echo '<div class="row">';
    }

    echo '<div class="col-12">';
    echo '<div class="spacer'. ($line ? ' line' : '') .'">';
    echo '</div>';
    echo '</div>';
}
