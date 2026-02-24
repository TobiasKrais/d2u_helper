<?php
    $d2u_helper = rex_addon::get('d2u_helper');
    $article = rex_article::getCurrent();

    $slider_pics_unfiltered = preg_grep('/^\s*$/s', explode(',', (string) $d2u_helper->getConfig('template_04_header_slider_pics_clang_'. rex_clang::getCurrentId())), PREG_GREP_INVERT);
    // get slider pics in case art_slider_pics is set
    if ($article instanceof rex_article) {
        $art_slider_pics_unfiltered = preg_grep('/^\s*$/s', explode(',', (string) $article->getValue('art_slider_pics')), PREG_GREP_INVERT);
        if (is_array($art_slider_pics_unfiltered) && count($art_slider_pics_unfiltered) > 0) {
            $slider_pics_unfiltered = $art_slider_pics_unfiltered;
        }
    }

    $slider_pics = is_array($slider_pics_unfiltered) ? $slider_pics_unfiltered : [];
    if (count($slider_pics) > 0) {
        $media_manager_webp_exists = false;
        $media_manager_webp_type_name = '';
        if ('' !== $d2u_helper->getConfig('template_header_media_manager_type', '')) {
            $sql = rex_sql::factory();
            $sql->setQuery('SELECT * FROM '. rex::getTablePrefix() .'media_manager_type WHERE name = "'. $d2u_helper->getConfig('template_header_media_manager_type', '') .'_webp"');
            if ($sql->getRows() > 0) {
                $media_manager_webp_exists = true;
                $media_manager_webp_type_name = $d2u_helper->getConfig('template_header_media_manager_type') .'_webp';
            }
        }

        $is_full_width = (bool) $d2u_helper->getConfig('template_04_header_slider_pics_full_width', false);
?>
<header>
	<?php
        if (1 === count($slider_pics)) {
            // Single image
            $srcset = $media_manager_webp_exists ? ' srcset="'. rex_media_manager::getUrl($media_manager_webp_type_name, $slider_pics[0]) .' 2000w"' : '';
            $src = '' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) $d2u_helper->getConfig('template_header_media_manager_type', ''), $slider_pics[0]) : rex_url::media($slider_pics[0]);
            echo '<img'. $srcset .' src="'. $src .'" alt="" class="header-slider-pic'. ($is_full_width ? ' header-slider-pic-full-width' : '') .'">';

            // Slogan
            $slogan_text = (string) ($article instanceof rex_article && '' !== $article->getValue('art_slogan') ? $article->getValue('art_slogan') : $d2u_helper->getConfig('template_04_1_slider_slogan_clang_'. rex_clang::getCurrentId()));
            if ('' !== $slogan_text && 'slider' === $d2u_helper->getConfig('template_slogan_position', 'slider')) {
                $slogan = '<span class="slogan-text-row">'. str_replace('<br>', '</span><span class="slogan-text-row">', nl2br($slogan_text, false)) .'</span>';
                echo '<div class="slogan"><div class="container"><span class="slogan-text">'. $slogan .'</span></div></div>';
            }
        } else {
            // Bootstrap 5 Carousel (no jQuery)
            echo '<div id="headerCarousel" class="carousel carousel-fade slide" data-bs-ride="carousel" data-bs-pause="false">';

            // Indicators
            echo '<div class="carousel-indicators">';
            for ($j = 0; $j < count($slider_pics); ++$j) {
                echo '<button type="button" data-bs-target="#headerCarousel" data-bs-slide-to="'. $j .'"'. (0 === $j ? ' class="active" aria-current="true"' : '') .' aria-label="Slide '. ($j + 1) .'"></button>';
            }
            echo '</div>';

            // Slides
            echo '<div class="carousel-inner">';
            for ($k = 0; $k < count($slider_pics); ++$k) {
                $rex_media_slider_pic = rex_media::get($slider_pics[$k]);
                if ($rex_media_slider_pic instanceof rex_media) {
                    echo '<div class="carousel-item'. (0 === $k ? ' active' : '') .'">';
                    $srcset = $media_manager_webp_exists ? ' srcset="'. rex_media_manager::getUrl($media_manager_webp_type_name, $slider_pics[$k]) .' 2000w"' : '';
                    $src = '' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) $d2u_helper->getConfig('template_header_media_manager_type'), $slider_pics[$k]) : rex_url::media($slider_pics[$k]);
                    echo '<img class="d-block w-100"'. $srcset .' src="'. $src .'" alt="'. $rex_media_slider_pic->getTitle() .'"'. ($k > 0 ? ' loading="lazy"' : '') .'>';

                    // Slogan
                    $slogan_text = (string) ($article instanceof rex_article && '' !== $article->getValue('art_slogan') ? $article->getValue('art_slogan') : $d2u_helper->getConfig('template_04_1_slider_slogan_clang_'. rex_clang::getCurrentId()));
                    if ('' !== $slogan_text && 'slider' === $d2u_helper->getConfig('template_slogan_position', 'slider')) {
                        $slogan = '<span class="slogan-text-row">'. str_replace('<br>', '</span><span class="slogan-text-row">', nl2br($slogan_text, false)) .'</span>';
                        echo '<div class="slogan"><div class="container"><span class="slogan-text">'. $slogan .'</span></div></div>';
                    }
                    echo '</div>';
                }
            }
            echo '</div>';

            // Controls
            echo '<button class="carousel-control-prev" type="button" data-bs-target="#headerCarousel" data-bs-slide="prev">';
            echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
            echo '<span class="visually-hidden">Previous</span>';
            echo '</button>';
            echo '<button class="carousel-control-next" type="button" data-bs-target="#headerCarousel" data-bs-slide="next">';
            echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
            echo '<span class="visually-hidden">Next</span>';
            echo '</button>';

            echo '</div>';
        }
    ?>
</header>
<?php
    }
