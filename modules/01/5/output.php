<?php

$cols = 0 === (int) 'REX_VALUE[20]' ? 8 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' me-lg-auto ms-lg-auto ' : ''; /** @phpstan-ignore-line */

$heading = 'REX_VALUE[1]';
$same_height = 'REX_VALUE[5]' === 'true' ? 'same-height ' : ''; /** @phpstan-ignore-line */
$show_title = ('REX_VALUE[9]' === 'true'); /** @phpstan-ignore-line */

// Link
$link_type = 'REX_VALUE[7]';
$link_url = '';
if ('link' === $link_type) { /** @phpstan-ignore-line */
    $link_url = 'REX_VALUE[8]';
} elseif ('download' === $link_type) { /** @phpstan-ignore-line */
    $link_url = rex_url::media('REX_MEDIA[2]');
} elseif ('article' === $link_type) { /** @phpstan-ignore-line */
    $article_id = 'REX_LINK[1]';
    if ($article_id > 0 && rex_article::get($article_id) instanceof rex_article) { /** @phpstan-ignore-line */
        $link_url = rex_getUrl($article_id);
    }
}

$text_1 = 'REX_VALUE[id=2 output="html"]';
$show_text_2 = 'REX_VALUE[10]' === 'true' ? true : false; /** @phpstan-ignore-line */
$text_2 = 'REX_VALUE[id=11 output="html"]';
$picture_center = 'REX_VALUE[12]' === 'true' ? true : false; /** @phpstan-ignore-line */

// Picture
$picture = 'REX_MEDIA[1]';
$picture_cols = 'REX_VALUE[6]' === '' ? 4 : (int) 'REX_VALUE[6]'; /** @phpstan-ignore-line */
$picture_type = 'REX_VALUE[3]';
$picture_position = 'REX_VALUE[4]';

$container_classes = 'col-12 col-lg-'. $cols . $offset_lg;
if ('left' === $picture_position) { /** @phpstan-ignore-line */
    $container_classes = 'col-12 col-lg-'. $cols . $offset_lg;
}

