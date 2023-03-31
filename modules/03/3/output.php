<?php
$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$picture = 'REX_MEDIA[1]';

$viewer_id = random_int(0, getrandmax());

if (!function_exists('includePhotoSphereViewerJS')) {
    /**
     * Echo Photo Sphere Viewer JS files.
     */
    function includePhotoSphereViewerJS(): void
    {
        $three_js = 'modules/03-3/three.min.js';
        echo '<script src="'. rex_url::addonAssets('d2u_helper', $three_js) .'?buster='. filemtime(rex_path::addonAssets('d2u_helper', $three_js)) .'"></script>' . PHP_EOL;
        $photosphereviewer_js = 'modules/03-3/photosphereviewer.min.js';
        echo '<script src="'. rex_url::addonAssets('d2u_helper', $photosphereviewer_js) .'?buster='. filemtime(rex_path::addonAssets('d2u_helper', $photosphereviewer_js)) .'"></script>' . PHP_EOL;

        $photosphereviewer_css = 'modules/03/3/style.css';
        if (rex::isBackend() && file_exists(rex_path::addon('d2u_helper', $photosphereviewer_css))) {
            echo '<style>'. file_get_contents(rex_path::addon('d2u_helper', $photosphereviewer_css)) .'</style>';
        }
    }

    includePhotoSphereViewerJS();
}

?>
<div class="col-12 col-sm-<?= $cols_sm ?> col-md-<?= $cols_md ?> col-lg-<?= $cols_lg . $offset_lg ?>">
    <div id="viewer_<?= $viewer_id ?>" style="height: 75vh;"></div>

    <script>
        const viewer = new PhotoSphereViewer.Viewer({
            container: document.querySelector('#viewer_<?= $viewer_id ?>'),
            panorama: '<?= rex_url::media($picture) ?>',
            navbar: [
                'zoom',
                'move',
                'fullscreen'
            ]
        });
    </script>
</div>