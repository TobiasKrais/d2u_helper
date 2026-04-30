<?php

if (!function_exists('d2uHelperGetVidstackLang')) {
    function d2uHelperGetVidstackLang(): string
    {
        $lang = 'de';
        if (class_exists('rex_clang')) {
            $current_clang = rex_clang::getCurrent();
            if ($current_clang) {
                $lang = substr((string) $current_clang->getCode(), 0, 2);
            }
        }

        return in_array($lang, ['de', 'en', 'es', 'fr', 'si'], true) ? $lang : 'de';
    }
}

if (!function_exists('d2uHelperLoadVidstackAssets')) {
    function d2uHelperLoadVidstackAssets(): void
    {
        static $loaded = false;
        if ($loaded) {
            return;
        }

        $loaded = true;
        echo '<link rel="stylesheet" href="'. rex_url::addonAssets('vidstack', 'vidstack.css') .'">';
        echo '<link rel="stylesheet" href="'. rex_url::addonAssets('vidstack', 'vidstack_helper.css') .'">';
        echo '<script src="'. rex_url::addonAssets('vidstack', 'vidstack.js') .'"></script>';
        echo '<script src="'. rex_url::addonAssets('vidstack', 'vidstack_helper.js') .'"></script>';
    }
}

if (!function_exists('d2uHelperRenderVidstackPlayer')) {
    function d2uHelperRenderVidstackPlayer(string $filename, string $title, bool $autoplay = false): string
    {
        $video_class = 'FriendsOfRedaxo\\VidStack\\Video';
        $video = new $video_class($filename, $title, d2uHelperGetVidstackLang());
        $is_audio = $video_class::isAudio($filename);
        $attributes = [
            'crossorigin' => '',
            'controls' => true,
            'preload' => 'metadata',
        ];

        if (!$is_audio) {
            $attributes['playsinline'] = true;
        }

        if ($autoplay) {
            $attributes['autoplay'] = true;
        }

        $video->setAttributes($attributes);
        $player = $video->generate();

        if ($is_audio) {
            return '<div class="d2u-helper-vidstack-audio-player">'. $player .'</div>';
        }

        return $player;
    }
}

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$use_vidstack = rex_addon::get('vidstack')->isAvailable();
$use_plyr = !$use_vidstack && rex_addon::get('plyr')->isAvailable();

$description = trim('REX_VALUE[1]');
$autoplay = 'REX_VALUE[2]' === 'true'; /** @phpstan-ignore-line */
$filename_video = 'REX_MEDIA[1]';
$media_video = '' !== $filename_video ? rex_media::get($filename_video) : null;
$filename_preview = 'REX_MEDIA[2]';
$media_preview = '' !== $filename_preview ? rex_media::get($filename_preview) : null;
if ($media_video instanceof rex_media) {
    $title = trim((string) $media_video->getTitle());
    if ('' === $title) {
        $title = $filename_video;
    }

    if ($use_vidstack) {
        d2uHelperLoadVidstackAssets();
        $video_class = 'FriendsOfRedaxo\\VidStack\\Video';

        echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' d2u-helper-vidstack-stack">';
        echo '<div class="d2u-helper-vidstack-item">';
        echo d2uHelperRenderVidstackPlayer($filename_video, $title, $autoplay);

        if (!$video_class::isAudio($filename_video)) {
            $domain = rex_addon::get('yrewrite')->isAvailable() && class_exists('rex_yrewrite') ? rex_yrewrite::getCurrentDomain() : null;
            $server = rtrim($domain ? $domain->getUrl() : rex::getServer(), '/');
            echo '<script type="application/ld+json">'. PHP_EOL;
            echo '{'. PHP_EOL;
            echo '"@context": "https://schema.org",'. PHP_EOL;
            echo '"@type": "VideoObject",'. PHP_EOL;
            echo '"name": '. json_encode($title, JSON_UNESCAPED_UNICODE) .','. PHP_EOL;
            echo '"description": '. json_encode('' !== $description ? $description : $title, JSON_UNESCAPED_UNICODE) .','. PHP_EOL;
            echo '"uploadDate": "'. date('c', $media_video->getUpdateDate()) . '",'. PHP_EOL;
            echo '"contentUrl": '. json_encode($server . $media_video->getUrl(), JSON_UNESCAPED_UNICODE) .''. PHP_EOL;
            echo '}'. PHP_EOL;
            echo '</script>'. PHP_EOL;
        }

        echo '</div>';
        echo '</div>';
    } elseif ($use_plyr) {
        echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' plyr-container">';
        echo rex_plyr::outputMedia($filename_video, 'play-large,play,progress,current-time,duration,restart,volume,mute,pip,fullscreen'. ($autoplay ? ',autoplay' : ''), $media_preview instanceof rex_media ? $media_preview->getUrl() : null);

        if ($media_preview instanceof rex_media) {
            $server = rtrim(rex_addon::get('yrewrite')->isAvailable() ? rex_yrewrite::getCurrentDomain()->getUrl() : rex::getServer(), '/');
            echo '<script type="application/ld+json">'. PHP_EOL;
            echo '{'. PHP_EOL;
            echo '"@context": "https://schema.org",'. PHP_EOL;
            echo '"@type": "VideoObject",'. PHP_EOL;
            echo '"name": '. json_encode($title, JSON_UNESCAPED_UNICODE) .','. PHP_EOL;
            echo '"description": '. json_encode('' !== $description ? $description : $title, JSON_UNESCAPED_UNICODE) .','. PHP_EOL;
            echo '"thumbnailUrl": [ '. json_encode($server . $media_preview->getUrl(), JSON_UNESCAPED_UNICODE) .' ],'. PHP_EOL;
            echo '"uploadDate": "'. date('c', $media_video->getUpdateDate()) . '",'. PHP_EOL;
            echo '"contentUrl": '. json_encode($server . $media_video->getUrl(), JSON_UNESCAPED_UNICODE) .''. PHP_EOL;
            echo '}'. PHP_EOL;
            echo '</script>'. PHP_EOL;
        }

        echo '</div>';
    }
}

if ($use_plyr && !function_exists('loadJsPlyr')) {
    function loadJsPlyr(): void
    {
        echo '<script src="'. rex_url::base('assets/addons/plyr/vendor/plyr/dist/plyr.min.js') .'"></script>';
    }
    loadJsPlyr();
}
if ($use_plyr) {
    echo '<script src="'. rex_url::base('assets/addons/plyr/plyr_init.js') .'"></script>';
}
