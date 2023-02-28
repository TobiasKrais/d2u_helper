<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

if (rex::isFrontend()) {
    echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
    $link_type = 'REX_VALUE[1]';
    if ('media' === $link_type && 'REX_MEDIA[1]' !== '') { /** @phpstan-ignore-line */
        $file = 'REX_MEDIA[1]';
        echo '<script src="'. rex_url::media($file) .'?buster='. filemtime(rex_path::media($file)) .'"></script>'. PHP_EOL;
    } elseif ('link' === $link_type && 'REX_VALUE[2]' !== '') { /** @phpstan-ignore-line */
        echo '<script src="REX_VALUE[2]"></script>'. PHP_EOL;
    }

    if ('REX_VALUE[id=3 isset=1]' !== '') { /** @phpstan-ignore-line */
        echo htmlspecialchars_decode('REX_VALUE[id=3 output=html]');
    }
    echo '</div>';
} else {
    echo 'Ausgabe nur im Frontend.';
}
