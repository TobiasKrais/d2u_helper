<?php
	$d2u_helper = rex_addon::get("d2u_helper");
	$d2u_immo = rex_addon::get("d2u_immo");
	$sprog = rex_addon::get("sprog");
	$tag_open = $sprog->getConfig('wildcard_open_tag');
	$tag_close = $sprog->getConfig('wildcard_close_tag');
	
	$current_domain = \rex::getServer();
	if (rex_addon::get('yrewrite')->isAvailable()) {
		$current_domain = rex_yrewrite::getCurrentDomain()->getUrl();
	}
?>
<!DOCTYPE html>
<html lang="<?php echo rex_clang::getCurrent()->getCode(); ?>">
<head>
	<?php
		$fragment = new rex_fragment();
		// <head></head>
		echo $fragment->parse('d2u_template_head.php');
	?>
	<link rel="stylesheet" href="/index.php?template_id=03-2&amp;d2u_helper=template.css">
	<meta http-equiv="refresh" content="10800; URL=<?php print rex_getUrl(); ?>">
</head>

<body>
	<?php
	$header_margin = "";
		if($d2u_helper->hasConfig("template_03_2_margin_top") || $d2u_helper->getConfig("template_03_2_margin_top") != "") {
			$header_margin = ' style="margin-top: '. $d2u_helper->getConfig("template_03_2_margin_top") .'px"';
		}
	?>
	<header class="advertising"<?php print $header_margin; ?>>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<?php
						if($d2u_helper->hasConfig("template_03_2_header_pic") || $d2u_helper->getConfig("template_03_2_header_pic") != "") {
							$header_image = $d2u_helper->getConfig("template_03_2_header_pic");
							print '<img src="'. ($d2u_helper->getConfig('template_header_media_manager_type', '') ? rex_media_manager::getUrl($d2u_helper->getConfig('template_header_media_manager_type', ''), $header_image) : rex_url::media($header_image)) .'" alt="">';
						}
						else {
							print "<p style='font: 2em red bold;'>WARNING: Template settings are not complete.</p>";
						}
					?>
				</div>
			</div>
		</div>
	</header>
	<?php
	$properties = [];
	$ads = [];
	if(rex_plugin::get('d2u_immo', 'window_advertising')->isInstalled()) {
		$properties = D2U_Immo\Property::getAllWindowAdvertisingProperties(rex_clang::getCurrentId());
		$ads = D2U_Immo\Advertisement::getAll(rex_clang::getCurrentId(), true);
	}
	// Mix - not merge - arrays
	$all_data = [];

	if (count($properties) == 0) {
		$all_data = $ads;
	}
	else if (count($ads) == 0) {
		$all_data = $properties;
	}
	else {
		$counter_ads = 0;
		$counter_properties = 0;
		// Arrays je nach Verhaeltnis mischen
		$number_properties = 1;
		$number_ads = 1;
		if(count($properties) > count($ads)) {
			$number_properties = round(count($properties) / count($ads));
		}
		else {
			$number_ads = round(count($ads) / count($properties));
		}
		for ($i = 1; $i <= count($properties) + count($ads); $i++) {
			$modulo = $number_properties + $number_ads;
			if(count($properties) >= count($ads)) {
				if($i % $modulo == 0) {
					if($counter_ads < count($ads)) {
						$all_data[$i] = $ads[$counter_ads++];
					}
					else {
						$all_data[$i] = $properties[$counter_properties++];
					}
				}
				else {
					if($counter_properties < count($properties)) {
						$all_data[$i] = $properties[$counter_properties++];
					}
					else {
						$all_data[$i] = $ads[$counter_ads++];
					}
				}
			}
			else {
				if($i % $modulo == 0) {
					if($counter_properties < count($properties)) {
						$all_data[$i] = $properties[$counter_properties++];
					}
					else {
						$all_data[$i] = $ads[$counter_ads++];
					}
				}
				else {
					if($counter_ads < count($ads)) {
						$all_data[$i] = $ads[$counter_ads++];
					}
					else {
						$all_data[$i] = $properties[$counter_properties++];
					}
				}
			}
		}
	}		
	?>
	<article>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<?php
						$interval = '';
						if($d2u_helper->hasConfig("template_03_2_time_show_ad") && $d2u_helper->getConfig("template_03_2_time_show_ad") > 0) {
							$interval = ' data-interval="'. $d2u_helper->getConfig("template_03_2_time_show_ad") .'000"';
						}
					?>

					<div id="immo_advertising" class="carousel slide" data-ride="carousel"<?php print $interval ?> data-cycle="true">
						<div class="carousel-inner" role="listbox">
							<?php
								$active = true;
								foreach($all_data as $data_row) {
									print '<div class="carousel-item'.  ($active ? ' active' : '') .'">';
									print '<div class="row">';
									$active = false;
									if($data_row instanceof D2U_Immo\Advertisement) {
										$advertisement = $data_row;
										print '<div class="col-12 contact-advertising"></div>';
										print '<div class="col-12 print-border-h">';
										print '<h1>'. $advertisement->title .'</b></h1>';
										print '</div>';

										print '<div class="col-12 print-border">';
										print '<div class="row">';
										if($advertisement->picture != "") {
											print '<div class="col-12 col-md-6">';
											print '<img src="index.php?rex_media_type=d2u_helper_sm&rex_media_file='.
													$advertisement->picture .'" alt='. $advertisement->title .' class="overviewpic">';
											print '</div>';

											print '<div class="col-12 col-md-6">';
										}
										else {
											print '<div class="col-12">';
										}
										print d2u_addon_frontend_helper::prepareEditorField($advertisement->description);

										print '</div>';

										print '</div>';
										print '</div>';

									}
									else if($data_row instanceof D2U_Immo\Property) {
										$property = $data_row;
										print '<div class="col-12 contact-advertising">';
										print "<p>". $property->contact->firstname ." ". $property->contact->lastname ."<br />";
										print $property->contact->street ." ". $property->contact->house_number ."<br />";
										print $property->contact->zip_code ." ". $property->contact->city ."<br />";
										print $tag_open .'d2u_immo_form_phone'. $tag_close .' '. $property->contact->phone ."</p>";
										print '</div>';

										print '<div class="col-12 print-border-h">';
										print '<h1>'. $property->name .'</h1>';
										print '</div>';
										if($property->publish_address) {
											print '<div class="col-12 print-border">';
											print "<p>". $property->street ." ". $property->house_number .", ". $property->zip_code ." ". $property->city ."</p>";
											print '</div>';
										}

										print '<div class="col-12 print-border">'; // START overview image and details
										print '<div class="row">';
										if(count($property->pictures) > 0) {
											print '<div class="col-12 col-md-6">'; // START overview image
											if($property->object_reserved || $property->object_sold) {
												print '<div class="reserved">';
											}
											print '<img src="index.php?rex_media_type=d2u_helper_sm&rex_media_file='.
													$property->pictures[0] .'" alt='. $property->name .' class="overviewpic">';
											if($property->object_reserved) {
												print '<span>'. $tag_open .'d2u_immo_object_reserved'. $tag_close .'</span>';
											}
											else if($property->object_sold) {
												print '<span>'. $tag_open .'d2u_immo_object_sold'. $tag_close .'</span>';										
											}
											if($property->object_reserved || $property->object_sold) {
												print '</div>';
											}
											print '</div>'; // END overview image

											print '<div class="col-12 col-md-6">'; // START content details left overview image
										}
										else {
											print '<div class="col-12">'; // START content details left overview image
										}
										print '<div class="row">';

										if(strtoupper($property->market_type) == "KAUF") {
											if($property->purchase_price > 0) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_purchase_price'. $tag_close .':</div>';
												print '<div class="col-6"><b>'. number_format($property->purchase_price, 0, ",", ".") .',-&nbsp;'. $property->currency_code .'</b></div>';
											}
											if($property->purchase_price_m2 > 0) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_purchase_price_m2'. $tag_close .':</div>';
												print '<div class="col-6">'. number_format($property->purchase_price_m2, 2, ",", ".") .'&nbsp;'. $property->currency_code .'</div>';
											}
											if($property->price_plus_vat) {
												print '<div class="col-12">'. $tag_open .'d2u_immo_prices_plus_vat'. $tag_close .'</div>';
												print '<div class="col-12">&nbsp;</div>';
											}
										}
										else {
											if($property->cold_rent > 0 && $property->additional_costs > 0) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_warm_rent'. $tag_close .':</div>';
												print '<div class="col-6"><b>'. number_format($property->cold_rent + $property->additional_costs, 2, ",", ".") .'&nbsp;'. $property->currency_code .'</b></div>';
											}
											if($property->cold_rent > 0) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_cold_rent'. $tag_close .':</div>';
												print '<div class="col-6">'. number_format($property->cold_rent, 2, ",", ".") .'&nbsp;'. $property->currency_code .'</div>';
											}
											if($property->additional_costs > 0) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_additional_costs'. $tag_close .':</div>';
												print '<div class="col-6">'. number_format($property->additional_costs, 2, ",", ".") .'&nbsp;'. $property->currency_code .'</div>';
											}
											if($property->price_plus_vat) {
												print '<div class="col-12">'. $tag_open .'d2u_immo_prices_plus_vat'. $tag_close .'</div>';
												print '<div class="col-12">&nbsp;</div>';
											}
											if($property->deposit != "") {
												print '<div class="col-6">'. $tag_open .'d2u_immo_deposit'. $tag_close .':</div>';
												print '<div class="col-6">'.  number_format($property->deposit, 2, ",", ".") .'&nbsp;'. $property->currency_code .'</div>';
											}
											print '<div class="col-6">'. $tag_open .'d2u_immo_courtage'. $tag_close .'</div>';
											if($property->courtage == "") {
												print '<div class="col-6">'. $tag_open .'d2u_immo_courtage_no'. $tag_close .'</div>';
											}
											else {
												print '<div class="col-6">'. $property->courtage .' '. $tag_open .'d2u_immo_courtage_incl_vat'. $tag_close .'</div>';
											}
										}

										if(strtoupper($property->object_type) == "HAUS" || strtoupper($property->object_type) == "WOHNUNG" || strtoupper($property->object_type) == "BUERO_PRAXEN") {
											if($property->living_area > 0) {
												if(strtoupper($property->object_type) == "HAUS" || strtoupper($property->object_type) == "WOHNUNG") {
													print '<div class="col-6">'. $tag_open .'d2u_immo_living_area'. $tag_close .':</div>';
												}
												else if(strtoupper($property->object_type) == "BUERO_PRAXEN") {
													print '<div class="col-6">'. $tag_open .'d2u_immo_office_area'. $tag_close .':</div>';
												}
												print '<div class="col-6">'. number_format($property->living_area, 2, ",", ".") .'&nbsp;m²</div>';
											}

											if($property->rooms > 0) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_rooms'. $tag_close .':</div>';
												print '<div class="col-6">'. $property->rooms .'</div>';
											}

											if($property->floor > 0) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_floor'. $tag_close .':</div>';
												print '<div class="col-6">'. $property->floor .'</div>';
											}

											if($property->flat_sharing_possible) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_flat_sharing'. $tag_close .':</div>';
												print '<div class="col-6">'. $tag_open .'d2u_immo_yes'. $tag_close .'</div>';
											}

											if($property->condition_type != "") {
												print '<div class="col-6">'. $tag_open .'d2u_immo_condition'. $tag_close .':</div>';
												print '<div class="col-6">'. $tag_open .'d2u_immo_condition_'. $property->condition_type . $tag_close .'</div>';
											}

											if($property->available_from != "") {
												print '<div class="col-6">'. $tag_open .'d2u_immo_available_from'. $tag_close .':</div>';
												print '<div class="col-6">'. date_format(date_create_from_format('Y-m-d', $property->available_from), "d.m.Y") .'</div>';
											}

											if($property->animals) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_animals'. $tag_close .':</div>';
												print '<div class="col-6">'. $tag_open .'d2u_immo_yes'. $tag_close .'</div>';
											}

											if($property->rented) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_rented'. $tag_close .':</div>';
												print '<div class="col-6">'. $tag_open .'d2u_immo_yes'. $tag_close .'</div>';
											}

											if($property->parking_space_duplex > 0) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_parking_space_duplex'. $tag_close .':</div>';
												print '<div class="col-6">'. $property->parking_space_duplex .'</div>';
											}

											if($property->parking_space_simple > 0) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_parking_space_simple'. $tag_close .':</div>';
												print '<div class="col-6">'. $property->parking_space_simple .'</div>';
											}

											if($property->parking_space_garage > 0) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_parking_space_garage'. $tag_close .':</div>';
												print '<div class="col-6">'. $property->parking_space_garage .'</div>';
											}

											if($property->parking_space_undergroundcarpark > 0) {
												print '<div class="col-6">'. $tag_open .'d2u_immo_parking_space_undergroundcarpark'. $tag_close .':</div>';
												print '<div class="col-6">'. $property->parking_space_undergroundcarpark .'</div>';
											}
										}

										if(($property->object_type != "grundstueck" && $property->object_type != "parken") && strlen($property->energy_pass) > 5) {
											print '<div class="col-6">'. $tag_open .'d2u_immo_energy_pass'. $tag_close .'</div>';
											print '<div class="col-6">'. $tag_open .'d2u_immo_energy_pass_'. $property->energy_pass . $tag_close .'</div>';

											print '<div class="col-6"><ul><li>'. $tag_open .'d2u_immo_energy_pass_valid_until'. $tag_close .':</li></ul></div>';
											print '<div class="col-6">'. date_format(date_create_from_format('Y-m-d', $property->energy_pass_valid_until), "d.m.Y") .'</div>';

											print '<div class="col-6"><ul><li>'. $tag_open .'d2u_immo_energy_pass_value'. $tag_close .':</li></ul></div>';
											print '<div class="col-6">'. $property->energy_consumption .'&nbsp;kWh/(m²*a)</div>';

											if($property->including_warm_water) {
												print '<div class="col-6"><ul><li>'. $tag_open .'d2u_immo_energy_pass_incl_warm_water'. $tag_close .':</li></ul></div>';
												print '<div class="col-6">'. $tag_open .'d2u_immo_yes'. $tag_close .'</div>';
											}

											if($property->construction_year > 0) {
												print '<div class="col-6"><ul><li>'. $tag_open .'d2u_immo_construction_year'. $tag_close .':</li></ul></div>';
												print '<div class="col-6">'. $property->construction_year .'</div>';
											}

											if($property->firing_type > 0) {
												print '<div class="col-6"><ul><li>'. $tag_open .'d2u_immo_firing_type'. $tag_close .':</li></ul></div>';
												print '<div class="col-6">';
												$first_element = true;
												foreach($property->firing_type as $firing_type) {
													print ($first_element ? "" : ", ") . $tag_open .'d2u_immo_firing_type_'. $firing_type . $tag_close;
													$first_element = false;
												}
												print '</div>';
											}
										}									

										if($property->total_area > 0) {
											print '<div class="col-6">'. $tag_open .'d2u_immo_total_area'. $tag_close .':</div>';
											print '<div class="col-6">'. $property->total_area .'&nbsp;m²</div>';
										}

										print '</div>';
										print '</div>'; // END content details left overview image
										print '</div>';
										print '</div>'; // END overview image and details
									}
									print '</div>';
									print '</div>';
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</article>
	<div class="container">
		<footer>
			<div class="row">
				<div class="col-12">
					<?php
						if($d2u_helper->hasConfig("template_03_2_footer_pic") || $d2u_helper->getConfig("template_03_2_footer_pic") != "") {
							print '<img src="'. rex_url::media($d2u_helper->getConfig("template_03_2_footer_pic")) .'" alt="">';
						}
						else {
							print "<p style='font: 2em red bold;'>WARNING: Template settings are not complete.</p>";
						}
					?>
				</div>
			</div>
		</footer>
	</div>
</body>
</html>