<?php

$cols_sm = 'REX_VALUE[20]';
if ('' == $cols_sm) {
    $cols_sm = 12;
}
$cols_md = 'REX_VALUE[19]';
if ('' == $cols_md) {
    $cols_md = 6;
}
$cols_lg = 'REX_VALUE[18]';
if ('' == $cols_lg) {
    $cols_lg = 4;
}
$offset_lg_cols = (int) 'REX_VALUE[17]';
$offset_lg = '';
if ($offset_lg_cols > 0) { /** @phpstan-ignore-line */
    $offset_lg = ' mr-lg-auto ml-lg-auto ';
}

$show_preview_pictures = 'REX_VALUE[2]' === 'true' ? true : false; /** @phpstan-ignore-line */

// Number downloads per row
$downloads_cols = 'col-12';
if (12 == $cols_sm) {
    $downloads_cols .= ' col-sm-4';
}
if (12 == $cols_md) {
    $downloads_cols .= ' col-md-3';
} elseif (8 == $cols_md) {
    $downloads_cols .= ' col-md-4';
}
if ($cols_lg >= 8) {
    $downloads_cols .= ' col-lg-3';
} elseif (6 == $cols_lg) {
    $downloads_cols .= ' col-lg-4';
}

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';

$sprog = rex_addon::get('sprog');
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');

$documents = explode(',', 'REX_MEDIALIST[1]');
$ueberschrift = 'REX_VALUE[1]';

// Output
if ('' != $ueberschrift) {
    echo '<h2>'. $ueberschrift .'</h2>';
}
echo '<div class="download-list'. ($show_preview_pictures ? '-pics' : '') .'">';
echo '<div class="row">';

foreach ($documents as $document) {
    $rex_document = rex_media::get($document);
    if ($rex_document instanceof rex_media) {
        $filesize = round(filesize(rex_path::media() .'/'. $document) / 1024 ** 2, 2);
        $filetype = strtoupper(pathinfo(rex_path::media($document), PATHINFO_EXTENSION));
        $title = $rex_document->getValue('med_title_'. rex_clang::getCurrentId()) ?: ($rex_document->getTitle() ?: $document);

        // Check permissions
        $has_permission = true;
        if (rex_plugin::get('ycom', 'media_auth')->isAvailable()) {
            $has_permission = rex_ycom_media_auth::checkPerm(rex_media_manager::create('', $document));
        }
        if ($has_permission) {
            echo '<div class="element '. $downloads_cols .'">';
            echo '<a href="'. rex_url::media($document) .'" target="_blank">';
            if ($show_preview_pictures && !str_contains($rex_document->getType(), 'video') && 'application/octet-stream' !== $rex_document->getType()) {
                echo '<img src="'. rex_media_manager::getUrl('d2u_helper_module_d2u_10-3', $rex_document->getFileName()) .'" class="uk-padding-small uk-padding-remove-horizontal uk-padding-remove-top"><br>';
            }

            if (!$show_preview_pictures) {
                if ('pdf' == $filetype) {
                    echo '<span class="icon pdf"></span>&nbsp;&nbsp;';
                } else {
                    echo '<span class="icon file"></span>&nbsp;&nbsp;';
                }
            }
            echo $title .' <span>('. $filetype .', '. $filesize .' MB)</span></a></div>';
        }
    }
}
echo '</div>';
echo '</div>';

echo '</div>';
