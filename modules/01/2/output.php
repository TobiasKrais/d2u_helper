<?php

$cols = 0 === (int) 'REX_VALUE[20]' ? 8 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$heading = 'REX_VALUE[1]';
$heading_type = 'REX_VALUE[13]';
if ('' === $heading_type) { $heading_type = 'b'; } /** @phpstan-ignore-line */
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
	<div class="<?= $same_height ?>wysiwyg_content">
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
                    echo '<'. $heading_type .'>'. $heading .'</'. $heading_type .'>';
                    if ('b' === $heading_type) {
                        echo '<br>';
                    }
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
                    echo '<button id="button_'. $id .'" class="text-toggler angle-down" type="button">'. \Sprog\Wildcard::get('d2u_helper_modules_show_more') .'</button>';
                    echo '<div id="second_text_'. $id .'" class="hide-text">';
                    echo TobiasKrais\D2UHelper\FrontendHelper::prepareEditorField($text_2);
                    echo '</div>';
                    echo '</div>';

                    echo '<script nonce="'. rex_response::getNonce() .'">';
                    echo 'document.addEventListener("DOMContentLoaded", function () {'. PHP_EOL;
                        echo 'var button = document.getElementById("button_'. $id .'");'. PHP_EOL;
                        echo 'var text = document.getElementById("second_text_'. $id .'");'. PHP_EOL;
                        echo 'if (!button || !text) { return; }'. PHP_EOL;
                        echo 'var isOpen = false;'. PHP_EOL;
                        echo 'button.addEventListener("click", function () {'. PHP_EOL;
                            echo 'isOpen = !isOpen;'. PHP_EOL;
                            echo 'text.style.display = isOpen ? "block" : "none";'. PHP_EOL;
                            echo 'button.textContent = isOpen ? "'. addslashes(\Sprog\Wildcard::get('d2u_helper_modules_show_less')) .'" : "'. addslashes(\Sprog\Wildcard::get('d2u_helper_modules_show_more')) .'";'. PHP_EOL;
                            echo 'button.classList.toggle("angle-down", !isOpen);'. PHP_EOL;
                            echo 'button.classList.toggle("angle-up", isOpen);'. PHP_EOL;
                        echo '});'. PHP_EOL;
                    echo '});'. PHP_EOL;
                    echo '</script>';
                }
            ?>
		</div>
	</div>
</div>