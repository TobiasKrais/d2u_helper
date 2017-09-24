<?php
// Update modules
if(class_exists(D2UModuleManager)) {
	$modules = [];
	$modules[] = new D2UModule("00-1",
		"Umbruch ganze Breite",
		2);
	$modules[] = new D2UModule("01-1",
		"Texteditor",
		5);
	$modules[] = new D2UModule("01-2",
		"Texteditor mit Bild und Überschrift",
		4);
	$modules[] = new D2UModule("02-1",
		"Ueberschrift",
		3);
	$modules[] = new D2UModule("03-1",
		"Bild",
		4);
	$modules[] = new D2UModule("03-2",
		"Bildergallerie Ekko Lightbox",
		4);
	$modules[] = new D2UModule("04-1",
		"Google Maps",
		6);
	$modules[] = new D2UModule("05-1",
		"Artikelweiterleitung",
		4);
	$modules[] = new D2UModule("05-2",
		"Artikel aus anderer Sprache übernehmen",
		1);
	$modules[] = new D2UModule("06-1",
		"YouTube Video einbinden",
		2);
	$modules[] = new D2UModule("06-2",
		"IFrame einbinden",
		1);
	$modules[] = new D2UModule("10-1",
		"Box mit Bild und Ueberschrift",
		1);
	$modules[] = new D2UModule("10-2",
		"Box mit Bild und Text",
		1);
	$modules[] = new D2UModule("10-3",
		"Box mit Download",
		1);
	$d2u_module_manager = new D2UModuleManager($d2u_modules);
	$d2u_module_manager->autoupdate();
}

// Update templates
if(class_exists(D2UTemplateManager)) {
	$d2u_templates = [];
	$d2u_templates[] = new D2UTemplate("00-1",
		"Big Header Template",
		3);
	$d2u_templates[] = new D2UTemplate("01-1",
		"Side Picture Template",
		1);
	$d2u_templates[] = new D2UTemplate("02-1",
		"Header Pic Template",
		1);
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
$sql->setQuery("SELECT * FROM ". rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_gallery_thumb'");
if($sql->getRows() == 0) {
	$sql->setQuery("INSERT INTO ". rex::getTablePrefix() ."media_manager_type (`status`, `name`, `description`) VALUES
		(1, 'd2u_helper_gallery_thumb', 'D2U Helper Bildergallerie Vorschaubild');");
	$last_id = $sql->getLastId();
	$sql->setQuery("INSERT INTO ". rex::getTablePrefix() ."media_manager_type_effect (`type_id`, `effect`, `parameters`, `priority`, `createdate`, `createuser`) VALUES
		(". $last_id .", 'resize', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"350\",\"rex_effect_resize_height\":\"350\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"\",\"rex_effect_workspace_height\":\"\",\"rex_effect_workspace_hpos\":\"left\",\"rex_effect_workspace_vpos\":\"top\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"\",\"rex_effect_workspace_bg_g\":\"\",\"rex_effect_workspace_bg_b\":\"\"}}', 1, '". date("Y-m-d H:i:s") ."', 'd2u_immo'),
		(". $last_id .", 'workspace', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"\",\"rex_effect_resize_height\":\"\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"350\",\"rex_effect_workspace_height\":\"350\",\"rex_effect_workspace_hpos\":\"center\",\"rex_effect_workspace_vpos\":\"middle\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"255\",\"rex_effect_workspace_bg_g\":\"255\",\"rex_effect_workspace_bg_b\":\"255\"}}', 2, '". date("Y-m-d H:i:s") ."', 'd2u_immo');");
}
$sql->setQuery("SELECT * FROM ". rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_gallery_detail'");
if($sql->getRows() == 0) {
	$sql->setQuery("INSERT INTO ". rex::getTablePrefix() ."media_manager_type (`status`, `name`, `description`) VALUES
		(1, 'd2u_helper_gallery_detail', 'D2U Helper Bildergallerie Detailbild');");
	$last_id = $sql->getLastId();
	$sql->setQuery("INSERT INTO ". rex::getTablePrefix() ."media_manager_type_effect (`type_id`, `effect`, `parameters`, `priority`, `createdate`, `createuser`) VALUES
		(". $last_id .", 'resize', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"2000\",\"rex_effect_resize_height\":\"2000\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"not_enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"\",\"rex_effect_workspace_height\":\"\",\"rex_effect_workspace_hpos\":\"left\",\"rex_effect_workspace_vpos\":\"top\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"\",\"rex_effect_workspace_bg_g\":\"\",\"rex_effect_workspace_bg_b\":\"\"}}', 1, '". date("Y-m-d H:i:s") ."', 'd2u_immo');");
}

// Set default lang
if (!$this->hasConfig()) {
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
