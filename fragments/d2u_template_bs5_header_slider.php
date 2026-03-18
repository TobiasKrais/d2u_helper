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
        $media_manager_type = (string) $d2u_helper->getConfig('template_header_media_manager_type', '');
        $is_full_width = (bool) $d2u_helper->getConfig('template_04_header_slider_pics_full_width', false);
?>
<header>
	<?php
        if (!$is_full_width) {
            echo '<div id="background">';
            if (1 === count($slider_pics)) {
                $responsive = TobiasKrais\D2UHelper\FrontendHelper::getResponsiveImageAttributes($media_manager_type, $slider_pics[0]);
                echo '<img src="'. $responsive['src'] .'"'. $responsive['srcset_attr'] . $responsive['sizes_attr'] .' alt="" id="background-single-image">';
            } else {
                echo '<div id="headerCarouselbg" class="carousel carousel-fade slide" data-bs-interval="false">';
                echo '<div class="carousel-inner">';
                for ($index = 0; $index < count($slider_pics); ++$index) {
                    $background_media = rex_media::get($slider_pics[$index]);
                    if ($background_media instanceof rex_media) {
                        echo '<div class="carousel-item'. (0 === $index ? ' active' : '') .'">';
                        $responsive = TobiasKrais\D2UHelper\FrontendHelper::getResponsiveImageAttributes($media_manager_type, $slider_pics[$index]);
                        echo '<img class="d-block w-100" src="'. $responsive['src'] .'"'. $responsive['srcset_attr'] . $responsive['sizes_attr'] .' alt="'. $background_media->getTitle() .'"'. ($index > 0 ? ' loading="lazy"' : '') .'>';
                        echo '</div>';
                    }
                }
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '<div class="container">';
            echo '<div class="row d-print-none">';
            echo '<div class="col-12">';
        }

        if (1 === count($slider_pics)) {
            // Single image
            $responsive = TobiasKrais\D2UHelper\FrontendHelper::getResponsiveImageAttributes($media_manager_type, $slider_pics[0]);
            echo '<img src="'. $responsive['src'] .'"'. $responsive['srcset_attr'] . $responsive['sizes_attr'] .' alt="" class="header-slider-pic'. ($is_full_width ? ' header-slider-pic-full-width' : '') .'">';

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
                    $responsive = TobiasKrais\D2UHelper\FrontendHelper::getResponsiveImageAttributes($media_manager_type, $slider_pics[$k]);
                    echo '<img class="d-block w-100" src="'. $responsive['src'] .'"'. $responsive['srcset_attr'] . $responsive['sizes_attr'] .' alt="'. $rex_media_slider_pic->getTitle() .'"'. ($k > 0 ? ' loading="lazy"' : '') .'>';

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
            if ($is_full_width) {
                echo '<button class="carousel-control-prev" type="button" data-bs-target="#headerCarousel" data-bs-slide="prev">';
                echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                echo '<span class="visually-hidden">Previous</span>';
                echo '</button>';
                echo '<button class="carousel-control-next" type="button" data-bs-target="#headerCarousel" data-bs-slide="next">';
                echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                echo '<span class="visually-hidden">Next</span>';
                echo '</button>';
            }

            echo '</div>';
        }

        if (!$is_full_width) {
            echo '</div>';
            echo '</div>';
            echo '</div>';

            if (count($slider_pics) > 1) {
                echo '<script>';
                echo 'document.addEventListener("DOMContentLoaded", function () {';
                echo 'const headerCarousel = document.getElementById("headerCarousel");';
                echo 'const backgroundCarousel = document.getElementById("headerCarouselbg");';
                echo 'if (!headerCarousel || !backgroundCarousel || typeof bootstrap === "undefined") { return; }';
                echo 'const backgroundInstance = bootstrap.Carousel.getOrCreateInstance(backgroundCarousel, { interval: false });';
                echo 'headerCarousel.addEventListener("slide.bs.carousel", function (event) {';
                echo 'if (typeof event.to === "number") { backgroundInstance.to(event.to); }';
                echo '});';
                echo '});';
                echo '</script>';
            }
        }
    ?>
</header>
<?php
    }
