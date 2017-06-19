<?php
// Get placeholder wildcard tags
$sprog = rex_addon::get("sprog");
$tag_open = $sprog->getConfig('wildcard_open_tag');
$tag_close = $sprog->getConfig('wildcard_close_tag');

// SEO stuff
$alternate = ""; 
$canonical = "";
$current_domain = rex::getServer();
$description = "";
$robots = "";
$title = "";
if (rex_addon::get('yrewrite')->isAvailable()) {
	$yrewrite = new rex_yrewrite_seo();
	$alternate = $yrewrite->getHreflangTags();
	$canonical = $yrewrite->getCanonicalUrlTag();
	$current_domain = rex_yrewrite::getCurrentDomain()->getUrl();
	$description = $yrewrite->getDescriptionTag();
	$robots = $yrewrite->getRobotsTag();
	$title = $yrewrite->getTitleTag();
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
if(rex_Addon::get('d2u_machinery')->isAvailable()) {
	if(filter_input(INPUT_GET, 'machine_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "machine_id")) {
		$machine_id = filter_input(INPUT_GET, 'machine_id', FILTER_VALIDATE_INT);
		if(rex_addon::get("url")->isAvailable() && UrlGenerator::getId() > 0) {
			$machine_id = UrlGenerator::getId();
		}
		$machine = new Machine($machine_id, rex_clang::getCurrentId());
		$alternate = $machine->getMetaAlternateHreflangTags();
		$canonical = $machine->getCanonicalTag();
		$description = $machine->getMetaDescriptionTag();
		$title = $machine->getTitleTag();
	}
	else if(filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "category_id")) {
		$category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
		if(rex_addon::get("url")->isAvailable() && UrlGenerator::getId() > 0) {
			$category_id = UrlGenerator::getId();
		}
		$category = new Category($category_id, rex_clang::getCurrentId());
		$alternate = $category->getMetaAlternateHreflangTags();
		$canonical = $category->getCanonicalTag();
		$description = $category->getMetaDescriptionTag();
		$title = $category->getTitleTag();
	}
	else if(rex_plugin::get("d2u_machinery", "industry_sectors")->isAvailable() && (filter_input(INPUT_GET, 'industry_sector_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "industry_sector_id"))) {
		$industry_sector_id = filter_input(INPUT_GET, 'industry_sector_id', FILTER_VALIDATE_INT);
		if(rex_addon::get("url")->isAvailable() && UrlGenerator::getId() > 0) {
			$industry_sector_id = UrlGenerator::getId();
		}
		$industry_sector = new IndustrySector($industry_sector_id, rex_clang::getCurrentId());
		$alternate = $industry_sector->getMetaAlternateHreflangTags();
		$canonical = $industry_sector->getCanonicalTag();
		$description = $industry_sector->getMetaDescriptionTag();
		$title = $industry_sector->getTitleTag();
	}
	else if(rex_plugin::get("d2u_machinery", "used_machines")->isAvailable() && (filter_input(INPUT_GET, 'used_machine_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "used_machine_id"))) {
		$used_machine_id = filter_input(INPUT_GET, 'used_machine_id', FILTER_VALIDATE_INT);
		if(rex_addon::get("url")->isAvailable() && UrlGenerator::getId() > 0) {
			$used_machine_id = UrlGenerator::getId();
		}
		$used_machine = new UsedMachine($used_machine_id, rex_clang::getCurrentId());
		$alternate = $used_machine->getMetaAlternateHreflangTags();
		$canonical = $used_machine->getCanonicalTag();
		$description = $used_machine->getMetaDescriptionTag();
		$title = $used_machine->getTitleTag();
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
		echo $title.PHP_EOL;
		echo $description .PHP_EOL;
		echo $robots .PHP_EOL;
		echo $alternate .PHP_EOL;
		echo $canonical .PHP_EOL;
	?>
	<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
	<link rel="stylesheet" href="index.php?template_id=00-1&d2u_helper=template.css">
	<link rel="icon" href="<?php print rex_url::media('favicon.ico') ?>">
	<?php
  		if (rex_addon::get('rex_emailobfuscator')->isAvailable()) {
    ?>
    	<link rel="stylesheet" type="text/css" href="<?=rex_url::addonAssets('rex_emailobfuscator', 'rex_emailobfuscator.css');?>">
    	<script src="<?=rex_url::addonAssets('rex_emailobfuscator', 'rex_emailobfuscator.js');?>"></script>
    <?php
		}
	?>
</head>

<body>
	<?php
		$header_css = "";
		if(!$d2u_helper->hasConfig("template_header_pic") || !$d2u_helper->hasConfig("template_logo")) {
			print "<p style='font: 2em red bold;'>WARNING: Template settings are not complete.</p>";
		}
		else {
			$header_image = $d2u_helper->getConfig("template_header_pic");
			if($this->hasValue("art_file") && $this->getValue("art_file") != "") {
				$header_image = $this->getValue("art_file");
				$header_css = 'style="background-image: url('. rex_url::media($header_image) .')"';
			}
		}
	?>
	<header <?php echo $header_css; ?>>
		<?php
			if($d2u_helper->hasConfig("template_logo") && $d2u_helper->getConfig("template_logo") != "") {
		?>
		<div class="container">
			<div class="row">
				<div class="col-9 col-md-6">
					<a href="<?php echo rex_getUrl(rex_article::getSiteStartArticleId()); ?>">
						<?php
						$media_logo = rex_media::get($d2u_helper->getConfig("template_logo"));
						if($media_logo instanceof rex_media) {
							print '<img src="'. rex_url::media($d2u_helper->getConfig("template_logo")) .'" alt="'. $media_logo->getTitle() .'" title="'. $media_logo->getTitle() .'" id="logo">';
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
	<nav>
		<div class="container">
			<div class="row">
				<?php
					// Navi
					if(count(rex_clang::getAllIds(TRUE)) > 1) {
						print '<div class="col-9" data-match-height>';
					}
					else {
						print '<div class="col-12" data-match-height>';
					}
					print '<div class="navi">';
							if(rex_addon::get('d2u_helper')->isAvailable()) {
								d2u_mobile_navi::getResponsiveMultiLevelMobileMenu();
								d2u_mobile_navi::getResponsiveMultiLevelDesktopMenu();
							}
					print '</div>';
					print '</div>';
					// Languages
					if(count(rex_clang::getAllIds(TRUE)) > 1) {
						print '<div class="col-sm-3">';
						print '<div id="langchooser" class="desktop-inner">';
						// FIXME
						if(rex_clang::getCurrentId() == 1) {
							$clang = rex_clang::get(2);
							print '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId(), 2) .'"><img src="'. rex_url::media('de.gif') .'" alt="'. $clang->getName() .'">'. $clang->getName() .'</a>';
						}
						else {
							$clang = rex_clang::get(1);
							print '<a href="'. rex_getUrl(rex_article::getSiteStartArticleId(), 1) .'"><img src="'. rex_url::media('en_uk.gif') .'" alt="'. $clang->getName() .'">'. $clang->getName() .'</a>';
						}
						print '</div>';
						print '</div>';
					}
				?>
			</div>
		</div>
	</nav>
	<section id="breadcrumbs" class="subhead">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<?php
						// Breadcrumbs
						if($d2u_helper->hasConfig("show_breadcrumbs") && $d2u_helper->getConfig("show_breadcrumbs")) {
							$startarticle = rex_article::get(rex_article::getSiteStartArticleId());
							echo '<a href="' . $startarticle->getUrl() . '"><span class="fa-home"></span></a>';
							$path = $current_article->getPathAsArray();
							foreach ($path as $id) {
								$article = rex_category::get($id);
								echo ' &nbsp;»&nbsp;&nbsp;<a href="' . $article->getUrl() . '">' . $article->getName() . '</a>';
							}
							if(!$current_article->isStartArticle()) {
								echo ' &nbsp;»&nbsp;&nbsp;<a href="' . $current_article->getUrl() . '">' . $current_article->getName() . '</a>';
							}
							if(rex_Addon::get('d2u_machinery')->isAvailable()) {
								foreach(d2u_machinery_frontend_helper::getBreadcrumbs() as $breadcrumb) {
									echo ' &nbsp;»&nbsp;&nbsp;' . $breadcrumb;
								}
							}
						}
					?>
				</div>
				<div class="col-12 subhead-nav">
					<?php
						if($machine !== FALSE) {
							print '<br><h1 class="subhead">'. ($machine->lang_name == "" ? $machine->name : $machine->lang_name) .'</h1>';
							print '<ul class="nav nav-pills">';
							print '<li class="nav-item"><a data-toggle="tab" class="active" href="#tab_overview">'. $tag_open .'d2u_machinery_overview'. $tag_close .'</a></li>';
							if(rex_plugin::get("d2u_machinery", "machine_agitator_extension")->isAvailable() && $machine->agitator_type_id > 0 && $machine->agitator_type_id > 0 && $machine->category->show_agitators == "show") {
								print '<li class="nav-item"><a data-toggle="tab" href="#tab_agitator">'. $tag_open .'d2u_machinery_agitator'. $tag_close .'</a></li>';
							}
							if(rex_plugin::get("d2u_machinery", "machine_features_extension")->isAvailable() && count($machine->feature_ids) > 0){
								print '<li class="nav-item"><a data-toggle="tab" href="#tab_features">'. $tag_open .'d2u_machinery_features'. $tag_close .'</a></li>';
							}
							if($d2u_machinery->hasConfig("show_techdata") && $d2u_machinery->getConfig("show_techdata") == "show" && count($machine->getTechnicalData()) > 0){
								print '<li class="nav-item"><a data-toggle="tab" href="#tab_tech_data">'. $tag_open .'d2u_machinery_tech_data'. $tag_close .'</a></li>';
							}
							print '<li class="nav-item"><a data-toggle="tab" href="#tab_request">'. $tag_open .'d2u_machinery_request'. $tag_close .'</a></li>';
							print '</ul>';
						}
						else if($category !== FALSE && (count($category->getMachines()) > 0 || count($category->getUsedMachines()) > 0)) {
							print '<br><h1 class="subhead">'. $category->name .'</h1>';
							print '<ul class="nav nav-pills">';
							print '<li class="nav-item"><a data-toggle="tab" class="active" href="#tab_overview">'. $tag_open .'d2u_machinery_overview'. $tag_close .'</a></li>';
							if(rex_plugin::get("d2u_machinery", "machine_usage_area_extension")->isAvailable() && count($category->getMachines()) > 0) {
								print '<li class="nav-item"><a data-toggle="tab" href="#tab_usage_areas">'. $tag_open .'d2u_machinery_usage_areas'. $tag_close .'</a></li>';
							}
							if($d2u_machinery->hasConfig("show_techdata") && $d2u_machinery->getConfig("show_techdata") == "show" && count($category->getMachines()) > 0) {
								print '<li class="nav-item"><a data-toggle="tab" href="#tab_tech_data">'. $tag_open .'d2u_machinery_tech_data'. $tag_close .'</a></li>';
							}
							print '</ul>';
						}
						else if($used_machine !== FALSE) {
							print '<br><h1 class="subhead">'. $used_machine->manufacturer .' '. $used_machine->name .'</h1>';
							print '<ul class="nav nav-pills">';
							print '<li class="nav-item"><a data-toggle="tab" class="active" href="#tab_overview">'. $tag_open .'d2u_machinery_overview'. $tag_close .'</a></li>';
							print '<li class="nav-item"><a data-toggle="tab" href="#tab_request">'. $tag_open .'d2u_machinery_request'. $tag_close .'</a></li>';
							print '</ul>';
						}
						else if(($d2u_machinery->hasConfig('used_machine_article_id_rent') && $current_article->getId() == $d2u_machinery->getConfig('used_machine_article_id_rent'))
								|| ($d2u_machinery->hasConfig('used_machine_article_id_sale') && $current_article->getId() == $d2u_machinery->getConfig('used_machine_article_id_sale'))) {
							print '<h1 class="subhead">'. $current_article->getName() .'</h1>';
							print '<ul class="nav nav-pills">';
							$class_active = ' class="active"';
							if($current_article->getId() == $d2u_machinery->getConfig('used_machine_article_id_sale')) {
								print '<li class="nav-item"><a data-toggle="tab" href="#tab_sale"'. $class_active .'>'. $tag_open .'d2u_machinery_used_machines_offers_sale'. $tag_close .'</a></li>';
								$class_active = '';
							}
							if($current_article->getId() == $d2u_machinery->getConfig('used_machine_article_id_rent')) {
								print '<li class="nav-item"><a data-toggle="tab" href="#tab_rent"'. $class_active .'>'. $tag_open .'d2u_machinery_used_machines_offers_rent'. $tag_close .'</a></li>';
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
		<div class="container">
			<div class="row">
				<?php
					// Content follows
					print $this->getArticle();
				?>
			</div>
		</div>
	</article>
	<footer>
		<div class="container">
			<div class="row">
				<?php
					$rex_articles = rex_article::getRootArticles(true);
					foreach($rex_articles as $rex_articles) {
						print '<div class="col-sm-6 col-md-4 col-lg-3">';
						print '<div class="footerbox">';
						print '<a href="'. $rex_articles->getUrl() .'">'. $rex_articles->getName() .'</a>';
						print '</div>';
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