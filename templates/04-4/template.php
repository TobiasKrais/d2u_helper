<?php
// Get placeholder wildcard tags

use TobiasKrais\D2UCourses\Course;
use TobiasKrais\D2UMachinery\Category;
use TobiasKrais\D2UMachinery\IndustrySector;
use TobiasKrais\D2UMachinery\Machine;
use TobiasKrais\D2UMachinery\UsedMachine;

// SEO stuff
$current_domain = \rex::getServer();
if (rex_addon::get('yrewrite')->isAvailable()) {
    $yrewrite = new rex_yrewrite_seo();
    $current_domain = rex_yrewrite::getCurrentDomain()->getUrl();
}

$current_article = rex_article::getCurrent();

// Get d2u_helper stuff
$d2u_helper = rex_addon::get('d2u_helper');

// Get d2u_machinery stuff
$d2u_machinery = rex_addon::get('d2u_machinery');
$category = false;
$industry_sector = false;
$machine = false;
$used_machine = false;

$url_namespace = TobiasKrais\D2UHelper\FrontendHelper::getUrlNamespace();
$url_id = TobiasKrais\D2UHelper\FrontendHelper::getUrlId();

if (rex_addon::get('d2u_machinery')->isAvailable()) {
    if (filter_input(INPUT_GET, 'machine_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0 || 'machine_id' === $url_namespace) {
        $machine_id = filter_input(INPUT_GET, 'machine_id', FILTER_VALIDATE_INT);
        if (rex_addon::get('url')->isAvailable() && $url_id > 0) {
            $machine_id = $url_id;
        }

        if ($machine_id > 0) {
            $machine = new Machine($machine_id, rex_clang::getCurrentId());
        }
    } elseif (filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0 || 'category_id' === $url_namespace
            || filter_input(INPUT_GET, 'used_rent_category_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0 || 'used_rent_category_id' === $url_namespace
            || filter_input(INPUT_GET, 'used_sale_category_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0 || 'used_sale_category_id' === $url_namespace
        ) {

        // Category for normal machines
        $category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
        $offer_type = '';
        if (\TobiasKrais\D2UHelper\FrontendHelper::isD2UMachineryExtensionActive('used_machines') && filter_input(INPUT_GET, 'used_rent_category_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0) {
            $category_id = filter_input(INPUT_GET, 'used_rent_category_id', FILTER_VALIDATE_INT);
            $offer_type = 'rent';
        } elseif (\TobiasKrais\D2UHelper\FrontendHelper::isD2UMachineryExtensionActive('used_machines') && filter_input(INPUT_GET, 'used_sale_category_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0) {
            $category_id = filter_input(INPUT_GET, 'used_sale_category_id', FILTER_VALIDATE_INT);
            $offer_type = 'sale';
        }
        if (rex_addon::get('url')->isAvailable() && $url_id > 0) {
            $category_id = $url_id;
            if ('used_rent_category_id' === $url_namespace) {
                $offer_type = 'rent';
            } elseif ('used_sale_category_id' === $url_namespace) {
                $offer_type = 'sale';
            }
        }

        if ($category_id > 0) {
            $category = new Category($category_id, rex_clang::getCurrentId());
            $category->setOfferType($offer_type);
        }
    } elseif (\TobiasKrais\D2UHelper\FrontendHelper::isD2UMachineryExtensionActive('industry_sectors') && (filter_input(INPUT_GET, 'industry_sector_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0 || 'industry_sector_id' === $url_namespace)) {
        $industry_sector_id = filter_input(INPUT_GET, 'industry_sector_id', FILTER_VALIDATE_INT);
        if (rex_addon::get('url')->isAvailable() && $url_id > 0) {
            $industry_sector_id = $url_id;
        }

        if ($industry_sector_id > 0) {
            $industry_sector = new IndustrySector($industry_sector_id, rex_clang::getCurrentId());
        }
    } elseif ((filter_input(INPUT_GET, 'used_rent_machine_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0 || 'used_rent_machine_id' === $url_namespace)
            || (filter_input(INPUT_GET, 'used_sale_machine_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0 || 'used_sale_machine_id' === $url_namespace)) {
        $used_machine_id = filter_input(INPUT_GET, 'used_sale_machine_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0 ? filter_input(INPUT_GET, 'used_sale_machine_id', FILTER_VALIDATE_INT) : filter_input(INPUT_GET, 'used_rent_machine_id', FILTER_VALIDATE_INT);
        if (rex_addon::get('url')->isAvailable() && $url_id > 0) {
            $used_machine_id = $url_id;
        }

        if ($used_machine_id > 0) {
            $used_machine = new UsedMachine($used_machine_id, rex_clang::getCurrentId());
            $current_category = $used_machine->category;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?= rex_clang::getCurrent()->getCode() ?>">
<head>
	<?php
        $fragment = new rex_fragment();
        // <head></head>
        echo $fragment->parse('d2u_template_head.php');

        echo '<link rel="stylesheet" href="'. rex_url::frontendController(['template_id' => '04-4', 'd2u_helper' => 'template.css']) .'">';

        // Bootstrap 5 CSS (no jQuery needed)
        echo '<link rel="stylesheet" type="text/css" href="'. rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap5/css/bootstrap.min.css') .'" />';
    ?>
	<?php $fragment = new rex_fragment(); $fragment->setVar('position', 'head'); echo $fragment->parse('d2u_template_darkmode.php'); ?>
</head>

<body>
	<a id="top"></a>
	<?php
    $show_top_logo = 'bottom' === $d2u_helper->getConfig('template_navi_pos', 'top');
        // Logo and optional slogan
        $article_slogan_text = $current_article instanceof rex_article ? trim((string) $current_article->getValue('art_slogan')) : '';
        $slogan_text = '' !== $article_slogan_text ? $article_slogan_text : (string) $d2u_helper->getConfig('template_04_1_slider_slogan_clang_'. rex_clang::getCurrentId(), '');
        $show_top_slogan = 'top' === $d2u_helper->getConfig('template_slogan_position', 'slider') && '' !== $slogan_text;
    if (($show_top_logo && '' !== $d2u_helper->getConfig('template_logo', '')) || $show_top_slogan) {
            echo '<section id="logo-container">';
            echo '<div class="container">';
            echo '<div class="row">';
            echo '<div class="col-12'. ($show_top_slogan && $show_top_logo ? ' top-header-branding' : '') .'">';
            if ($show_top_slogan) {
                echo '<div id="slogan-top">'. nl2br($slogan_text, false) .'</div>';
            }
            if ($show_top_logo && '' !== $d2u_helper->getConfig('template_logo', '')) {
                echo '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'" class="top-header-logo">';
                $media_logo = rex_media::get((string) $d2u_helper->getConfig('template_logo'));
                if ($media_logo instanceof rex_media) {
                    echo '<span class="logo-light">';
                    echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('template_logo')) .'?v='. $media_logo->getUpdateDate() .'" alt="'. $media_logo->getTitle() .'" id="logo">';
                    echo '</span>';
                    $dark_logo = (string) $d2u_helper->getConfig('template_logo_dark', '');
                    if ('' !== $dark_logo) {
                        $media_logo_dark = rex_media::get($dark_logo);
                        if ($media_logo_dark instanceof rex_media) {
                            echo '<span class="logo-dark">';
                            echo '<img src="'. rex_url::media($dark_logo) .'?v='. $media_logo_dark->getUpdateDate() .'" alt="'. $media_logo_dark->getTitle() .'" id="logo-dark">';
                            echo '</span>';
                        }
                    }
                }
                echo '</a>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</section>';
        }

        // Navigation position: configurable via template_navi_pos (top or bottom of slider)
        if ($d2u_helper->isAvailable() && 'top' === $d2u_helper->getConfig('template_navi_pos', 'top')) {
            // Navigation (Bootstrap 5 version)
            $fragment->setVar('show_logo_bar', true, false);
            echo $fragment->parse('d2u_template_bs5_nav.php');
        }

        // Slider (Bootstrap 5 version)
        echo $fragment->parse('d2u_template_bs5_header_slider.php');

        // Navigation, if configured on bottom
        if ($d2u_helper->isAvailable() && 'bottom' === $d2u_helper->getConfig('template_navi_pos', 'top')) {
            // Navigation (Bootstrap 5 version)
            $fragment->setVar('show_logo_bar', false, false);
            echo $fragment->parse('d2u_template_bs5_nav.php');
        }
    ?>
	<section id="breadcrumbs">
		<div class="container subhead">
			<div class="row">
				<?php
                    $show_cart = (rex_addon::get('d2u_courses')->isAvailable() && rex_config::get('d2u_courses', 'article_id_shopping_cart', 0) > 0 && is_callable(Course::existCoursesForCart(...)) && Course::existCoursesForCart()) ? true : false;
                    if ((bool) $d2u_helper->getConfig('show_breadcrumbs', false) || $show_cart) {
                        // Breadcrumbs
                        if ($d2u_helper->hasConfig('show_breadcrumbs') && (bool) $d2u_helper->getConfig('show_breadcrumbs')) {
                            if ($show_cart) {
                                echo '<div class="col-8 col-sm-10 col-lg-11 d-print-none">';
                            } else {
                                echo '<div class="col-12 d-print-none">';
                            }
                            echo '<div id="breadcrumbs-inner"><small>';
                            echo TobiasKrais\D2UHelper\FrontendHelper::getBreadcrumbs();
                            echo '</small></div>';
                            echo '</div>';
                        }
                        // D2U Courses cart
                        $cart_article = rex_article::get((int) rex_config::get('d2u_courses', 'article_id_shopping_cart', 0));
                        if ($show_cart && $cart_article instanceof rex_article) {
                            echo '<div class="col-4 col-sm-2 col-lg-1">';
                            echo '<a href="'. rex_getUrl((int) rex_config::get('d2u_courses', 'article_id_shopping_cart')) .'" class="cart_link">';
                            echo '<div id="cart_symbol">';
                            echo '<img src="'. rex_url::addonAssets('d2u_courses', 'cart_only.png') .'" alt="'. $cart_article->getName() .'">';
                            if (count(\TobiasKrais\D2UCourses\Cart::getCourseIDs()) > 0) {
                                echo '<div id="cart_info">'. count(\TobiasKrais\D2UCourses\Cart::getCourseIDs()) .'</div>';
                            }
                            echo '</div>';
                            echo '</a>';
                            echo '</div>';
                        }
                    }

                    $has_subhead = false;
                    if (false !== $machine || false !== $used_machine ||
                        (false !== $category && (count($category->getMachines()) > 0 || count($category->getUsedMachines()) > 0)) ||
                        ($current_article instanceof rex_article && ($current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_rent', 0) || $current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_sale', 0))) ||
                        ($d2u_helper->hasConfig('subhead_include_articlename') && true === (bool) $d2u_helper->getConfig('subhead_include_articlename'))) {
                        $has_subhead = true;
                    }
                    if ($has_subhead) {
                        echo '<div class="col-12 subhead-nav">';
                    }
                    if (false !== $machine) {
                        echo '<h1 class="subhead">'. ('' === $machine->lang_name ? $machine->name : $machine->lang_name) .'</h1>';
                    } elseif (false !== $category && (count($category->getMachines()) > 0 || count($category->getUsedMachines()) > 0)) {
                        echo '<h1 class="subhead">'. $category->name .'</h1>';
                    } elseif (false !== $used_machine) {
                        echo '<h1 class="subhead">'. $used_machine->manufacturer .' '. $used_machine->name .'</h1>';
                    } elseif ($current_article instanceof rex_article && ($current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_rent', 0) || $current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_sale', 0))) {
                        echo '<h1 class="subhead">'. $current_article->getName() .'</h1>';
                    } elseif ($current_article instanceof rex_article && $d2u_helper->hasConfig('subhead_include_articlename') && true === (bool) $d2u_helper->getConfig('subhead_include_articlename')) {
                        echo '<h1 class="subhead">'. $current_article->getName() .'</h1>';
                    }
                    if ($has_subhead) {
                        echo '</div>';
                    }
                ?>
			</div>
		</div>
	</section>
	<article>
        <div class="container-wrapper">
            <div class="container">
                <div class="row">
                    <?php
                        if (rex_addon::get('d2u_news')->isAvailable() && (bool) $d2u_helper->getConfig('template_show_news_column', false)) {
                    ?>
                        <div class="col-12 col-md-8 col-lg-9">
                            <div class="row" id="content">
                                <?php
                                    // Content follows
                                    echo $this->getArticle(); /** @phpstan-ignore-line */
                                ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="row">
                                <?php
                                    // News / Teaser
                                    $news_category = new \D2U_News\Category((int) rex_config::get('d2u_helper', 'template_news_category'), rex_clang::getCurrentId());
                                    $news = [];
                                    if ($news_category->category_id > 0) {
                                        $news = $news_category->getNews(true);
                                    } else {
                                        $news = \D2U_News\News::getAll(rex_clang::getCurrentId(), 5, true);
                                    }

                                    if (count($news) > 0) {
                                        foreach ($news as $nachricht) {
                                            if (!$nachricht->hide_this_lang) {
                                                echo '<div class="col-12 col-sm-6 col-md-12">';
                                                echo '<div class="newsbox">';
                                                // Heading
                                                if ('' !== trim($nachricht->name)) {
                                                    echo '<div class="newshead">';
                                                    if ('' !== $nachricht->getUrl()) {
                                                        echo '<a href="'. $nachricht->getUrl() .'">';
                                                    }
                                                    echo $nachricht->name;
                                                    if ('' !== $nachricht->getUrl()) {
                                                        echo '</a>';
                                                    }
                                                    echo '</div>';
                                                }

                                                // Picture
                                                if ('' !== $nachricht->picture) {
                                                    echo '<div class="newspic">';
                                                    if ('' !== $nachricht->getUrl()) {
                                                        echo '<a href="'. $nachricht->getUrl() .'">';
                                                    }
                                                    echo '<img src="index.php?rex_media_type=news_preview&rex_media_file='. $nachricht->picture .'" alt="'. $nachricht->name .'" class="listpic">';
                                                    if ('' !== $nachricht->getUrl()) {
                                                        echo '</a>';
                                                    }
                                                    echo '</div>';
                                                }

                                                // Text
                                                if ('' !== $nachricht->teaser) {
                                                    echo '<div class="news">';
                                                    echo TobiasKrais\D2UHelper\FrontendHelper::prepareEditorField($nachricht->teaser);
                                                    echo '</div>';
                                                }

                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    <?php
                        } else {
                            // Content follows
                            echo $this->getArticle(); /** @phpstan-ignore-line */
                        }
                    ?>
                </div>
            </div>
        </div>
	</article>
	<footer class="d-print-none">
		<div class="container footer">
			<a href="#top" id="jump-top" title="<?= \Sprog\Wildcard::get('d2u_helper_template_jump_top') ?>"></a>
			<?= $fragment->parse('d2u_template_bs5_footer.php') ?>
		</div>
	</footer>
	<script>
		// Match height functionality (vanilla JS replacement for jQuery matchHeight)
		document.addEventListener('DOMContentLoaded', function() {
			document.querySelectorAll('[data-match-height]').forEach(function(container) {
				var items = container.querySelectorAll('[data-height-watch]');
				var maxH = 0;
				items.forEach(function(el) { if (el.offsetHeight > maxH) maxH = el.offsetHeight; });
				items.forEach(function(el) { el.style.minHeight = (maxH + 1) + 'px'; });
			});
			var sameHeightEls = document.querySelectorAll('.same-height');
			if (sameHeightEls.length > 0) {
				var maxH = 0;
				sameHeightEls.forEach(function(el) { if (el.offsetHeight > maxH) maxH = el.offsetHeight; });
				sameHeightEls.forEach(function(el) { el.style.minHeight = maxH + 'px'; });
			}
		});
	</script>
	<?= $fragment->parse('d2u_template_cta_box.php') ?>
	<script src="<?= rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap5/js/bootstrap.bundle.min.js') ?>"></script>
	<?php $fragment = new rex_fragment(); $fragment->setVar('position', 'body'); echo $fragment->parse('d2u_template_darkmode.php'); ?>
</body>
</html>
