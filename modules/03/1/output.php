<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 8 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$picture = 'REX_MEDIA[1]';
$picture_type = 'REX_VALUE[1]';
$show_title = ('REX_VALUE[2]' === 'true'); /** @phpstan-ignore-line */

if ('REX_MEDIA[1]' !== '') { /** @phpstan-ignore-line */
    $media = rex_media::get('REX_MEDIA[1]');
    if ($media instanceof rex_media) {
        echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
        echo '<figure>';
        echo '<img src="';
        if ('' === $picture_type) { /** @phpstan-ignore-line */
            echo rex_url::media($picture);
        } else {
            echo 'index.php?rex_media_type='. $picture_type .'&rex_media_file='. $picture;
        }
        echo '" alt="'. $media->getValue('title') .'" title="'. $media->getValue('title') .'" class="module_3_1_image" loading="lazy">';
        if ($show_title && ('' !== $media->getValue('title') || ($media->hasValue('med_title_'. rex_clang::getCurrentId()) && '' !== $media->getValue('med_title_'. rex_clang::getCurrentId())))) { /** @phpstan-ignore-line */
            $med_title = ($media->hasValue('med_title_'. rex_clang::getCurrentId()) && '' !== $media->getValue('med_title_'. rex_clang::getCurrentId())) ? $media->getValue('med_title_'. rex_clang::getCurrentId()) : $media->getValue('title');
            echo '<figcaption class="d2u_figcaption">'. $med_title .'</figcaption>';
        }
        echo '</figure>';
        echo '</div>';
    }
}
