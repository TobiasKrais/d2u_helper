<?php
$sql = rex_sql::factory();

/*
 *  START managing media manager types
 */
// Media Manager media types
$sql->setQuery("SELECT * FROM ". \rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_gallery_thumb'");
if($sql->getRows() == 0) {
	$sql->setQuery("INSERT INTO ". \rex::getTablePrefix() ."media_manager_type (`status`, `name`, `description`) VALUES
		(0, 'd2u_helper_gallery_thumb', 'D2U Helper Bildergalerie Vorschaubild');");
	$last_id = $sql->getLastId();
	$sql->setQuery("INSERT INTO ". \rex::getTablePrefix() ."media_manager_type_effect (`type_id`, `effect`, `parameters`, `priority`, `createdate`, `createuser`) VALUES
		(". $last_id .", 'resize', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"350\",\"rex_effect_resize_height\":\"350\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"\",\"rex_effect_workspace_height\":\"\",\"rex_effect_workspace_hpos\":\"left\",\"rex_effect_workspace_vpos\":\"top\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"\",\"rex_effect_workspace_bg_g\":\"\",\"rex_effect_workspace_bg_b\":\"\"}}', 1, CURRENT_TIMESTAMP, 'd2u_helper'),
		(". $last_id .", 'workspace', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"\",\"rex_effect_resize_height\":\"\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"350\",\"rex_effect_workspace_height\":\"350\",\"rex_effect_workspace_hpos\":\"center\",\"rex_effect_workspace_vpos\":\"middle\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"255\",\"rex_effect_workspace_bg_g\":\"255\",\"rex_effect_workspace_bg_b\":\"255\"}}', 2, CURRENT_TIMESTAMP, 'd2u_helper');");
}
$sql->setQuery("SELECT * FROM ". \rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_gallery_detail'");
if($sql->getRows() == 0) {
	$sql->setQuery("INSERT INTO ". \rex::getTablePrefix() ."media_manager_type (`status`, `name`, `description`) VALUES
		(0, 'd2u_helper_gallery_detail', 'D2U Helper Bildergalerie Detailbild');");
	$last_id = $sql->getLastId();
	$sql->setQuery("INSERT INTO ". \rex::getTablePrefix() ."media_manager_type_effect (`type_id`, `effect`, `parameters`, `priority`, `createdate`, `createuser`) VALUES
		(". $last_id .", 'resize', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"2000\",\"rex_effect_resize_height\":\"2000\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"not_enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"\",\"rex_effect_workspace_height\":\"\",\"rex_effect_workspace_hpos\":\"left\",\"rex_effect_workspace_vpos\":\"top\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"\",\"rex_effect_workspace_bg_g\":\"\",\"rex_effect_workspace_bg_b\":\"\"}}', 1, CURRENT_TIMESTAMP, 'd2u_helper');");
}
$sql->setQuery("SELECT * FROM ". \rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_sm'");
if($sql->getRows() == 0) {
	$sql->setQuery("INSERT INTO ". \rex::getTablePrefix() ."media_manager_type (`status`, `name`, `description`) VALUES
		(0, 'd2u_helper_sm', 'D2U Helper volle Breite Smartphone Bildschirme.');");
	$last_id = $sql->getLastId();
	$sql->setQuery("INSERT INTO ". \rex::getTablePrefix() ."media_manager_type_effect (`type_id`, `effect`, `parameters`, `priority`, `createdate`, `createuser`) VALUES
		(". $last_id .", 'resize', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"768\",\"rex_effect_resize_height\":\"768\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"\",\"rex_effect_workspace_height\":\"\",\"rex_effect_workspace_hpos\":\"left\",\"rex_effect_workspace_vpos\":\"top\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"\",\"rex_effect_workspace_bg_g\":\"\",\"rex_effect_workspace_bg_b\":\"\"}}', 1, CURRENT_TIMESTAMP, 'd2u_helper');");
}
// Update to 1.8.8
if (rex_version::compare($this->getVersion(), '1.8.8', '<')) {
	$sql->setQuery("UPDATE ". \rex::getTablePrefix() ."media_manager_type SET status = 0 WHERE name LIKE 'd2u_helper_%'");
}
/*
 *  END managing media manager types
 */

/*
 *  START managing settings
 */
// Standard settings that cannot be set in package.yml
if (!$this->hasConfig('editor')) {
	if(rex_addon::get('tinymce4')->isAvailable()) {
		$this->setConfig('editor', 'tinymce4');
	}
	elseif(rex_addon::get('redactor2')->isAvailable()) {
		$this->setConfig('editor', 'redactor2');
	}
	elseif(rex_addon::get('ckeditor')->isAvailable()) {
		$this->setConfig('editor', 'ckeditor');
	}
	elseif(rex_addon::get('markitup')->isAvailable()) {
		$this->setConfig('editor', 'markitup');
	}
	else {
		$this->setConfig('editor', 'tinymce5_default');
	}
}
else {
	if(rex_addon::get('tinymce5')->isAvailable() && $this->getConfig('editor') == 'tinymce5') {
		$this->setConfig('editor', 'tinymce5_default');
	}
}
if (!$this->hasConfig('default_lang')) {
	$this->setConfig('default_lang', rex_clang::getStartId());
}

// Set default lang
if (!$this->hasConfig('default_lang')) {
	$this->setConfig('default_lang', rex_clang::getStartId());
}

// 1.5.0 Update
if($this->hasConfig('include_bootstrap')) {
	if($this->getConfig('include_bootstrap') == 'true') {
		$this->setConfig('include_bootstrap4', 'true');
		$this->setConfig('include_jquery', 'true');
	}
	else {
		$this->setConfig('include_bootstrap4', 'false');
		$this->setConfig('include_jquery', 'false');
	}
	$this->removeConfig('include_bootstrap');
}

// Update to 1.6.0
if (rex_version::compare($this->getVersion(), '1.6.0', '<')) {
	// Update settings to switch from multilevel menu to smartmenu
	if((rex_config::has('d2u_helper', 'template_00-1') || rex_config::has('d2u_helper', 'template_01-1') || rex_config::has('d2u_helper', 'template_02-1')
			|| rex_config::has('d2u_helper', 'template_04-1') || rex_config::has('d2u_helper', 'template_04-2') || rex_config::has('d2u_helper', 'template_04-3'))
		&& rex_config::get('d2u_helper', 'include_menu_multilevel', FALSE) == TRUE) {
		$this->setConfig('include_menu', 'smartmenu');
	}
}
if($this->hasConfig('template_02_1_navi_pos')) {
	$this->setConfig('template_navi_pos', $this->getConfig('template_02_1_navi_pos'));
	$this->removeConfig('template_02_1_navi_pos');
}
if($this->hasConfig('emetrics_customno')) {
	$this->setConfig('wiredminds_tracking_account_id', $this->getConfig('emetrics_customno'));
	$this->removeConfig('emetrics_customno');
}

// Update to 1.6.1
if($this->hasConfig('activate_rewrite_scheme')) {
	$this->removeConfig('activate_rewrite_scheme');
	foreach (rex_clang::getAll() as $rex_clang) {
		if($this->hasConfig('rewrite_scheme_clang_'. $rex_clang->getId())) {
			$this->removeConfig('rewrite_scheme_clang_'. $rex_clang->getId());
		}
	}
}

// Update to 1.8
if($this->hasConfig('template_02_1_footer_text')) {
	$this->setConfig('footer_text', $this->getConfig('template_02_1_footer_text'));
	$this->removeConfig('template_02_1_footer_text');
}
if($this->hasConfig('template_05_1_footer_text')) {
	$this->setConfig('footer_text', $this->getConfig('template_05_1_footer_text'));
	$this->removeConfig('template_05_1_footer_text');
}
if($this->hasConfig('template_04_1_footer_logo')) {
	$this->setConfig('footer_logo', $this->getConfig('template_04_1_footer_logo'));
	$this->removeConfig('template_04_1_footer_logo');
}
if($this->hasConfig('template_04_2_facebook_link')) {
	$this->setConfig('footer_facebook_link', $this->getConfig('template_04_2_facebook_link'));
	$this->removeConfig('template_04_2_facebook_link');
}
if($this->hasConfig('template_04_2_facebook_icon')) {
	$this->setConfig('footer_facebook_icon', $this->getConfig('template_04_2_facebook_icon'));
	$this->removeConfig('template_04_2_facebook_icon');
}

// Update to 1.8.6
if($this->hasConfig('wiredminds_tracking_account_id')) {
	$this->removeConfig('wiredminds_tracking_account_id');
}
if($this->hasConfig('google_analytics')) {
	$this->removeConfig('google_analytics');
}

// Update settings to 1.9.2
if($this->hasConfig('include_menu_multilevel')) {
	if($this->getConfig('include_menu_multilevel') == 'true') {
		$this->setConfig('include_menu', 'multilevel');
	}
	$this->removeConfig('include_menu_multilevel');
}
if($this->hasConfig('include_menu_slicknav')) {
	if($this->getConfig('include_menu_slicknav') == 'true') {
		$this->setConfig('include_menu', 'slicknav');
	}
	$this->removeConfig('include_menu_slicknav');
}
if($this->hasConfig('include_menu_smartmenu')) {
	if($this->getConfig('include_menu_smartmenu') == 'true') {
		$this->setConfig('include_menu', 'smartmenu');
	}
	$this->removeConfig('include_menu_smartmenu');
}
if($this->getConfig('include_menu', 'true') == 'true') {
	$this->setConfig('include_menu', 'none');
}
/*
 *  END managing settings
 */

/*
 *  START update modules
 */
// Move module config from rex_config to rex_module
$sql->setQuery("SELECT * FROM `". rex::getTablePrefix() ."config` WHERE `key` LIKE 'module_%' AND value LIKE '{\"rex_module_id\":%,\"autoupdate\":\"%\"}'");
foreach ($sql->getArray() as $result) {
	$attributes = json_decode($result['value'], true);

	$sql_module = rex_sql::factory();
	$sql_module->setQuery("UPDATE `". rex::getTablePrefix() ."module` "
		. "SET `key` = '". str_replace("module_", "d2u_", $result['key'])."', attributes = '". json_encode(["autoupdate" => $attributes['autoupdate'], "addon_key" => $result['namespace']]) ."'"
		. "where id = ". $attributes['rex_module_id']);
}
$sql->setQuery("DELETE FROM `". rex::getTablePrefix() ."config` WHERE `key` LIKE 'module_%' AND value LIKE '{\"rex_module_id\":%,\"autoupdate\":\"%\"}'");
 
// Update modules
if(class_exists('D2UModuleManager')) {
	$modules = [];
	$modules[] = new D2UModule("00-1",
		"Umbruch ganze Breite",
		6);
	$modules[] = new D2UModule("01-1",
		"Texteditor",
		11);
	$modules[] = new D2UModule("01-2",
		"Texteditor mit Bild und Fettschrift",
		13);
	$modules[] = new D2UModule("02-1",
		"Überschrift",
		10);
	$modules[] = new D2UModule("02-2",
		"Überschrift mit Klapptext",
		4);
	$modules[] = new D2UModule("02-3",
		"Überschrift mit Untertitel und Textfeld",
		6);
	$modules[] = new D2UModule("03-1",
		"Bild",
		10);
	$modules[] = new D2UModule("03-2",
		"Bildergalerie Ekko Lightbox",
		10);
	$modules[] = new D2UModule("04-1",
		"Google Maps Karte",
		11);
	$modules[] = new D2UModule("04-2",
		"OpenStreetMap Karte",
		2);
	$modules[] = new D2UModule("05-1",
		"Artikelweiterleitung",
		13);
	$modules[] = new D2UModule("05-2",
		"Artikel aus anderer Sprache übernehmen",
		4);
	$modules[] = new D2UModule("06-1",
		"YouTube Video einbinden",
		12);
	$modules[] = new D2UModule("06-2",
		"IFrame einbinden",
		4);
	$modules[] = new D2UModule("06-3",
		"Video mit Plyr einbinden",
		2);
	$modules[] = new D2UModule("06-4",
		"Videoliste mit Plyr einbinden",
		1);
	$modules[] = new D2UModule("07-1",
		"JavaScript einbinden",
		1);
	$modules[] = new D2UModule("10-1",
		"Box mit Bild und Ueberschrift",
		3);
	$modules[] = new D2UModule("10-2",
		"Box mit Bild und Text",
		10);
	$modules[] = new D2UModule("10-3",
		"Box mit Downloads",
		8);
	$modules[] = new D2UModule("11-1",
		"YForm Kontaktformular (DSGVO kompatibel)",
		8);
	$modules[] = new D2UModule("11-2",
		"Box mit Kontaktinformationen",
		1);
	$modules[] = new D2UModule("12-1",
		"Feeds Stream Galerie",
		3);
	$modules[] = new D2UModule("13-1",
		"Lauftext",
		3);
	$modules[] = new D2UModule("14-1",
		"Search It Suchmodul",
		3);
	$modules[] = new D2UModule("15-1",
		"Kategorie mit Liste der Unterkategorien",
		2);
	$d2u_module_manager = new D2UModuleManager($modules);
	$d2u_module_manager->autoupdate();
}
/*
 *  END update modules
 */

/*
 *  START update templates
 */
if (rex_version::compare($this->getVersion(), '1.5.4', '<')) {
	// Rename template 02-2 to 04-2
	if(rex_config::has('d2u_helper', 'template_02-2')) {
		$result = rex_sql::factory();
		$result->setQuery('UPDATE ' . \rex::getTablePrefix() . 'config SET `key` = "template_04-2" WHERE `namespace` = "d2u_helper" AND `key` = "template_02-2";');
		$result->setQuery('UPDATE ' . \rex::getTablePrefix() . 'config SET `key` = REPLACE(`key`, "template_02_2", "template_04_2") WHERE `namespace` = "d2u_helper";');
		$result->setQuery('UPDATE ' . \rex::getTablePrefix() . 'config SET `key` = REPLACE(`key`, "template_04_2_header_slider_pics", "template_04_header_slider_pics") WHERE `namespace` = "d2u_helper";');
		$result->setQuery('UPDATE ' . \rex::getTablePrefix() . 'template SET `name` = REPLACE(`name`, "02-2 Header Slider Template", "04-2 Header Slider Template");');
		// Force template update
		if(class_exists('D2UTemplate')) {
			ob_start();
			$d2u_templates[] = new D2UTemplate("04-2",
				"Header Slider Template",
				5);
			$d2u_template_manager = new D2UTemplateManager($d2u_templates);
			$d2u_template_manager->doActions("04-2", "", rex_config::get('d2u_helper', 'template_02-2')['rex_template_id']);
			ob_end_clean();
			rex_delete_cache();
		}
	}
}
if(class_exists('D2UTemplateManager')) {
	$d2u_templates = [];
	$d2u_templates[] = new D2UTemplate("00-1",
		"Big Header Template",
		19);
	$d2u_templates[] = new D2UTemplate("01-1",
		"Side Picture Template",
		11);
	$d2u_templates[] = new D2UTemplate("02-1",
		"Header Pic Template",
		14);
	$d2u_templates[] = new D2UTemplate("03-1",
		"Immo Template - 2 Columns",
		13);
	$d2u_templates[] = new D2UTemplate("03-2",
		"Immo Window Advertising Template",
		10);
	$d2u_templates[] = new D2UTemplate("04-1",
		"Header Slider Template with Slogan",
		12);
	$d2u_templates[] = new D2UTemplate("04-2",
		"Header Slider Template",
		18);
	$d2u_templates[] = new D2UTemplate("04-3",
		"Header Slider Template with news column",
		13);
	$d2u_templates[] = new D2UTemplate("05-1",
		"Double Logo Template",
		12);
	$d2u_templates[] = new D2UTemplate("06-1",
		"Paper Sheet Template",
		6);
	$d2u_templates[] = new D2UTemplate("99-1",
		"Feed Generator",
		1);
	$d2u_template_manager = new D2UTemplateManager($d2u_templates);
	$d2u_template_manager->autoupdate();

	if (rex_version::compare($this->getVersion(), '1.8.0', '<')) {
		if(!$this->hasConfig('footer_color_font')) {
			$this->setConfig('footer_color_font', '#ffffff');
			foreach ($d2u_templates as $d2u_template) {
				if($d2u_template->getD2UId() === "05-1" && $d2u_template->isInstalled()) {
					$this->setConfig('footer_color_font', $this->getConfig('navi_color_bg'));
				}
				else if($d2u_template->getD2UId() === "06-1" && $d2u_template->isInstalled()) {
					$this->setConfig('footer_color_font', '#777777');
				}
			}
		}
		// set footer type
		foreach ($d2u_templates as $d2u_template) {
			if(($d2u_template->getD2UId() === "00-1" && $d2u_template->isInstalled()) ||
				($d2u_template->getD2UId() === "01-1" && $d2u_template->isInstalled()) ||
				($d2u_template->getD2UId() === "04-3" && $d2u_template->isInstalled())) {
				$this->setConfig('footer_type', 'box');
			}
			else if($d2u_template->getD2UId() === "02-1" && $d2u_template->isInstalled() ||
				$d2u_template->getD2UId() === "03-1" && $d2u_template->isInstalled()) {
				$this->setConfig('footer_type', 'links_text');
			}
			else if($d2u_template->getD2UId() === "04-1" && $d2u_template->isInstalled()) {
				$this->setConfig('footer_type', 'links_logo_address');
			}
			else if($d2u_template->getD2UId() === "04-2" && $d2u_template->isInstalled()) {
				$this->setConfig('footer_type', 'box_logo');
			}
			else if($d2u_template->getD2UId() === "05-1" && $d2u_template->isInstalled()) {
				$this->setConfig('footer_type', 'text');
			}
		}
	}
	if (rex_version::compare($this->getVersion(), '1.8.8', '<')) {
		// set footer type
		foreach ($d2u_templates as $d2u_template) {
			if($d2u_template->getD2UId() === "05-1" && $d2u_template->isInstalled()) {
				$this->setConfig('template_header_media_manager_type', 'titelbild');
			}
		}
	}
}
/*
 *  END update templates
 */

/*
 *  START update translations
 */
if ($this->getConfig('lang_replacements_install', 'false')) {
	
	if(!class_exists('d2u_helper_lang_helper')) {
		// Load class in case addon is deactivated
		require_once 'lib/d2u_helper_lang_helper.php';
	}
	d2u_helper_lang_helper::factory()->install();

	// Update to 1.8.8
	if (rex_version::compare($this->getVersion(), '1.8.8', '<')) {
		if(rex_addon::get('sprog')->isAvailable()) {
			$sql->setQuery("DELETE FROM ". \rex::getTablePrefix() ."sprog_wildcard WHERE wildcard LIKE 'd2u_helper_module_11_%'");
		}
	}
}
/*
 *  END update translations
 */