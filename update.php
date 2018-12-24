<?php
// Update modules
if(class_exists('D2UModuleManager')) {
	$modules = [];
	$modules[] = new D2UModule("00-1",
		"Umbruch ganze Breite",
		4);
	$modules[] = new D2UModule("01-1",
		"Texteditor",
		9);
	$modules[] = new D2UModule("01-2",
		"Texteditor mit Bild und Fettschrift",
		10);
	$modules[] = new D2UModule("02-1",
		"Überschrift",
		7);
	$modules[] = new D2UModule("02-2",
		"Überschrift mit Klapptext",
		1);
	$modules[] = new D2UModule("03-1",
		"Bild",
		6);
	$modules[] = new D2UModule("03-2",
		"Bildergalerie Ekko Lightbox",
		7);
	$modules[] = new D2UModule("04-1",
		"Google Maps",
		9);
	$modules[] = new D2UModule("05-1",
		"Artikelweiterleitung",
		7);
	$modules[] = new D2UModule("05-2",
		"Artikel aus anderer Sprache übernehmen",
		3);
	$modules[] = new D2UModule("06-1",
		"YouTube Video einbinden",
		3);
	$modules[] = new D2UModule("06-2",
		"IFrame einbinden",
		3);
	$modules[] = new D2UModule("10-1",
		"Box mit Bild und Ueberschrift",
		2);
	$modules[] = new D2UModule("10-2",
		"Box mit Bild und Text",
		4);
	$modules[] = new D2UModule("10-3",
		"Box mit Download",
		3);
	$modules[] = new D2UModule("11-1",
		"YForm Kontaktformular (DSGVO kompatibel)",
		4);
	$modules[] = new D2UModule("12-1",
		"YFeed Stream Galerie",
		2);
	$modules[] = new D2UModule("13-1",
		"Lauftext",
		1);
	$d2u_module_manager = new D2UModuleManager($modules);
	$d2u_module_manager->autoupdate();
}

