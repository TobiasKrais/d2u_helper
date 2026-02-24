<?php
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
            $category_id = filter_input(INPUT_GET, 'used_rent_category_id', FILTER_VALIDATE_INT);
            $offer_type = 'rent';
        } elseif (rex_plugin::get('d2u_machinery', 'used_machines')->isAvailable() && filter_input(INPUT_GET, 'used_sale_category_id', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]) > 0) {
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

        echo '<link rel="stylesheet" href="'. rex_url::frontendController(['template_id' => '00-2', 'd2u_helper' => 'template.css']) .'">';

        // Bootstrap 5 CSS (no jQuery needed)
        echo '<link rel="stylesheet" type="text/css" href="'. rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap5/css/bootstrap.min.css') .'" />';
    ?>
	<?php $fragment = new rex_fragment(); $fragment->setVar('position', 'head'); echo $fragment->parse('d2u_template_darkmode.php'); ?>
</head>
<body>
	<a id="top"></a>
	<?php
        $header_css = '';
        if ($d2u_helper->hasConfig('template_header_pic')) {
            $header_image = (string) $d2u_helper->getConfig('template_header_pic');
            if ($this->hasValue('art_file') && '' !== (string) $this->getValue('art_file')) { /** @phpstan-ignore-line */
                $header_image = (string) $this->getValue('art_file'); /** @phpstan-ignore-line */
            }
            if ('' !== $header_image) {
                $header_css = 'style="background-image: url('. ('' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) $d2u_helper->getConfig('template_header_media_manager_type', ''), $header_image) : rex_url::media($header_image)) .')"';
            } else {
                $header_css = 'style="height: 100px"';
            }
        }
    ?>
	<header <?= $header_css ?>>
		<?php
            if ($d2u_helper->hasConfig('template_logo') && '' !== $d2u_helper->getConfig('template_logo')) {
        ?>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<a href="<?= rex_getUrl(rex_article::getSiteStartArticleId()) ?>">
						<?php
                        $media_logo = rex_media::get((string) $d2u_helper->getConfig('template_logo'));
                        if ($media_logo instanceof rex_media) {
                            echo '<img src="'. rex_url::media((string) $d2u_helper->getConfig('template_logo')) .'" alt="'. $media_logo->getTitle() .'" id="logo">';
                        }
                        ?>
					</a>
				</div>
			</div>
		</div>
		<?php
            }
        ?>
	</header>
	<?php
        // Navigation (Bootstrap 5 version)
        echo $fragment->parse('d2u_template_bs5_nav.php');
    ?>
	<section id="breadcrumbs" class="subhead">
		<div class="container">
			<div class="row">
				<?php
                    // Breadcrumbs
                    if ($d2u_helper->hasConfig('show_breadcrumbs') && '' !== $d2u_helper->getConfig('show_breadcrumbs')) {
                        echo '<div class="col-12 d-print-none">';
                        echo TobiasKrais\D2UHelper\FrontendHelper::getBreadcrumbs();
                        echo '</div>';
                    }
                ?>
				<div class="col-12 subhead-nav">
					<?php
                        if (false !== $machine) {
                            echo '<h1 class="subhead">'. ('' === $machine->lang_name ? $machine->name : $machine->lang_name) .'</h1>';
                            echo '<ul class="nav nav-pills">';
                            echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link active" href="#tab_overview">'. \Sprog\Wildcard::get('d2u_machinery_overview') .'<div class="active-navi-pill"></div></a></li>';
                            if (rex_plugin::get('d2u_machinery', 'machine_agitator_extension')->isAvailable() && $machine->agitator_type_id > 0 && $machine->category instanceof Category && 'show' === $machine->category->show_agitators) {
                                echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#tab_agitator">'. \Sprog\Wildcard::get('d2u_machinery_agitator') .'<div class="active-navi-pill"></div></a></li>';
                            }
                            if (rex_plugin::get('d2u_machinery', 'machine_features_extension')->isAvailable() && count($machine->feature_ids) > 0) {
                                echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#tab_features">'. \Sprog\Wildcard::get('d2u_machinery_features') .'<div class="active-navi-pill"></div></a></li>';
                            }
                            if ('show' === $d2u_machinery->getConfig('show_techdata', 'hide') && count($machine->getTechnicalData()) > 0) {
                                echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#tab_tech_data"><span class="fa-icon fa-list-ul d-block d-md-none" title="'. \Sprog\Wildcard::get('d2u_machinery_tech_data') .'"></span><span class="d-none d-md-block">'. \Sprog\Wildcard::get('d2u_machinery_tech_data') .'</span><div class="active-navi-pill"></div></a></li>';
                            }
                            if (rex_plugin::get('d2u_machinery', 'machine_usage_area_extension')->isAvailable() && 'show' === $d2u_machinery->getConfig('show_machine_usage_areas', 'hide') && count($machine->usage_area_ids) > 0) {
                                echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#tab_usage_areas"><span class="fa-icon fa-codepen d-block d-md-none" title="'. \Sprog\Wildcard::get('d2u_machinery_usage_areas') .'"></span><span class="d-none d-md-block">'. \Sprog\Wildcard::get('d2u_machinery_usage_areas') .'</span><div class="active-navi-pill"></div></a></li>';
                            }
                            if (rex_plugin::get('d2u_machinery', 'machine_construction_equipment_extension')->isAvailable()) {
                                if (strlen($machine->delivery_set_basic) > 5 || strlen($machine->delivery_set_conversion) > 5 || strlen($machine->delivery_set_full) > 5) {
                                    echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#tab_delivery_set"><span class="fa-icon fa-dropbox d-block d-lg-none" title="'. \Sprog\Wildcard::get('d2u_machinery_construction_equipment_delivery_sets') .'"></span><span class="d-none d-lg-block">'. \Sprog\Wildcard::get('d2u_machinery_construction_equipment_delivery_sets') .'</span><div class="active-navi-pill"></div></a></li>';
                                }
                            }
                            if (rex_plugin::get('d2u_machinery', 'service_options')->isAvailable() && count($machine->service_option_ids) > 0) {
                                echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#tab_service_options">'. \Sprog\Wildcard::get('d2u_machinery_construction_equipment_service') .'<div class="active-navi-pill"></div></a></li>';
                            }
                            if (rex_plugin::get('d2u_machinery', 'equipment')->isAvailable() && count($machine->equipment_ids) > 0) {
                                echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#tab_equipment"><span class="fa-icon fa-plus d-block d-lg-none" title="'. \Sprog\Wildcard::get('d2u_machinery_equipment') .'"></span><span class="d-none d-lg-block">'. \Sprog\Wildcard::get('d2u_machinery_equipment') .'</span><div class="active-navi-pill"></div></a></li>';
                            }
                            echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#tab_request"><span class="fa-icon fa-envelope-o d-block d-lg-none" title="'. \Sprog\Wildcard::get('d2u_machinery_request') .'"></span><span class="d-none d-lg-block">'. \Sprog\Wildcard::get('d2u_machinery_request') .'</span><div class="active-navi-pill"></div></a></li>';
                            echo '</ul>';
                        } elseif (false !== $category && (count($category->getMachines()) > 0 || count($category->getUsedMachines()) > 0)) {
                            echo '<h1 class="subhead">'. $category->name .'</h1>';
                            if ((rex_plugin::get('d2u_machinery', 'machine_usage_area_extension')->isAvailable() && 'show' === $d2u_machinery->getConfig('show_categories_usage_areas', 'hide'))
                                || 'show' === $d2u_machinery->getConfig('show_techdata', 'hide')) {
                                echo '<ul class="nav nav-pills">';
                                echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link active" href="#tab_overview">'. \Sprog\Wildcard::get('d2u_machinery_overview') .'<div class="active-navi-pill"></div></a></li>';
                                if (rex_plugin::get('d2u_machinery', 'machine_usage_area_extension')->isAvailable() && 'show' === $d2u_machinery->getConfig('show_categories_usage_areas', 'hide') && count($category->getUsageAreaMatrix()) > 0) {
                                    echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#tab_usage_areas">'. \Sprog\Wildcard::get('d2u_machinery_usage_areas') .'<div class="active-navi-pill"></div></a></li>';
                                }
                                if ('show' === $d2u_machinery->getConfig('show_techdata', 'hide')) {
                                    echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#tab_tech_data">'. \Sprog\Wildcard::get('d2u_machinery_tech_data') .'<div class="active-navi-pill"></div></a></li>';
                                }
                                echo '</ul>';
                            }
                        } elseif (false !== $used_machine) {
                            echo '<h1 class="subhead">'. $used_machine->manufacturer .' '. $used_machine->name .'</h1>';
                            echo '<ul class="nav nav-pills">';
                            echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link active" href="#tab_overview">'. \Sprog\Wildcard::get('d2u_machinery_overview') .'<div class="active-navi-pill"></div></a></li>';
                            if ('lightbox' === rex_config::get('d2u_machinery', 'used_machines_pic_type', 'slider')) {
                                echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#tab_pics">'. \Sprog\Wildcard::get('d2u_machinery_pics') .'<div class="active-navi-pill"></div></a></li>';
                            }
                            echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link" href="#tab_request"><span class="fa-icon fa-envelope-o d-block d-lg-none" title="'. \Sprog\Wildcard::get('d2u_machinery_request') .'"></span><span class="d-none d-lg-block">'. \Sprog\Wildcard::get('d2u_machinery_request') .'</span><div class="active-navi-pill"></div></a></li>';
                            echo '</ul>';
                        } elseif ($current_article instanceof rex_article && ($current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_rent', 0) || $current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_sale', 0))) {
                            echo '<h1 class="subhead">'. $current_article->getName() .'</h1>';
                            echo '<ul class="nav nav-pills">';
                            $class_active = ' active';
                            if ($current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_sale')) {
                                echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link'. $class_active .'" href="#tab_sale">'. \Sprog\Wildcard::get('d2u_machinery_used_machines_offers_sale') .'<div class="active-navi-pill"></div></a></li>';
                                $class_active = '';
                            }
                            if ($current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_rent')) {
                                echo '<li class="nav-item"><a data-bs-toggle="tab" class="nav-link'. $class_active .'" href="#tab_rent">'. \Sprog\Wildcard::get('d2u_machinery_used_machines_offers_rent') .'<div class="active-navi-pill"></div></a></li>';
                            }
                            echo '</ul>';
                        } elseif ($current_article instanceof rex_article && $d2u_helper->hasConfig('subhead_include_articlename') && true === (bool) $d2u_helper->getConfig('subhead_include_articlename')) {
                            echo '<h1 class="subhead">'. $current_article->getName() .'</h1>';
                        }
                    ?>
				</div>
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
			<a href="#top" id="jump-top" title="<?= \Sprog\Wildcard::get('d2u_helper_template_jump_top') ?>"></a>
			<?= $fragment->parse('d2u_template_bs5_footer.php') ?>
		</div>
	</footer>
	<?= $fragment->parse('d2u_template_cta_box.php') ?>
	<script>
		// Match height functionality (vanilla JS)
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
	<script src="<?= rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap5/js/bootstrap.bundle.min.js') ?>"></script>
	<?php $fragment = new rex_fragment(); $fragment->setVar('position', 'body'); echo $fragment->parse('d2u_template_darkmode.php'); ?>
</body>
</html>
