<?php
// save settings
if ('save' === filter_input(INPUT_POST, 'btn_save')) {
    $settings = rex_post('settings', 'array', []);

    // Linkmap Link needs special treatment
    $link_ids = filter_input_array(INPUT_POST, ['REX_INPUT_LINK' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_REQUIRE_ARRAY]]);
    $settings['article_id_privacy_policy'] = is_array($link_ids['REX_INPUT_LINK']) ? $link_ids['REX_INPUT_LINK'][1] : 0;
    $settings['article_id_impress'] = !is_array($link_ids['REX_INPUT_LINK']) ? 0 : $link_ids['REX_INPUT_LINK'][2];
    $settings['article_id_search'] = $link_ids['REX_INPUT_LINK'][3] ?? 0;
    $linklist_ids = filter_input_array(INPUT_POST, ['REX_INPUT_LINKLIST' => ['flags' => FILTER_REQUIRE_ARRAY]]);
    if (is_array($linklist_ids['REX_INPUT_LINKLIST'])) {
        $settings['cta_box_article_ids'] = $linklist_ids['REX_INPUT_LINKLIST'][1];
    }

    // Special treatment for media fields
    $input_media = rex_post('REX_INPUT_MEDIA', 'array', []);
    $settings['custom_css'] = $input_media['custom_css'];
    $settings['template_header_pic'] = $input_media['template_header_pic'];
    $settings['template_logo'] = $input_media['template_logo'];
    $settings['template_logo_2'] = $input_media['template_logo_2'] ?? '';
    $settings['template_print_header_pic'] = $input_media['template_print_header_pic'] ?? '';
    $settings['template_print_footer_pic'] = $input_media['template_print_footer_pic'] ?? '';
    $settings['footer_logo'] = $input_media['footer_logo'] ?? '';
    $settings['header_lang_icon'] = $input_media['header_lang_icon'] ?? '';
    $settings['template_03_2_header_pic'] = $input_media['template_03_2_header_pic'] ?? '';
    $settings['template_03_2_footer_pic'] = $input_media['template_03_2_footer_pic'] ?? '';

    $input_media_list = rex_post('REX_INPUT_MEDIALIST', 'array', []);
    foreach (rex_clang::getAllIds() as $clang_id) {
        $settings['template_04_header_slider_pics_clang_'. $clang_id] = $input_media_list[$clang_id] ?? '';
    }

    // Checkbox also needs special treatment if empty
    $settings['check_media_template'] = array_key_exists('check_media_template', $settings);
    $settings['include_bootstrap4'] = array_key_exists('include_bootstrap4', $settings);
    $settings['include_jquery'] = array_key_exists('include_jquery', $settings);
    $settings['include_module'] = array_key_exists('include_module', $settings);
    $settings['lang_replacements_install'] = array_key_exists('lang_replacements_install', $settings);
    $settings['lang_wildcard_overwrite'] = array_key_exists('lang_wildcard_overwrite', $settings) ? 'true' : 'false';
    $settings['show_breadcrumbs'] = array_key_exists('show_breadcrumbs', $settings);
    $settings['show_cta_box'] = array_key_exists('show_cta_box', $settings);
    $settings['subhead_include_articlename'] = array_key_exists('subhead_include_articlename', $settings);
    $settings['submenu_use_articlename'] = array_key_exists('submenu_use_articlename', $settings);
    $settings['template_04_header_slider_pics_full_width'] = array_key_exists('template_04_header_slider_pics_full_width', $settings);

    // Save settings
    if (rex_config::set('d2u_helper', $settings)) {
        // Install / update language replacements
        if (rex_addon::get('sprog')->isAvailable()) {
            if ($settings['lang_replacements_install']) {
                \TobiasKrais\D2UHelper\LangHelper::factory()->install();
            } else {
                \TobiasKrais\D2UHelper\LangHelper::factory()->uninstall();
            }
        } elseif ($settings['lang_replacements_install']) {
            echo rex_view::error(rex_i18n::msg('d2u_helper_settings_install_sprog'));
        }

        // Install metafields
        if ('megamenu' === $settings['include_menu']) {
            $added = rex_metainfo_add_field('translate:d2u_helper_icon', 'cat_d2u_helper_icon', 10, '', rex_metainfo_table_manager::FIELD_REX_MEDIA_WIDGET, '', 'types="gif,jpg,png,webp,svg" preview="1"');
            if (true === $added) {
                rex_delete_cache();
            }
        }

        echo rex_view::success(rex_i18n::msg('form_saved'));
    } else {
        echo rex_view::error(rex_i18n::msg('form_save_error'));
    }
}
?>
<form action="<?= rex_url::currentBackendPage() ?>" method="post">
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_helper_settings') ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-system"></i></small> <?= rex_i18n::msg('d2u_helper_settings') ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
                        // Default language for translations
                        if (count(rex_clang::getAll()) > 1) {
                            $lang_options = [];
                            foreach (rex_clang::getAll() as $rex_clang) {
                                $lang_options[$rex_clang->getId()] = $rex_clang->getName();
                            }
                            \TobiasKrais\D2UHelper\BackendHelper::form_select('d2u_helper_defaultlang', 'settings[default_lang]', $lang_options, [(int) rex_config::get('d2u_helper', 'default_lang')]);
                        }

                        if (count(\TobiasKrais\D2UHelper\BackendHelper::getWYSIWYGEditors()) > 0) {
                            \TobiasKrais\D2UHelper\BackendHelper::form_select('d2u_helper_settings_editor', 'settings[editor]', \TobiasKrais\D2UHelper\BackendHelper::getWYSIWYGEditors(), [(string) rex_config::get('d2u_helper', 'editor')]);
                        }

                        \TobiasKrais\D2UHelper\BackendHelper::form_linkfield('d2u_helper_settings_article_id_privacy_policy', '1', (int) rex_config::get('d2u_helper', 'article_id_privacy_policy'), (int) rex_config::get('d2u_helper', 'default_lang'));
                        \TobiasKrais\D2UHelper\BackendHelper::form_linkfield('d2u_helper_settings_article_id_impress', '2', (int) rex_config::get('d2u_helper', 'article_id_impress'), (int) rex_config::get('d2u_helper', 'default_lang'));
                        if (rex_addon::get('search_it')->isAvailable()) {
                            \TobiasKrais\D2UHelper\BackendHelper::form_linkfield('d2u_helper_settings_article_id_search', '3', (int) rex_config::get('d2u_helper', 'article_id_search'), (int) rex_config::get('d2u_helper', 'default_lang'));
                        }
                        \TobiasKrais\D2UHelper\BackendHelper::form_checkbox('d2u_helper_settings_check_media_template', 'settings[check_media_template]', 'true', (bool) rex_config::get('d2u_helper', 'check_media_template'));
                    ?>
				</div>
			</fieldset>
			<fieldset>
				<legend><small><i class="rex-icon fa-navicon"></i></small> <?= rex_i18n::msg('d2u_helper_settings_menu') ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
                        \TobiasKrais\D2UHelper\BackendHelper::form_infotext('d2u_helper_settings_include_prevent', 'prevent_include_info');
                        $menu_options = [
                            'none' => rex_i18n::msg('d2u_helper_settings_include_menu_none'),
                            'megamenu' => rex_i18n::msg('d2u_helper_settings_include_menu_megamenu'),
                            'multilevel' => rex_i18n::msg('d2u_helper_settings_include_menu_multilevel'),
                            'slicknav' => rex_i18n::msg('d2u_helper_settings_include_menu_slicknav'),
                            'smartmenu' => rex_i18n::msg('d2u_helper_settings_include_menu_smartmenu'),
                        ];
                        \TobiasKrais\D2UHelper\BackendHelper::form_select('d2u_helper_settings_include_menu', 'settings[include_menu]', $menu_options, [(string) rex_config::get('d2u_helper', 'include_menu')]);
                        $width_options = [
                            'xs' => rex_i18n::msg('d2u_helper_settings_width_xs'),
                            'sm' => rex_i18n::msg('d2u_helper_settings_width_sm'),
                            'md' => rex_i18n::msg('d2u_helper_settings_width_md'),
                            'lg' => rex_i18n::msg('d2u_helper_settings_width_lg'),
                            'xl' => rex_i18n::msg('d2u_helper_settings_width_xl'),
                        ];
                        \TobiasKrais\D2UHelper\BackendHelper::form_select('d2u_helper_settings_menu_show', 'settings[include_menu_show]', $width_options, [(string) rex_config::get('d2u_helper', 'include_menu_show')]);
                        \TobiasKrais\D2UHelper\BackendHelper::form_checkbox('d2u_helper_settings_submenu_use_articlename', 'settings[submenu_use_articlename]', 'true', (bool) rex_config::get('d2u_helper', 'submenu_use_articlename'));
                    ?>
					<script>
						function changeSubmenuUseArticlename() {
							if($('input[name="settings\\[include_menu_smartmenu\\]"]').is(':checked')) {
								$('#settings\\[submenu_use_articlename\\]').hide();
							}
							else {
								$('#settings\\[submenu_use_articlename\\]').fadeIn();
							}
						}

						// On init
						changeSubmenuUseArticlename();
						// On change
						$('input[name="settings\\[include_menu_smartmenu\\]"]').on('change', function() {
							changeSubmenuUseArticlename();
						});
					</script>
				</div>
			</fieldset>
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-system"></i></small> <?= rex_i18n::msg('d2u_helper_settings_templates') ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
                        \TobiasKrais\D2UHelper\BackendHelper::form_checkbox('d2u_helper_settings_include_jquery', 'settings[include_jquery]', 'true', (bool) rex_config::get('d2u_helper', 'include_jquery'));
                        \TobiasKrais\D2UHelper\BackendHelper::form_checkbox('d2u_helper_settings_include_bootstrap', 'settings[include_bootstrap4]', 'true', (bool) rex_config::get('d2u_helper', 'include_bootstrap4'));
                        \TobiasKrais\D2UHelper\BackendHelper::form_checkbox('d2u_helper_settings_include_module', 'settings[include_module]', 'true', (bool) rex_config::get('d2u_helper', 'include_module'));
                        \TobiasKrais\D2UHelper\BackendHelper::form_infotext('d2u_helper_settings_include_prevent', 'prevent_include_info');
                        \TobiasKrais\D2UHelper\BackendHelper::form_mediafield('d2u_helper_settings_custom_css', 'custom_css', (string) rex_config::get('d2u_helper', 'custom_css'));

                        echo '<hr style="border-top: 1px solid #333">';
                        echo '<h3>'. rex_i18n::msg('d2u_helper_settings_header') .'</h3>';
                        \TobiasKrais\D2UHelper\BackendHelper::form_mediafield('d2u_helper_settings_template_header_pic', 'template_header_pic', (string) rex_config::get('d2u_helper', 'template_header_pic'));
                        $options_media_manager = ['' => 'Bild im Original einbinden'];
                        $sql_options_media_manager = rex_sql::factory();
                        $result_options_media_manager = $sql_options_media_manager->setQuery('SELECT name FROM ' . \rex::getTablePrefix() . 'media_manager_type ORDER BY status, name');
                        for ($i = 0; $i < $result_options_media_manager->getRows(); ++$i) {
                            $name = (string) $result_options_media_manager->getValue('name');
                            $options_media_manager[$name] = $name;
                            $result_options_media_manager->next();
                        }
                        \TobiasKrais\D2UHelper\BackendHelper::form_select('d2u_helper_settings_header_media_type', 'settings[template_header_media_manager_type]', $options_media_manager, [(string) rex_config::get('d2u_helper', 'template_header_media_manager_type')]);
                        \TobiasKrais\D2UHelper\BackendHelper::form_infotext('d2u_helper_settings_header_media_type_info', 'template_header_media_manager_type_info');
                        \TobiasKrais\D2UHelper\BackendHelper::form_mediafield('d2u_helper_settings_template_logo', 'template_logo', (string) rex_config::get('d2u_helper', 'template_logo'));
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_navi_color_bg', 'settings[navi_color_bg]', (string) rex_config::get('d2u_helper', 'navi_color_bg'), false, false, 'color');
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_navi_color_font', 'settings[navi_color_font]', (string) rex_config::get('d2u_helper', 'navi_color_font'), false, false, 'color');
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_navi_color_hover_bg', 'settings[navi_color_hover_bg]', (string) rex_config::get('d2u_helper', 'navi_color_hover_bg'), false, false, 'color');
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_navi_color_hover_font', 'settings[navi_color_hover_font]', (string) rex_config::get('d2u_helper', 'navi_color_hover_font'), false, false, 'color');
                        if (count(rex_clang::getAllIds()) > 1) {
                            \TobiasKrais\D2UHelper\BackendHelper::form_mediafield('d2u_helper_settings_header_lang_icon', 'header_lang_icon', (string) rex_config::get('d2u_helper', 'header_lang_icon'));
                        }

                        echo '<hr style="border-top: 1px solid #333">';
                        echo '<h3>'. rex_i18n::msg('d2u_helper_settings_article') .'</h3>';
                        \TobiasKrais\D2UHelper\BackendHelper::form_checkbox('d2u_helper_settings_show_breadcrumbs', 'settings[show_breadcrumbs]', 'true', (bool) rex_config::get('d2u_helper', 'show_breadcrumbs'));
                        \TobiasKrais\D2UHelper\BackendHelper::form_checkbox('d2u_helper_settings_subhead_include_articlename', 'settings[subhead_include_articlename]', 'true', (bool) rex_config::get('d2u_helper', 'subhead_include_articlename'));
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_subhead_color_bg', 'settings[subhead_color_bg]', (string) rex_config::get('d2u_helper', 'subhead_color_bg'), false, false, 'color');
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_subhead_color_font', 'settings[subhead_color_font]', (string) rex_config::get('d2u_helper', 'subhead_color_font'), false, false, 'color');
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_article_color_bg', 'settings[article_color_bg]', (string) rex_config::get('d2u_helper', 'article_color_bg'), false, false, 'color');
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_article_color_h', 'settings[article_color_h]', (string) rex_config::get('d2u_helper', 'article_color_h'), false, false, 'color');
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_article_color_box', 'settings[article_color_box]', (string) rex_config::get('d2u_helper', 'article_color_box'), false, false, 'color');

                        echo '<hr style="border-top: 1px solid #333">';
                        echo '<h3>'. rex_i18n::msg('d2u_helper_settings_footer') .'</h3>';
                        $options_footer = [
                            'box' => rex_i18n::msg('d2u_helper_settings_footer_type_option_box'),
                            'box_logo' => rex_i18n::msg('d2u_helper_settings_footer_type_option_box_logo'),
                            'links_logo_address' => rex_i18n::msg('d2u_helper_settings_footer_type_option_links_logo_address'),
                            'links_address_contact_logo' => rex_i18n::msg('d2u_helper_settings_footer_type_option_links_address_contact_logo'),
                            'simple_contact_links' => rex_i18n::msg('d2u_helper_settings_footer_type_option_simple_contact_links'),
                            'links_text' => rex_i18n::msg('d2u_helper_settings_footer_type_option_links_text'),
                            'text' => rex_i18n::msg('d2u_helper_settings_footer_type_option_text'),
                        ];
                        \TobiasKrais\D2UHelper\BackendHelper::form_select('d2u_helper_settings_footer_type', 'settings[footer_type]', $options_footer, [(string) rex_config::get('d2u_helper', 'footer_type')]);
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_color_bg', 'settings[footer_color_bg]', (string) rex_config::get('d2u_helper', 'footer_color_bg'), false, false, 'color');
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_color_box', 'settings[footer_color_box]', (string) rex_config::get('d2u_helper', 'footer_color_box'), false, false, 'color');
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_color_font', 'settings[footer_color_font]', (string) rex_config::get('d2u_helper', 'footer_color_font'), false, false, 'color');
                        \TobiasKrais\D2UHelper\BackendHelper::form_checkbox('d2u_helper_settings_show_cta_box', 'settings[show_cta_box]', 'true', (bool) rex_config::get('d2u_helper', 'show_cta_box'));
                        \TobiasKrais\D2UHelper\BackendHelper::form_linklistfield('d2u_helper_settings_article_ids_cta_box', 1, array_map('intval', explode(',', (string) rex_config::get('d2u_helper', 'cta_box_article_ids'))), rex_clang::getStartId());
                    ?>
					<script>
						function changeCTABoxFields() {
							if($('input[name="settings\\[show_cta_box\\]"]').is(':checked')) {
								$('#LINKLIST_1').fadeIn();
							}
							else {
								$('#LINKLIST_1').hide();
							}
						}

						// On init
						changeCTABoxFields();
						// On change
						$('input[name="settings\\[show_cta_box\\]"]').on('change', function() {
							changeCTABoxFields();
						});
					</script>

					<?php
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_text_company', 'settings[footer_text_company]', (string) rex_config::get('d2u_helper', 'footer_text_company'), false, false);
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_text_ceo', 'settings[footer_text_ceo]', (string) rex_config::get('d2u_helper', 'footer_text_ceo'), false, false);
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_text_street', 'settings[footer_text_street]', (string) rex_config::get('d2u_helper', 'footer_text_street'), false, false);
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_text_zip_city', 'settings[footer_text_zip_city]', (string) rex_config::get('d2u_helper', 'footer_text_zip_city'), false, false);
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_text_phone', 'settings[footer_text_phone]', (string) rex_config::get('d2u_helper', 'footer_text_phone'), false, false);
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_text_mobile', 'settings[footer_text_mobile]', (string) rex_config::get('d2u_helper', 'footer_text_mobile'), false, false);
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_text_fax', 'settings[footer_text_fax]', (string) rex_config::get('d2u_helper', 'footer_text_fax'), false, false);
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_text_email', 'settings[footer_text_email]', (string) rex_config::get('d2u_helper', 'footer_text_email'), false, false);
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_facebook_link', 'settings[footer_facebook_link]', (string) rex_config::get('d2u_helper', 'footer_facebook_link'), false, false);
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_google_link', 'settings[footer_google_link]', (string) rex_config::get('d2u_helper', 'footer_google_link'), false, false);
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_instagram_link', 'settings[footer_instagram_link]', (string) rex_config::get('d2u_helper', 'footer_instagram_link'), false, false);
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_linkedin_link', 'settings[footer_linkedin_link]', (string) rex_config::get('d2u_helper', 'footer_linkedin_link'), false, false);
                        \TobiasKrais\D2UHelper\BackendHelper::form_mediafield('d2u_helper_settings_footer_logo', 'footer_logo', (string) rex_config::get('d2u_helper', 'footer_logo'));
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_footer_text', 'settings[footer_text]', (string) rex_config::get('d2u_helper', 'footer_text'), false, false, 'text');
                    ?>
					<script>
						function footer_type_changer() {
							selection = $("select[name='settings[footer_type]']").val() ;
							if (selection === "box") {
								$("dl[id='settings[footer_color_bg]']").show();
								$("dl[id='settings[footer_color_box]']").show();
								$("dl[id='settings[footer_color_font]']").show();
								$("dl[id='settings[footer_text_company]']").hide();
								$("dl[id='settings[footer_text_ceo]']").hide();
								$("dl[id='settings[footer_text_street]']").hide();
								$("dl[id='settings[footer_text_zip_city]']").hide();
								if(!$("[name='settings[show_cta_box]']").is(':checked')) {
									$("dl[id='settings[footer_text_phone]']").hide();
									$("dl[id='settings[footer_text_mobile]']").hide();
									$("dl[id='settings[footer_text_fax]']").hide();
									$("dl[id='settings[footer_text_email]']").hide();
								}
								$("dl[id='settings[footer_text]']").hide();
								$("dl[id='MEDIA_footer_logo']").hide();
							}
							else if (selection === "box_logo") {
								$("dl[id='settings[footer_color_bg]']").show();
								$("dl[id='settings[footer_color_box]']").show();
								$("dl[id='settings[footer_color_font]']").show();
								$("dl[id='settings[footer_text_company]']").hide();
								$("dl[id='settings[footer_text_ceo]']").hide();
								$("dl[id='settings[footer_text_street]']").hide();
								$("dl[id='settings[footer_text_zip_city]']").hide();
								if(!$("[name='settings[show_cta_box]']").is(':checked')) {
									$("dl[id='settings[footer_text_phone]']").hide();
									$("dl[id='settings[footer_text_mobile]']").hide();
									$("dl[id='settings[footer_text_fax]']").hide();
									$("dl[id='settings[footer_text_email]']").hide();
								}
								$("dl[id='settings[footer_text]']").hide();
								$("dl[id='MEDIA_footer_logo']").show();
							}
							else if (selection === "links_address_contact_logo") {
								$("dl[id='settings[footer_color_bg]']").show();
								$("dl[id='settings[footer_color_box]']").show();
								$("dl[id='settings[footer_color_font]']").show();
								$("dl[id='settings[footer_text_company]']").show();
								$("dl[id='settings[footer_text_ceo]']").show();
								$("dl[id='settings[footer_text_street]']").show();
								$("dl[id='settings[footer_text_zip_city]']").show();
								$("dl[id='settings[footer_text_phone]']").show();
								$("dl[id='settings[footer_text_mobile]']").show();
								$("dl[id='settings[footer_text_fax]']").show();
								$("dl[id='settings[footer_text_email]']").show();
								$("dl[id='settings[footer_text]']").hide();
								$("dl[id='MEDIA_footer_logo']").show();
							}
							else if (selection === "links_logo_address") {
								$("dl[id='settings[footer_color_bg]']").show();
								$("dl[id='settings[footer_color_box]']").show();
								$("dl[id='settings[footer_color_font]']").show();
								$("dl[id='settings[footer_text_company]']").show();
								$("dl[id='settings[footer_text_ceo]']").show();
								$("dl[id='settings[footer_text_street]']").show();
								$("dl[id='settings[footer_text_zip_city]']").show();
								$("dl[id='settings[footer_text_phone]']").show();
								$("dl[id='settings[footer_text_mobile]']").show();
								$("dl[id='settings[footer_text_fax]']").show();
								$("dl[id='settings[footer_text_email]']").show();
								$("dl[id='settings[footer_text]']").hide();
								$("dl[id='MEDIA_footer_logo']").show();
							}
							else if (selection === "links_text") {
								$("dl[id='settings[footer_color_bg]']").show();
								$("dl[id='settings[footer_color_box]']").show();
								$("dl[id='settings[footer_color_font]']").show();
								$("dl[id='settings[footer_text_company]']").hide();
								$("dl[id='settings[footer_text_ceo]']").hide();
								$("dl[id='settings[footer_text_street]']").hide();
								$("dl[id='settings[footer_text_zip_city]']").hide();
								if(!$("[name='settings[show_cta_box]']").is(':checked')) {
									$("dl[id='settings[footer_text_phone]']").hide();
									$("dl[id='settings[footer_text_mobile]']").hide();
									$("dl[id='settings[footer_text_fax]']").hide();
									$("dl[id='settings[footer_text_email]']").hide();
								}
								$("dl[id='settings[footer_text]']").show();
								$("dl[id='MEDIA_footer_logo']").hide();
							}
							else if (selection === "text") {
								$("dl[id='settings[footer_color_bg]']").hide();
								$("dl[id='settings[footer_color_box]']").hide();
								$("dl[id='settings[footer_color_font]']").show();
								$("dl[id='settings[footer_text_company]']").hide();
								$("dl[id='settings[footer_text_ceo]']").hide();
								$("dl[id='settings[footer_text_street]']").hide();
								$("dl[id='settings[footer_text_zip_city]']").hide();
								if(!$("[name='settings[show_cta_box]']").is(':checked')) {
									$("dl[id='settings[footer_text_phone]']").hide();
									$("dl[id='settings[footer_text_mobile]']").hide();
									$("dl[id='settings[footer_text_fax]']").hide();
									$("dl[id='settings[footer_text_email]']").hide();
								}
								$("dl[id='settings[footer_text]']").show();
								$("dl[id='MEDIA_footer_logo']").hide();
							}

							if($("[name='settings[show_cta_box]']").is(':checked')) {
								$("dl[id='settings[footer_text_phone]']").show();
								$("dl[id='settings[footer_text_mobile]']").show();
								$("dl[id='settings[footer_text_fax]']").show();
								$("dl[id='settings[footer_text_email]']").show();
							}
						}

						// Hide on document load
						$(document).ready(function() {
							footer_type_changer();
						});

						// Hide on selection change
						$("select[name='settings[footer_type]").on('change', function(e) {
							footer_type_changer();
						});
						$("input[name='settings[show_cta_box]").on('change', function(e) {
							footer_type_changer();
						});
					</script>
					<?php
                        // Template specific part
                        $d2u_templates = \TobiasKrais\D2UHelper\TemplateManager::getD2UHelperTemplates();
                        foreach ($d2u_templates as $d2u_template) {
                            $d2u_template->initRedaxoContext(rex_addon::get('d2u_helper'), 'templates/');
                            $d2u_template_ids_for_settings = ['02-1', '03-1', '03-2', '04-1', '04-2', '04-3', '05-1'];
                            if (in_array($d2u_template->getD2UId(), $d2u_template_ids_for_settings, true) && $d2u_template->isInstalled()) {
                                echo '<hr style="border-top: 1px solid #333">';
                                echo '<h3>'. rex_i18n::msg('d2u_helper_settings_template') ." '". $d2u_template->getD2UId() .' '. $d2u_template->getName() ."'</h3>";
                                if ('02-1' === $d2u_template->getD2UId() || '04-3' === $d2u_template->getD2UId()) {
                                    $navi_pos_options = [
                                        'bottom' => rex_i18n::msg('d2u_helper_settings_template_navi_pos_bottom'),
                                        'top' => rex_i18n::msg('d2u_helper_settings_template_navi_pos_top'),
                                    ];
                                    \TobiasKrais\D2UHelper\BackendHelper::form_select('d2u_helper_settings_template_navi_pos_text', 'settings[template_navi_pos]', $navi_pos_options, [(string) rex_config::get('d2u_helper', 'template_navi_pos')]);
                                }
                                if ('03-1' === $d2u_template->getD2UId()) {
                                    \TobiasKrais\D2UHelper\BackendHelper::form_mediafield('d2u_helper_settings_template_03_1_print_header_pic', 'template_print_header_pic', (string) rex_config::get('d2u_helper', 'template_print_header_pic'));
                                    \TobiasKrais\D2UHelper\BackendHelper::form_mediafield('d2u_helper_settings_template_03_1_print_footer_pic', 'template_print_footer_pic', (string) rex_config::get('d2u_helper', 'template_print_footer_pic'));
                                }
                                if ('03-2' === $d2u_template->getD2UId()) {
                                    \TobiasKrais\D2UHelper\BackendHelper::form_mediafield('d2u_helper_settings_template_03_2_header_pic', 'template_03_2_header_pic', (string) rex_config::get('d2u_helper', 'template_03_2_header_pic'));
                                    \TobiasKrais\D2UHelper\BackendHelper::form_mediafield('d2u_helper_settings_template_03_2_footer_pic', 'template_03_2_footer_pic', (string) rex_config::get('d2u_helper', 'template_03_2_footer_pic'));
                                    \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_template_03_2_margin_top', 'settings[template_03_2_margin_top]', (string) rex_config::get('d2u_helper', 'template_03_2_margin_top'), false, false, 'number');
                                    \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_template_03_2_time_show_ad', 'settings[template_03_2_time_show_ad]', (string) rex_config::get('d2u_helper', 'template_03_2_time_show_ad'), false, false, 'number');
                                }
                                if ('04-1' === $d2u_template->getD2UId() || '04-2' === $d2u_template->getD2UId() || '04-3' === $d2u_template->getD2UId()) {
                                    \TobiasKrais\D2UHelper\BackendHelper::form_checkbox('d2u_helper_settings_template_04_slider_pics_width', 'settings[template_04_header_slider_pics_full_width]', 'full', 'full' === rex_config::get('d2u_helper', 'template_04_header_slider_pics_full_width'));
                                    // Language specific settings
                                    foreach (rex_clang::getAll() as $rex_clang) {
                                        echo '<div style="margin-bottom: 1em;">';
                                        echo '<dl class="rex-form-group form-group" id="specific_clang'. $rex_clang->getId() .'">';
                                        echo '<dt><label></label></dt>';
                                        echo '<dd><b>'. rex_i18n::msg('d2u_helper_settings_lang_specific') .' '. $rex_clang->getName() .'</b></dd>';
                                        echo '</dl>';
                                        $slider_pics_unfiltered = preg_grep('/^\s*$/s', explode(',', (string) rex_config::get('d2u_helper', 'template_04_header_slider_pics_clang_'. $rex_clang->getId())), PREG_GREP_INVERT);
                                        $slider_pics = is_array($slider_pics_unfiltered) ? $slider_pics_unfiltered : [];
                                        \TobiasKrais\D2UHelper\BackendHelper::form_imagelistfield('d2u_helper_settings_template_04_slider_pics', $rex_clang->getId(), $slider_pics);
                                        if ('04-1' === $d2u_template->getD2UId()) {
                                            $options_slogan = [
                                                'slider' => rex_i18n::msg('d2u_helper_settings_template_slogan_position_slider'),
                                                'top' => rex_i18n::msg('d2u_helper_settings_template_slogan_position_top'),
                                            ];
                                            \TobiasKrais\D2UHelper\BackendHelper::form_select('d2u_helper_settings_template_slogan_position', 'settings[template_slogan_position]', $options_slogan, [(string) rex_config::get('d2u_helper', 'template_slogan_position', 'slider')]);
                                            \TobiasKrais\D2UHelper\BackendHelper::form_textarea('d2u_helper_settings_template_04_1_slogan', 'settings[template_04_1_slider_slogan_clang_' . $rex_clang->getId() . ']', (string) rex_config::get('d2u_helper', 'template_04_1_slider_slogan_clang_' . $rex_clang->getId()), 3, false, false, false);
                                        }
                                        echo '</div>';
                                    }
                                }
                                if ('04-3' === $d2u_template->getD2UId() && rex_addon::get('d2u_news')->isAvailable()) {
                                    $news_categories = \D2U_News\Category::getAll(rex_clang::getCurrentId());
                                    $news_options = [];
                                    foreach ($news_categories as $news_category) {
                                        $news_options[$news_category->category_id] = $news_category->name;
                                    }
                                    \TobiasKrais\D2UHelper\BackendHelper::form_select('d2u_helper_settings_template_news_category', 'settings[template_news_category]', $news_options, [(string) rex_config::get('d2u_helper', 'template_news_category')]);
                                }
                                if ('05-1' === $d2u_template->getD2UId()) {
                                    \TobiasKrais\D2UHelper\BackendHelper::form_mediafield('d2u_helper_settings_template_05_1_logo', 'template_logo_2', (string) rex_config::get('d2u_helper', 'template_logo_2'));
                                    \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_template_05_1_logo_link', 'settings[template_logo_2_link]', (string) rex_config::get('d2u_helper', 'template_logo_2_link'), false, false);
                                    \TobiasKrais\D2UHelper\BackendHelper::form_textarea('d2u_helper_settings_template_05_1_info_text', 'settings[template_05_1_info_text]', (string) rex_config::get('d2u_helper', 'template_05_1_info_text'), 5, false, false, true);
                                }
                            }
                        }
                    ?>
				</div>
			</fieldset>
			<fieldset>
				<legend><small><i class="rex-icon fa-google"></i></small> <?= rex_i18n::msg('d2u_helper_settings_analytics') ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
                        \TobiasKrais\D2UHelper\BackendHelper::form_input('d2u_helper_settings_analytics_maps_key', 'settings[maps_key]', (string) rex_config::get('d2u_helper', 'maps_key'), false, false, 'text');
                    ?>
				</div>
			</fieldset>
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-language"></i></small> <?= rex_i18n::msg('d2u_helper_settings_lang_replacements') ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
                        \TobiasKrais\D2UHelper\BackendHelper::form_checkbox('d2u_helper_lang_install', 'settings[lang_replacements_install]', 'true', (bool) rex_config::get('d2u_helper', 'lang_replacements_install'));
                        \TobiasKrais\D2UHelper\BackendHelper::form_checkbox('d2u_helper_lang_wildcard_overwrite', 'settings[lang_wildcard_overwrite]', 'true', (bool) rex_config::get('d2u_helper', 'lang_wildcard_overwrite'));
                        foreach (rex_clang::getAll() as $rex_clang) {
                            echo '<dl class="rex-form-group form-group" id="settings[lang_replacement_'. $rex_clang->getId() .']">';
                            echo '<dt><label>'. $rex_clang->getName() .'</label></dt>';
                            echo '<dd>';
                            echo '<select class="form-control" name="settings[lang_replacement_'. $rex_clang->getId() .']">';
                            $replacement_options = [
                                'd2u_helper_lang_english' => 'english',
                                'd2u_helper_lang_french' => 'french',
                                'd2u_helper_lang_german' => 'german',
                                'd2u_helper_lang_dutch' => 'dutch',
                                'd2u_helper_lang_spanish' => 'spanish',
                                'd2u_helper_lang_russian' => 'russian',
                                'd2u_helper_lang_chinese' => 'chinese',
                            ];
                            foreach ($replacement_options as $key => $value) {
                                $selected = $value === rex_config::get('d2u_helper', 'lang_replacement_'. $rex_clang->getId(), 'none') ? ' selected="selected"' : '';
                                echo '<option value="'. $value .'"'. $selected .'>'. rex_i18n::msg('d2u_helper_lang_replacements_install') .' '. rex_i18n::msg($key) .'</option>';
                            }
                            echo '</select>';
                            echo '</dl>';
                        }
                    ?>
					<script>
						function changeLangType() {
							if($('input[name="settings\\[lang_replacements_install\\]"]').is(':checked')) {
								<?php
                                    foreach (rex_clang::getAll() as $rex_clang) {
                                        echo "$('#settings\\\\[lang_wildcard_overwrite\\\\]').fadeIn();";
                                        echo "$('#settings\\\\[lang_replacement_". $rex_clang->getId() ."\\\\]').fadeIn();";
                                    }
                                ?>

							}
							else {
								<?php
                                    foreach (rex_clang::getAll() as $rex_clang) {
                                        echo "$('#settings\\\\[lang_wildcard_overwrite\\\\]').hide();";
                                        echo "$('#settings\\\\[lang_replacement_". $rex_clang->getId() ."\\\\]').hide();";
                                    }
                                ?>
							}
						}

						// On init
						changeLangType();
						// On change
						$('input[name="settings\\[lang_replacements_install\\]"]').on('change', function() {
							changeLangType();
						});
					</script>
				</div>
			</fieldset>
		</div>
		<footer class="panel-footer">
			<div class="rex-form-panel-footer">
				<div class="btn-toolbar">
					<button class="btn btn-save rex-form-aligned" type="submit" name="btn_save" value="save"><?= rex_i18n::msg('form_save') ?></button>
				</div>
			</div>
		</footer>
	</div>
</form>
<?php
    echo \TobiasKrais\D2UHelper\BackendHelper::getCSS();
    echo \TobiasKrais\D2UHelper\BackendHelper::getJS();
