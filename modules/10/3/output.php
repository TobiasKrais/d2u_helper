<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 6 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 4 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$show_preview_pictures = 'REX_VALUE[2]' === 'true' ? true : false; /** @phpstan-ignore-line */

// Number downloads per row
$downloads_cols = 'col-12';
if (12 === $cols_sm) { /** @phpstan-ignore-line */
    $downloads_cols .= ' col-sm-4';
}
if (12 === $cols_md) { /** @phpstan-ignore-line */
    $downloads_cols .= ' col-md-3';
} elseif (8 === $cols_md) { /** @phpstan-ignore-line */
    $downloads_cols .= ' col-md-4';
}
if ($cols_lg >= 8) { /** @phpstan-ignore-line */
    $downloads_cols .= ' col-lg-3';
} elseif (6 === $cols_lg) { /** @phpstan-ignore-line */
    $downloads_cols .= ' col-lg-4';
}

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';

$sprog = rex_addon::get('sprog');
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');

$documents = explode(',', 'REX_MEDIALIST[1]');
$ueberschrift = 'REX_VALUE[1]';

// Output
if ('' !== $ueberschrift) { /** @phpstan-ignore-line */
    echo '<h2>'. $ueberschrift .'</h2>';
}
echo '<div class="download-list'. ($show_preview_pictures ? '-pics' : '') .'">'; /** @phpstan-ignore-line */
echo '<div class="row d-flex">';

foreach ($documents as $document) {
    $rex_document = rex_media::get($document);
    if ($rex_document instanceof rex_media) {
        $filesize = 0;
        if (filesize(rex_path::media() .'/'. $document) > 0) {
            $filesize = round(filesize(rex_path::media() .'/'. $document) / 1024 ** 2, 2);
        }
        $filetype = strtoupper(pathinfo(rex_path::media($document), PATHINFO_EXTENSION));
        $title = $document;
        if (null !== $rex_document->getValue('med_title_'. rex_clang::getCurrentId()) && '' !== $rex_document->getValue('med_title_'. rex_clang::getCurrentId())) {
            $title = $rex_document->getValue('med_title_'. rex_clang::getCurrentId());
        }
        else if (null !== $rex_document->getTitle() && '' !== $rex_document->getTitle()) {
            $title = $rex_document->getTitle();
        }

        // Check permissions
        $has_permission = true;
        if (rex_plugin::get('ycom', 'media_auth')->isAvailable()) {
            $has_permission = rex_ycom_media_auth::checkPerm(rex_media_manager::create('', $document));
        }
        if ($has_permission) {
            echo '<div class="'. $downloads_cols .' flex-fill d-flex">';
            echo '<a href="'. rex_url::media($document) .'" target="_blank" class="element flex-fill">';
            if ($show_preview_pictures && !str_contains($rex_document->getType(), 'video') && 'application/octet-stream' !== $rex_document->getType()) { /** @phpstan-ignore-line */
                echo '<img src="'. rex_media_manager::getUrl('d2u_helper_module_d2u_10-3', $rex_document->getFileName()) .'"><br>';
            }

            if (!$show_preview_pictures) { /** @phpstan-ignore-line */
                if ('pdf' === strtolower($filetype)) {
                    echo '<span class="icon pdf"></span>&nbsp;&nbsp;';
                } else {
                    echo '<span class="icon file"></span>&nbsp;&nbsp;';
                }
            }
            echo $title .'<br><span>('. $filetype . ($filesize > 0 ? ', '. $filesize .' MB' : '') .')</span>';
            echo '</a>';
            echo '</div>';
        }
    }
}
echo '</div>';
echo '</div>';

echo '</div>';