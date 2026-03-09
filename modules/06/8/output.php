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
        $attributes = [
            'crossorigin' => '',
            'playsinline' => true,
            'controls' => true,
            'preload' => 'metadata',
        ];

        if ($autoplay) {
            $attributes['autoplay'] = true;
        }

        $video->setAttributes($attributes);

        return $video->generate();
    }
}

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' me-lg-auto ms-lg-auto ' : ''; /** @phpstan-ignore-line */

$media_filenames_unfiltered = preg_grep('/^\s*$/s', explode(',', 'REX_MEDIALIST[1]'), PREG_GREP_INVERT);
$media_filenames = is_array($media_filenames_unfiltered) ? $media_filenames_unfiltered : [];

if ([] !== $media_filenames) {
    d2uHelperLoadVidstackAssets();

    echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' d2u-helper-vidstack-stack">';
    foreach ($media_filenames as $media_filename) {
        $media_filename = trim($media_filename);
        $media = '' !== $media_filename ? rex_media::get($media_filename) : null;
        if (!$media instanceof rex_media) {
            continue;
        }

        $title = trim((string) $media->getTitle());
        if ('' === $title) {
            $title = $media_filename;
        }

        echo '<div class="d2u-helper-vidstack-item">';
        echo '<div class="d2u-helper-vidstack-item-title">'. rex_escape($title) .'</div>';
        echo d2uHelperRenderVidstackPlayer($media_filename, $title);
        echo '</div>';
    }
    echo '</div>';
}