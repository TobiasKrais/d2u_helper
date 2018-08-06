<?php
/**
 * Offers methods for Redaxo backend forms, mostly used bei Addons published by
 * www.design-to-use.de.
 */
class d2u_addon_backend_helper {
	/**
	 * Create a HTML String with Redaxo Media Buttons for managing the element
	 * postition
	 * @param string $field_id Name of die media field
	 * @return string HTML String with buttons
	 */
	private static function getLinkPositionButtons($field_id) {
		$js_onclick_top = "moveREXLinklist('" . $field_id . "', 'top');return false;";
		$fields = '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_top . '" title="' . rex_i18n::msg('var_linklist_move_top') . '"><i class="rex-icon rex-icon-top"></i></a>';
		$js_onclick_up = "moveREXLinklist('" . $field_id . "', 'up');return false;";
		$fields .= '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_up . '" title="' . rex_i18n::msg('var_linklist_move_up') . '"><i class="rex-icon rex-icon-up"></i></a>';
		$js_onclick_down = "moveREXLinklist('" . $field_id . "', 'down');return false;";
		$fields .= '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_down . '" title="' . rex_i18n::msg('var_linklist_move_down') . '"><i class="rex-icon rex-icon-down"></i></a>';
		$js_onclick_bottom = "moveREXLinklist('" . $field_id . "', 'bottom');return false;";
		$fields .= '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_bottom . '" title="' . rex_i18n::msg('var_linklist_move_bottom') . '"><i class="rex-icon rex-icon-bottom"></i></a>';
		return $fields;
	}

	/**
	 * Create a HTML String with Redaxo Media Buttons for opening Mediapool,
	 * add, delete and view medias
	 * @param string $field_id Name of die media field
	 * @param boolean $isList TRUE if field is a medialist field
	 * @return string HTML String with buttons
	 */
	public static function getMediaManagingButtons($field_id, $isList = FALSE) {
		$type_html = "Media";
		if ($isList) {
			$type_html = "Medialist";
		}
		$js_onclick_open = "openREX" . $type_html . "('" . $field_id . "', '');return false;";
		$fields = '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_open . '" title="' . rex_i18n::msg('var_media_open') . '"><i class="rex-icon rex-icon-open-mediapool"></i></a>';
		$js_onclick_add = "addREX" . $type_html . "('" . $field_id . "', '');return false;";
		$fields .= '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_add . '" title="' . rex_i18n::msg('var_media_new') . '"><i class="rex-icon rex-icon-add-media"></i></a>';
		$js_onclick_delete = "deleteREX" . $type_html . "('" . $field_id . "');return false;";
		$fields .= '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_delete . '" title="' . rex_i18n::msg('var_media_remove') . '"><i class="rex-icon rex-icon-delete-media"></i></a>';
		$js_onclick_view = "viewREX" . $type_html . "('" . $field_id . "', '');return false;";
		$fields .= '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_view . '" title="' . rex_i18n::msg('var_media_view') . '"><i class="rex-icon rex-icon-view-media"></i></a>';
		return $fields;
	}

	/**
	 * Create a HTML String with Redaxo Media Buttons for managing the element
	 * postition
	 * @param string $field_id Name of die media field
	 * @return string HTML String with buttons
	 */
	public static function getMediaPositionButtons($field_id) {
		$js_onclick_top = "moveREXMedialist('" . $field_id . "', 'top');return false;";
		$fields = '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_top . '" title="' . rex_i18n::msg('var_medialist_move_top') . '"><i class="rex-icon rex-icon-top"></i></a>';
		$js_onclick_up = "moveREXMedialist('" . $field_id . "', 'up');return false;";
		$fields .= '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_up . '" title="' . rex_i18n::msg('var_medialist_move_up') . '"><i class="rex-icon rex-icon-up"></i></a>';
		$js_onclick_down = "moveREXMedialist('" . $field_id . "', 'down');return false;";
		$fields .= '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_down . '" title="' . rex_i18n::msg('var_medialist_move_down') . '"><i class="rex-icon rex-icon-down"></i></a>';
		$js_onclick_bottom = "moveREXMedialist('" . $field_id . "', 'bottom');return false;";
		$fields .= '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_bottom . '" title="' . rex_i18n::msg('var_medialist_move_bottom') . '"><i class="rex-icon rex-icon-bottom"></i></a>';
		return $fields;
	}

