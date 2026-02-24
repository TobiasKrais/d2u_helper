<?php
$cols = 0 === (int) 'REX_VALUE[20]' ? 8 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' me-lg-auto ms-lg-auto ' : ''; /** @phpstan-ignore-line */

$heading = 'REX_VALUE[1]';
$heading_type = 'REX_VALUE[2]';

$picture = 'REX_MEDIA[1]';
$rex_picture = rex_media::get($picture);

$article_id_1 = (int) 'REX_LINK[1]';
$rex_article_1 = rex_article::get($article_id_1);
$article_id_2 = (int) 'REX_LINK[2]';
$rex_article_2 = rex_article::get($article_id_2);

?>
<div class="col-12 col-lg-<?= $cols . $offset_lg ?>">
    <div class="d2u_helper_mod_02_4_wrapper">
        <?php
            if ($rex_picture instanceof rex_media) {
                echo '<img src="'. rex_url::media($picture) .'">';
            }
            echo '<div class="d2u_helper_mod_02_4_content d-flex align-items-center">'. PHP_EOL;

            echo '<div class="row">'. PHP_EOL;

            echo '<div class="col-6">';
            echo '<'. $heading_type .'>'. $heading .'</'. $heading_type .'>';
            echo '</div>'. PHP_EOL;
            if ($rex_article_1 instanceof rex_article) {
                echo '<div class="col-12">&nbsp;</div>';
                echo '<div class="col-12">';
                echo '<a href="'. $rex_article_1->getUrl() .'" title="'. $rex_article_1->getName() .'">'
                .'<button>'. $rex_article_1->getName()
                .'<span class="arrow_right"></span></button></a>';
                echo '</div>';
            }
            if ($rex_article_2 instanceof rex_article) {
                echo '<div class="col-12">&nbsp;</div>';
                echo '<div class="col-12">';
                echo '<a href="'. $rex_article_2->getUrl() .'" title="'. $rex_article_2->getName() .'">'
                .'<button>'. $rex_article_2->getName()
                .'<span class="arrow_right"></span></button></a>';
                echo '</div>';
            }
            echo '</div>'. PHP_EOL;

            echo '</div>'. PHP_EOL;
        ?>
    </div>
</div>