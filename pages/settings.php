<?php
// save settings
if (filter_input(INPUT_POST, "btn_save") == 'save') {
	$settings = (array) rex_post('settings', 'array', []);

	// Linkmap Link needs special treatment
	$link_ids = filter_input_array(INPUT_POST, ['REX_INPUT_LINK'=> ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_REQUIRE_ARRAY]]);
	$settings['article_id_privacy_policy'] = $link_ids["REX_INPUT_LINK"][1];
	$settings['article_id_impress'] = $link_ids["REX_INPUT_LINK"][2];
	$linklist_ids = filter_input_array(INPUT_POST, ['REX_INPUT_LINKLIST'=> ['flags' => FILTER_REQUIRE_ARRAY]]);
	$settings['template_02_3_footer_linklist'] = $linklist_ids["REX_INPUT_LINKLIST"][1];

	// Special treatment for media fields
	$input_media = (array) rex_post('REX_INPUT_MEDIA', 'array', []);
	$settings['custom_css'] = $input_media['custom_css'];
	$settings['template_header_pic'] = $input_media['template_header_pic'];
	$settings['template_logo'] = $input_media['template_logo'];
	$settings['template_logo_2'] = isset($input_media['template_logo_2']) ? $input_media['template_logo_2'] : '';
	$settings['template_print_header_pic'] = isset($input_media['template_print_header_pic']) ? $input_media['template_print_header_pic'] : '';
	$settings['template_print_footer_pic'] = isset($input_media['template_print_footer_pic']) ? $input_media['template_print_footer_pic'] : '';
	$settings['template_04_2_facebook_icon'] = isset($input_media['template_04_2_facebook_icon']) ? $input_media['template_04_2_facebook_icon'] : '';
	$settings['footer_logo'] = isset($input_media['footer_logo']) ? $input_media['footer_logo'] : '';
	$settings['template_03_2_header_pic'] = isset($input_media['template_03_2_header_pic']) ? $input_media['template_03_2_header_pic'] : '';
	$settings['template_03_2_footer_pic'] = isset($input_media['template_03_2_footer_pic']) ? $input_media['template_03_2_footer_pic'] : '';

	$input_media_list = (array) rex_post('REX_INPUT_MEDIALIST', 'array', []);
	foreach(rex_clang::getAllIds() as $clang_id) {
		$settings['template_04_header_slider_pics_clang_'. $clang_id] = isset($input_media_list[$clang_id]) ? $input_media_list[$clang_id] : '';
	}

	// Checkbox also need special treatment if empty
	$settings['check_media_template'] = array_key_exists('check_media_template', $settings);
	$settings['include_bootstrap4'] = array_key_exists('include_bootstrap4', $settings);
	$settings['include_jquery'] = array_key_exists('include_jquery', $settings);
	$settings['include_module'] = array_key_exists('include_module', $settings);
	$settings['include_menu_multilevel'] = array_key_exists('include_menu_multilevel', $settings);
	$settings['include_menu_slicknav'] = array_key_exists('include_menu_slicknav', $settings);
	$settings['include_menu_smartmenu'] = array_key_exists('include_menu_smartmenu', $settings);
	$settings['lang_replacements_install'] = array_key_exists('lang_replacements_install', $settings);
	$settings['lang_wildcard_overwrite'] = array_key_exists('lang_wildcard_overwrite', $settings) ? "true" : "false";
	$settings['show_breadcrumbs'] = array_key_exists('show_breadcrumbs', $settings);
	$settings['subhead_include_articlename'] = array_key_exists('subhead_include_articlename', $settings);
	$settings['submenu_use_articlename'] = array_key_exists('submenu_use_articlename', $settings);
	$settings['template_04_header_slider_pics_full_width'] = array_key_exists('template_04_header_slider_pics_full_width', $settings);
	
	// Save settings
	if(rex_config::set("d2u_helper", $settings)) {
		// Install / update language replacements
		if(rex_addon::get('sprog')->isAvailable()) {
			if($settings['lang_replacements_install']) {
				d2u_helper_lang_helper::factory()->install();
			}
			else {
				d2u_helper_lang_helper::factory()->uninstall();
			}
		}
		else if ($settings['lang_replacements_install']) {
			echo rex_view::error(rex_i18n::msg('d2u_helper_settings_install_sprog'));
		}

		echo rex_view::success(rex_i18n::msg('form_saved'));
	}
	else {
		echo rex_view::error(rex_i18n::msg('form_save_error'));
	}
}
?>
<form action="<?php print rex_url::currentBackendPage(); ?>" method="post">
	<div class="panel panel-edit">
		<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_helper_settings'); ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-system"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						// Default language for translations
						if(count(rex_clang::getAll()) > 1) {
							$lang_options = [];
							foreach(rex_clang::getAll() as $rex_clang) {
								$lang_options[$rex_clang->getId()] = $rex_clang->getName();
							}
							d2u_addon_backend_helper::form_select('d2u_helper_defaultlang', 'settings[default_lang]', $lang_options, [$this->getConfig('default_lang')]);
						}
							
						if(count(d2u_addon_backend_helper::getWYSIWYGEditors()) > 0) {
							d2u_addon_backend_helper::form_select('d2u_helper_settings_editor', 'settings[editor]', d2u_addon_backend_helper::getWYSIWYGEditors(), [$this->getConfig('editor')]);
						}
						
						d2u_addon_backend_helper::form_linkfield('d2u_helper_settings_article_id_privacy_policy', '1', $this->getConfig('article_id_privacy_policy'), rex_config::get("d2u_helper", "article_id_privacy_policy", rex_clang::getStartId()));
						d2u_addon_backend_helper::form_linkfield('d2u_helper_settings_article_id_impress', '2', $this->getConfig('article_id_impress'), rex_config::get("d2u_helper", "article_id_impress", rex_clang::getStartId()));
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_check_media_template', 'settings[check_media_template]', 'true', $this->getConfig('check_media_template') == 'true');
					?>
				</div>
			</fieldset>
			<fieldset>
				<legend><small><i class="rex-icon fa-navicon"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings_menu'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_include_menu_multilevel', 'settings[include_menu_multilevel]', 'true', $this->getConfig('include_menu_multilevel') == 'true');
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_include_menu_slicknav', 'settings[include_menu_slicknav]', 'true', $this->getConfig('include_menu_slicknav') == 'true');
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_include_menu_smartmenu', 'settings[include_menu_smartmenu]', 'true', $this->getConfig('include_menu_smartmenu') == 'true');
						d2u_addon_backend_helper::form_infotext('d2u_helper_settings_include_prevent', 'prevent_include_info');
						$width_options = [
							"xs" => rex_i18n::msg('d2u_helper_settings_width_xs'),
							"sm" => rex_i18n::msg('d2u_helper_settings_width_sm'),
							"md" => rex_i18n::msg('d2u_helper_settings_width_md'),
							"lg" => rex_i18n::msg('d2u_helper_settings_width_lg'),
							"xl" => rex_i18n::msg('d2u_helper_settings_width_xl')
						];
						d2u_addon_backend_helper::form_select('d2u_helper_settings_menu_show', 'settings[include_menu_show]', $width_options, [$this->getConfig('include_menu_show')]);
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_submenu_use_articlename', 'settings[submenu_use_articlename]', 'true', $this->getConfig('submenu_use_articlename') == 'true');
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
				<legend><small><i class="rex-icon rex-icon-system"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings_templates'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_include_jquery', 'settings[include_jquery]', 'true', $this->getConfig('include_jquery') == 'true');
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_include_bootstrap', 'settings[include_bootstrap4]', 'true', $this->getConfig('include_bootstrap4') == 'true');
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_include_module', 'settings[include_module]', 'true', $this->getConfig('include_module') == 'true');
						d2u_addon_backend_helper::form_infotext('d2u_helper_settings_include_prevent', 'prevent_include_info');
						print '<hr style="border-top: 1px solid #333">';
						d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_header_pic', 'template_header_pic', $this->getConfig('template_header_pic'));
						d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_logo', 'template_logo', $this->getConfig('template_logo'));
						print '<hr style="border-top: 1px solid #333">';
						d2u_addon_backend_helper::form_input('d2u_helper_settings_navi_color_bg', 'settings[navi_color_bg]', $this->getConfig('navi_color_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_navi_color_font', 'settings[navi_color_font]', $this->getConfig('navi_color_font'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_navi_color_hover_bg', 'settings[navi_color_hover_bg]', $this->getConfig('navi_color_hover_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_navi_color_hover_font', 'settings[navi_color_hover_font]', $this->getConfig('navi_color_hover_font'), FALSE, FALSE, "color");
						print '<hr style="border-top: 1px solid #333">';
						print '<h3>'. rex_i18n::msg('d2u_helper_settings_article') .'</h3>';
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_show_breadcrumbs', 'settings[show_breadcrumbs]', 'true', $this->getConfig('show_breadcrumbs') == 'true');
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_subhead_include_articlename', 'settings[subhead_include_articlename]', 'true', $this->getConfig('subhead_include_articlename') == 'true');
						d2u_addon_backend_helper::form_input('d2u_helper_settings_subhead_color_bg', 'settings[subhead_color_bg]', $this->getConfig('subhead_color_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_subhead_color_font', 'settings[subhead_color_font]', $this->getConfig('subhead_color_font'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_article_color_bg', 'settings[article_color_bg]', $this->getConfig('article_color_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_article_color_h', 'settings[article_color_h]', $this->getConfig('article_color_h'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_article_color_box', 'settings[article_color_box]', $this->getConfig('article_color_box'), FALSE, FALSE, "color");

						print '<hr style="border-top: 1px solid #333">';
						print '<h3>'. rex_i18n::msg('d2u_helper_settings_footer') .'</h3>';
						$options_footer = [
							'box' => rex_i18n::msg('d2u_helper_settings_footer_type_option_box'),
							'box_logo' => rex_i18n::msg('d2u_helper_settings_footer_type_option_box_logo'),
							'links_logo_address' => rex_i18n::msg('d2u_helper_settings_footer_type_option_links_logo_address'),
							'links_address_contact_logo' => rex_i18n::msg('d2u_helper_settings_footer_type_option_links_address_contact_logo'),
							'links_text' => rex_i18n::msg('d2u_helper_settings_footer_type_option_links_text'),
						];
						d2u_addon_backend_helper::form_select('d2u_helper_settings_footer_type', 'settings[footer_type]', $options_footer, [$this->getConfig('footer_type')]);
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_color_bg', 'settings[footer_color_bg]', $this->getConfig('footer_color_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_color_box', 'settings[footer_color_box]', $this->getConfig('footer_color_box'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_color_font', 'settings[footer_color_font]', $this->getConfig('footer_color_font'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_text_company', 'settings[footer_text_company]', $this->getConfig('footer_text_company'), FALSE, FALSE);
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_text_ceo', 'settings[footer_text_ceo]', $this->getConfig('footer_text_ceo'), FALSE, FALSE);
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_text_street', 'settings[footer_text_street]', $this->getConfig('footer_text_street'), FALSE, FALSE);
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_text_zip_city', 'settings[footer_text_zip_city]', $this->getConfig('footer_text_zip_city'), FALSE, FALSE);
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_text_phone', 'settings[footer_text_phone]', $this->getConfig('footer_text_phone'), FALSE, FALSE);
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_text_mobile', 'settings[footer_text_mobile]', $this->getConfig('footer_text_mobile'), FALSE, FALSE);
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_text_fax', 'settings[footer_text_fax]', $this->getConfig('footer_text_fax'), FALSE, FALSE);
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_text_email', 'settings[footer_text_email]', $this->getConfig('footer_text_email'), FALSE, FALSE);
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_text', 'settings[footer_text]', $this->getConfig('footer_text'), FALSE, FALSE, "text");
						d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_footer_logo', 'footer_logo', $this->getConfig('footer_logo'));
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_facebook_link', 'settings[footer_facebook_link]', $this->getConfig('footer_facebook_link'), FALSE, FALSE);
						d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_footer_facebook_icon', 'footer_facebook_icon', $this->getConfig('footer_facebook_icon'));
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
								$("dl[id='settings[footer_text_phone]']").hide();
								$("dl[id='settings[footer_text_mobile]']").hide();
								$("dl[id='settings[footer_text_fax]']").hide();
								$("dl[id='settings[footer_text_email]']").hide();
								$("dl[id='settings[footer_text]']").hide();
								$("dl[id='settings[footer_facebook_icon]']").hide();
								$("dl[id='settings[footer_facebook_link]']").hide();
								$("dl[id='settings[footer_logo]']").hide();
							}
							else if (selection === "box_logo") {
								$("dl[id='settings[footer_color_bg]']").show();
								$("dl[id='settings[footer_color_box]']").show();
								$("dl[id='settings[footer_color_font]']").show();
								$("dl[id='settings[footer_text_company]']").hide();
								$("dl[id='settings[footer_text_ceo]']").hide();
								$("dl[id='settings[footer_text_street]']").hide();
								$("dl[id='settings[footer_text_zip_city]']").hide();
								$("dl[id='settings[footer_text_phone]']").hide();
								$("dl[id='settings[footer_text_mobile]']").hide();
								$("dl[id='settings[footer_text_fax]']").hide();
								$("dl[id='settings[footer_text_email]']").hide();
								$("dl[id='settings[footer_text]']").hide();
								$("dl[id='settings[footer_facebook_icon]']").show();
								$("dl[id='settings[footer_facebook_link]']").show();
								$("dl[id='settings[footer_logo]']").show();
							}
							else if (selection === "links_address_contact_logo") {
								$("dl[id='settings[footer_color_bg]']").show();
								$("dl[id='settings[footer_color_box]']").hide();
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
								$("dl[id='settings[footer_facebook_icon]']").hide();
								$("dl[id='settings[footer_facebook_link]']").show();
								$("dl[id='settings[footer_logo]']").show();
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
								$("dl[id='settings[footer_facebook_icon]']").hide();
								$("dl[id='settings[footer_facebook_link]']").show();
								$("dl[id='settings[footer_logo]']").show();
							}
							else if (selection === "links_text") {
								$("dl[id='settings[footer_color_bg]']").show();
								$("dl[id='settings[footer_color_box]']").show();
								$("dl[id='settings[footer_color_font]']").show();
								$("dl[id='settings[footer_text_company]']").hide();
								$("dl[id='settings[footer_text_ceo]']").hide();
								$("dl[id='settings[footer_text_street]']").hide();
								$("dl[id='settings[footer_text_zip_city]']").hide();
								$("dl[id='settings[footer_text_phone]']").hide();
								$("dl[id='settings[footer_text_mobile]']").hide();
								$("dl[id='settings[footer_text_fax]']").hide();
								$("dl[id='settings[footer_text_email]']").hide();
								$("dl[id='settings[footer_text]']").show();
								$("dl[id='settings[footer_facebook_icon]']").hide();
								$("dl[id='settings[footer_facebook_link]']").hide();
								$("dl[id='settings[footer_logo]']").hide();
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
					</script>
					<?php
						print '<hr style="border-top: 1px solid #333">';
						d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_custom_css', 'custom_css', $this->getConfig('custom_css'));
						
						// Template specific part
						$d2u_templates = D2UTemplateManager::getD2UHelperTemplates();
						foreach($d2u_templates as $d2u_template) {
							$d2u_template->initRedaxoContext($this, "templates/");
							$d2u_template_ids_for_settings = ["02-1", "03-1", "03-2", "04-1", "04-2", "04-3", "05-1"];
							if(in_array($d2u_template->getD2UId(), $d2u_template_ids_for_settings) && $d2u_template->isInstalled()) {
								print '<hr style="border-top: 1px solid #333">';
								print '<dl class="rex-form-group form-group" id="'. $fieldname .'">';
								print '<dt><label></label></dt>';
								print '<dd><b>' . rex_i18n::msg('d2u_helper_settings_template') ." '". $d2u_template->getName() . "'</b></dd>";
								print '</dl>';
								if(($d2u_template->getD2UId() === "02-1" || $d2u_template->getD2UId() === "04-3") && $d2u_template->isInstalled()) {
									$navi_pos_options = [
										"bottom" => rex_i18n::msg('d2u_helper_settings_template_navi_pos_bottom'),
										"top" => rex_i18n::msg('d2u_helper_settings_template_navi_pos_top')
									];
									d2u_addon_backend_helper::form_select('d2u_helper_settings_template_navi_pos_text', 'settings[template_navi_pos]', $navi_pos_options, [$this->getConfig('template_navi_pos')]);
								}
								if($d2u_template->getD2UId() === "03-1" && $d2u_template->isInstalled()) {
									d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_03_1_print_header_pic', 'template_print_header_pic', $this->getConfig('template_print_header_pic'));
									d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_03_1_print_footer_pic', 'template_print_footer_pic', $this->getConfig('template_print_footer_pic'));
								}
								if($d2u_template->getD2UId() === "03-2" && $d2u_template->isInstalled()) {
									d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_03_2_header_pic', 'template_03_2_header_pic', $this->getConfig('template_03_2_header_pic'));
									d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_03_2_footer_pic', 'template_03_2_footer_pic', $this->getConfig('template_03_2_footer_pic'));
									d2u_addon_backend_helper::form_input('d2u_helper_settings_template_03_2_margin_top', 'settings[template_03_2_margin_top]', $this->getConfig('template_03_2_margin_top'), FALSE, FALSE, "number");
									d2u_addon_backend_helper::form_input('d2u_helper_settings_template_03_2_time_show_ad', 'settings[template_03_2_time_show_ad]', $this->getConfig('template_03_2_time_show_ad'), FALSE, FALSE, "number");
								}
								if(($d2u_template->getD2UId() === "04-1" || $d2u_template->getD2UId() === "04-2" || $d2u_template->getD2UId() === "04-3") && $d2u_template->isInstalled()) {
									d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_template_04_slider_pics_width', 'settings[template_04_header_slider_pics_full_width]', 'full', $this->getConfig('template_04_header_slider_pics_full_width') == 'full');
									// Language specific settings
									foreach(rex_clang::getAll() as $rex_clang) {
										print '<div style="background-color: white; margin-bottom: 1em; padding: 1em;">';
										print '<dl class="rex-form-group form-group" id="specific_clang'. $rex_clang->getId() .'">';
										print '<dt><label></label></dt>';
										print '<dd><b>'. rex_i18n::msg('d2u_helper_settings_lang_specific') .' '. $rex_clang->getName() .'</b></dd>';
										print '</dl>';
										$slider_pics = preg_grep('/^\s*$/s', explode(",", $this->getConfig('template_04_header_slider_pics_clang_'. $rex_clang->getId())), PREG_GREP_INVERT);
										d2u_addon_backend_helper::form_medialistfield('d2u_helper_settings_template_04_slider_pics', $rex_clang->getId(), $slider_pics);
										if($d2u_template->getD2UId() === "04-1" && $d2u_template->isInstalled()) {
											d2u_addon_backend_helper::form_textarea('d2u_helper_settings_template_04_1_slogan', 'settings[template_04_1_slider_slogan_clang_' . $rex_clang->getId() . ']', $this->getConfig('template_04_1_slider_slogan_clang_' . $rex_clang->getId()), 3, FALSE, FALSE, FALSE);
											d2u_addon_backend_helper::form_textarea('d2u_helper_settings_template_04_1_footer_text', 'settings[template_04_1_footer_text_clang_' . $rex_clang->getId() . ']', $this->getConfig('template_04_1_footer_text_clang_' . $rex_clang->getId()), 8, FALSE, FALSE, FALSE);
										}
										print '</div>';
									}
								}
								if($d2u_template->getD2UId() === "04-3" && $d2u_template->isInstalled() && rex_addon::get('d2u_news')->isAvailable()) {
									$news_categories = \D2U_News\Category::getAll(rex_clang::getCurrentId());
									$news_options = [];
									foreach ($news_categories as $news_category) {
										$news_options[$news_category->category_id] = $news_category->name;
									}
									d2u_addon_backend_helper::form_select('d2u_helper_settings_template_news_category', 'settings[template_news_category]', $news_options, [$this->getConfig('template_news_category')]);
								}
								if($d2u_template->getD2UId() === "05-1" && $d2u_template->isInstalled()) {
									d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_05_1_logo', 'template_logo_2', $this->getConfig('template_logo_2'));
									d2u_addon_backend_helper::form_input('d2u_helper_settings_template_05_1_logo_link', 'settings[template_logo_2_link]', $this->getConfig('template_logo_2_link'), FALSE, FALSE);
									d2u_addon_backend_helper::form_input('d2u_helper_settings_template_05_1_footer_text', 'settings[template_05_1_footer_text]', $this->getConfig('template_05_1_footer_text'), FALSE, FALSE, "text");
									d2u_addon_backend_helper::form_textarea('d2u_helper_settings_template_05_1_info_text', 'settings[template_05_1_info_text]', $this->getConfig('template_05_1_info_text'), 5, FALSE, FALSE, TRUE);
								}
							}
						}
					?>
				</div>
			</fieldset>
			<fieldset>
				<legend><small><i class="rex-icon fa-google"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings_analytics'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						d2u_addon_backend_helper::form_input('d2u_helper_settings_analytics_analytics', 'settings[google_analytics]', $this->getConfig('google_analytics'), FALSE, FALSE, "text");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_analytics_maps_key', 'settings[maps_key]', $this->getConfig('maps_key'), FALSE, FALSE, "text");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_wiredmetrics_costumerno', 'settings[wiredminds_tracking_account_id]', $this->getConfig('wiredminds_tracking_account_id'), FALSE, FALSE, "text");
					?>
				</div>
			</fieldset>
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-language"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings_lang_replacements'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						d2u_addon_backend_helper::form_checkbox('d2u_helper_lang_install', 'settings[lang_replacements_install]', 'true', $this->getConfig('lang_replacements_install') == 'true');
						d2u_addon_backend_helper::form_checkbox('d2u_helper_lang_wildcard_overwrite', 'settings[lang_wildcard_overwrite]', 'true', $this->getConfig('lang_wildcard_overwrite') == 'true');
						foreach(rex_clang::getAll() as $rex_clang) {
							print '<dl class="rex-form-group form-group" id="settings[lang_replacement_'. $rex_clang->getId() .']">';
							print '<dt><label>'. $rex_clang->getName() .'</label></dt>';
							print '<dd>';
							print '<select class="form-control" name="settings[lang_replacement_'. $rex_clang->getId() .']">';
							$replacement_options = [
								'd2u_helper_lang_english' => 'english',
								'd2u_helper_lang_french' => 'french',
								'd2u_helper_lang_german' => 'german',
								'd2u_helper_lang_spanish' => 'spanish',
								'd2u_helper_lang_russian' => 'russian',
								'd2u_helper_lang_chinese' => 'chinese',
							];
							foreach($replacement_options as $key => $value) {
								$selected = $value == $this->getConfig('lang_replacement_'. $rex_clang->getId(), 'none') ? ' selected="selected"' : '';
								print '<option value="'. $value .'"'. $selected .'>'. rex_i18n::msg('d2u_helper_lang_replacements_install') .' '. rex_i18n::msg($key) .'</option>';
							}
							print '</select>';
							print '</dl>';
						}
					?>
					<script>
						function changeLangType() {
							if($('input[name="settings\\[lang_replacements_install\\]"]').is(':checked')) {
								<?php
									foreach(rex_clang::getAll() as $rex_clang) {
										print "$('#settings\\\\[lang_wildcard_overwrite\\\\]').fadeIn();";
										print "$('#settings\\\\[lang_replacement_". $rex_clang->getId() ."\\\\]').fadeIn();";
									}
								?>
								
							}
							else {
								<?php
									foreach(rex_clang::getAll() as $rex_clang) {
										print "$('#settings\\\\[lang_wildcard_overwrite\\\\]').hide();";
										print "$('#settings\\\\[lang_replacement_". $rex_clang->getId() ."\\\\]').hide();";
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
					<button class="btn btn-save rex-form-aligned" type="submit" name="btn_save" value="save"><?php echo rex_i18n::msg('form_save'); ?></button>
				</div>
			</div>
		</footer>
	</div>
</form>
<?php
	print d2u_addon_backend_helper::getCSS();
	print d2u_addon_backend_helper::getJS();