// Update templates
if (rex_string::versionCompare($this->getVersion(), '1.5.4', '<')) {
	// Rename template 02-2 to 04-2
	if(rex_config::has('d2u_helper', 'template_02-2')) {
		$result = rex_sql::factory();
		$result->setQuery('UPDATE ' . \rex::getTablePrefix() . 'config SET `key` = "template_04-2" WHERE `namespace` = "d2u_helper" AND `key` = "template_02-2";');
		$result->setQuery('UPDATE ' . \rex::getTablePrefix() . 'config SET `key` = REPLACE(`key`, "template_02_2", "template_04_2") WHERE `namespace` = "d2u_helper";');
		$result->setQuery('UPDATE ' . \rex::getTablePrefix() . 'config SET `key` = REPLACE(`key`, "template_04_2_header_slider_pics", "template_04_header_slider_pics") WHERE `namespace` = "d2u_helper";');
		$result->setQuery('UPDATE ' . \rex::getTablePrefix() . 'template SET `name` = REPLACE(`name`, "02-2 Header Slider Template", "04-2 Header Slider Template");');
		// Force template update
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
if(class_exists('D2UTemplateManager')) {
	$d2u_templates = [];
	$d2u_templates[] = new D2UTemplate("00-1",
		"Big Header Template",
		10);
	$d2u_templates[] = new D2UTemplate("01-1",
		"Side Picture Template",
		4);
	$d2u_templates[] = new D2UTemplate("02-1",
		"Header Pic Template",
		6);
	$d2u_templates[] = new D2UTemplate("03-1",
		"Immo Template - 2 Columns",
		5);
	$d2u_templates[] = new D2UTemplate("03-2",
		"Immo Window Advertising Template",
		5);
	$d2u_templates[] = new D2UTemplate("04-1",
		"Header Slider Template with Slogan",
		1);
	$d2u_templates[] = new D2UTemplate("04-2",
		"Header Slider Template",
		5);
	$d2u_templates[] = new D2UTemplate("99-1",
		"Feed Generator",
		1);
	$d2u_template_manager = new D2UTemplateManager($d2u_templates);
	$d2u_template_manager->autoupdate();
}

// Update standard settings
if (!$this->hasConfig('subhead_include_articlename')) {
	$this->setConfig('subhead_include_articlename', '"true"');
}
if (!$this->hasConfig('show_breadcrumbs')) {
	$this->setConfig('show_breadcrumbs', '"true"');
}

// Media Manager media types
$sql = rex_sql::factory();
$sql->setQuery("SELECT * FROM ". \rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_gallery_thumb'");
if($sql->getRows() == 0) {
	$sql->setQuery("INSERT INTO ". \rex::getTablePrefix() ."media_manager_type (`status`, `name`, `description`) VALUES
		(1, 'd2u_helper_gallery_thumb', 'D2U Helper Bildergalerie Vorschaubild');");
	$last_id = $sql->getLastId();
	$sql->setQuery("INSERT INTO ". \rex::getTablePrefix() ."media_manager_type_effect (`type_id`, `effect`, `parameters`, `priority`, `createdate`, `createuser`) VALUES
		(". $last_id .", 'resize', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"350\",\"rex_effect_resize_height\":\"350\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"\",\"rex_effect_workspace_height\":\"\",\"rex_effect_workspace_hpos\":\"left\",\"rex_effect_workspace_vpos\":\"top\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"\",\"rex_effect_workspace_bg_g\":\"\",\"rex_effect_workspace_bg_b\":\"\"}}', 1, '". date("Y-m-d H:i:s") ."', 'd2u_helper'),
		(". $last_id .", 'workspace', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"\",\"rex_effect_resize_height\":\"\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"350\",\"rex_effect_workspace_height\":\"350\",\"rex_effect_workspace_hpos\":\"center\",\"rex_effect_workspace_vpos\":\"middle\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"255\",\"rex_effect_workspace_bg_g\":\"255\",\"rex_effect_workspace_bg_b\":\"255\"}}', 2, '". date("Y-m-d H:i:s") ."', 'd2u_helper');");
}
$sql->setQuery("SELECT * FROM ". \rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_gallery_detail'");
if($sql->getRows() == 0) {
	$sql->setQuery("INSERT INTO ". \rex::getTablePrefix() ."media_manager_type (`status`, `name`, `description`) VALUES
		(1, 'd2u_helper_gallery_detail', 'D2U Helper Bildergalerie Detailbild');");
	$last_id = $sql->getLastId();
	$sql->setQuery("INSERT INTO ". \rex::getTablePrefix() ."media_manager_type_effect (`type_id`, `effect`, `parameters`, `priority`, `createdate`, `createuser`) VALUES
		(". $last_id .", 'resize', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"2000\",\"rex_effect_resize_height\":\"2000\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"not_enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"\",\"rex_effect_workspace_height\":\"\",\"rex_effect_workspace_hpos\":\"left\",\"rex_effect_workspace_vpos\":\"top\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"\",\"rex_effect_workspace_bg_g\":\"\",\"rex_effect_workspace_bg_b\":\"\"}}', 1, '". date("Y-m-d H:i:s") ."', 'd2u_helper');");
}

// Set default lang
if (!$this->hasConfig('default_lang')) {
	if(rex_addon::get('d2u_machinery')->isAvailable()) {
		$this->setConfig('default_lang', rex_addon::get('d2u_machinery')->getConfig('default_lang'));
	}
	elseif(rex_addon::get('d2u_immo')->isAvailable()) {
		$this->setConfig('default_lang', rex_addon::get('d2u_immo')->getConfig('default_lang'));
	}
	elseif(rex_addon::get('d2u_guestbook')->isAvailable()) {
		$this->setConfig('default_lang', rex_addon::get('d2u_guestbook')->getConfig('default_lang'));
	}
	elseif(rex_addon::get('d2u_news')->isAvailable()) {
		$this->setConfig('default_lang', rex_addon::get('d2u_news')->getConfig('default_lang'));
	}
	elseif(rex_addon::get('d2u_references')->isAvailable()) {
		$this->setConfig('default_lang', rex_addon::get('d2u_references')->getConfig('default_lang'));
	}
	elseif(rex_addon::get('d2u_videos')->isAvailable()) {
		$this->setConfig('default_lang', rex_addon::get('d2u_videos')->getConfig('default_lang'));
	}
	elseif(rex_addon::get('multinewsletter')->isAvailable()) {
		$this->setConfig('default_lang', rex_addon::get('multinewsletter')->getConfig('default_lang'));
	}
	else {
		$this->setConfig('default_lang', rex_clang::getStartId());
	}
}
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
		$this->setConfig('editor', 'tinymce4');
	}
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
if($this->hasConfig('include_menu')) {
	if($this->getConfig('include_menu') == 'true') {
		$this->setConfig('include_menu_multilevel', 'true');
	}
	else {
		$this->setConfig('include_menu_multilevel', 'false');
	}
	$this->removeConfig('include_menu');
}

// Update translations
if ($this->getConfig('lang_replacements_install', 'false') == 'true') {
	if(!class_exists('d2u_helper_lang_helper')) {
		// Load class in case addon is deactivated
		require_once 'lib/d2u_helper_lang_helper.php';
	}
	d2u_helper_lang_helper::factory()->install();
}