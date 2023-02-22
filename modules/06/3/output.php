<?php

$cols_sm = 'REX_VALUE[20]';
if ('' == $cols_sm) {
    $cols_sm = 12;
}
$cols_md = 'REX_VALUE[19]';
if ('' == $cols_md) {
    $cols_md = 12;
}
$cols_lg = 'REX_VALUE[18]';
if ('' == $cols_lg) {
    $cols_lg = 8;
}
$offset_lg_cols = (int) 'REX_VALUE[17]';
$offset_lg = '';
if ($offset_lg_cols > 0) { /** @phpstan-ignore-line */
    $offset_lg = ' mr-lg-auto ml-lg-auto ';
}

$description = 'REX_VALUE[1]';
$filename_video = 'REX_MEDIA[1]';
$media_video = rex_media::get($filename_video);
$filename_preview = 'REX_MEDIA[2]';
$media_preview = rex_media::get($filename_preview);
if ($media_video) {
    echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' plyr-container">';
    $plyr_media = rex_plyr::outputMedia($filename_video, 'play-large,play,progress,current-time,duration,restart,volume,mute,pip,fullscreen', $media_preview ? $media_preview->getUrl() : null);
    echo $plyr_media;

    if ($media_preview) {
        $server = rtrim(rex_addon::get('yrewrite')->isAvailable() ? rex_yrewrite::getCurrentDomain()->getUrl() : rex::getServer(), '/');
        echo '<script type="application/ld+json">'. PHP_EOL;
        echo '{'. PHP_EOL;
        echo '"@context": "https://schema.org",'. PHP_EOL;
        echo '"@type": "VideoObject",'. PHP_EOL;
        echo '"name": "'. $media_video->getTitle() .'",'. PHP_EOL;
        echo '"description": "'. ($description ?: $media_video->getTitle()) .'",'. PHP_EOL;
        echo '"thumbnailUrl": [ "'. $server . $media_preview->getUrl() .'" ],'. PHP_EOL;
        echo '"uploadDate": "'. date('c', $media_video->getUpdateDate()) .'",'. PHP_EOL;
        echo '"contentUrl": "'. $server . $media_video->getUrl() .'"'. PHP_EOL;
        echo '}'. PHP_EOL;
        echo '</script>'. PHP_EOL;
    }

    echo '</div>';
}

if (!function_exists('loadJsPlyr')) {
    function loadJsPlyr()
    {
        echo '<script src="'. rex_url::base('assets/addons/plyr/vendor/plyr/dist/plyr.min.js') .'"></script>';
    }
    loadJsPlyr();
}
echo '<script src="'. rex_url::base('assets/addons/plyr/plyr_init.js') .'"></script>';
