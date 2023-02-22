<?php
$cols = 'REX_VALUE[20]' == '' ? 8 : 'REX_VALUE[20]';
$offset_lg_cols = (int) 'REX_VALUE[17]';
$offset_lg = '';
if ($offset_lg_cols > 0) { /** @phpstan-ignore-line */
    $offset_lg = ' mr-lg-auto ml-lg-auto ';
}
$heading = 'REX_VALUE[1]';
$same_height = 'REX_VALUE[5]' == 'true' ? 'same-height ' : '';
$show_title = ('REX_VALUE[9]' == 'true');

// Link
$link_type = 'REX_VALUE[7]';
$link_url = '';
if ('link' == $link_type) {
    $link_url = 'REX_VALUE[8]';
} elseif ('download' == $link_type) {
    $link_url = rex_url::media('REX_MEDIA[2]');
} elseif ('article' == $link_type) {
    $article_id = 'REX_LINK[1]';
    if ($article_id > 0 && rex_article::get($article_id) instanceof rex_article) {
        $link_url = rex_getUrl($article_id);
    }
}

$text_1 = 'REX_VALUE[id=2 output="html"]';
$show_text_2 = 'REX_VALUE[10]' == 'true' ? true : false;
$text_2 = 'REX_VALUE[id=11 output="html"]';

// Picture
$picture = 'REX_MEDIA[1]';
$picture_cols = 'REX_VALUE[6]' == '' ? 4 : 'REX_VALUE[6]';
$picture_type = 'REX_VALUE[3]';
$picture_position = 'REX_VALUE[4]';

$container_classes = 'col-12 col-lg-'. $cols . $offset_lg;
if ('left' == $picture_position) {
    $container_classes = 'col-12 col-lg-'. $cols . $offset_lg;
}
?>
<div class="<?= $container_classes ?> abstand">
	<div class="<?= $same_height ?>module-box wysiwyg_content">
		<div class="row">
			<?php
                // Picture
                $html_picture = 'left' == $picture_position || 'right' == $picture_position ? '<div class="col-12 col-sm-'. (2 == $picture_cols ? 4 : 6) .' col-md-'. $picture_cols .'">' : '<div class="col-12">';

                if ('REX_MEDIA[1]' != '') {
                    if ('' != $link_url) {
                        $html_picture .= '<a href="'. $link_url .'">';
                    }
                    $media = rex_media::get('REX_MEDIA[1]');
                    $html_picture .= '<figure>';
                    $html_picture .= '<img src="';
                    if ('' == $picture_type) {
                        $html_picture .= rex_url::media($picture);
                    } else {
                        $html_picture .= 'index.php?rex_media_type='. $picture_type .'&rex_media_file='. $picture;
                    }
                    $html_picture .= '" alt="'. $media->getValue('title') .'" title="'. $media->getValue('title') .'">';
                    if ($show_title && ('' != $media->getValue('title') || ($media->hasValue('med_title_'. rex_clang::getCurrentId()) && '' != $media->getValue('med_title_'. rex_clang::getCurrentId())))) {
                        $med_title = ($media->hasValue('med_title_'. rex_clang::getCurrentId()) && '' != $media->getValue('med_title_'. rex_clang::getCurrentId())) ? $media->getValue('med_title_'. rex_clang::getCurrentId()) : $media->getValue('title');
                        $html_picture .= '<figcaption class="d2u_figcaption">'. $med_title .'</figcaption>';
                    }
                    $html_picture .= '</figure>';
                    if ('' != $link_url) {
                        $html_picture .= '</a>';
                    }
                }

                $html_picture .= '</div>';
                if ('left' == $picture_position || 'top' == $picture_position) {
                    echo $html_picture;
                }

                // Heading and Text
                if ('left' == $picture_position || 'right' == $picture_position) {
                    echo '<div class="col-12 col-sm-'. (2 == $picture_cols ? 8 : 6) .' col-md-'. (12 - $picture_cols) .'">';
                } else {
                    echo '<div class="col-12">';
                }

                if ('' != $heading) {
                    if ('' != $link_url) {
                        echo '<a href="'. $link_url .'">';
                    }
                    echo '<b>'. $heading .'</b><br>';
                    if ('' != $link_url) {
                        echo '</a>';
                    }
                }
                if ($text_1) {
                    echo d2u_addon_frontend_helper::prepareEditorField($text_1);
                }

                echo '</div>';

                // Picture right
                if ('right' == $picture_position || 'bottom' == $picture_position) {
                    echo $html_picture;
                }

                if ($show_text_2 && $text_2) {
                    $id = random_int(0, getrandmax());
                    echo '<div class="col-12">';
                    echo '<button id="button_'. $id .'" class="text-toggler angle-down" onclick="toggle_text_'. $id .'()">'. \Sprog\Wildcard::get('d2u_helper_modules_show_more') .'</button>';
                    echo '<div id="second_text_'. $id .'" class="hide-text">';
                    echo d2u_addon_frontend_helper::prepareEditorField($text_2);
                    echo '</div>';
                    echo '</div>';

                    echo '<script>';
                    echo 'function toggle_text_'. $id .'() {'. PHP_EOL;
                        echo '$("#second_text_'. $id .'").slideToggle();'. PHP_EOL;
                        echo 'if($("#button_'. $id .'").hasClass("angle-down")) {';
                            echo '$("#button_'. $id .'").fadeOut(500, function() { $(this).html("'. addslashes(\Sprog\Wildcard::get('d2u_helper_modules_show_less')) .'").removeClass("angle-down").addClass("angle-up").fadeIn(500); });';
                        echo '}'. PHP_EOL;
                        echo 'else {';
                            echo '$("#button_'. $id .'").fadeOut(500, function() { $(this).html("'. addslashes(\Sprog\Wildcard::get('d2u_helper_modules_show_more')) .'").removeClass("angle-up").addClass("angle-down").fadeIn(500); });';
                        echo '}'. PHP_EOL;
                    echo '}'. PHP_EOL;
                    echo '</script>';
                }
            ?>
		</div>
	</div>
</div>