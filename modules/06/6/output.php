<?php
$cols_sm = 0 === (int) 'REX_VALUE[20]' ? 12 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$cols_md = 0 === (int) 'REX_VALUE[19]' ? 12 : (int) 'REX_VALUE[19]'; /** @phpstan-ignore-line */
$cols_lg = 0 === (int) 'REX_VALUE[18]' ? 12 : (int) 'REX_VALUE[18]'; /** @phpstan-ignore-line */
$offset_lg_cols = (int) 'REX_VALUE[17]';
$offset_lg = '';
if ($offset_lg_cols > 0) { /** @phpstan-ignore-line */
    $offset_lg = ' me-lg-auto ms-lg-auto ';
}

$rawSource = (string) 'REX_VALUE[1]'; /** @phpstan-ignore-line */
// Restrict iframe URL to safe schemes (http/https) or relative paths to prevent javascript: / data: injection
$source = '';
if ('' !== $rawSource) {
    if (preg_match('#^(?:https?:)?//#i', $rawSource) || str_starts_with($rawSource, '/')) {
        $source = $rawSource;
    }
}

if ('' !== $source) {
    echo '<div class="col-12 col-sm-'. $cols_sm .' col-md-'. $cols_md .' col-lg-'. $cols_lg . $offset_lg .' abstand">';
    $max_width = (int) 'REX_VALUE[2]'; /** @phpstan-ignore-line */
    $max_height = (int) 'REX_VALUE[3]'; /** @phpstan-ignore-line */
    $allowed_overflow = ['auto', 'scroll', 'hidden'];
    $overflow = in_array((string) 'REX_VALUE[4]', $allowed_overflow, true) ? (string) 'REX_VALUE[4]' : 'auto'; /** @phpstan-ignore-line */

    $frame_id = 'frame_'. random_int(0, getrandmax());

    echo '<iframe src="'. rex_escape($source, 'html_attr') .'" width="'. $max_width .'" height="'. $max_height .'" style="overflow: '. $overflow .';" class="d2u_iframe" id="'. rex_escape($frame_id, 'html_attr') .'">';
?>
	  <p>Ihr Browser kann leider keine eingebetteten Frames anzeigen:
		  Sie k&ouml;nnen die eingebettete Seite &uuml;ber den folgenden
		  Verweis aufrufen: <a href="<?= rex_escape($source, 'html_attr') ?>"><?= rex_escape($source) ?></a></p>
	  <p>Your browser does not support embedded Frames:
		 You can open the Link here: <a href="<?= rex_escape($source, 'html_attr') ?>"><?= rex_escape($source) ?></a></p>
<?php
    echo '</iframe>';
    echo '</div>';
}
