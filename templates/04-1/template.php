<?php
// Get placeholder wildcard tags

use TobiasKrais\D2UCourses\Course;

$sprog = rex_addon::get('sprog');
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');

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
        if (rex_plugin::get('d2u_machinery', 'used_machines')->isAvailable() && filter_input(INPUT_GET, 'used_rent_category_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0) {
            // Category for used machines (rent)
            $category_id = filter_input(INPUT_GET, 'used_rent_category_id', FILTER_VALIDATE_INT);
            $offer_type = 'rent';
        } elseif (rex_plugin::get('d2u_machinery', 'used_machines')->isAvailable() && filter_input(INPUT_GET, 'used_sale_category_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0) {
            // Category for used machines (sale)
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
    } elseif (rex_plugin::get('d2u_machinery', 'industry_sectors')->isAvailable() && (filter_input(INPUT_GET, 'industry_sector_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0 || 'industry_sector_id' === $url_namespace)) {
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

        echo '<link rel="stylesheet" href="'. rex_url::frontendController(['template_id' => '04-1', 'd2u_helper' => 'template.css']) .'">';

        $jquery_file = 'jquery.min.js';
        echo '<script src="'. rex_url::coreAssets($jquery_file) .'?buster='. filemtime(rex_path::coreAssets($jquery_file)) .'"></script>';
        echo '<link rel="stylesheet" type="text/css" href="'. rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap4/bootstrap.min.css') .'?v=4.6.2" />';
    ?>
</head>

<body>
	<a name="top"></a>
	<?php
        // Logo
        $slogan_text = ($current_article instanceof rex_article && '' !== $current_article->getValue('art_slogan')) ? (string) $current_article->getValue('art_slogan') : (string) $d2u_helper->getConfig('template_04_1_slider_slogan_clang_'. rex_clang::getCurrentId(), '');
        if ('' !== $d2u_helper->getConfig('template_logo', '') || ('top' === $d2u_helper->getConfig('template_slogan_position', 'slider') && '' !== $slogan_text)) {
            echo '<section id="logo-container">';
            echo '<div class="container">';
            echo '<div class="row">';
            echo '<div class="col-12">';
            if ('top' === $d2u_helper->getConfig('template_slogan_position', 'slider') && '' !== $slogan_text) {
                echo '<div id="slogan-top">'. nl2br($slogan_text, false) .'</div>';
            }
            if ('' !== $d2u_helper->getConfig('template_logo', '')) {
                echo '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
                $media_logo = rex_media::get((string) $d2u_helper->getConfig('template_logo'));
                if ($media_logo instanceof rex_media) {
                    echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('template_logo')) .'?v='. $media_logo->getUpdateDate() .'" alt="'. $media_logo->getTitle() .'" id="logo">';
                }
                echo '</a>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</section>';
        }

        // Navi
        echo $fragment->parse('d2u_template_nav.php');

        // Slider
        echo $fragment->parse('d2u_template_header_slider.php');
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
                            echo '<div id="cart_symbol" class="desktop-inner">';
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
                        echo '<h1 class="subhead">'. ('' !== $machine->lang_name ? $machine->name : $machine->lang_name) .'</h1>';
                        echo '<ul class="nav nav-pills">';
                        echo '<li class="nav-item"><a data-toggle="tab" class="nav-link active" href="#tab_overview">'. $tag_open .'d2u_machinery_overview'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
                        if (rex_plugin::get('d2u_machinery', 'machine_agitator_extension')->isAvailable() && $machine->agitator_type_id > 0 && $machine->category instanceof Category && 'show' === $machine->category->show_agitators) {
                            echo '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_agitator">'. $tag_open .'d2u_machinery_agitator'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
                        }
                        if (rex_plugin::get('d2u_machinery', 'machine_features_extension')->isAvailable() && count($machine->feature_ids) > 0) {
                            echo '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_features">'. $tag_open .'d2u_machinery_features'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
                        }
                        if ('show' === $d2u_machinery->getConfig('show_techdata', 'hide') && count($machine->getTechnicalData()) > 0) {
                            echo '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_tech_data"><span class="fa-icon fa-list-ul d-block d-md-none" title="'. $tag_open .'d2u_machinery_tech_data'. $tag_close .'"></span><span class="d-none d-md-block">'. $tag_open .'d2u_machinery_tech_data'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
                        }
                        if (rex_plugin::get('d2u_machinery', 'machine_usage_area_extension')->isAvailable() && 'show' === $d2u_machinery->getConfig('show_machine_usage_areas', 'hide') && count($machine->usage_area_ids) > 0) {
                            echo '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_usage_areas"><span class="fa-icon fa-codepen d-block d-md-none" title="'. $tag_open .'d2u_machinery_usage_areas'. $tag_close .'"></span><span class="d-none d-md-block">'. $tag_open .'d2u_machinery_usage_areas'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
                        }
                        if (rex_plugin::get('d2u_machinery', 'machine_construction_equipment_extension')->isAvailable()) {
                            if (strlen($machine->delivery_set_basic) > 5 || strlen($machine->delivery_set_conversion) > 5 || strlen($machine->delivery_set_full) > 5) {
                                echo '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_delivery_set"><span class="fa-icon fa-dropbox d-block d-lg-none" title="'. $tag_open .'d2u_machinery_construction_equipment_delivery_sets'. $tag_close .'"></span><span class="d-none d-lg-block">'. $tag_open .'d2u_machinery_construction_equipment_delivery_sets'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
                            }
                        }
                        if (rex_plugin::get('d2u_machinery', 'service_options')->isAvailable() && count($machine->service_option_ids) > 0) {
                            echo '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_service_options">'. $tag_open .'d2u_machinery_construction_equipment_service'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
                        }
                        if (rex_plugin::get('d2u_machinery', 'equipment')->isAvailable() && count($machine->equipment_ids) > 0) {
                            echo '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_equipment"><span class="fa-icon fa-plus d-block d-lg-none" title="'. $tag_open .'d2u_machinery_equipment'. $tag_close .'"></span><span class="d-none d-lg-block">'. $tag_open .'d2u_machinery_equipment'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
                        }
                        echo '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_request"><span class="fa-icon fa-envelope-o d-block d-lg-none" title="'. $tag_open .'d2u_machinery_request'. $tag_close .'"></span><span class="d-none d-lg-block">'. $tag_open .'d2u_machinery_request'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
                        echo '</ul>';
                    } elseif (false !== $category && (count($category->getMachines()) > 0 || count($category->getUsedMachines()) > 0)) {
                        echo '<h1 class="subhead">'. $category->name .'</h1>';
                        if ((rex_plugin::get('d2u_machinery', 'machine_usage_area_extension')->isAvailable() && 'show' === $d2u_machinery->getConfig('show_categories_usage_areas', 'hide'))
                            || 'show' === $d2u_machinery->getConfig('show_techdata', 'hide')) {
                            echo '<ul class="nav nav-pills">';
                            echo '<li class="nav-item"><a data-toggle="tab" class="nav-link active" href="#tab_overview">'. $tag_open .'d2u_machinery_overview'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
                            if (rex_plugin::get('d2u_machinery', 'machine_usage_area_extension')->isAvailable() && 'show' === $d2u_machinery->getConfig('show_categories_usage_areas', 'hide') && count($category->getUsageAreaMatrix()) > 0) {
                                echo '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_usage_areas">'. $tag_open .'d2u_machinery_usage_areas'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
                            }
                            if ('show' === $d2u_machinery->getConfig('show_techdata', 'hide')) {
                                echo '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_tech_data">'. $tag_open .'d2u_machinery_tech_data'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
                            }
                            echo '</ul>';
                        }
                    } elseif (false !== $used_machine) {
                        echo '<h1 class="subhead">'. $used_machine->manufacturer .' '. $used_machine->name .'</h1>';
                        echo '<ul class="nav nav-pills">';
                        echo '<li class="nav-item"><a data-toggle="tab" class="nav-link active" href="#tab_overview">'. $tag_open .'d2u_machinery_overview'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
                        if ('lightbox' === rex_config::get('d2u_machinery', 'used_machines_pic_type', 'slider')) {
                            echo '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_pics">'. $tag_open .'d2u_machinery_pics'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
                        }
                        echo '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_request"><span class="fa-icon fa-envelope-o d-block d-lg-none" title="'. $tag_open .'d2u_machinery_request'. $tag_close .'"></span><span class="d-none d-lg-block">'. $tag_open .'d2u_machinery_request'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
                        echo '</ul>';
                    } elseif ($current_article instanceof rex_article && ($current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_rent', 0) || $current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_sale', 0))) {
                        echo '<h1 class="subhead">'. $current_article->getName() .'</h1>';
                        echo '<ul class="nav nav-pills">';
                        $class_active = ' active';
                        if ($current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_sale')) {
                            echo '<li class="nav-item"><a data-toggle="tab" class="nav-link'. $class_active .'" href="#tab_sale">'. $tag_open .'d2u_machinery_used_machines_offers_sale'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
                            $class_active = '';
                        }
                        if ($current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_rent')) {
                            echo '<li class="nav-item"><a data-toggle="tab" class="nav-link'. $class_active .'" href="#tab_rent">'. $tag_open .'d2u_machinery_used_machines_offers_rent'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
                        }
                        echo '</ul>';
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
                        // Content follows
                        echo $this->getArticle(); /** @phpstan-ignore-line */
                    ?>
                </div>
            </div>
        </div>
	</article>
	<footer class="d-print-none">
		<div class="container footer">
			<a href="#top" id="jump-top" title="<?= \Sprog\Wildcard::get('d2u_helper_template_jump_top'); ?>"></a>
			<?= $fragment->parse('d2u_template_footer.php');
            ?>
		</div>
	</footer>
	<script>
		$(window).on("load",
			function(e) {
				$("[data-match-height]").each(
					function() {
						var e=$(this),
							t=$(this).find("[data-height-watch]"),
							n=t.map(function() {
								return $(this).innerHeight();
							}).get(),
							i=Math.max.apply(Math,n);
						t.css("min-height", i+1);
					}
				);
			}
		);

		$(window).on("load", function() {
			var heights = $(".same-height").map(function() {
				return $(this).innerHeight();
			}).get(),

			maxHeight = Math.max.apply(null, heights);

			$(".same-height").css("min-height", maxHeight);
		});
	</script>
	<?= $fragment->parse('d2u_template_cta_box.php');
    ?>
	<script src="<?= rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap4/bootstrap.bundle.min.js') ?>?v=4.6.2"></script>
</body>
</html>
