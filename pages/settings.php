<?php
// save settings
if (filter_input(INPUT_POST, "btn_save") == 'save') {
	$settings = (array) rex_post('settings', 'array', []);

	// Linkmap Link needs special treatment
	$link_ids = filter_input_array(INPUT_POST, array('REX_INPUT_LINK'=> array('filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_REQUIRE_ARRAY)));
	$settings['article_id_privacy_policy'] = $link_ids["REX_INPUT_LINK"][1];
	$settings['article_id_impress'] = $link_ids["REX_INPUT_LINK"][2];

	// Special treatment for media fields
	$input_media = (array) rex_post('REX_INPUT_MEDIA', 'array', []);
	$settings['custom_css'] = $input_media['custom_css'];
	$settings['template_header_pic'] = $input_media['template_header_pic'];
	$settings['template_logo'] = $input_media['template_logo'];
	$settings['template_print_header_pic'] = isset($input_media['template_print_header_pic']) ? $input_media['template_print_header_pic'] : '';
	$settings['template_print_footer_pic'] = isset($input_media['template_print_footer_pic']) ? $input_media['template_print_footer_pic'] : '';
	$settings['template_03_2_header_pic'] = isset($input_media['template_03_2_header_pic']) ? $input_media['template_03_2_header_pic'] : '';
	$settings['template_03_2_footer_pic'] = isset($input_media['template_03_2_footer_pic']) ? $input_media['template_03_2_footer_pic'] : '';

	$input_media_list = (array) rex_post('REX_INPUT_MEDIALIST', 'array', []);
	foreach(rex_clang::getAllIds() as $clang_id) {
		$settings['template_02_2_header_slider_pics_clang_'. $clang_id] = $input_media_list[$clang_id];
	}

	// Checkbox also need special treatment if empty
	$settings['activate_rewrite_scheme'] = array_key_exists('activate_rewrite_scheme', $settings);
	$settings['include_bootstrap'] = array_key_exists('include_bootstrap', $settings);
	$settings['include_module'] = array_key_exists('include_module', $settings);
	$settings['include_menu'] = array_key_exists('include_menu', $settings);
	$settings['subhead_include_articlename'] = array_key_exists('subhead_include_articlename', $settings);
	$settings['show_breadcrumbs'] = array_key_exists('show_breadcrumbs', $settings);
	$settings['template_02_2_header_slider_pics_full_width'] = array_key_exists('template_02_2_header_slider_pics_full_width', $settings);
	
	// Update URLs
	if($settings['activate_rewrite_scheme'] == 'true') {
		// YRewrite
		if(rex_addon::get("yrewrite")->isAvailable()) {
			rex_yrewrite::deleteCache();
		}
		else {
			$settings['activate_rewrite_scheme'] = 'false';
		}
		
		// URL Addon
		if(rex_addon::get("url")->isAvailable()) {
			UrlGenerator::generatePathFile([]);
		}
	}

	// Save settings
	if(rex_config::set("d2u_helper", $settings)) {
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
							
						if(rex_addon::get('tinymce4')->isAvailable() || rex_addon::get('redactor2')->isAvailable() || rex_addon::get('ckeditor')->isAvailable() || rex_addon::get('markitup')->isAvailable()) {
							$options_editor = [];
							if(rex_addon::get('ckeditor')->isAvailable()) {
								$options_editor['ckeditor'] = rex_i18n::msg('ckeditor_title');
							}
							if(rex_addon::get('markitup')->isAvailable()) {
								$options_editor['markitup'] = rex_i18n::msg('markitup_title');
								$options_editor['markitup_textile'] = rex_i18n::msg('markitup_title') ." - Textile";
							}
							if(rex_addon::get('redactor2')->isAvailable()) {
								$options_editor['redactor2'] = rex_i18n::msg('redactor2_title');
							}
							if(rex_addon::get('tinymce4')->isAvailable()) {
								$options_editor['tinymce4'] = "TinyMCE 4";
							}
							d2u_addon_backend_helper::form_select('d2u_helper_settings_editor', 'settings[editor]', $options_editor, [$this->getConfig('editor')]);
						}
						
						d2u_addon_backend_helper::form_linkfield('d2u_helper_settings_article_id_privacy_policy', '1', $this->getConfig('article_id_privacy_policy'), rex_config::get("d2u_helper", "article_id_privacy_policy", rex_clang::getStartId()));
						d2u_addon_backend_helper::form_linkfield('d2u_helper_settings_article_id_impress', '2', $this->getConfig('article_id_impress'), rex_config::get("d2u_helper", "article_id_impress", rex_clang::getStartId()));
					?>
				</div>
			</fieldset>
			<fieldset>
				<legend><small><i class="rex-icon fa-navicon"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings_menu'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_include_menu', 'settings[include_menu]', 'true', $this->getConfig('include_menu') == 'true');
						$width_options = [
							"xs" => rex_i18n::msg('d2u_helper_settings_width_xs'),
							"sm" => rex_i18n::msg('d2u_helper_settings_width_sm'),
							"md" => rex_i18n::msg('d2u_helper_settings_width_md'),
							"lg" => rex_i18n::msg('d2u_helper_settings_width_lg'),
							"xl" => rex_i18n::msg('d2u_helper_settings_width_xl')
						];
						d2u_addon_backend_helper::form_select('d2u_helper_settings_menu_show', 'settings[include_menu_show]', $width_options, [$this->getConfig('include_menu_show')]);
					?>
				</div>
			</fieldset>
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-system"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings_templates'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_include_bootstrap', 'settings[include_bootstrap]', 'true', $this->getConfig('include_bootstrap') == 'true');
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_include_module', 'settings[include_module]', 'true', $this->getConfig('include_module') == 'true');
						print '<hr style="border-top: 1px solid #333">';
						d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_header_pic', 'template_header_pic', $this->getConfig('template_header_pic'));
						d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_logo', 'template_logo', $this->getConfig('template_logo'));
						print '<hr style="border-top: 1px solid #333">';
						d2u_addon_backend_helper::form_input('d2u_helper_settings_navi_color_bg', 'settings[navi_color_bg]', $this->getConfig('navi_color_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_navi_color_font', 'settings[navi_color_font]', $this->getConfig('navi_color_font'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_navi_color_hover_bg', 'settings[navi_color_hover_bg]', $this->getConfig('navi_color_hover_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_navi_color_hover_font', 'settings[navi_color_hover_font]', $this->getConfig('navi_color_hover_font'), FALSE, FALSE, "color");
						print '<hr style="border-top: 1px solid #333">';
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_show_breadcrumbs', 'settings[show_breadcrumbs]', 'true', $this->getConfig('show_breadcrumbs') == 'true');
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_subhead_include_articlename', 'settings[subhead_include_articlename]', 'true', $this->getConfig('subhead_include_articlename') == 'true');
						d2u_addon_backend_helper::form_input('d2u_helper_settings_subhead_color_bg', 'settings[subhead_color_bg]', $this->getConfig('subhead_color_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_subhead_color_font', 'settings[subhead_color_font]', $this->getConfig('subhead_color_font'), FALSE, FALSE, "color");
						print '<hr style="border-top: 1px solid #333">';
						d2u_addon_backend_helper::form_input('d2u_helper_settings_article_color_bg', 'settings[article_color_bg]', $this->getConfig('article_color_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_article_color_h', 'settings[article_color_h]', $this->getConfig('article_color_h'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_article_color_box', 'settings[article_color_box]', $this->getConfig('article_color_box'), FALSE, FALSE, "color");
						print '<hr style="border-top: 1px solid #333">';
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_color_bg', 'settings[footer_color_bg]', $this->getConfig('footer_color_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_color_box', 'settings[footer_color_box]', $this->getConfig('footer_color_box'), FALSE, FALSE, "color");
						print '<hr style="border-top: 1px solid #333">';
						d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_custom_css', 'custom_css', $this->getConfig('custom_css'));
						
						// Template specific part
						$d2u_templates = D2UTemplateManager::getD2UHelperTemplates();
						foreach($d2u_templates as $d2u_template) {
							$d2u_template->initRedaxoContext($this, "templates/");
							$d2u_template_ids_for_settings = ["02-1", "02-2", "03-1", "03-2"];
							if(in_array($d2u_template->getD2UId(), $d2u_template_ids_for_settings) && $d2u_template->isInstalled()) {
								print '<hr style="border-top: 1px solid #333">';
								print '<dl class="rex-form-group form-group" id="'. $fieldname .'">';
								print '<dt><label></label></dt>';
								print '<dd><b>' . rex_i18n::msg('d2u_helper_settings_template') ." '". $d2u_template->getName() . '</b></dd>';
								print '</dl>';
								if($d2u_template->getD2UId() === "02-1" && $d2u_template->isInstalled()) {
									d2u_addon_backend_helper::form_input('d2u_helper_settings_template_02_1_footer_text', 'settings[template_02_1_footer_text]', $this->getConfig('template_02_1_footer_text'), FALSE, FALSE, "text");
									$navi_pos_options = [
										"bottom" => rex_i18n::msg('d2u_helper_settings_template_02_1_navi_pos_bottom'),
										"top" => rex_i18n::msg('d2u_helper_settings_template_02_1_navi_pos_top')
									];
									d2u_addon_backend_helper::form_select('d2u_helper_settings_template_02_1_navi_pos_text', 'settings[template_02_1_navi_pos]', $navi_pos_options, [$this->getConfig('template_02_1_navi_pos')]);
								}
								else if($d2u_template->getD2UId() === "02-2" && $d2u_template->isInstalled()) {
									d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_template_02_2_slider_pics_width', 'settings[template_02_2_header_slider_pics_full_width]', 'full', $this->getConfig('template_02_2_header_slider_pics_full_width') == 'full');
									foreach(rex_clang::getAll() as $rex_clang) {
										print '<dl class="rex-form-group form-group" id="MEDIALIST_'. $rex_clang->getId() .'">';
										print '<dt><label>' . rex_i18n::msg('d2u_helper_settings_template_02_2_slider_pics') .' - '. $rex_clang->getName() .'</label></dt>';
										print '<dd><div class="input-group">';
										print '<select class="form-control" name="REX_MEDIALIST_SELECT[' . $rex_clang->getId() . ']" id="REX_MEDIALIST_SELECT_' . $rex_clang->getId() . '" size="10" style="margin: 0">';
										$slider_pics = preg_grep('/^\s*$/s', explode(",", $this->getConfig('template_02_2_header_slider_pics_clang_'. $rex_clang->getId())), PREG_GREP_INVERT);
										foreach ($slider_pics as $slider_pic) {
											print '<option value="'. $slider_pic .'">'. $slider_pic .'</option>';
										}
										print '</select>';
										print '<input type="hidden" name="REX_INPUT_MEDIALIST[' . $rex_clang->getId() . ']" id="REX_MEDIALIST_' . $rex_clang->getId() . '" value="' . $this->getConfig('template_02_2_header_slider_pics_clang_'. $rex_clang->getId()) . '">';
										print '<span class="input-group-addon">';
										print '<div class="btn-group-vertical">' . d2u_addon_backend_helper::getMediaPositionButtons($rex_clang->getId()) . '</div>';
										print '<div class="btn-group-vertical">' . d2u_addon_backend_helper::getMediaManagingButtons($rex_clang->getId(), TRUE) . '</div>';
										print '</span>';
										print '</div><div class="rex-js-media-preview"></div></dd>';
										print '</dl>';
									}
								}
								else if($d2u_template->getD2UId() === "03-1" && $d2u_template->isInstalled()) {
									d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_03_1_print_header_pic', 'template_print_header_pic', $this->getConfig('template_print_header_pic'));
									d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_03_1_print_footer_pic', 'template_print_footer_pic', $this->getConfig('template_print_footer_pic'));
								}
								else if($d2u_template->getD2UId() === "03-2" && $d2u_template->isInstalled()) {
									d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_03_2_header_pic', 'template_03_2_header_pic', $this->getConfig('template_03_2_header_pic'));
									d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_03_2_footer_pic', 'template_03_2_footer_pic', $this->getConfig('template_03_2_footer_pic'));
									d2u_addon_backend_helper::form_input('d2u_helper_settings_template_03_2_margin_top', 'settings[template_03_2_margin_top]', $this->getConfig('template_03_2_margin_top'), FALSE, FALSE, "number");
									d2u_addon_backend_helper::form_input('d2u_helper_settings_template_03_2_time_show_ad', 'settings[template_03_2_time_show_ad]', $this->getConfig('template_03_2_time_show_ad'), FALSE, FALSE, "number");
								}
							}
						}
					?>
					</div>
				</fieldset>
			<?php
				if(rex_addon::get('yrewrite')->isAvailable()) {
			?>
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-package-addon"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings_rewrite'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_rewrite_activate', 'settings[activate_rewrite_scheme]', 'true', $this->getConfig('activate_rewrite_scheme') == 'true');
						$rewrite_options = [
							'd2u_helper_settings_rewrite_standard' => 'standard',
							'd2u_helper_settings_rewrite_urlencode' => 'urlencode'
						];
						foreach(rex_clang::getAll() as $rex_clang) {
							print '<dl class="rex-form-group form-group">';
							print '<dt><label>'. $rex_clang->getName() .'</label></dt>';
							print '<dd>';
							print '<select class="form-control" name="settings[rewrite_scheme_clang_'. $rex_clang->getId() .']">';
							foreach($rewrite_options as $key => $value) {
								$selected = $value == $this->getConfig('rewrite_scheme_clang_'. $rex_clang->getId()) ? ' selected="selected"' : '';
								print '<option value="'. $value .'"'. $selected .'>'. rex_i18n::msg($key) .'</option>';
							}
							print '</select>';
							print '</dl>';
						}
					?>
				</div>
			</fieldset>
			<?php
				}
			?>
			<fieldset>
				<legend><small><i class="rex-icon fa-google"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings_analytics'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						d2u_addon_backend_helper::form_input('d2u_helper_settings_analytics_analytics', 'settings[google_analytics]', $this->getConfig('google_analytics'), FALSE, FALSE, "text");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_analytics_maps_key', 'settings[maps_key]', $this->getConfig('maps_key'), FALSE, FALSE, "text");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_wiredmetrics_costumerno', 'settings[emetrics_customno]', $this->getConfig('emetrics_customno'), FALSE, FALSE, "text");
					?>
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