<?php
$cols = 0 === (int) 'REX_VALUE[20]' ? 8 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' me-lg-auto ms-lg-auto ' : ''; /** @phpstan-ignore-line */

$heading = (string) 'REX_VALUE[1]'; /** @phpstan-ignore-line */
// Whitelist HTML tag for headline (defense-in-depth, input is a select)
$allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'b', 'p'];
$heading_type = in_array((string) 'REX_VALUE[2]', $allowed_tags, true) ? (string) 'REX_VALUE[2]' : 'h2'; /** @phpstan-ignore-line */

$picture = 'REX_MEDIA[1]';
$rex_picture = rex_media::get($picture);

$article_id_1 = (int) 'REX_LINK[1]';
$rex_article_1 = rex_article::get($article_id_1);
$article_id_2 = (int) 'REX_LINK[2]';
$rex_article_2 = rex_article::get($article_id_2);

?>
<div class="col-12 col-lg-<?= $cols . $offset_lg ?>">
    <div class="d2u_helper_mod_02_9_wrapper">
        <?php
            if ($rex_picture instanceof rex_media) {
                echo '<img src="'. rex_escape(rex_url::media($picture), 'html_attr') .'" alt="'. rex_escape((string) $rex_picture->getTitle(), 'html_attr') .'">';
            }
            echo '<div class="d2u_helper_mod_02_9_content d-flex align-items-center">'. PHP_EOL;

            echo '<div class="row">'. PHP_EOL;

            echo '<div class="col-6">';
            echo '<'. $heading_type .'>'. rex_escape($heading) .'</'. $heading_type .'>';
            echo '</div>'. PHP_EOL;
            if ($rex_article_1 instanceof rex_article) {
                $name1 = (string) $rex_article_1->getName();
                $url1 = (string) $rex_article_1->getUrl();
                echo '<div class="col-12">&nbsp;</div>';
                echo '<div class="col-12">';
                echo '<a href="'. rex_escape($url1, 'html_attr') .'" title="'. rex_escape($name1, 'html_attr') .'">'
                .'<button>'. rex_escape($name1)
                .'<span class="arrow_right"></span></button></a>';
                echo '</div>';
            }
            if ($rex_article_2 instanceof rex_article) {
                $name2 = (string) $rex_article_2->getName();
                $url2 = (string) $rex_article_2->getUrl();
                echo '<div class="col-12">&nbsp;</div>';
                echo '<div class="col-12">';
                echo '<a href="'. rex_escape($url2, 'html_attr') .'" title="'. rex_escape($name2, 'html_attr') .'">'
                .'<button>'. rex_escape($name2)
                .'<span class="arrow_right"></span></button></a>';
                echo '</div>';
            }
            echo '</div>'. PHP_EOL;

            echo '</div>'. PHP_EOL;
        ?>
    </div>
</div>