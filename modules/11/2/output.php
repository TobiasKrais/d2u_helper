<?php
$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg_cols = (int) 'REX_VALUE[17]';
$offset_lg = '';
if ($offset_lg_cols > 0) { /** @phpstan-ignore-line */
    $offset_lg = ' mr-lg-auto ml-lg-auto ';
}

$picture = 'REX_MEDIA[1]';
$picture_type = 'REX_VALUE[1]' == '' ? 'd2u_helper_sm' : 'REX_VALUE[1]';
$name = 'REX_VALUE[2]';
$street = 'REX_VALUE[5]';
$city = 'REX_VALUE[6]';
$phone = 'REX_VALUE[3]';
$email = 'REX_VALUE[4]';

if (rex::isBackend()) {
    // Ausgabe im BACKEND
?>
	Bildtyp des Media Managers: REX_VALUE[1]<br>
	Name: REX_VALUE[2]<br>
	Telefon: REX_VALUE[3]<br>
	E-Mail: REX_VALUE[4]<br>
<?php
} else {
    // Ausgabe im FRONTEND
    echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' abstand">';
    echo '<div class="row">';

    echo '<div class="col-12 helper-11-2-spacer helper-11-2-wrapper">';

    if ('' != $picture) {
        echo '<div class="helper-11-2-picture-right">';
        $media = rex_media::get($picture);
        $html_picture = '<img src="';
        if ('' == $picture_type) {
            $html_picture .= rex_url::media($picture);
        } else {
            $html_picture .= 'index.php?rex_media_type='. $picture_type .'&rex_media_file='. $picture;
        }
        $html_picture .= '" alt="'. $media->getValue('title') .'" title="'. $media->getValue('title') .'">';
        echo $html_picture;
        echo '</div>';
    }

    $bg_color = ' style="background-color: '. rex_config::get('d2u_helper', 'article_color_h') .'dd"'; // Add "dd" to generate opacity
    echo '<div class="helper-11-2-title-box-left"'. $bg_color .'>';
    echo '<h3 class="helper-11-2-title">'. $name .'</h3>';
    if ('' != $phone || '' != $street || '' != $city) {
        echo '<p class="helper-11-2-teaser">'
            .($street ? $street .'<br>' : '')
            .($city ? $city .'<br>' : '')
            .($phone ? '<span class="fa-icon fa-phone footer-icon"></span>'. $phone : '') .'<br>'
            .'</p>';
    }
    if ('' != $email) {
        echo '<p class="helper-11-2-email"><a href="mailto:'. $email .'"><span class="fa-icon fa-envelope footer-icon"></span>'. $email .'</a></p>';
    }
    echo '</div>';

    echo '</div>'; // class="col...

    echo '</div>';
    echo '</div>';
}
