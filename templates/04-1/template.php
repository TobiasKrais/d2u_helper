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

/**
* Convert a hexa decimal color code to its RGB equivalent
* @param string $hexStr (hexadecimal color value)
* @param bool $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
* @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
* @return array or string (depending on second parameter. Returns False if invalid hex color value)
*/                                                                                                
function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}

// Get d2u_machinery stuff
$d2u_machinery = rex_addon::get("d2u_machinery");
$category = FALSE;
$industry_sector = FALSE;
$machine = FALSE;
$used_machine = FALSE;

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
	<meta charset="utf-8">
	<base href="<?php echo $current_domain; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		print d2u_addon_frontend_helper::getMetaTags();
	?>
	<link rel="stylesheet" href="/index.php?template_id=04-1&d2u_helper=template.css">
	<?php
		if(file_exists(rex_path::media('favicon.ico'))) {
			print '<link rel="icon" href="'. rex_url::media('favicon.ico') .'">';
		}
		if(rex_addon::get('iwcc')->isAvailable()) {
			print 'REX_IWCC[]';
		}
	?>
</head>

<body>
	<nav>
		<div class="container d-print-none navigation">
			<div class="row">
				<?php
					// Logo
					if($d2u_helper->getConfig("template_logo", "") != "") {
						print '<div class="col-12 col-md-4 col-lg-3">';
						print '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
						$media_logo = rex_media::get($d2u_helper->getConfig("template_logo"));
						if($media_logo instanceof rex_media) {
							print '<img src="'. rex_url::media($d2u_helper->getConfig("template_logo")) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo">';
						}
						print '</a>';
						print '</div>';
					}

					print '<div class="col-12 col-md-'. ($d2u_helper->getConfig("template_logo", "") != "" ? '8' : '12') .' col-lg-'. ($d2u_helper->getConfig("template_logo", "") != "" ? '9' : '12') .' navi-row">';
					print '<div class="row">';
					
					// Navi
					print '<div class="col-12">';
					if(rex_addon::get('d2u_helper')->isAvailable()) {
						d2u_mobile_navi_smartmenus::getMenu();
					}
					// Languages
					$clangs = rex_clang::getAll(TRUE);
					if(count($clangs) == 2) {
						print '<div id="langchooser">';
						$alternate_urls = d2u_addon_frontend_helper::getAlternateURLs();
						foreach ($clangs as $clang) {
							if($clang->getId() != rex_clang::getCurrentId()) {
								print '<a href="'. (isset($alternate_urls[$clang->getId()]) ? $alternate_urls[$clang->getId()] : rex_getUrl(rex_article::getSiteStartArticleId(), $clang->getId())) .'">';
								if($clang->getValue('clang_icon') != "") {
									print '<img src="'. rex_url::media($clang->getValue('clang_icon')) .'" alt="'. $clang->getName() .'" class="single-lang">';
								}
								print '</a>';							
							}
						}
						print '</div>';
					}
					else if(count($clangs) > 2) {
						print '<div id="langchooser">';
						print '<ul class="navbar-nav ">';
						print '<li class="nav-item dropdown">';
						print '<a class="nav-item nav-link mr-md-2" href="#" id="langs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
						. '<svg xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M3.814 16.464a.501.501 0 00.65-.278L5.54 13.5h2.923l1.074 2.686a.5.5 0 00.928-.372l-3-7.5a.52.52 0 00-.928 0l-3 7.5a.5.5 0 00.278.65zM7 9.846L8.061 12.5H5.94zM6 7.5a.5.5 0 00.224-.053l2-1a.5.5 0 10-.448-.894l-2 1A.5.5 0 006 7.5zM11.75 14.25a2.025 2.025 0 001.75 2.25 2.584 2.584 0 001.482-.431c.039.088.07.152.075.162a.5.5 0 00.887-.461 4.654 4.654 0 01-.15-.368c.176-.168.359-.348.56-.548a11.374 11.374 0 001.92-2.652A1.55 1.55 0 0119 13.5a2.082 2.082 0 01-1.607 2.012.5.5 0 00.107.988.506.506 0 00.107-.012A3.055 3.055 0 0020 13.5a2.542 2.542 0 00-1.283-2.205c.16-.364.244-.6.255-.63a.5.5 0 10-.944-.33 7.97 7.97 0 01-.225.552 5.11 5.11 0 00-2.482-.21c.04-.428.091-.845.153-1.229 1.427-.123 3.04-.44 3.124-.458a.5.5 0 00-.196-.98c-.019.003-1.43.283-2.736.418.162-.761.31-1.273.313-1.284a.5.5 0 10-.958-.288c-.016.053-.206.695-.393 1.64-.041 0-.088.004-.128.004h-2a.5.5 0 000 1h1.955c-.072.476-.134.985-.17 1.517a4.001 4.001 0 00-2.535 3.233zm1.75 1.25c-.362 0-.75-.502-.75-1.25a2.82 2.82 0 011.506-2.094 11.674 11.674 0 00.384 2.927 1.684 1.684 0 01-1.14.417zm2.604-3.897a4.4 4.4 0 011.251.193 10.325 10.325 0 01-1.708 2.35l-.163.162A11.04 11.04 0 0115.25 12c0-.093.008-.185.01-.278a3.318 3.318 0 01.844-.12z M22.5 3h-21a.5.5 0 00-.5.5v16a.5.5 0 00.5.5H10v3.5a.5.5 0 00.854.354L14.707 20H22.5a.5.5 0 00.5-.5v-16a.5.5 0 00-.5-.5zM22 19h-7.5a.5.5 0 00-.354.146L11 22.293V19.5a.5.5 0 00-.5-.5H2V4h20z"></path></svg></a>';
						print '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="langs">';
						$alternate_urls = d2u_addon_frontend_helper::getAlternateURLs();
						foreach ($clangs as $clang) {
							if($clang->getId() != rex_clang::getCurrentId()) {
								print '<a class="dropdown-item" href="'. (isset($alternate_urls[$clang->getId()]) ? $alternate_urls[$clang->getId()] : rex_getUrl(rex_article::getSiteStartArticleId(), $clang->getId())) .'">'
									. $clang->getName() .' ';
								if($clang->getValue('clang_icon') != "") {
									print '<img src="'. rex_url::media($clang->getValue('clang_icon')) .'" alt="'. $clang->getName() .'">';
								}
								print  '</a>';							
							}
						}
						print '</div>';
						print '</li>';
						print '</ul>';
						print '</div>';
					}
					print '</div>';

					print '</div>';
					print '</div>';
				?>
			</div>
		</div>
	</nav>
	<?php
		$slider_pics = preg_grep('/^\s*$/s', explode(",", $d2u_helper->getConfig('template_04_header_slider_pics_clang_'. rex_clang::getCurrentId())), PREG_GREP_INVERT);
		if(count($slider_pics) > 0) {
	?>
	<header>
		<?php 
			if($d2u_helper->getConfig("template_04_header_slider_pics_full_width", FALSE) == FALSE) {
				// START Only if slider background slider is shown
		?>
		<div id="background">
			<?php
				if(count($slider_pics) == 1) {
					print '<img src="'. rex_url::media($slider_pics[0]) .'" alt="" id="background-single-image">';
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
									print ' class="active"';
								}
								print '></li>';
							}
							print '</ol>';

							// Wrapper for slides
							print '<div class="carousel-inner">';
							for($i = 0; $i < count($slider_pics); $i++) {
								$slider_pic = rex_media::get($slider_pics[$i]);
								if($slider_pic instanceof rex_media) {
									print '<div class="carousel-item';
									if($i == 0) {
										print ' active';
									}
									print '">';
									// Image
									$ratio = $slider_pic->getWidth() / $slider_pic->getHeight();
									$ratio_min_style = ' style="min-height: 250px; min-width:'. round(250 * $ratio).'px;"';
									print '<img class="d-block w-100" src="'. rex_url::media($slider_pics[$i]) .'" alt="'. $slider_pic->getTitle() .'"'. $ratio_min_style .'>';
									// Slogan
									$slogan_text = $this->getValue('art_slogan') != "" ? $this->getValue('art_slogan') : $d2u_helper->getConfig('template_04_1_slider_slogan_clang_'. rex_clang::getCurrentId());
									$slogan = '<span class="slogan-text-row">'. str_replace('<br>', '</span><span class="slogan-text-row">', nl2br($slogan_text, FALSE)) .'</span>';
									if($slogan_text != "") {
										print '<div class="slogan"><div class="container"><span class="slogan-text">'. $slogan .'</span></div></div>';
									}
									print '</div>';
								}
							}
							print '</div>';
							print '</div>';
						}
			if($d2u_helper->getConfig("template_04_header_slider_pics_full_width", FALSE) == FALSE) {
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
							if(count(\D2U_Courses\Cart::getCourseIDs()) > 0) {
								print '<div id="cart_info">'. count(\D2U_Courses\Cart::getCourseIDs()) .'</div>';
							}
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
							if(rex_config::get('d2u_machinery', 'used_machines_pic_type', 'slider') == 'lightbox') {
								print '<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#tab_pics">'. $tag_open .'d2u_machinery_pics'. $tag_close .'<div class="active-navi-pill"></div></a></li>';
							}
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
			<div class="row align-items-center">
				<?php
					// Article links
					print '<div class="col-12 col-md-4 col-lg-4">';
					$rex_articles = rex_article::getRootArticles(true);
					foreach($rex_articles as $rex_article) {
						print '<p><span class="fa-icon fa-link footer-icon"></span><a href="'. $rex_article->getUrl() .'">'. $rex_article->getName() .'</a></p>';
					}
					if(rex_addon::get('iwcc')->isAvailable()) {
						print '<p><span class="fa-icon fa-link footer-icon"></span><a class="iwcc-show-box">'. $tag_open .'iwcc_template_edit_cookiesettings'. $tag_close .'</a></p>';
					}
					print '</div>';

					// Logo
					print '<div class="col-12 col-md-4 col-lg-4 text-md-center">';
					if($d2u_helper->getConfig("template_04_1_footer_logo", "") != "") {
							print '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId()) .'">';
							$media_logo = rex_media::get($d2u_helper->getConfig("template_04_1_footer_logo"));
							if($media_logo instanceof rex_media) {
								print '<img src="'. rex_url::media($d2u_helper->getConfig("template_04_1_footer_logo")) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo-footer">';
							}
							print '</a>';
					}
					print '</div>';

					// Custom field
					if($d2u_helper->getConfig("template_04_1_footer_text_clang_". rex_clang::getCurrentId(), "") != "") {
						print '<div class="col-12 col-md-6 col-lg-4">';
						print '<p class="text-md-right">'. nl2br($d2u_helper->getConfig("template_04_1_footer_text_clang_". rex_clang::getCurrentId(), "")) .'</p>';
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