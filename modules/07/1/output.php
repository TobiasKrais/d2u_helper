<?php

if (rex::isFrontend()) {
    if ('REX_VALUE[id=3 isset=1]' !== '') { /** @phpstan-ignore-line */
        echo htmlspecialchars_decode('REX_VALUE[id=3 output=html]');
    }
} else {
    echo 'Ausgabe nur im Frontend.';
}
