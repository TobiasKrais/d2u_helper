<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$description = 'REX_VALUE[1]';
$filename_video = 'REX_MEDIA[1]';
$media_video = rex_media::get($filename_video);
$filename_preview = 'REX_MEDIA[2]';
$media_preview = rex_media::get($filename_preview);
if ($media_video instanceof rex_media) {
    echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' plyr-container">';
    $plyr_media = rex_plyr::outputMedia($filename_video, 'play-large,play,progress,current-time,duration,restart,volume,mute,pip,fullscreen', $media_preview instanceof rex_media ? $media_preview->getUrl() : null);
    echo $plyr_media;

    if ($media_preview instanceof rex_media) {
        $server = rtrim(rex_addon::get('yrewrite')->isAvailable() ? rex_yrewrite::getCurrentDomain()->getUrl() : rex::getServer(), '/');
        echo '<script type="application/ld+json">'. PHP_EOL;
        echo '{'. PHP_EOL;
        echo '"@context": "https://schema.org",'. PHP_EOL;
        echo '"@type": "VideoObject",'. PHP_EOL;
        echo '"name": "'. $media_video->getTitle() .'",'. PHP_EOL;
        echo '"description": '. json_encode('' !== $description ? $description : $media_video->getTitle(), JSON_UNESCAPED_UNICODE) .','. PHP_EOL; /** @phpstan-ignore-line */
        echo '"thumbnailUrl": [ "'. $server . $media_preview->getUrl() .'" ],'. PHP_EOL;
        echo '"uploadDate": "'. date('c', $media_video->getUpdateDate()) .'",'. PHP_EOL;
        echo '"contentUrl": "'. $server . $media_video->getUrl() .'"'. PHP_EOL;
        echo '}'. PHP_EOL;
        echo '</script>'. PHP_EOL;
    }

    echo '</div>';
}

// Load player JS only one time per page
if (!function_exists('loadJsPlyr')) {
    function loadJsPlyr(): void
    {
        echo '<script src="'. rex_url::base('assets/addons/plyr/vendor/plyr/dist/plyr.min.js') .'"></script>';
    }
    loadJsPlyr();
}
echo '<script src="'. rex_url::base('assets/addons/plyr/plyr_init.js') .'"></script>';
