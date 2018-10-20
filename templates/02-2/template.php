<?php
// Get placeholder wildcard tags
$sprog = rex_addon::get("sprog");
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
$d2u_helper = rex_addon::get("d2u_helper");

// Get d2u_machinery stuff
$d2u_machinery = rex_addon::get("d2u_machinery");
$category = FALSE;
$industry_sector = FALSE;
$machine = FALSE;
$used_machine = FALSE;
$urlParamKey = "";
if(rex_addon::get("url")->isAvailable()) {
	$url_data = UrlGenerator::getData();
	$urlParamKey = isset($url_data->urlParamKey) ? $url_data->urlParamKey : "";
}
if(rex_addon::get('d2u_machinery')->isAvailable()) {
	if(filter_input(INPUT_GET, 'machine_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "machine_id")) {
		$machine_id = filter_input(INPUT_GET, 'machine_id', FILTER_VALIDATE_INT);
		if(rex_addon::get("url")->isAvailable() && UrlGenerator::getId() > 0) {
			$machine_id = UrlGenerator::getId();
		}
		
		if($machine_id > 0) {
			$machine = new Machine($machine_id, rex_clang::getCurrentId());
		}
	}
	else if(filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "category_id")
			|| filter_input(INPUT_GET, 'used_rent_category_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "used_rent_category_id")
			|| filter_input(INPUT_GET, 'used_sale_category_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "used_sale_category_id")
		) {

		// Category for normal machines
		$category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
		$offer_type = '';
		if(rex_plugin::get("d2u_machinery", "used_machines")->isAvailable() && filter_input(INPUT_GET, 'used_rent_category_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0) {
			// Category for used machines (rent)
			$category_id = filter_input(INPUT_GET, 'used_rent_category_id', FILTER_VALIDATE_INT);
			$offer_type = 'rent';
		}
		elseif(rex_plugin::get("d2u_machinery", "used_machines")->isAvailable() && filter_input(INPUT_GET, 'used_sale_category_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0) {
			// Category for used machines (sale)
			$category_id = filter_input(INPUT_GET, 'used_sale_category_id', FILTER_VALIDATE_INT);
			$offer_type = 'sale';
		}
		if(rex_addon::get("url")->isAvailable() && UrlGenerator::getId() > 0) {
			$category_id = UrlGenerator::getId();
			if($urlParamKey === "used_rent_category_id") {
				$offer_type = 'rent';
			}
			elseif($urlParamKey === "used_sale_category_id") {
				$offer_type = 'sale';
			}
		}

		if($category_id > 0) {
			$category = new Category($category_id, rex_clang::getCurrentId());
			$category->setOfferType($offer_type);
		}
	}
	else if(rex_plugin::get("d2u_machinery", "industry_sectors")->isAvailable() && (filter_input(INPUT_GET, 'industry_sector_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "industry_sector_id"))) {
		$industry_sector_id = filter_input(INPUT_GET, 'industry_sector_id', FILTER_VALIDATE_INT);
		if(rex_addon::get("url")->isAvailable() && UrlGenerator::getId() > 0) {
			$industry_sector_id = UrlGenerator::getId();
		}
		
		if($industry_sector_id > 0) {
			$industry_sector = new IndustrySector($industry_sector_id, rex_clang::getCurrentId());
		}
	}
	else if((filter_input(INPUT_GET, 'used_rent_machine_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "used_rent_machine_id"))
			|| (filter_input(INPUT_GET, 'used_sale_machine_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "used_sale_machine_id"))) {
		$used_machine_id = filter_input(INPUT_GET, 'used_sale_machine_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 ? filter_input(INPUT_GET, 'used_sale_machine_id', FILTER_VALIDATE_INT) : filter_input(INPUT_GET, 'used_rent_machine_id', FILTER_VALIDATE_INT);
		if(rex_addon::get("url")->isAvailable() && UrlGenerator::getId() > 0) {
			$used_machine_id = UrlGenerator::getId();
		}
		
		if($used_machine_id > 0) { 
			$used_machine = new UsedMachine($used_machine_id, rex_clang::getCurrentId());
			$current_category = $used_machine->category;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="<?php echo rex_clang::getCurrent()->getCode(); ?>">
<head>
	<meta charset="utf-8">
	<base href="<?php echo $current_domain; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		print d2u_addon_frontend_helper::getMetaTags();
	?>
	<link rel="stylesheet" href="index.php?template_id=02-2&d2u_helper=template.css">
	<?php
		if(file_exists(rex_path::media('favicon.ico'))) {
			print '<link rel="icon" href="'. rex_url::media('favicon.ico') .'">';
		}
	?>
</head>

<body>
	<nav>
		<div class="container d-print-none navigation">
			<div class="row">
				<?php
					// Navi
					print '<div class="col-'. ($d2u_helper->getConfig("template_logo", "") != "" ? '4' : '12') .' col-md-'. ($d2u_helper->getConfig("template_logo", "") != "" ? '8' : '12') .' col-lg-'. ($d2u_helper->getConfig("template_logo", "") != "" ? '9' : '12') .'">';

					print '<div class="navi">';
					if(rex_addon::get('d2u_helper')->isAvailable()) {
						d2u_mobile_navi::getResponsiveMultiLevelMobileMenu();
						d2u_mobile_navi::getResponsiveMultiLevelDesktopMenu();
					}
					print '</div>';

					// Languages
					$clangs = rex_clang::getAll(TRUE);
					if(count($clangs) > 1) {
						print '<div id="langchooser" class="desktop-inner">';
						$alternate_urls = d2u_addon_frontend_helper::getAlternateURLs();
						foreach ($clangs as $clang) {
							if($clang->getId() != rex_clang::getCurrentId()) {
								print '<a href="'. (isset($alternate_urls[$clang->getId()]) ? $alternate_urls[$clang->getId()] : rex_getUrl(rex_article::getSiteStartArticleId(), $clang->getId())) .'">';
								if($clang->getValue('clang_icon') != "") {
									print '<img src="'. rex_url::media($clang->getValue('clang_icon')) .'" alt="'. $clang->getName() .'">';
								}
								print '</a>';							
							}
						}
						print '</div>';
					}

					print '</div>';

					// Logo
					if($d2u_helper->getConfig("template_logo", "") != "") {
						print '<div class="col-8 col-md-4 col-lg-3">';
						print '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
						$media_logo = rex_media::get($d2u_helper->getConfig("template_logo"));
						if($media_logo instanceof rex_media) {
							print '<img src="'. rex_url::media($d2u_helper->getConfig("template_logo")) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo">';
						}
						print '</a>';
						print '</div>';
					}
				?>
			</div>
		</div>
	</nav>
	<?php
		$slider_pics = preg_grep('/^\s*$/s', explode(",", $d2u_helper->getConfig('template_02_2_header_slider_pics_clang_'. rex_clang::getCurrentId())), PREG_GREP_INVERT);
		if(count($slider_pics) > 0) {
	?>
	<header>
		<?php 
			if($d2u_helper->getConfig("template_02_2_header_slider_pics_full_width", FALSE) == FALSE) {
				// START Only if slider background slider is shown
		?>
		<div id="background">
			<?php
				if(count($slider_pics) == 1) {
					print '<img src="'. rex_url::media($slider_pics[0]) .'" alt="" style="max-width:100%;">';
				}
				else if(count($slider_pics) > 1) {
					// Slider
					print '<div id="headerCarouselbg" class="carousel carousel-fade slide carousel-sync" data-pause="false">';

					// Wrapper for slides
					print '<div class="carousel-inner">';
					for($i = 0; $i < count($slider_pics); $i++) {
						print '<div class="carousel-item';
						if($i == 0) {
							print ' active';
						}
						print '">';
						print '<img class="d-block w-100" src="'. rex_url::media($slider_pics[$i]) .'" alt="">';
						print '</div>';
					}
					print '</div>';
					print '</div>';
				}
			?>			
		</div>
		<div class="container">
			<div class="row d-print-none">
				<div class="col-12">
					<?php
			}  // END Only if slider background slider is shown
						if(count($slider_pics) == 1) {
							print '<img src="'. rex_url::media($slider_pics[0]) .'" alt="" style="max-width:100%;">';
						}
						else if(count($slider_pics) > 1) {
							// Slider
							print '<div id="headerCarousel" class="carousel carousel-fade slide carousel-sync" data-ride="carousel" data-pause="false">';

							// Slider indicators
							print '<ol class="carousel-indicators">';
							for($i = 0; $i < count($slider_pics); $i++) {
								print '<li data-target="#headerCarousel" data-slide-to="'. $i .'"';
								if($i == 0) {
									print 'class="active"';
								}
								print '></li>';
							}
							print '</ol>';

							// Wrapper for slides
							print '<div class="carousel-inner">';
							for($i = 0; $i < count($slider_pics); $i++) {
								print '<div class="carousel-item';
								if($i == 0) {
									print ' active';
								}
								print '">';
								print '<img class="d-block w-100" src="'. rex_url::media($slider_pics[$i]) .'" alt="">';
								print '</div>';
							}
							print '</div>';

							// Left and right controls
							if($d2u_helper->getConfig("template_02_2_header_slider_pics_full_width", FALSE)) {
								print '<a class="carousel-control-prev" href="#headerCarousel" role="button" data-slide="prev">';
								print '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
								print '<span class="sr-only">Previous</span>';
								print '</a>';
								print '<a class="carousel-control-next" href="#headerCarousel" role="button" data-slide="next">';
								print '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
								print '<span class="sr-only">Next</span>';
								print '</a>';
							}
							print '</div>';
						}
			if($d2u_helper->getConfig("template_02_2_header_slider_pics_full_width", FALSE) == FALSE) {
				// START Only if slider background slider is shown
					?>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function () {
				// Sync sliders
				jQuery('#headerCarousel').on('slide.bs.carousel', function() {
					jQuery('#headerCarouselbg').carousel('next');
				});

				// Sync events
				$('.carousel-sync').on('slide.bs.carousel', function(ev) {
					// get the direction, based on the event which occurs
					var dir = ev.direction === 'right' ? 'prev' : 'next';
					// get synchronized non-sliding carousels, and make'em sliding
					$('.carousel-sync').not('.sliding').addClass('sliding').carousel(dir);
				});
				$('.carousel-sync').on('slid.bs.carousel', function(ev) {
					// remove .sliding class, to allow the next move
					$('.carousel-sync').removeClass('sliding');
				});
			});
		</script>
		<?php
			} // END Only if slider background slider is shown
		?>
	</header>
	<?php
		}
	?>
	<section id="breadcrumbs">
		<div class="container subhead">
			<div class="row">
				<?php
					$show_cart = (rex_addon::get('d2u_courses')->isAvailable() && rex_config::get('d2u_courses', 'article_id_shopping_cart', 0) > 0) ? TRUE : FALSE;
					if($d2u_helper->getConfig("show_breadcrumbs", FALSE) || $show_cart) {
						// Breadcrumbs
						if($d2u_helper->hasConfig("show_breadcrumbs") && $d2u_helper->getConfig("show_breadcrumbs")) {
							if($show_cart) {
								print '<div class="col-8 col-sm-10 col-lg-11 d-print-none">';
							}
							else {
								print '<div class="col-12 d-print-none">';
							}
							print '<div id="breadcrumbs-inner"><small>';
							print d2u_addon_frontend_helper::getBreadcrumbs();
							print '</small></div>';
							print '</div>';
						}
						// D2U Courses cart
						if($show_cart) {
							print '<div class="col-4 col-sm-2 col-lg-1">';
							print '<a href="'. rex_getUrl(rex_config::get('d2u_courses', 'article_id_shopping_cart')) .'" class="cart_link">';
							print '<div id="cart_symbol" class="desktop-inner">';
							print '<img src="'. rex_url::addonAssets('d2u_courses', 'cart_only.png') .'" alt="'. rex_article::get(rex_config::get('d2u_courses', 'article_id_shopping_cart', 0))->getName() .'">';
							print '</div>';
							print '</a>';
							print '</div>';
						}
					}
				?>
				<div class="col-12 subhead-nav">
					<?php
						if($machine !== FALSE) {
							print '<h1 class="subhead">'. ($machine->lang_name == "" ? $machine->name : $machine->lang_name) .'</h1>';
							print '<ul class="nav nav-pills">';
							print '<li class="nav-item"><a data-toggle="tab" class="nav-link active" href="#tab_overview">'. $tag_open .'d2u_machinery_overview'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							if(rex_plugin::get("d2u_machinery", "machine_agitator_extension")->isAvailable() && $machine->agitator_type_id > 0 && $machine->agitator_type_id > 0 && $machine->category->show_agitators == "show") {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_agitator">'. $tag_open .'d2u_machinery_agitator'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							}
							if(rex_plugin::get("d2u_machinery", "machine_features_extension")->isAvailable() && count($machine->feature_ids) > 0){
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_features">'. $tag_open .'d2u_machinery_features'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							}
							if($d2u_machinery->getConfig("show_techdata", "hide") == "show" && count($machine->getTechnicalData()) > 0){
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_tech_data"><span class="fa-icon fa-list-ul d-block d-md-none" title="'. $tag_open .'d2u_machinery_tech_data'. $tag_close .'"></span><span class="d-none d-md-block">'. $tag_open .'d2u_machinery_tech_data'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
							}
							if(rex_plugin::get("d2u_machinery", "machine_usage_area_extension")->isAvailable() && $d2u_machinery->getConfig("show_machine_usage_areas", "hide") == "show" && count($machine->usage_area_ids) > 0) {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_usage_areas"><span class="fa-icon fa-codepen d-block d-md-none" title="'. $tag_open .'d2u_machinery_usage_areas'. $tag_close .'"></span><span class="d-none d-md-block">'. $tag_open .'d2u_machinery_usage_areas'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
							}
							if(rex_plugin::get("d2u_machinery", "machine_construction_equipment_extension")->isAvailable()) {
								if(strlen($machine->delivery_set_basic) > 5 || strlen($machine->delivery_set_conversion) > 5 || strlen($machine->delivery_set_full) > 5) {
									print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_delivery_set"><span class="fa-icon fa-dropbox d-block d-lg-none" title="'. $tag_open .'d2u_machinery_construction_equipment_delivery_sets'. $tag_close .'"></span><span class="d-none d-lg-block">'. $tag_open .'d2u_machinery_construction_equipment_delivery_sets'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
								}
							}
							if(rex_plugin::get("d2u_machinery", "service_options")->isAvailable() && count($machine->service_option_ids) > 0) {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_service_options">'. $tag_open .'d2u_machinery_construction_equipment_service'. $tag_close .'</a></li>';
							}
							if(rex_plugin::get("d2u_machinery", "equipment")->isAvailable() && count($machine->equipment_ids) > 0) {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_equipment"><span class="fa-icon fa-plus d-block d-lg-none" title="'. $tag_open .'d2u_machinery_equipment'. $tag_close .'"></span><span class="d-none d-lg-block">'. $tag_open .'d2u_machinery_equipment'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
							}
							print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_request"><span class="fa-icon fa-envelope-o d-block d-lg-none" title="'. $tag_open .'d2u_machinery_request'. $tag_close .'"></span><span class="d-none d-lg-block">'. $tag_open .'d2u_machinery_request'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
							print '</ul>';
						}
						else if($category !== FALSE && (count($category->getMachines()) > 0 || count($category->getUsedMachines()) > 0)) {
							print '<h1 class="subhead">'. $category->name .'</h1>';
							if((rex_plugin::get("d2u_machinery", "machine_usage_area_extension")->isAvailable() && $d2u_machinery->getConfig("show_categories_usage_areas", "hide") == "show")
								|| $d2u_machinery->getConfig("show_techdata", "hide") == "show") {
								print '<ul class="nav nav-pills">';
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link active" href="#tab_overview">'. $tag_open .'d2u_machinery_overview'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
								if(rex_plugin::get("d2u_machinery", "machine_usage_area_extension")->isAvailable() && $d2u_machinery->getConfig("show_categories_usage_areas", "hide") == "show" && count($category->getUsageAreaMatrix()) > 0) {
									print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_usage_areas">'. $tag_open .'d2u_machinery_usage_areas'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
								}
								if($d2u_machinery->getConfig("show_techdata", "hide") == "show") {
									print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_tech_data">'. $tag_open .'d2u_machinery_tech_data'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
								}
								print '</ul>';
							}
						}
						else if($used_machine !== FALSE) {
							print '<h1 class="subhead">'. $used_machine->manufacturer .' '. $used_machine->name .'</h1>';
							print '<ul class="nav nav-pills">';
							print '<li class="nav-item"><a data-toggle="tab" class="nav-link active" href="#tab_overview">'. $tag_open .'d2u_machinery_overview'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_request"><span class="fa-icon fa-envelope-o d-block d-lg-none" title="'. $tag_open .'d2u_machinery_request'. $tag_close .'"></span><span class="d-none d-lg-block">'. $tag_open .'d2u_machinery_request'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
							print '</ul>';
						}
						else if($current_article->getId() == $d2u_machinery->getConfig('used_machine_article_id_rent', 0) || $current_article->getId() == $d2u_machinery->getConfig('used_machine_article_id_sale', 0)) {
							print '<h1 class="subhead">'. $current_article->getName() .'</h1>';
							print '<ul class="nav nav-pills">';
							$class_active = ' active';
							if($current_article->getId() == $d2u_machinery->getConfig('used_machine_article_id_sale')) {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link'. $class_active .'" href="#tab_sale">'. $tag_open .'d2u_machinery_used_machines_offers_sale'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
								$class_active = '';
							}
							if($current_article->getId() == $d2u_machinery->getConfig('used_machine_article_id_rent')) {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link'. $class_active .'" href="#tab_rent">'. $tag_open .'d2u_machinery_used_machines_offers_rent'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							}
							print '</ul>';
						}
						else if($d2u_helper->hasConfig('subhead_include_articlename') && $d2u_helper->getConfig('subhead_include_articlename') == "true") {
							print '<h1 class="subhead">'. $current_article->getName() .'</h1>';
						}
					?>
				</div>
			</div>
		</div>
	</section>
	<article>
		<div class="container article">
			<div class="row">
				<?php
					// Content follows
					print $this->getArticle();
				?>
			</div>
		</div>
	</article>
	<footer class="d-print-none">
		<div class="container footer">
			<div class="row">
				<?php
					if($d2u_helper->getConfig("template_logo", "") != "" || ($d2u_helper->getConfig("template_02_2_facebook_link", "") != "" && $d2u_helper->getConfig("template_02_2_facebook_icon", "") != "")) {
						print '<div class="col-8 col-md-9">';
					}
					else if($d2u_helper->getConfig("template_logo", "") != "" || $d2u_helper->getConfig("template_02_2_facebook_link", "") != "") {
						print '<div class="col-8 col-md-9 col-lg-10">';
					}
					else {
						print '<div class="col-12">';
					}
					$rex_articles = rex_article::getRootArticles(true);
					print '<div class="row">';
					foreach($rex_articles as $rex_articles) {
						print '<div class="col-md-6 col-lg-4">';
						print '<div class="footerbox">';
						print '<a href="'. $rex_articles->getUrl() .'">'. $rex_articles->getName() .'</a>';
						print '</div>';
						print '</div>';
					}
					print '</div>';
					print '</div>';

					if($d2u_helper->getConfig("template_logo", "") != "" || ($d2u_helper->getConfig("template_02_2_facebook_link", "") != "" && $d2u_helper->getConfig("template_02_2_facebook_icon", "") != "")) {
						if($d2u_helper->getConfig("template_logo", "") != "" || ($d2u_helper->getConfig("template_02_2_facebook_link", "") != "" && $d2u_helper->getConfig("template_02_2_facebook_icon", "") != "")) {
							print '<div class="col-4 col-sm-4 col-md-3">';
						}
						else if($d2u_helper->getConfig("template_logo", "") != "" || $d2u_helper->getConfig("template_02_2_facebook_link", "") != "") {
							print '<div class="col-4 col-sm-4 col-md-3 col-lg-2">';
						}

						if($d2u_helper->getConfig("template_logo", "") != "" && ($d2u_helper->getConfig("template_02_2_facebook_link", "") != "" && $d2u_helper->getConfig("template_02_2_facebook_icon", "") != "")) {
							print '<div class="row">';
							print '<div class="col-12 col-lg-6 facebook-logo-div">';
						}

						// Facebook Logo
						if($d2u_helper->getConfig("template_02_2_facebook_link", "") != "" && $d2u_helper->getConfig("template_02_2_facebook_icon", "") != "") {
							print '<a href="'. $d2u_helper->getConfig("template_02_2_facebook_link") .'" target="_blank">';
							print '<img src="'. rex_url::media($d2u_helper->getConfig("template_02_2_facebook_icon")) .'" alt="Facebook" id="facebook">';
							print '</a>';
						}

						if($d2u_helper->getConfig("template_logo", "") != "" && ($d2u_helper->getConfig("template_02_2_facebook_link", "") != "" && $d2u_helper->getConfig("template_02_2_facebook_icon", "") != "")) {
							print '</div>';
							print '<div class="d-block d-lg-none col-lg-6">&nbsp;</div>';
							print '<div class="col-12 col-lg-6">';
						}

						// Logo
						if($d2u_helper->hasConfig("template_logo") && $d2u_helper->getConfig("template_logo") != "") {
							print '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
							$media_logo = rex_media::get($d2u_helper->getConfig("template_logo"));
							if($media_logo instanceof rex_media) {
								print '<img src="'. rex_url::media($d2u_helper->getConfig("template_logo")) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo-footer">';
							}
							print '</a>';
						}

						if($d2u_helper->getConfig("template_logo", "") != "" && ($d2u_helper->getConfig("template_02_2_facebook_link", "") != "" && $d2u_helper->getConfig("template_02_2_facebook_icon", "") != "")) {
							print '</div>';
							print '</div>';
						}
						print '</div>';
					}
				?>
			</div>
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
</body>
</html>