	/**
	 * Get available WYSIWYG Editor
	 * @return string[] WYSIWYG editor classes
	 */
	public static function getWYSIWYGEditors() {
		$options_editor = [];
		if(rex_addon::get('ckeditor')->isAvailable()) {
			if(method_exists(rex_ckeditor, 'getProfiles')) {
				foreach(rex_ckeditor::getProfiles() as $cke_profile_name) {
					$options_editor['ckeditor_'. $cke_profile_name] = rex_i18n::msg('ckeditor_title') ." - ". $cke_profile_name;
				}
			}
			else {
				$options_editor['ckeditor'] = rex_i18n::msg('ckeditor_title');
			}
		}
		if(rex_addon::get('cke5')->isAvailable()) {
			foreach(\Cke5\Creator\Cke5ProfilesApi::getAllProfiles() as $cke5_profile) {
				$options_editor['cke5-editor_'. $cke5_profile["name"]] = rex_i18n::msg('cke5_title') ." - ". $cke5_profile["name"];
			}
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
		return $options_editor;
	}
	
	/**
	 * Get users choice WYSIWYG Editor class
	 * @return string WYSIWYG editor classes
	 */
	public static function getWYSIWYGEditorClass() {
		$wysiwyg_class = '';
		if(rex_config::get('d2u_helper', 'editor') == 'tinymce4' && rex_addon::get('tinymce4')->isAvailable()) {
			$wysiwyg_class = ' tinyMCEEditor';
		}
		else if(rex_config::get('d2u_helper', 'editor') == 'redactor2' && rex_addon::get('redactor2')->isAvailable()) {
			$wysiwyg_class = ' redactorEditor2-full';
		}
		else if(strpos(rex_config::get('d2u_helper', 'editor'), 'cke5-editor') !== FALSE && rex_addon::get('cke5')->isAvailable()) {
			$wysiwyg_class = ' cke5-editor" data-lang="'. \Cke5\Utils\Cke5Lang::getUserLang();
			foreach(\Cke5\Creator\Cke5ProfilesApi::getAllProfiles() as $cke5_profile) {
				if(rex_config::get('d2u_helper', 'editor') == 'cke5-editor_'. $cke5_profile["name"]) {
					$wysiwyg_class = ' cke5-editor" data-profile="'. $cke5_profile["name"] .'" data-lang="'. \Cke5\Utils\Cke5Lang::getUserLang();
					break;
				}
			}
		}
		else if(strpos(rex_config::get('d2u_helper', 'editor'), 'ckeditor') !== FALSE && rex_addon::get('ckeditor')->isAvailable()) {
			$wysiwyg_class = ' ckeditor" data-ckeditor-profile="standard';
			if(method_exists(rex_ckeditor, 'getProfiles')) {
				foreach(rex_ckeditor::getProfiles() as $cke_profile_name) {
					if(rex_config::get('d2u_helper', 'editor') == 'ckeditor_'. $cke_profile_name) {
						$wysiwyg_class = ' ckeditor" data-ckeditor-profile="'. $cke_profile_name ;
						break;
					}
				}
			}
		}
		else if(rex_config::get('d2u_helper', 'editor') == 'markitup' && rex_addon::get('markitup')->isAvailable()) {
			$wysiwyg_class .= ' markitupEditor-markdown_full';
		}
		else if(rex_config::get('d2u_helper', 'editor') == 'markitup_textile' && rex_addon::get('markitup')->isAvailable()) {
			$wysiwyg_class .= ' markitupEditor-textile_full';
		}

		return $wysiwyg_class;
	}
	
	/**
	 * Returns CSS for D2U specific forms
	 * @return string HTML String with css tag
	 */
	public static function getCSS() {
		$css =
'<style type="text/css">
	input[type="radio"], input[type="checkbox"] {
		width: auto;
	}
	/* Slide fieldsets*/
	div.panel-body legend {
		background: transparent url("' . rex_addon::get('d2u_helper')->getAssetsUrl('arrows.png') . '") no-repeat 0px 7px;
		padding-left: 19px;
	}
	div.panel-body legend.open {
		background-position: 0px -36px;
	}
	.panel-body-wrapper.slide {
		display: none;
	}
	input:required, select:required, textarea:required {
		background-color: pink;
	}
</style>';
		return $css;
	}

	/**
	 * Returns JS for D2U specific forms
	 * @return string HTML String with js tag
	 */
	public static function getJS() {
		$js =
"<script type='text/javascript'>
	// slide fieldsets
	jQuery(document).ready(function($) {
		$('legend').click(function(e) {
			$(this).toggleClass('open');
			$(this).next('.panel-body-wrapper.slide').slideToggle();
		});
	});
	// Open all fieldsets when save was clicked for being able to focus required fields
	$('button[type=submit]').click(function() {
		$('legend').each(function() {
			if(!$(this).hasClass('open')) {
				$(this).addClass('open');
				$(this).next('.panel-body-wrapper.slide').slideToggle();
			}
		});
		return true;
	});
</script>";
		return $js;
	}
	
	/**
	 * Opens all D2U specific fieldsets 
	 * @return string HTML String with js tag
	 */
	public static function getJSOpenAll() {
		$js =
"<script type='text/javascript'>
	$('legend').each(function() {
		$(this).addClass('open');
		$(this).next('.panel-body-wrapper.slide').slideToggle();
	});
</script>";
		return $js;
	}

	/**
	 * Prints a checkbox field
	 * @param string $message_id rex_i18n message id for the label text.
	 * @param string $fieldname Input field name.
	 * @param string $value Field value.
	 * @param string $checked TRUE if checked
	 * @param boolean $readonly TRUE if field should have readonly attribute.
	 */
	public static function form_checkbox($message_id, $fieldname, $value, $checked = FALSE, $readonly = FALSE) {
		print '<dl class="rex-form-group form-group">';
		print '<dt><input class="form-control" type="checkbox" name="' . $fieldname . '" value="' . $value . '"';
		if ($checked) {
			print ' checked="checked"';
		}
		if ($readonly) {
			print ' disabled';
		}
		print ' style="float: right; height: auto; width: auto;" />';
		if ($readonly && $checked) {
			print '<input type="hidden" name="'. $fieldname .'" value="' . $value . '">';
		}
		print '</dt>';
		print '<dd><label>' . rex_i18n::msg($message_id) . '</label></dd>';
		print '</dl>';
	}


	/**
	 * Prints a row with an infotext
	 * @param string $message_id rex_i18n message id for the info text.
	 * @param string $fieldname Input field name.
	 */
	public static function form_infotext($message_id, $fieldname) {
		print '<dl class="rex-form-group form-group" id="'. $fieldname .'">';
		print '<dt><label></label></dt>';
		print '<dd>' . htmlspecialchars_decode(rex_i18n::msg($message_id)) . '</dd>';
		print '</dl>';
	}

	/**
	 * Prints a row with an input field
	 * @param string $message_id rex_i18n message id for the label text.
	 * @param string $fieldname Input field name.
	 * @param string $value Field value.
	 * @param boolean $required TRUE if field should have required attribute.
	 * @param boolean $readonly TRUE if field should have readonly attribute.
	 * @param string $type HTML5 input type, e.g. text, number or email
	 */
	public static function form_input($message_id, $fieldname, $value, $required = FALSE, $readonly = FALSE, $type = "text") {
		print '<dl class="rex-form-group form-group" id="'. $fieldname .'">';
		$label = '<label>' . rex_i18n::msg($message_id) . '</label>';
		$input = '<input class="form-control" type="' . $type . '" name="' . $fieldname . '" value="' . str_replace('"', "'", $value) . '"';
		if ($required && $readonly !== TRUE) {
			$input .= ' required';
		}
		if ($readonly) {
			$input .= ' readonly';
		}
		if($type == "color" || $type == "number") {
			$input .= ' style="max-width: 150px;"';
		}
		if($type == "date") {
			$input .= ' placeholder="Format: JJJJ-MM-TT"';
		}
		$input .=  '/>';
		print '<dt>'. ($type == "color" ? $input : $label) .'</dt>';
		print '<dd>'. ($type == "color" ? $label : $input) .'</dd>';
		print '</dl>';
	}

	/**
	 * Prints a row with an link map field
	 * @param string $message_id rex_i18n message id for the label text.
	 * @param string $fieldname Input field name (without REX_LINK_NAME part).
	 * @param int $article_id ID of the selected article.
	 * @param int $clang_id ID of the selected language.
	 * @param boolean $readonly TRUE if field should have readonly attribute.
	 */
	public static function form_linkfield($message_id, $fieldname, $article_id, $clang_id, $readonly = FALSE) {
		if (!in_array($clang_id, rex_clang::getAllIds())) {
			$clang_id = rex_clang::getStartId();
		}
		print '<dl class="rex-form-group form-group" id="LINK_'. $fieldname .'">';
		print '<dt><label>' . rex_i18n::msg($message_id) . '</label></dt>';
		print '<dd><div class="input-group">';
		$article = rex_article::get($article_id, $clang_id);
		$article_name = $article instanceof rex_article ? $article->getValue('name') : "";		
		print '<input class="form-control" type="text" name="REX_LINK_NAME[' . $fieldname . ']" value="' . $article_name . '" id="REX_LINK_' . $fieldname . '_NAME" readonly="readonly">';
		print '<input type="hidden" name="REX_INPUT_LINK[' . $fieldname . ']" id="REX_LINK_' . $fieldname . '" value="' . $article_id . '">';
		print '<span class="input-group-btn">';
		if (!$readonly) {
			$js_onclick_open = "openLinkMap('REX_LINK_" . $fieldname . "', '&category_id=" . $article_id . "&clang=" . $clang_id . "');return false;";
			print '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_open . '" title="' . rex_i18n::msg('var_link_open') . '"><i class="rex-icon rex-icon-open-linkmap"></i></a>';
			$js_onclick_delete = "deleteREXLink('" . $fieldname . "');return false;";
			print '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_delete . '" title="' . rex_i18n::msg('var_link_delete') . '"><i class="rex-icon rex-icon-delete-link"></i></a>';
		}
		print '</span>';
		print '</div><div class="rex-js-media-preview"></div></dd>';
		print '</dl>';
	}

	/**
	 * Prints a row with an link map list field
	 * @param string $message_id rex_i18n message id for the label text.
	 * @param int $fieldnumber Input field name (without REX_MEDIALIST_SELECT part).
	 * @param int[] $article_ids ID of the selected articles.
	 * @param int $clang_id ID of the selected language.
	 * @param boolean $readonly TRUE if field should have readonly attribute.
	 */
	public static function form_linklistfield($message_id, $fieldnumber, $article_ids, $clang_id, $readonly = FALSE) {
		print '<dl class="rex-form-group form-group" id="LINKLIST_'. $fieldnumber .'">';
		print '<dt><label>' . rex_i18n::msg($message_id) . '</label></dt>';
		print '<dd><div class="input-group">';
		print '<select class="form-control" name="REX_LINKLIST_SELECT[' . $fieldnumber . ']" id="REX_LINKLIST_SELECT_' . $fieldnumber . '" size="10" style="margin: 0">';
		foreach ($article_ids as $article_id) {
			$article = rex_article::get($article_id, $clang_id);
			if($article instanceof rex_article) {
				print '<option value="' . $article_id . '">' . $article->getValue('name') . '</option>';
			}
		}
		print '</select>';
		print '<input type="hidden" name="REX_INPUT_LINKLIST[' . $fieldnumber . ']" id="REX_LINKLIST_' . $fieldnumber . '" value="' . implode(",", $article_ids) . '">';
		print '<span class="input-group-addon"><div class="btn-group-vertical">';
		if (!$readonly) {
			print d2u_addon_backend_helper::getLinkPositionButtons($fieldnumber);
			print '</div>';
			print '<div class="btn-group-vertical">';
			$js_onclick_open = "openREXLinklist(" . $fieldnumber . ", '&clang=" . $clang_id . "');return false;";
			print '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_open . '" title="' . rex_i18n::msg('var_link_open') . '"><i class="rex-icon rex-icon-open-linkmap"></i></a>';
			$js_onclick_delete = "deleteREXLinklist(" . $fieldnumber . ");return false;";
			print '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_delete . '" title="' . rex_i18n::msg('var_link_delete') . '"><i class="rex-icon rex-icon-delete-link"></i></a>';
		}
		print '</div></span>';
		print '</div><div class="rex-js-media-preview"></div></dd>';
		print '</dl>';
	}

	/**
	 * Prints a row with an media input field
	 * @param string $message_id rex_i18n message id for the label text.
	 * @param string $fieldname Input field name (without REX_INPUT_MEDIA part).
	 * @param string $value Field value.
	 * @param boolean $readonly TRUE if field should have readonly attribute.
	 */
	public static function form_mediafield($message_id, $fieldname, $value, $readonly = FALSE) {
		print '<dl class="rex-form-group form-group" id="MEDIA_'. $fieldname .'">';
		print '<dt><label>' . rex_i18n::msg($message_id) . '</label></dt>';
		print '<dd><div class="input-group">';
		print '<input class="form-control" type="text" name="REX_INPUT_MEDIA[' . $fieldname . ']" value="' . $value . '" id="REX_MEDIA_' . $fieldname . '" readonly="readonly">';
		print '<span class="input-group-btn">';
		if (!$readonly) {
			print d2u_addon_backend_helper::getMediaManagingButtons($fieldname);
		}
		print '</span>';
		print '</div><div class="rex-js-media-preview"></div></dd>';
		print '</dl>';
	}

	/**
	 * Prints a row with an medialist input field
	 * @param string $message_id rex_i18n message id for the label text.
	 * @param int $fieldnumber Input field name (without REX_MEDIALIST_SELECT part).
	 * @param string[] $values Field values.
	 * @param boolean $readonly TRUE if field should have readonly attribute.
	 */
	public static function form_medialistfield($message_id, $fieldnumber, $values, $readonly = FALSE) {
		print '<dl class="rex-form-group form-group" id="MEDIALIST_'. $fieldnumber .'">';
		print '<dt><label>' . rex_i18n::msg($message_id) . '</label></dt>';
		print '<dd><div class="input-group">';
		print '<select class="form-control" name="REX_MEDIALIST_SELECT[' . $fieldnumber . ']" id="REX_MEDIALIST_SELECT_' . $fieldnumber . '" size="10" style="margin: 0">';
		foreach ($values as $value) {
			print '<option value="' . $value . '">' . $value . '</option>';
		}
		print '</select>';
		print '<input type="hidden" name="REX_INPUT_MEDIALIST[' . $fieldnumber . ']" id="REX_MEDIALIST_' . $fieldnumber . '" value="' . implode(',', $values) . '">';
		print '<span class="input-group-addon">';
		if (!$readonly) {
			print '<div class="btn-group-vertical">' . d2u_addon_backend_helper::getMediaPositionButtons($fieldnumber) . '</div>';
			print '<div class="btn-group-vertical">' . d2u_addon_backend_helper::getMediaManagingButtons($fieldnumber, TRUE) . '</div>';
		}
		print '</span>';
		print '</div><div class="rex-js-media-preview"></div></dd>';
		print '</dl>';
	}

	/**
	 * Prints a select field
	 * @param string $message_id rex_i18n message id for the label text.
	 * @param string $fieldname Select field name
	 * @param string[] $values Field values.
	 * @param string[] $selected_values Preselected value
	 * @param int $size Size of the select field, default 1
	 * @param boolean $multiple TRUE if multiple selections are allowed
	 * @param boolean $readonly TRUE if field should have readonly attribute.
	 */
	public static function form_select($message_id, $fieldname, $values, $selected_values = [], $size = 1, $multiple = FALSE, $readonly = FALSE) {
		print '<dl class="rex-form-group form-group" id="'. $fieldname .'">';
		print '<dt><label>' . rex_i18n::msg($message_id) . '</label></dt>';
		print '<dd>';
		$multiple_attr = $multiple ? ' multiple="multiple"' : '';
		if ($readonly) {
			// Submit array
			foreach ($selected_values as $selected_value) {
				print '<input type="hidden" name="' . $fieldname . '" value="' . $selected_value . '">';
			}
			print '<select class="form-control" name="disabled" disabled size=' . $size . $multiple_attr . '>';
		} else {
			print '<select class="form-control" name="' . $fieldname . '" size=' . $size . $multiple_attr . '>';
		}
		foreach ($values as $key => $value) {
			$selected = '';
			if(is_array($selected_values) && in_array($key, $selected_values)) {
				$selected = ' selected="selected"';
			}
			else if($key == $selected_values) {
				$selected = ' selected="selected"';
			}
			print '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
		}
		print '</select>';
		print '</dl>';
	}

	/**
	 * Prints a row with an textare
	 * @param string $message_id rex_i18n message id for the label text.
	 * @param string $fieldname Textarea field name.
	 * @param string $value Textarea value.
	 * @param int $rows Number rows
	 * @param boolean $required TRUE if field should have required attribute. If
	 * $use_wysiwyg is TRUE, $required is automatically FALSE
	 * @param boolean $readonly TRUE if field should have readonly attribute.
	 * @param string $use_wysiwyg Use WYSIWYG Editor
	 */
	public static function form_textarea($message_id, $fieldname, $value, $rows = 5, $required = FALSE, $readonly = FALSE, $use_wysiwyg = TRUE) {
		print '<dl class="rex-form-group form-group" id="'. $fieldname .'">';
		print '<dt><label>' . rex_i18n::msg($message_id) . '</label></dt>';
		$wysiwyg_class = ' ';
		if($use_wysiwyg) {
			if(rex_config::get('d2u_helper', 'editor') == 'tinymce4' && rex_addon::get('tinymce4')->isAvailable()) {
				$wysiwyg_class .= 'tinyMCEEditor';
			}
			else if(rex_config::get('d2u_helper', 'editor') == 'redactor2' && rex_addon::get('redactor2')->isAvailable()) {
				$wysiwyg_class .= 'redactorEditor2-full';
			}
			else if(rex_config::get('d2u_helper', 'editor') == 'ckeditor' && rex_addon::get('ckeditor')->isAvailable()) {
				$wysiwyg_class .= 'ckeditor';
			}
			else if(rex_config::get('d2u_helper', 'editor') == 'markitup' && rex_addon::get('markitup')->isAvailable()) {
				$wysiwyg_class .= 'markitupEditor-markdown_full';
			}
			else if(rex_config::get('d2u_helper', 'editor') == 'markitup_textile' && rex_addon::get('markitup')->isAvailable()) {
				$wysiwyg_class .= 'markitupEditor-textile_full';
			}
		}
		if ($readonly) {
			print '<dd><div class="form-control" style="height: 100px;overflow-y: scroll">'. $value .'</div>'
				. '<input type="hidden" name="' . $fieldname . '" value="'. str_replace('"', "'", $value) .'"></dd>';
		}
		else { 
			print '<dd><textarea cols="1" rows="' . $rows . '" class="form-control' . $wysiwyg_class . '" name="' . $fieldname . '"';
			// Required can only be activated if WYSIWYG Editor is not activated
			if ($required && !$use_wysiwyg) {
				print ' required';
			}
			print '>' . $value . '</textarea></dd>';
		}
		print '</dl>';
	}

	/**
	 * Updates url addon scheme article id.
	 * @param string $table Table/view name used for url scheme. Parameter is used as identifier.
	 * @param int $article_id Redaxo article id
	 */
    public static function update_url_scheme($table, $article_id) {
		if(rex_addon::get('url')->isAvailable()) {
			$query = "UPDATE `". \rex::getTablePrefix() ."url_generate` SET `article_id` = ". $article_id ." "
				."WHERE `table` LIKE '%". $table ."'";
			$sql = rex_sql::factory();
			$sql->setQuery($query);
		}
    }
}
