<?php
    $picture_type = 'REX_VALUE[3]';
    $same_height = 'REX_VALUE[17]' === 'true' ? ' same-height' : ''; /** @phpstan-ignore-line */
    $heading = (string) 'REX_VALUE[1]';
    $heading_allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'b', 'p'];
    $heading_type = in_array((string) 'REX_VALUE[2]', $heading_allowed_tags, true) ? (string) 'REX_VALUE[2]' : 'b'; /** @phpstan-ignore-line */
?>
<div class="col-12 col-sm-<?= (int) 'REX_VALUE[20]' ?> col-md-<?= (int) 'REX_VALUE[19]' ?> col-lg-<?= (int) 'REX_VALUE[18]' ?> abstand">
	<div class="module-box<?= $same_height ?>">
		<<?= $heading_type ?>><?= rex_escape($heading) ?></<?= $heading_type ?>>
		<?php
            if ('REX_MEDIA[1]' !== '') { /** @phpstan-ignore-line */
                $media = rex_media::get('REX_MEDIA[1]');
                if ($media instanceof rex_media) {
                    $title = (string) $media->getValue('title');
                    echo '<img src="'. rex_escape('index.php?rex_media_type='. $picture_type .'&rex_media_file=REX_MEDIA[1]', 'html_attr') .'" alt="'.
                        rex_escape($title, 'html_attr') .'" title="'. rex_escape($title, 'html_attr') .'">';
                }
            }
        ?>
	</div>
</div>