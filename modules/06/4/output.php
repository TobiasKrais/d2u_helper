<?php

$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$media_filenames_unfiltered = preg_grep('/^\s*$/s', explode(',', REX_MEDIALIST[1]), PREG_GREP_INVERT);
$media_filenames = is_array($media_filenames_unfiltered) ? $media_filenames_unfiltered : [];

echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .'">';
echo rex_plyr::outputMediaPlaylist($media_filenames, 'play-large,play,progress,current-time,duration,restart,volume,mute,pip,fullscreen');
echo '</div>';
if (!function_exists('loadJsPlyr')) {
    function loadJsPlyr()
    {
        echo '<script src="'. rex_url::base('assets/addons/plyr/vendor/plyr/dist/plyr.min.js') .'"></script>';
    }
    loadJsPlyr();
}
echo '<script src="'. rex_url::base('assets/addons/plyr/plyr_playlist.js') .'"></script>';
