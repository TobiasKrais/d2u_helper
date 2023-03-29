<?php

$line = 'REX_VALUE[1]' === 'line' ? true : false; /** @phpstan-ignore-line */
$container_new = 'REX_VALUE[2]' === 'true' ? true : false; /** @phpstan-ignore-line */
$container_fluid = 'REX_VALUE[3]' === 'true' ? true : false; /** @phpstan-ignore-line */

$compatible_template = false;
$current_article = rex_article::getCurrent();
if ($current_article instanceof rex_article) {
    $current_template = new rex_template($current_article->getTemplateId());
    $compatible_template = in_array(str_replace('d2u_', '', null !== $current_template->getKey() ? $current_template->getKey() : ''), ['00-1', '02-1', '04-1', '04-2'], true);
}
if (\rex::isBackend()) {
    echo 'Umbruch '. ($line ? 'mit' : 'ohne') .' Linie'; /** @phpstan-ignore-line */
    if ($container_new && $compatible_template) { /** @phpstan-ignore-line */
        echo 'Neuen Container beginnen: '. ($container_new ? 'Ja' : 'Nein') .'<br>';
        echo 'Neuen Container Fluid setzen (komplette Bildschirmbreite): '. ($container_fluid ? 'Ja' : 'Nein') .'<br>'; /** @phpstan-ignore-line */
        echo 'CSS Klasse des neuen Containers: REX_VALUE[3]';
    }
} else {
    if ($container_new && $compatible_template) { /** @phpstan-ignore-line */
        echo '</div>';
        echo '</div>';
        echo '<div class="container'. ('true' === $container_fluid ? '-fluid' : '') . ('REX_VALUE[4]' !== '' ? ' REX_VALUE[4]' : '') .'">'; /** @phpstan-ignore-line */
        echo '<div class="row">';
    }

    echo '<div class="col-12">';
    echo '<div class="spacer'. ($line ? ' line' : '') .'">'; /** @phpstan-ignore-line */
    echo '</div>';
    echo '</div>';
}
