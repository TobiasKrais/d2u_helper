<?php
// save settings
if (filter_input(INPUT_POST, "btn_save") == 'save') {
	$settings = (array) rex_post('settings', 'array', array());

	// Special treatment for media fields
	$input_media = (array) rex_post('REX_INPUT_MEDIA', 'array', array());
	$settings['template_header_pic'] = $input_media['template_header_pic'];
	$settings['template_logo'] = $input_media['template_logo'];

	// Checkbox also need special treatment if empty
	$settings['include_bootstrap'] = array_key_exists('include_bootstrap', $settings);
	$settings['include_module'] = array_key_exists('include_module', $settings);
	$settings['include_menu'] = array_key_exists('include_menu', $settings);
	$settings['subhead_include_articlename'] = array_key_exists('subhead_include_articlename', $settings);
	$settings['show_breadcrumbs'] = array_key_exists('show_breadcrumbs', $settings);
	
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
		<header class="panel-heading"><div class="panel-title"><?php print rex_i18n::msg('d2u_helper_meta_settings'); ?></div></header>
		<div class="panel-body">
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-system"></i></small> <?php echo rex_i18n::msg('d2u_helper_meta_settings'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_include_bootstrap', 'settings[include_bootstrap]', 'true', $this->getConfig('include_bootstrap') == 'true');
					?>
				</div>
			</fieldset>
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-database"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings_module'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_include_module', 'settings[include_module]', 'true', $this->getConfig('include_module') == 'true');
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
						d2u_addon_backend_helper::form_select('d2u_helper_settings_menu_show', 'settings[include_menu_show]', $width_options, array($this->getConfig('include_menu_show')));
					?>
				</div>
			</fieldset>
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-system"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings_templates'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_header_pic', 'template_header_pic', $this->getConfig('template_header_pic'));
						d2u_addon_backend_helper::form_mediafield('d2u_helper_settings_template_logo', 'template_logo', $this->getConfig('template_logo'));
						d2u_addon_backend_helper::form_input('d2u_helper_settings_navi_color_bg', 'settings[navi_color_bg]', $this->getConfig('navi_color_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_navi_color_font', 'settings[navi_color_font]', $this->getConfig('navi_color_font'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_navi_color_hover_bg', 'settings[navi_color_hover_bg]', $this->getConfig('navi_color_hover_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_navi_color_hover_font', 'settings[navi_color_hover_font]', $this->getConfig('navi_color_hover_font'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_subhead_color_bg', 'settings[subhead_color_bg]', $this->getConfig('subhead_color_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_subhead_color_font', 'settings[subhead_color_font]', $this->getConfig('subhead_color_font'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_subhead_include_articlename', 'settings[subhead_include_articlename]', 'true', $this->getConfig('subhead_include_articlename') == 'true');
						d2u_addon_backend_helper::form_input('d2u_helper_settings_article_color_bg', 'settings[article_color_bg]', $this->getConfig('article_color_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_article_color_h', 'settings[article_color_h]', $this->getConfig('article_color_h'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_article_color_box', 'settings[article_color_box]', $this->getConfig('article_color_box'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_color_bg', 'settings[footer_color_bg]', $this->getConfig('footer_color_bg'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_footer_color_box', 'settings[footer_color_box]', $this->getConfig('footer_color_box'), FALSE, FALSE, "color");
						d2u_addon_backend_helper::form_checkbox('d2u_helper_settings_show_breadcrumbs', 'settings[show_breadcrumbs]', 'true', $this->getConfig('show_breadcrumbs') == 'true');
					?>
				</div>
			</fieldset>
			<?php
				$d2u_templates = D2UTemplateManager::getD2UHelperTemplates();
				foreach($d2u_templates as $d2u_template) {
					$d2u_template->initRedaxoContext($this, "templates/");
					if($d2u_template->getD2UId() === "02-1" && $d2u_template->isInstalled()) {
			?>
						<fieldset>
							<legend><small><i class="rex-icon rex-icon-template"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings_template') ." '". $d2u_template->getName() ."'"; ?></legend>
							<div class="panel-body-wrapper slide">
								<?php
									d2u_addon_backend_helper::form_input('d2u_helper_settings_template_02_1_footer_text', 'settings[template_02_1_footer_text]', $this->getConfig('template_02_1_footer_text'), FALSE, FALSE, "text");
								?>
							</div>
						</fieldset>
			<?php
					}
				}
			?>
			<fieldset>
				<legend><small><i class="rex-icon rex-icon-system"></i></small> <?php echo rex_i18n::msg('d2u_helper_settings_google'); ?></legend>
				<div class="panel-body-wrapper slide">
					<?php
						d2u_addon_backend_helper::form_input('d2u_helper_settings_google_analytics', 'settings[google_analytics]', $this->getConfig('google_analytics'), FALSE, FALSE, "text");
						d2u_addon_backend_helper::form_input('d2u_helper_settings_google_maps_key', 'settings[maps_key]', $this->getConfig('maps_key'), FALSE, FALSE, "text");
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