?>
<div class="<?= $container_classes ?> abstand">
	<div class="<?= $same_height ?>module-box wysiwyg_content">
		<div class="row">
			<?php
                // Picture
                $html_picture = 'left' === $picture_position || 'right' === $picture_position ? '<div class="col-12 col-sm-'. (2 === $picture_cols ? 4 : 6) .' col-md-'. $picture_cols .'">' : '<div class="col-12">'; /** @phpstan-ignore-line */

                if ('REX_MEDIA[1]' !== '') { /** @phpstan-ignore-line */
                    $media = rex_media::get('REX_MEDIA[1]');
                    if ($media instanceof rex_media) {
                        if ('' !== $link_url) { /** @phpstan-ignore-line */
                            $html_picture .= '<a href="'. $link_url .'">';
                        }
                        $html_picture .= '<figure>';
                        $html_picture .= '<img src="';
                        if ('' === $picture_type) { /** @phpstan-ignore-line */
                            $html_picture .= rex_url::media($picture);
                        } else {
                            $html_picture .= 'index.php?rex_media_type='. $picture_type .'&rex_media_file='. $picture;
                        }
                        $html_picture .= '" alt="'. $media->getValue('title') .'" title="'. $media->getValue('title') .'">';
                        if ($show_title && ('' !== $media->getValue('title') || ($media->hasValue('med_title_'. rex_clang::getCurrentId()) && '' !== $media->getValue('med_title_'. rex_clang::getCurrentId())))) { /** @phpstan-ignore-line */
                            $med_title = ($media->hasValue('med_title_'. rex_clang::getCurrentId()) && '' !== $media->getValue('med_title_'. rex_clang::getCurrentId())) ? $media->getValue('med_title_'. rex_clang::getCurrentId()) : $media->getValue('title');
                            $html_picture .= '<figcaption class="d2u_figcaption">'. $med_title .'</figcaption>';
                        }
                        $html_picture .= '</figure>';
                        if ('' !== $link_url) { /** @phpstan-ignore-line */
                            $html_picture .= '</a>';
                        }
                    }
                }

                $html_picture .= '</div>';
                if ('left' === $picture_position || 'top' === $picture_position) { /** @phpstan-ignore-line */
                    echo $html_picture;
                }

                // Heading and Text
                if ('left' === $picture_position || 'right' === $picture_position) { /** @phpstan-ignore-line */
                    echo '<div class="col-12 col-sm-'. (2 === $picture_cols ? 8 : 6) .' col-md-'. (12 - $picture_cols)  /** @phpstan-ignore-line */
                        . ($picture_center ? ' d-flex flex-column justify-content-center' : '') .'">';
                } else {
                    echo '<div class="col-12'. ($picture_center ? ' d-flex flex-column justify-content-center' : '') .'">';
                }

                if ('' !== $heading) { /** @phpstan-ignore-line */
                    if ('' !== $link_url) { /** @phpstan-ignore-line */
                        echo '<a href="'. $link_url .'">';
                    }
                    echo '<b>'. $heading .'</b><br>';
                    if ('' !== $link_url) { /** @phpstan-ignore-line */
                        echo '</a>';
                    }
                }
                if ('' !== $text_1) { /** @phpstan-ignore-line */
                    echo TobiasKrais\D2UHelper\FrontendHelper::prepareEditorField($text_1);
                }

                echo '</div>';

                // Picture right
                if ('right' === $picture_position || 'bottom' === $picture_position) { /** @phpstan-ignore-line */
                    echo $html_picture;
                }

                if ($show_text_2 && '' !== $text_2) { /** @phpstan-ignore-line */
                    $id = random_int(0, getrandmax());
                    echo '<div class="col-12">';
                    echo '<button id="button_'. $id .'" class="text-toggler angle-down" onclick="toggle_text_'. $id .'()">'. \Sprog\Wildcard::get('d2u_helper_modules_show_more') .'</button>';
                    echo '<div id="second_text_'. $id .'" class="hide-text">';
                    echo TobiasKrais\D2UHelper\FrontendHelper::prepareEditorField($text_2);
                    echo '</div>';
                    echo '</div>';

                    echo '<script>';
                    echo 'function toggle_text_'. $id .'() {'. PHP_EOL;
                    echo 'var secondText = document.getElementById("second_text_'. $id .'");'. PHP_EOL;
                    echo 'secondText.classList.toggle("hide-text");'. PHP_EOL;
                    echo 'var btn = document.getElementById("button_'. $id .'");'. PHP_EOL;
                    echo 'if(btn.classList.contains("angle-down")) {'. PHP_EOL;
                    echo 'btn.style.opacity = "0";'. PHP_EOL;
                    echo 'setTimeout(function() {'. PHP_EOL;
                    echo 'btn.textContent = "'. addslashes(\Sprog\Wildcard::get('d2u_helper_modules_show_less')) .'";'. PHP_EOL;
                    echo 'btn.classList.remove("angle-down");'. PHP_EOL;
                    echo 'btn.classList.add("angle-up");'. PHP_EOL;
                    echo 'btn.style.opacity = "1";'. PHP_EOL;
                    echo '}, 500);'. PHP_EOL;
                    echo '}'. PHP_EOL;
                    echo 'else {'. PHP_EOL;
                    echo 'btn.style.opacity = "0";'. PHP_EOL;
                    echo 'setTimeout(function() {'. PHP_EOL;
                    echo 'btn.textContent = "'. addslashes(\Sprog\Wildcard::get('d2u_helper_modules_show_more')) .'";'. PHP_EOL;
                    echo 'btn.classList.remove("angle-up");'. PHP_EOL;
                    echo 'btn.classList.add("angle-down");'. PHP_EOL;
                    echo 'btn.style.opacity = "1";'. PHP_EOL;
                    echo '}, 500);'. PHP_EOL;
                    echo '}'. PHP_EOL;
                    echo '}'. PHP_EOL;
                    echo '</script>';
                }
            ?>
		</div>
	</div>
</div>
