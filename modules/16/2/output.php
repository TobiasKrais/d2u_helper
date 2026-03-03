<?php

$cols = 0 === (int) 'REX_VALUE[20]' ? 8 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' me-lg-auto ms-lg-auto ' : ''; /** @phpstan-ignore-line */

$button_text = trim((string) 'REX_VALUE[1]');
$button_type = 'secondary' === 'REX_VALUE[9]' ? 'secondary' : 'primary'; /** @phpstan-ignore-line */

$link_type = 'REX_VALUE[7]';
$link_url = '';
if ('link' === $link_type) { /** @phpstan-ignore-line */
    $link_url = trim((string) 'REX_VALUE[8]');
} elseif ('media' === $link_type) { /** @phpstan-ignore-line */
    if ('REX_MEDIA[2]' !== '') { /** @phpstan-ignore-line */
        $link_url = rex_url::media('REX_MEDIA[2]');
    }
} elseif ('article' === $link_type) { /** @phpstan-ignore-line */
    $article_id = (int) 'REX_LINK[1]';
    if ($article_id > 0 && rex_article::get($article_id) instanceof rex_article) {
        $link_url = rex_getUrl($article_id);
    }
}

if ('' !== $button_text && '' !== $link_url) {
    echo '<div class="col-12 col-lg-'. $cols . $offset_lg .' abstand">';
    echo '<a class="btn btn-'. $button_type .'" href="'. rex_escape($link_url) .'">'. rex_escape($button_text) .'</a>';
    echo '</div>';
}
