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
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' me-lg-auto ms-lg-auto ' : ''; /** @phpstan-ignore-line */

$media_filenames_unfiltered = preg_grep('/^\s*$/s', explode(',', 'REX_MEDIALIST[1]'), PREG_GREP_INVERT);
$media_filenames = is_array($media_filenames_unfiltered) ? $media_filenames_unfiltered : [];
$playlist_items = [];

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

    $playlist_items[] = [
        'filename' => $media_filename,
        'title' => $title,
    ];
}

if ([] !== $playlist_items) {
    d2uHelperLoadVidstackAssets();

    $playlist_id = uniqid('d2u-helper-vidstack-playlist-', false);
    $first_item = $playlist_items[0];

    echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' d2u-helper-vidstack-stack">';

    echo '<div class="d2u-helper-vidstack-playlist" id="'. rex_escape($playlist_id) .'">';
    echo '<div class="d2u-helper-vidstack-stage">';
    echo '<div class="d2u-helper-vidstack-item-title" data-role="current-title">'. rex_escape($first_item['title']) .'</div>';
    echo '<div class="d2u-helper-vidstack-stage-player" data-role="player-stage">';
    echo d2uHelperRenderVidstackPlayer($first_item['filename'], $first_item['title']);
    echo '</div>';
    echo '</div>';

    echo '<div class="d2u-helper-vidstack-playlist-wrapper">';
    echo '<ul class="d2u-helper-vidstack-playlist-items">';
    foreach ($playlist_items as $index => $playlist_item) {
        $template_id = $playlist_id .'-item-'. $index;
        $is_active = 0 === $index;

        echo '<li class="'. ($is_active ? 'is-active' : '') .'">';
        echo '<button type="button" class="d2u-helper-vidstack-playlist-button" data-target="'. rex_escape($template_id) .'" data-title="'. rex_escape($playlist_item['title']) .'">';
        echo rex_escape($playlist_item['title']);
        echo '</button>';
        echo '</li>';

        echo '<template id="'. rex_escape($template_id) .'">';
        echo d2uHelperRenderVidstackPlayer($playlist_item['filename'], $playlist_item['title']);
        echo '</template>';
    }
    echo '</ul>';
    echo '</div>';
    echo '</div>';

    echo '</div>';

    echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    var playlist = document.getElementById('. json_encode($playlist_id) .');
    if (!playlist || playlist.dataset.initialized === "true") {
        return;
    }

    playlist.dataset.initialized = "true";
    var stage = playlist.querySelector("[data-role=\"player-stage\"]");
    var title = playlist.querySelector("[data-role=\"current-title\"]");

    playlist.querySelectorAll(".d2u-helper-vidstack-playlist-button").forEach(function(button) {
        button.addEventListener("click", function() {
            var template = document.getElementById(button.dataset.target);
            if (!template || !stage) {
                return;
            }

            stage.innerHTML = template.innerHTML;
            if (title) {
                title.textContent = button.dataset.title || "";
            }

            playlist.querySelectorAll(".d2u-helper-vidstack-playlist-items li").forEach(function(item) {
                item.classList.remove("is-active");
            });

            var listItem = button.closest("li");
            if (listItem) {
                listItem.classList.add("is-active");
            }
        });
    });
});
</script>';
}