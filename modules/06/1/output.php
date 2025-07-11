<?php
$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

preg_match(
    '/[\\?\\&]v=([^\\?\\&]+)/',
    'REX_VALUE[1]',
    $matches,
);
$youtube_id = isset($matches[1]) ? trim($matches[1]) : '';
if ('' === $youtube_id && (str_contains('REX_VALUE[1]', 'youtu.be/') || str_contains('REX_VALUE[1]', '/embed/') || str_contains('REX_VALUE[1]', '/shorts/'))) {
    $youtube_id = trim(substr('REX_VALUE[1]', (int) strrpos('REX_VALUE[1]', '/') + 1));
}
$youtube_url = 'https://www.youtube-nocookie.com/embed/'. $youtube_id .'?autoplay=1';
$youtube_previewimage_url = 'https://img.youtube.com/vi/'. $youtube_id .'/hqdefault.jpg';
$youtube_videoinfo_url = 'https://www.youtube.com/oembed?format=json&url=https%3A//youtube.com/watch%3Fv%3D'. $youtube_id;
$previewimage_target_filename = 'youtube-'. $youtube_id .'.jpg';

$show_title = 'REX_VALUE[2]' === 'true' ? true : false; /** @phpstan-ignore-line */

if ('' !== $youtube_id) {
    // Copy preview image
    if (!is_dir(rex_path::addonCache('d2u_helper')) || !file_exists(rex_path::addonCache('d2u_helper', $previewimage_target_filename))) {
        if (!is_dir(rex_path::addonCache('d2u_helper'))) {
            rex_dir::create(rex_path::addonCache('d2u_helper'));
        }
        copy($youtube_previewimage_url, rex_path::addonCache('d2u_helper', $previewimage_target_filename));
    }

    echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' mb-2">';
    if ($show_title) { /** @phpstan-ignore-line */
        echo '<div class="same-height youtubeTitleWrapper">';
    }
        echo '<div id="youtubeWrapper-'. $youtube_id .'" class="youtubeWrapper">';
            echo '<div class="youtube-click-overlay" id="youtube-click-overlay-'. $youtube_id .'" data-youtube-id="'. $youtube_id .'" data-youtube-url="'. $youtube_url .'"></div>';
            echo '<div class="youtube-play-button" id="youtube-play-button-'. $youtube_id .'">';
                echo '<button type="button" id="play-'. $youtube_id .'">';
                    echo '<span class="d-none">'. \Sprog\Wildcard::get('d2u_helper_module_06_play') .'</span>';
                    echo '<svg aria-hidden="true" focusable="false" viewBox="0 0 18 18"><path d="M15.562 8.1L3.87.225c-.818-.562-1.87 0-1.87.9v15.75c0 .9 1.052 1.462 1.87.9L15.563 9.9c.584-.45.584-1.35 0-1.8z"></path></svg>';
                echo '</button>';
            echo '</div>';
            echo '<iframe width="1600" height="900" src="" id="player-'. $youtube_id .'" frameborder="0" webkitAllowFullScreen moziallowfullscreen allowfullscreen style="background: url('. rex_media_manager::getUrl('d2u_helper_module_06-1_preview', $previewimage_target_filename) .') center; background-size: contain;"></iframe>';
        echo '</div>';
        if ($show_title) { /** @phpstan-ignore-line */
            $video_info_raw = file_get_contents($youtube_videoinfo_url);
            if (false !== $video_info_raw) {
                $video_info = json_decode($video_info_raw);
                echo '<h2>'. $video_info->title .'</h2>'; /** @phpstan-ignore-line */
            }
            echo '</div>';
        }
        echo '<div class="youtube-gdpr-hint youtube-gdpr-hint-overlay" id="youtube-gdpr-hint-'. $youtube_id .'">';
            echo '<p>'. \Sprog\Wildcard::get('d2u_helper_module_06_gdpr_hint') .'</p>';
        echo '</div>';
    echo '</div>';
} else {
    if (rex::isBackend()) {
        echo "<p class='error'>Die YouTube URL ist ungültig!</p>";
    }
}