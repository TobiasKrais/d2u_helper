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

preg_match(
    '/[\\?\\&]v=([^\\?\\&]+)/',
    'REX_VALUE[1]',
    $matches,
);
$youtube_id = isset($matches[1]) ? trim($matches[1]) : '';
if (0 == strlen($youtube_id) && (str_contains('REX_VALUE[1]', 'youtu.be/') || str_contains('REX_VALUE[1]', '/embed/'))) {
    $youtube_id = trim(substr('REX_VALUE[1]', strrpos('REX_VALUE[1]', '/') + 1));
}
$youtube_url = 'https://www.youtube-nocookie.com/embed/'. $youtube_id .'?autoplay=1';
$youtube_previewimage_url = 'https://img.youtube.com/vi/'. $youtube_id .'/hqdefault.jpg';
$youtube_videoinfo_url = 'https://www.youtube.com/oembed?format=json&url=https%3A//youtube.com/watch%3Fv%3D'. $youtube_id;
$previewimage_target_filename = 'youtube-'. $youtube_id .'.jpg';

$show_title = 'REX_VALUE[2]' == 'true' ? true : false;

if ('' != $youtube_id) {
    // Copy preview image
    if (!is_dir(rex_path::addonCache('d2u_helper')) || !file_exists(rex_path::addonCache('d2u_helper', $previewimage_target_filename))) {
        if (!is_dir(rex_path::addonCache('d2u_helper'))) {
            mkdir(rex_path::addonCache('d2u_helper'), 0o755, true);
        }
        copy($youtube_previewimage_url, rex_path::addonCache('d2u_helper', $previewimage_target_filename));
    }

    echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
    if ($show_title) {
        echo '<div class="same-height youtubeTitleWrapper">';
    }
?>
	<div id="youtubeWrapper-<?= $youtube_id ?>" class="youtubeWrapper">
		<div class="youtube-play-button" id="youtube-play-button-<?= $youtube_id ?>">
			<button type="button" id="play-<?= $youtube_id ?>" >
				<svg aria-hidden="true" focusable="false" viewBox="0 0 18 18"><path d="M15.562 8.1L3.87.225c-.818-.562-1.87 0-1.87.9v15.75c0 .9 1.052 1.462 1.87.9L15.563 9.9c.584-.45.584-1.35 0-1.8z"></path></svg>
			</button>
		</div>
		<div class="youtube-gdpr-hint" id="youtube-gdpr-hint-<?= $youtube_id ?>">
			<p><?= \Sprog\Wildcard::get('d2u_helper_module_06_gdpr_hint') ?></p>
		</div>
		<iframe width="1600" height="900" src="" id="player-<?= $youtube_id ?>" frameborder="0" webkitAllowFullScreen moziallowfullscreen allowfullscreen
				style="background: url(<?= rex_media_manager::getUrl('d2u_helper_module_06-1_preview', $previewimage_target_filename) ?>) center; background-size: cover;"></iframe>
	</div>
<?php
    if ($show_title) {
        $video_info = json_decode(file_get_contents($youtube_videoinfo_url));
        echo '<h2>'. $video_info->title .'</h2>';
        echo '</div>';
    }
?>
	<script>
		document.getElementById('play-<?= $youtube_id ?>').addEventListener('click', function() {
			loadYoutubeVideo('<?= $youtube_url ?>', '<?= $youtube_id ?>') ;
		});
	</script>
<?php
    echo '</div>';
} else {
    if (rex::isBackend()) {
        echo "<p class='error'>Die YouTube URL ist ungültig!</p>";
    }
}
