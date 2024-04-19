<?php

$d2u_helper = rex_addon::get('d2u_helper');

$sql = rex_sql::factory();

/*
 *  START managing media manager types
 */
// Media Manager media types
$sql->setQuery('SELECT * FROM '. \rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_gallery_thumb'");
if (0 === (int) $sql->getRows()) {
    $sql->setQuery('INSERT INTO '. \rex::getTablePrefix() ."media_manager_type (`status`, `name`, `description`) VALUES
		(0, 'd2u_helper_gallery_thumb', 'D2U Helper Bildergalerie Vorschaubild');");
    $last_id = $sql->getLastId();
    $sql->setQuery('INSERT INTO '. \rex::getTablePrefix() .'media_manager_type_effect (`type_id`, `effect`, `parameters`, `priority`, `createdate`, `createuser`) VALUES
		('. $last_id .", 'resize', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"350\",\"rex_effect_resize_height\":\"350\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"\",\"rex_effect_workspace_height\":\"\",\"rex_effect_workspace_hpos\":\"left\",\"rex_effect_workspace_vpos\":\"top\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"\",\"rex_effect_workspace_bg_g\":\"\",\"rex_effect_workspace_bg_b\":\"\"}}', 1, CURRENT_TIMESTAMP, 'd2u_helper'),
		(". $last_id .", 'workspace', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"\",\"rex_effect_resize_height\":\"\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"350\",\"rex_effect_workspace_height\":\"350\",\"rex_effect_workspace_hpos\":\"center\",\"rex_effect_workspace_vpos\":\"middle\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"255\",\"rex_effect_workspace_bg_g\":\"255\",\"rex_effect_workspace_bg_b\":\"255\"}}', 2, CURRENT_TIMESTAMP, 'd2u_helper');");
}
$sql->setQuery('SELECT * FROM '. \rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_gallery_detail'");
if (0 === (int) $sql->getRows()) {
    $sql->setQuery('INSERT INTO '. \rex::getTablePrefix() ."media_manager_type (`status`, `name`, `description`) VALUES
		(0, 'd2u_helper_gallery_detail', 'D2U Helper Bildergalerie Detailbild');");
    $last_id = $sql->getLastId();
    $sql->setQuery('INSERT INTO '. \rex::getTablePrefix() .'media_manager_type_effect (`type_id`, `effect`, `parameters`, `priority`, `createdate`, `createuser`) VALUES
		('. $last_id .", 'resize', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"2000\",\"rex_effect_resize_height\":\"2000\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"not_enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"\",\"rex_effect_workspace_height\":\"\",\"rex_effect_workspace_hpos\":\"left\",\"rex_effect_workspace_vpos\":\"top\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"\",\"rex_effect_workspace_bg_g\":\"\",\"rex_effect_workspace_bg_b\":\"\"}}', 1, CURRENT_TIMESTAMP, 'd2u_helper');");
}
$sql->setQuery('SELECT * FROM '. \rex::getTablePrefix() ."media_manager_type WHERE name = 'd2u_helper_sm'");
if (0 === (int) $sql->getRows()) {
    $sql->setQuery('INSERT INTO '. \rex::getTablePrefix() ."media_manager_type (`status`, `name`, `description`) VALUES
		(0, 'd2u_helper_sm', 'D2U Helper volle Breite Smartphone Bildschirme.');");
    $last_id = $sql->getLastId();
    $sql->setQuery('INSERT INTO '. \rex::getTablePrefix() .'media_manager_type_effect (`type_id`, `effect`, `parameters`, `priority`, `createdate`, `createuser`) VALUES
		('. $last_id .", 'resize', '{\"rex_effect_convert2img\":{\"rex_effect_convert2img_convert_to\":\"jpg\",\"rex_effect_convert2img_density\":\"100\"},\"rex_effect_crop\":{\"rex_effect_crop_width\":\"\",\"rex_effect_crop_height\":\"\",\"rex_effect_crop_offset_width\":\"\",\"rex_effect_crop_offset_height\":\"\",\"rex_effect_crop_hpos\":\"left\",\"rex_effect_crop_vpos\":\"top\"},\"rex_effect_filter_blur\":{\"rex_effect_filter_blur_repeats\":\"\",\"rex_effect_filter_blur_type\":\"\",\"rex_effect_filter_blur_smoothit\":\"\"},\"rex_effect_filter_colorize\":{\"rex_effect_filter_colorize_filter_r\":\"\",\"rex_effect_filter_colorize_filter_g\":\"\",\"rex_effect_filter_colorize_filter_b\":\"\"},\"rex_effect_filter_sharpen\":{\"rex_effect_filter_sharpen_amount\":\"\",\"rex_effect_filter_sharpen_radius\":\"\",\"rex_effect_filter_sharpen_threshold\":\"\"},\"rex_effect_flip\":{\"rex_effect_flip_flip\":\"X\"},\"rex_effect_header\":{\"rex_effect_header_download\":\"open_media\",\"rex_effect_header_cache\":\"no_cache\"},\"rex_effect_insert_image\":{\"rex_effect_insert_image_brandimage\":\"\",\"rex_effect_insert_image_hpos\":\"left\",\"rex_effect_insert_image_vpos\":\"top\",\"rex_effect_insert_image_padding_x\":\"\",\"rex_effect_insert_image_padding_y\":\"\"},\"rex_effect_mediapath\":{\"rex_effect_mediapath_mediapath\":\"\"},\"rex_effect_mirror\":{\"rex_effect_mirror_height\":\"\",\"rex_effect_mirror_set_transparent\":\"colored\",\"rex_effect_mirror_bg_r\":\"\",\"rex_effect_mirror_bg_g\":\"\",\"rex_effect_mirror_bg_b\":\"\"},\"rex_effect_resize\":{\"rex_effect_resize_width\":\"768\",\"rex_effect_resize_height\":\"768\",\"rex_effect_resize_style\":\"maximum\",\"rex_effect_resize_allow_enlarge\":\"enlarge\"},\"rex_effect_rotate\":{\"rex_effect_rotate_rotate\":\"0\"},\"rex_effect_rounded_corners\":{\"rex_effect_rounded_corners_topleft\":\"\",\"rex_effect_rounded_corners_topright\":\"\",\"rex_effect_rounded_corners_bottomleft\":\"\",\"rex_effect_rounded_corners_bottomright\":\"\"},\"rex_effect_workspace\":{\"rex_effect_workspace_width\":\"\",\"rex_effect_workspace_height\":\"\",\"rex_effect_workspace_hpos\":\"left\",\"rex_effect_workspace_vpos\":\"top\",\"rex_effect_workspace_set_transparent\":\"colored\",\"rex_effect_workspace_bg_r\":\"\",\"rex_effect_workspace_bg_g\":\"\",\"rex_effect_workspace_bg_b\":\"\"}}', 1, CURRENT_TIMESTAMP, 'd2u_helper');");
}
// Update to 1.8.8
if (rex_version::compare($d2u_helper->getVersion(), '1.8.8', '<')) {
    $sql->setQuery('UPDATE '. \rex::getTablePrefix() ."media_manager_type SET status = 0 WHERE name LIKE 'd2u_helper_%'");
}
/*
 *  END managing media manager types
 */

/*
 *  START managing settings
 */
// Standard settings that cannot be set in package.yml
if (!$d2u_helper->hasConfig('editor')) {
    if (rex_addon::get('cke5')->isAvailable()) {
        $d2u_helper->setConfig('editor', 'cke5-editor_default');
    }
    elseif (rex_addon::get('redactor')->isAvailable()) {
        $d2u_helper->setConfig('editor', 'redactor-editor--full');
    }
    else {
        $d2u_helper->setConfig('editor', 'tinymce_default1');
    }
}
if (!$d2u_helper->hasConfig('default_lang')) {
    $d2u_helper->setConfig('default_lang', rex_clang::getStartId());
}

// Set default lang
if (!$d2u_helper->hasConfig('default_lang')) {
    $d2u_helper->setConfig('default_lang', rex_clang::getStartId());
}

// 1.5.0 Update
if ($d2u_helper->hasConfig('include_bootstrap')) {
    if ((bool) $d2u_helper->getConfig('include_bootstrap')) {
        $d2u_helper->setConfig('include_bootstrap4', true);
        $d2u_helper->setConfig('include_jquery', true);
    } else {
        $d2u_helper->setConfig('include_bootstrap4', false);
        $d2u_helper->setConfig('include_jquery', false);
    }
    $d2u_helper->removeConfig('include_bootstrap');
}

// Update to 1.6.0
if (rex_version::compare($d2u_helper->getVersion(), '1.6.0', '<')) {
    // Update settings to switch from multilevel menu to smartmenu
    if ((rex_config::has('d2u_helper', 'template_00-1') || rex_config::has('d2u_helper', 'template_01-1') || rex_config::has('d2u_helper', 'template_02-1')
            || rex_config::has('d2u_helper', 'template_04-1') || rex_config::has('d2u_helper', 'template_04-2') || rex_config::has('d2u_helper', 'template_04-3'))
        && true === (bool) rex_config::get('d2u_helper', 'include_menu_multilevel', false)) {
        $d2u_helper->setConfig('include_menu', 'smartmenu');
    }
}
if ($d2u_helper->hasConfig('template_02_1_navi_pos')) {
    $d2u_helper->setConfig('template_navi_pos', $d2u_helper->getConfig('template_02_1_navi_pos'));
    $d2u_helper->removeConfig('template_02_1_navi_pos');
}
if ($d2u_helper->hasConfig('emetrics_customno')) {
    $d2u_helper->setConfig('wiredminds_tracking_account_id', $d2u_helper->getConfig('emetrics_customno'));
    $d2u_helper->removeConfig('emetrics_customno');
}

// Update to 1.6.1
if ($d2u_helper->hasConfig('activate_rewrite_scheme')) {
    $d2u_helper->removeConfig('activate_rewrite_scheme');
    foreach (rex_clang::getAll() as $rex_clang) {
        if ($d2u_helper->hasConfig('rewrite_scheme_clang_'. $rex_clang->getId())) {
            $d2u_helper->removeConfig('rewrite_scheme_clang_'. $rex_clang->getId());
        }
    }
}

// Update to 1.8
if ($d2u_helper->hasConfig('template_02_1_footer_text')) {
    $d2u_helper->setConfig('footer_text', $d2u_helper->getConfig('template_02_1_footer_text'));
    $d2u_helper->removeConfig('template_02_1_footer_text');
}
if ($d2u_helper->hasConfig('template_05_1_footer_text')) {
    $d2u_helper->setConfig('footer_text', $d2u_helper->getConfig('template_05_1_footer_text'));
    $d2u_helper->removeConfig('template_05_1_footer_text');
}
if ($d2u_helper->hasConfig('template_04_1_footer_logo')) {
    $d2u_helper->setConfig('footer_logo', $d2u_helper->getConfig('template_04_1_footer_logo'));
    $d2u_helper->removeConfig('template_04_1_footer_logo');
}
if ($d2u_helper->hasConfig('template_04_2_facebook_link')) {
    $d2u_helper->setConfig('footer_facebook_link', $d2u_helper->getConfig('template_04_2_facebook_link'));
    $d2u_helper->removeConfig('template_04_2_facebook_link');
}
if ($d2u_helper->hasConfig('template_04_2_facebook_icon')) {
    $d2u_helper->setConfig('footer_facebook_icon', $d2u_helper->getConfig('template_04_2_facebook_icon'));
    $d2u_helper->removeConfig('template_04_2_facebook_icon');
}

// Update to 1.8.6
if ($d2u_helper->hasConfig('wiredminds_tracking_account_id')) {
    $d2u_helper->removeConfig('wiredminds_tracking_account_id');
}
if ($d2u_helper->hasConfig('google_analytics')) {
    $d2u_helper->removeConfig('google_analytics');
}

// Update settings to 1.9.2
if ($d2u_helper->hasConfig('include_menu_multilevel')) {
    if ('true' === $d2u_helper->getConfig('include_menu_multilevel')) {
        $d2u_helper->setConfig('include_menu', 'multilevel');
    }
    $d2u_helper->removeConfig('include_menu_multilevel');
}
if ($d2u_helper->hasConfig('include_menu_slicknav')) {
    if ('true' === $d2u_helper->getConfig('include_menu_slicknav')) {
        $d2u_helper->setConfig('include_menu', 'slicknav');
    }
    $d2u_helper->removeConfig('include_menu_slicknav');
}
if ($d2u_helper->hasConfig('include_menu_smartmenu')) {
    if ('true' === $d2u_helper->getConfig('include_menu_smartmenu')) {
        $d2u_helper->setConfig('include_menu', 'smartmenu');
    }
    $d2u_helper->removeConfig('include_menu_smartmenu');
}
if ('true' === $d2u_helper->getConfig('include_menu', 'true')) {
    $d2u_helper->setConfig('include_menu', 'none');
}
/*
 *  END managing settings
 */

/*
 *  START update modules
 */
// Move module config from rex_config to rex_module
$sql->setQuery('SELECT * FROM `'. rex::getTablePrefix() ."config` WHERE `key` LIKE 'module_%' AND value LIKE '{\"rex_module_id\":%,\"autoupdate\":\"%\"}'");
foreach ($sql->getArray() as $result) {
    $attributes = json_decode((string) $result['value'], true);

    if (is_array($attributes)) {
        $sql_module = rex_sql::factory();
        $sql_module->setQuery('UPDATE `'. rex::getTablePrefix() .'module` '
            . "SET `key` = '". str_replace('module_', 'd2u_', (string) $result['key'])."', attributes = '". json_encode(['autoupdate' => $attributes['autoupdate'], 'addon_key' => $result['namespace']]) ."'"
            . 'WHERE id = '. $attributes['rex_module_id']);
    }
}
$sql->setQuery('DELETE FROM `'. rex::getTablePrefix() ."config` WHERE `key` LIKE 'module_%' AND value LIKE '{\"rex_module_id\":%,\"autoupdate\":\"%\"}'");

// Update modules
if (class_exists(TobiasKrais\D2UHelper\ModuleManager::class)) {
    $modules = [];
    $modules[] = new \TobiasKrais\D2UHelper\Module('00-1',
        'Umbruch ganze Breite',
        9);
    $modules[] = new \TobiasKrais\D2UHelper\Module('01-1',
        'Texteditor',
        14);
    $modules[] = new \TobiasKrais\D2UHelper\Module('01-2',
        'Texteditor mit Bild und Fettschrift',
        17);
    $modules[] = new \TobiasKrais\D2UHelper\Module('01-3',
        'Texteditor in Alertbox',
        2);
    $modules[] = new \TobiasKrais\D2UHelper\Module('02-1',
        'Überschrift',
        11);
    $modules[] = new \TobiasKrais\D2UHelper\Module('02-2',
        'Überschrift mit Klapptext',
        7);
    $modules[] = new \TobiasKrais\D2UHelper\Module('02-3',
        'Überschrift mit Untertitel und Textfeld',
        9);
    $modules[] = new \TobiasKrais\D2UHelper\Module('02-4',
        'Überschrift mit Hintergrundbild und 2 Buttons',
        1);
    $modules[] = new \TobiasKrais\D2UHelper\Module('02-5',
        'Inhaltsverzeichnis der Überschriften',
        1);
    $modules[] = new \TobiasKrais\D2UHelper\Module('03-1',
        'Bild',
        12);
    $modules[] = new \TobiasKrais\D2UHelper\Module('03-2',
        'Bildergalerie Ekko Lightbox',
        15);
    $modules[] = new \TobiasKrais\D2UHelper\Module('03-3',
        '360° Bild',
        1);
    $modules[] = new \TobiasKrais\D2UHelper\Module('04-1',
        'Google Maps Karte',
        13);
    $modules[] = new \TobiasKrais\D2UHelper\Module('04-2',
        'OpenStreetMap Karte',
        6);
    $modules[] = new \TobiasKrais\D2UHelper\Module('05-1',
        'Artikelweiterleitung',
        14);
    $modules[] = new \TobiasKrais\D2UHelper\Module('05-2',
        'Artikel aus anderer Sprache übernehmen',
        5);
    $modules[] = new \TobiasKrais\D2UHelper\Module('06-1',
        'YouTube Video einbinden',
        16);
    $modules[] = new \TobiasKrais\D2UHelper\Module('06-2',
        'IFrame einbinden',
        5);
    $modules[] = new \TobiasKrais\D2UHelper\Module('06-3',
        'Video mit Plyr einbinden',
        4);
    $modules[] = new \TobiasKrais\D2UHelper\Module('06-4',
        'Videoliste mit Plyr einbinden',
        2);
    $modules[] = new \TobiasKrais\D2UHelper\Module('07-1',
        'JavaScript einbinden',
        2);
    $modules[] = new \TobiasKrais\D2UHelper\Module('10-1',
        'Box mit Bild und Ueberschrift',
        4);
    $modules[] = new \TobiasKrais\D2UHelper\Module('10-2',
        'Box mit Bild und Text',
        6);
    $modules[] = new \TobiasKrais\D2UHelper\Module('10-3',
        'Box mit Downloads',
        10);
    $modules[] = new \TobiasKrais\D2UHelper\Module('11-1',
        'YForm Kontaktformular (DSGVO kompatibel)',
        14);
    $modules[] = new \TobiasKrais\D2UHelper\Module('11-2',
        'Box mit Kontaktinformationen',
        2);
    $modules[] = new \TobiasKrais\D2UHelper\Module('12-1',
        'Feeds Stream Galerie',
        5);
    $modules[] = new \TobiasKrais\D2UHelper\Module('13-1',
        'Lauftext',
        5);
    $modules[] = new \TobiasKrais\D2UHelper\Module('14-1',
        'Search It Suchmodul',
        6);
    $modules[] = new \TobiasKrais\D2UHelper\Module('15-1',
        'Kategorie mit Liste der Unterkategorien',
        3);
    $d2u_module_manager = new \TobiasKrais\D2UHelper\ModuleManager($modules);
    $d2u_module_manager->autoupdate();
}
/*
 *  END update modules
 */

/*
 *  START update templates
 */
if (rex_version::compare($d2u_helper->getVersion(), '1.5.4', '<')) {
    // Rename template 02-2 to 04-2
    if (rex_config::has('d2u_helper', 'template_02-2')) {
        $result = rex_sql::factory();
        $result->setQuery('UPDATE ' . \rex::getTablePrefix() . 'config SET `key` = "template_04-2" WHERE `namespace` = "d2u_helper" AND `key` = "template_02-2";');
        $result->setQuery('UPDATE ' . \rex::getTablePrefix() . 'config SET `key` = REPLACE(`key`, "template_02_2", "template_04_2") WHERE `namespace` = "d2u_helper";');
        $result->setQuery('UPDATE ' . \rex::getTablePrefix() . 'config SET `key` = REPLACE(`key`, "template_04_2_header_slider_pics", "template_04_header_slider_pics") WHERE `namespace` = "d2u_helper";');
        $result->setQuery('UPDATE ' . \rex::getTablePrefix() . 'template SET `name` = REPLACE(`name`, "02-2 Header Slider Template", "04-2 Header Slider Template");');
        // Force template update
        if (class_exists(\TobiasKrais\D2UHelper\Template::class)) {
            ob_start();
            $d2u_templates = [];
            $d2u_templates[] = new \TobiasKrais\D2UHelper\Template('04-2',
                'Header Slider Template',
                5);
            $d2u_template_manager = new \TobiasKrais\D2UHelper\TemplateManager($d2u_templates);
            $template_02_2 = rex_config::get('d2u_helper', 'template_02-2');
            if (is_array($template_02_2) && array_key_exists('rex_template_id', $template_02_2)) {
                $d2u_template_manager->doActions('04-2', '', (int) $template_02_2['rex_template_id']);
            }
            ob_end_clean();
            rex_delete_cache();
        }
    }
}

if (class_exists(\TobiasKrais\D2UHelper\TemplateManager::class)) {
    $d2u_templates = [];
    $d2u_templates[] = new \TobiasKrais\D2UHelper\Template('00-1',
        'Big Header Template',
        24);
    $d2u_templates[] = new \TobiasKrais\D2UHelper\Template('01-1',
        'Side Picture Template',
        16);
    $d2u_templates[] = new \TobiasKrais\D2UHelper\Template('02-1',
        'Header Pic Template',
        18);
    $d2u_templates[] = new \TobiasKrais\D2UHelper\Template('03-1',
        'Immo Template - 2 Columns',
        18);
    $d2u_templates[] = new \TobiasKrais\D2UHelper\Template('03-2',
        'Immo Window Advertising Template',
        14);
    $d2u_templates[] = new \TobiasKrais\D2UHelper\Template('04-1',
        'Header Slider Template with Slogan',
        17);
    $d2u_templates[] = new \TobiasKrais\D2UHelper\Template('04-2',
        'Header Slider Template',
        23);
    $d2u_templates[] = new \TobiasKrais\D2UHelper\Template('04-3',
        'Header Slider Template with news column',
        18);
    $d2u_templates[] = new \TobiasKrais\D2UHelper\Template('05-1',
        'Double Logo Template',
        16);
    $d2u_templates[] = new \TobiasKrais\D2UHelper\Template('06-1',
        'Paper Sheet Template',
        9);
    $d2u_template_manager = new \TobiasKrais\D2UHelper\TemplateManager($d2u_templates);
    $d2u_template_manager->autoupdate();

    if (rex_version::compare($d2u_helper->getVersion(), '1.8.0', '<')) {
        if (!$d2u_helper->hasConfig('footer_color_font')) {
            $d2u_helper->setConfig('footer_color_font', '#ffffff');
            foreach ($d2u_templates as $d2u_template) {
                if ('05-1' === $d2u_template->getD2UId() && $d2u_template->isInstalled()) {
                    $d2u_helper->setConfig('footer_color_font', $d2u_helper->getConfig('navi_color_bg'));
                } elseif ('06-1' === $d2u_template->getD2UId() && $d2u_template->isInstalled()) {
                    $d2u_helper->setConfig('footer_color_font', '#777777');
                }
            }
        }
        // set footer type
        foreach ($d2u_templates as $d2u_template) {
            if (('00-1' === $d2u_template->getD2UId() && $d2u_template->isInstalled()) ||
                ('01-1' === $d2u_template->getD2UId() && $d2u_template->isInstalled()) ||
                ('04-3' === $d2u_template->getD2UId() && $d2u_template->isInstalled())) {
                $d2u_helper->setConfig('footer_type', 'box');
            } elseif ('02-1' === $d2u_template->getD2UId() && $d2u_template->isInstalled() ||
                '03-1' === $d2u_template->getD2UId() && $d2u_template->isInstalled()) {
                $d2u_helper->setConfig('footer_type', 'links_text');
            } elseif ('04-1' === $d2u_template->getD2UId() && $d2u_template->isInstalled()) {
                $d2u_helper->setConfig('footer_type', 'links_logo_address');
            } elseif ('04-2' === $d2u_template->getD2UId() && $d2u_template->isInstalled()) {
                $d2u_helper->setConfig('footer_type', 'box_logo');
            } elseif ('05-1' === $d2u_template->getD2UId() && $d2u_template->isInstalled()) {
                $d2u_helper->setConfig('footer_type', 'text');
            }
        }
    }
    if (rex_version::compare($d2u_helper->getVersion(), '1.8.8', '<')) {
        // set footer type
        foreach ($d2u_templates as $d2u_template) {
            if ('05-1' === $d2u_template->getD2UId() && $d2u_template->isInstalled()) {
                $d2u_helper->setConfig('template_header_media_manager_type', 'titelbild');
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
if ('true' === $d2u_helper->getConfig('lang_replacements_install', 'false')) {

    if (!class_exists(\TobiasKrais\D2UHelper\LangHelper::class)) {
        // Load class in case addon is deactivated
        require_once 'lib/LangHelper.php';
    }
    \TobiasKrais\D2UHelper\LangHelper::factory()->install();

    // Update to 1.8.8
    if (rex_version::compare($d2u_helper->getVersion(), '1.8.8', '<')) {
        if (rex_addon::get('sprog')->isAvailable()) {
            $sql->setQuery('DELETE FROM '. \rex::getTablePrefix() ."sprog_wildcard WHERE wildcard LIKE 'd2u_helper_module_11_%'");
        }
    }
}
/*
 *  END update translations
 */

// old plugin translation_helper still exists ? -> delete
$plugins = __DIR__ . '/plugins';
if (file_exists($plugins)) {
    rex_dir::delete($plugins);
}