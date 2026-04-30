<?php
    $picture_type = 'REX_VALUE[3]';
    $same_height = 'REX_VALUE[17]' === 'true' ? ' same-height' : ''; /** @phpstan-ignore-line */
?>
<div class="col-12 col-sm-<?= (int) 'REX_VALUE[20]' ?> col-md-<?= (int) 'REX_VALUE[19]' ?> col-lg-<?= (int) 'REX_VALUE[18]' ?> abstand">
	<div class="module-box<?= $same_height ?>">
		<?php
            if ('REX_MEDIA[1]' !== '') { /** @phpstan-ignore-line */
                $media = rex_media::get('REX_MEDIA[1]');
                if ($media instanceof rex_media) {
                    $title = (string) $media->getValue('title');
                    echo '<img src="'. rex_escape('index.php?rex_media_type='. $picture_type .'&rex_media_file=REX_MEDIA[1]', 'html_attr') .'" alt="'.
                        rex_escape($title, 'html_attr') .'" title="'. rex_escape($title, 'html_attr') .'" class="module-box-pic">';
                }
            }
            if ('REX_VALUE[id=1 isset=1]' !== '') { /** @phpstan-ignore-line */
                echo 'REX_VALUE[id=1 output=html]';
            }
        ?>
	</div>
</div>