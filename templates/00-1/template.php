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
$category = false;
$industry_sector = false;
$machine = false;
$used_machine = false;

$url_namespace = d2u_addon_frontend_helper::getUrlNamespace();
$url_id = d2u_addon_frontend_helper::getUrlId();

if(rex_addon::get('d2u_machinery')->isAvailable()) {
	if(filter_input(INPUT_GET, 'machine_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || $url_namespace === "machine_id") {
		$machine_id = filter_input(INPUT_GET, 'machine_id', FILTER_VALIDATE_INT);
		if(rex_addon::get("url")->isAvailable() && $url_id > 0) {
			$machine_id = $url_id;
		}
		
		if($machine_id > 0) {
			$machine = new Machine($machine_id, rex_clang::getCurrentId());
		}
	}
	else if(filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || $url_namespace === "category_id"
			|| filter_input(INPUT_GET, 'used_rent_category_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || $url_namespace === "used_rent_category_id"
			|| filter_input(INPUT_GET, 'used_sale_category_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || $url_namespace === "used_sale_category_id"
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
		if(rex_addon::get("url")->isAvailable() && $url_id > 0) {
			$category_id = $url_id;
			if($url_namespace === "used_rent_category_id") {
				$offer_type = 'rent';
			}
			elseif($url_namespace === "used_sale_category_id") {
				$offer_type = 'sale';
			}
		}

		if($category_id > 0) {
			$category = new Category($category_id, rex_clang::getCurrentId());
			$category->setOfferType($offer_type);
		}
	}
	else if(rex_plugin::get("d2u_machinery", "industry_sectors")->isAvailable() && (filter_input(INPUT_GET, 'industry_sector_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || $url_namespace === "industry_sector_id")) {
		$industry_sector_id = filter_input(INPUT_GET, 'industry_sector_id', FILTER_VALIDATE_INT);
		if(rex_addon::get("url")->isAvailable() && $url_id > 0) {
			$industry_sector_id = $url_id;
		}
		
		if($industry_sector_id > 0) {
			$industry_sector = new IndustrySector($industry_sector_id, rex_clang::getCurrentId());
		}
	}
	else if((filter_input(INPUT_GET, 'used_rent_machine_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || $url_namespace === "used_rent_machine_id")
			|| (filter_input(INPUT_GET, 'used_sale_machine_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || $url_namespace === "used_sale_machine_id")) {
		$used_machine_id = filter_input(INPUT_GET, 'used_sale_machine_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 ? filter_input(INPUT_GET, 'used_sale_machine_id', FILTER_VALIDATE_INT) : filter_input(INPUT_GET, 'used_rent_machine_id', FILTER_VALIDATE_INT);
		if(rex_addon::get("url")->isAvailable() && $url_id > 0) {
			$used_machine_id = $url_id;
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
	<?php
		$fragment = new rex_fragment();
		// <head></head>
		echo $fragment->parse('d2u_template_head.php');

		echo '<link rel="stylesheet" href="'. rex_url::frontendController(['template_id' => '00-1', 'd2u_helper' => 'template.css']) .'">';
	?>
</head>
<body>
	<?php
		$header_css = "";
		if(!$d2u_helper->hasConfig("template_header_pic") || !$d2u_helper->hasConfig("template_logo")) {
			print "<p style='font: 2em red bold;'>WARNING: Template settings are not complete.</p>";
		}
		else {
			$header_image = (string) $d2u_helper->getConfig("template_header_pic");
			if($this->hasValue("art_file") && '' !== (string) $this->getValue('art_file')) { /** @phpstan-ignore-line */
				$header_image = (string) $this->getValue("art_file"); /** @phpstan-ignore-line */
			}
			if($header_image !== '') {
				$header_css = 'style="background-image: url('. ('' !== $d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl((string) $d2u_helper->getConfig('template_header_media_manager_type', ''), $header_image) : rex_url::media($header_image)) .')"';
			}
			else {
				$header_css = 'style="height: 100px"';
			}
		}
	?>
	<header <?php echo $header_css; ?>>
		<?php
			if($d2u_helper->hasConfig("template_logo") && $d2u_helper->getConfig('template_logo') !== '') {
		?>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<a href="<?php echo rex_getUrl(rex_article::getSiteStartArticleId()); ?>">
						<?php
						$media_logo = rex_media::get((string) $d2u_helper->getConfig("template_logo"));
						if($media_logo instanceof rex_media) {
							print '<img src="'. rex_url::media((string) $d2u_helper->getConfig("template_logo")) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo">';
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
		// Navi
		echo $fragment->parse('d2u_template_nav.php');
	?>
	<section id="breadcrumbs" class="subhead">
		<div class="container">
			<div class="row">
				<?php
					// Breadcrumbs
					if($d2u_helper->hasConfig("show_breadcrumbs") && '' !== $d2u_helper->getConfig("show_breadcrumbs")) {
						print '<div class="col-12 d-print-none">';
						print d2u_addon_frontend_helper::getBreadcrumbs();
						print '</div>';
					}
				?>
				<div class="col-12 subhead-nav">
					<?php
						if($machine !== false) {
							print '<h1 class="subhead">'. ($machine->lang_name === "" ? $machine->name : $machine->lang_name) .'</h1>';
							print '<ul class="nav nav-pills">';
							print '<li class="nav-item"><a data-toggle="tab" class="nav-link active" href="#tab_overview">'. $tag_open .'d2u_machinery_overview'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							if(rex_plugin::get("d2u_machinery", "machine_agitator_extension")->isAvailable() && $machine->agitator_type_id > 0 && $machine->category instanceof Category && $machine->category->show_agitators === "show") {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_agitator">'. $tag_open .'d2u_machinery_agitator'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							}
							if(rex_plugin::get("d2u_machinery", "machine_features_extension")->isAvailable() && count($machine->feature_ids) > 0){
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_features">'. $tag_open .'d2u_machinery_features'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							}
							if($d2u_machinery->getConfig("show_techdata", "hide") === "show" && count($machine->getTechnicalData()) > 0){
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_tech_data"><span class="fa-icon fa-list-ul d-block d-md-none" title="'. $tag_open .'d2u_machinery_tech_data'. $tag_close .'"></span><span class="d-none d-md-block">'. $tag_open .'d2u_machinery_tech_data'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
							}
							if(rex_plugin::get("d2u_machinery", "machine_usage_area_extension")->isAvailable() && $d2u_machinery->getConfig("show_machine_usage_areas", "hide") === "show" && count($machine->usage_area_ids) > 0) {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_usage_areas"><span class="fa-icon fa-codepen d-block d-md-none" title="'. $tag_open .'d2u_machinery_usage_areas'. $tag_close .'"></span><span class="d-none d-md-block">'. $tag_open .'d2u_machinery_usage_areas'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
							}
							if(rex_plugin::get("d2u_machinery", "machine_construction_equipment_extension")->isAvailable()) {
								if(strlen($machine->delivery_set_basic) > 5 || strlen($machine->delivery_set_conversion) > 5 || strlen($machine->delivery_set_full) > 5) {
									print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_delivery_set"><span class="fa-icon fa-dropbox d-block d-lg-none" title="'. $tag_open .'d2u_machinery_construction_equipment_delivery_sets'. $tag_close .'"></span><span class="d-none d-lg-block">'. $tag_open .'d2u_machinery_construction_equipment_delivery_sets'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
								}
							}
							if(rex_plugin::get("d2u_machinery", "service_options")->isAvailable() && count($machine->service_option_ids) > 0) {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_service_options">'. $tag_open .'d2u_machinery_construction_equipment_service'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							}
							if(rex_plugin::get("d2u_machinery", "equipment")->isAvailable() && count($machine->equipment_ids) > 0) {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_equipment"><span class="fa-icon fa-plus d-block d-lg-none" title="'. $tag_open .'d2u_machinery_equipment'. $tag_close .'"></span><span class="d-none d-lg-block">'. $tag_open .'d2u_machinery_equipment'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
							}
							print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_request"><span class="fa-icon fa-envelope-o d-block d-lg-none" title="'. $tag_open .'d2u_machinery_request'. $tag_close .'"></span><span class="d-none d-lg-block">'. $tag_open .'d2u_machinery_request'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
							print '</ul>';
						}
						else if($category !== false && (count($category->getMachines()) > 0 || count($category->getUsedMachines()) > 0)) {
							print '<h1 class="subhead">'. $category->name .'</h1>';
							if((rex_plugin::get("d2u_machinery", "machine_usage_area_extension")->isAvailable() && $d2u_machinery->getConfig("show_categories_usage_areas", "hide") === "show")
								|| $d2u_machinery->getConfig("show_techdata", "hide") === "show") {
								print '<ul class="nav nav-pills">';
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link active" href="#tab_overview">'. $tag_open .'d2u_machinery_overview'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
								if(rex_plugin::get("d2u_machinery", "machine_usage_area_extension")->isAvailable() && $d2u_machinery->getConfig("show_categories_usage_areas", "hide") === "show" && count($category->getUsageAreaMatrix()) > 0) {
									print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_usage_areas">'. $tag_open .'d2u_machinery_usage_areas'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
								}
								if($d2u_machinery->getConfig("show_techdata", "hide") === "show") {
									print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_tech_data">'. $tag_open .'d2u_machinery_tech_data'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
								}
								print '</ul>';
							}
						}
						else if($used_machine !== false) {
							print '<h1 class="subhead">'. $used_machine->manufacturer .' '. $used_machine->name .'</h1>';
							print '<ul class="nav nav-pills">';
							print '<li class="nav-item"><a data-toggle="tab" class="nav-link active" href="#tab_overview">'. $tag_open .'d2u_machinery_overview'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							if(rex_config::get('d2u_machinery', 'used_machines_pic_type', 'slider') === 'lightbox') {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_pics">'. $tag_open .'d2u_machinery_pics'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							}
							print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_request"><span class="fa-icon fa-envelope-o d-block d-lg-none" title="'. $tag_open .'d2u_machinery_request'. $tag_close .'"></span><span class="d-none d-lg-block">'. $tag_open .'d2u_machinery_request'. $tag_close .'</span><div class="active-navi-pill"></div></a></li>';
							print '</ul>';
						}
						else if($current_article instanceof rex_article && ($current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_rent', 0) || $current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_sale', 0))) {
							print '<h1 class="subhead">'. $current_article->getName() .'</h1>';
							print '<ul class="nav nav-pills">';
							$class_active = ' active';
							if($current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_sale')) {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link'. $class_active .'" href="#tab_sale">'. $tag_open .'d2u_machinery_used_machines_offers_sale'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
								$class_active = '';
							}
							if($current_article->getId() === (int) $d2u_machinery->getConfig('used_machine_article_id_rent')) {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link'. $class_active .'" href="#tab_rent">'. $tag_open .'d2u_machinery_used_machines_offers_rent'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							}
							print '</ul>';
						}
						else if($current_article instanceof rex_article && $d2u_helper->hasConfig('subhead_include_articlename') && (bool) $d2u_helper->getConfig('subhead_include_articlename') === true) {
							print '<h1 class="subhead">'. $current_article->getName() .'</h1>';
						}
					?>
				</div>
			</div>
		</div>
	</section>
	<article>
		<div class="container">
			<div class="row">
				<?php
					// Content follows
					print $this->getArticle(); /** @phpstan-ignore-line */
				?>
			</div>
		</div>
	</article>
	<footer class="d-print-none">
		<div class="container">
			<?php
				echo $fragment->parse('d2u_template_footer.php');
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
	<?php
		echo $fragment->parse('d2u_template_cta_box.php');
	?>
</body>
</html>