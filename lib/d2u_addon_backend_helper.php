<?php
/**
 * @api
 * Offers methods for Redaxo backend forms, used by addons published by
 * www.design-to-use.de.
 */
class d2u_addon_backend_helper
{
    /**
     * Create a HTML String with Redaxo Media Buttons for managing the element
     * postition.
     * @param int $field_id Name of die media field
     * @return string HTML String with buttons
     */
    private static function getLinkPositionButtons($field_id)
    {
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
     * add, delete and view medias.
     * @param string|int $field_id Name of die media field
     * @param bool $isList true if field is a medialist field
     * @param string $filetypes comma separated list of filetypes
     * @param int $category_id comma separated list of filetypes
     * @return string HTML String with buttons
     */
    private static function getMediaManagingButtons($field_id, $isList = false, $filetypes = '', $category_id = 0)
    {
        $type_html = 'Media';
        if ($isList) {
            $type_html = 'Medialist';
        }
        $args = '';
        if ('' !== $filetypes) {
            $args .= '&args[types]='. $filetypes;
        }
        if ($category_id > 0) {
            $args .= '&rex_file_category='. $category_id;
        }
        $js_onclick_open = 'openREX' . $type_html . "('" . $field_id . "', '".$args."');return false;";
        $fields = '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_open . '" title="' . rex_i18n::msg('var_media_open') . '"><i class="rex-icon rex-icon-open-mediapool"></i></a>';
        $js_onclick_add = 'addREX' . $type_html . "('" . $field_id . "','".$args."');return false;";
        $fields .= '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_add . '" title="' . rex_i18n::msg('var_media_new') . '"><i class="rex-icon rex-icon-add-media"></i></a>';
        $js_onclick_delete = 'deleteREX' . $type_html . "('" . $field_id . "');return false;";
        $fields .= '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_delete . '" title="' . rex_i18n::msg('var_media_remove') . '"><i class="rex-icon rex-icon-delete-media"></i></a>';
        $js_onclick_view = 'viewREX' . $type_html . "('" . $field_id . "','".$args."');return false;";
        $fields .= '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_view . '" title="' . rex_i18n::msg('var_media_view') . '"><i class="rex-icon rex-icon-view-media"></i></a>';
        return $fields;
    }

    /**
     * Create a HTML String with Redaxo Media Buttons for managing the element
     * postition.
     * @param int $field_id Name of die media field
     * @return string HTML String with buttons
     */
    public static function getMediaPositionButtons($field_id)
    {
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
     * Get available WYSIWYG Editor.
     * @return string[] WYSIWYG editor classes
     */
    public static function getWYSIWYGEditors()
    {
        $options_editor = [];
        if (rex_addon::get('ckeditor')->isAvailable()) {
            if (method_exists(rex_ckeditor::class, 'getProfiles')) { /** @phpstan-ignore-line */
                $ckeditor_profiles = \rex_ckeditor::getProfiles(); /** @phpstan-ignore-line */
                if (is_array($ckeditor_profiles)) {
                    foreach ($ckeditor_profiles as $cke_profile_name) {
                        $options_editor['ckeditor_'. $cke_profile_name] = rex_i18n::msg('ckeditor_title') .' - '. $cke_profile_name;
                    }
                }
            } else {
                $options_editor['ckeditor'] = rex_i18n::msg('ckeditor_title');
            }
        }
        if (rex_addon::get('cke5')->isAvailable()) {
            $cke5_profiles = \Cke5\Creator\Cke5ProfilesApi::getAllProfiles();  /** @phpstan-ignore-line */
            if (is_array($cke5_profiles)) {
                foreach ($cke5_profiles as $cke5_profile) {
                    if (is_array($cke5_profile) && array_key_exists('name', $cke5_profile)) {
                        $options_editor['cke5-editor_'. $cke5_profile['name']] = rex_i18n::msg('cke5_title') .' - '. $cke5_profile['name'];
                    }
                }
            }
        }
        if (rex_addon::get('markitup')->isAvailable()) {
            $options_editor['markitup'] = rex_i18n::msg('markitup_title');
            $options_editor['markitup_textile'] = rex_i18n::msg('markitup_title') .' - Textile';
        }
        if (rex_addon::get('redactor2')->isAvailable()) {
            $options_editor['redactor2'] = rex_i18n::msg('redactor2_title');
        }

        if (rex_addon::get('tinymce')->isAvailable()) {
            $tinymce_profiles = \TinyMCE\Handler\TinyMCEDatabaseHandler::getAllProfiles();
            if (is_array($tinymce_profiles)) {
                foreach ($tinymce_profiles as $profile_infos) {
                    $options_editor['tinymce_'. $profile_infos['name']] = rex_i18n::msg('tinymce_title') .' - '. $profile_infos['name'];
                }
            }
        }
        if (rex_addon::get('tinymce4')->isAvailable()) {
            $options_editor['tinymce4'] = 'TinyMCE 4';
        }
        if (rex_addon::get('tinymce5')->isAvailable()) {
            $tinymce5_profiles = \TinyMCE5\Handler\TinyMCE5DatabaseHandler::getAllProfiles(); /** @phpstan-ignore-line */
            if (is_array($tinymce5_profiles)) {
                foreach ($tinymce5_profiles as $profile_infos) {
                    if (is_array($profile_infos)) {
                        $options_editor['tinymce5_'. $profile_infos['name']] = rex_i18n::msg('tinymce5_title') .' - '. $profile_infos['name'];
                    }
                }
            }
        }
        return $options_editor;
    }

    /**
     * Get users choice WYSIWYG Editor class.
     * @return string WYSIWYG editor classes
     */
    public static function getWYSIWYGEditorClass()
    {
        $wysiwyg_class = '';
        if ('tinymce4' === (string) rex_config::get('d2u_helper', 'editor') && rex_addon::get('tinymce4') instanceof rex_addon && rex_addon::get('tinymce4')->isAvailable()) {
            $wysiwyg_class = ' tinyMCEEditor';
        } elseif (str_contains((string) rex_config::get('d2u_helper', 'editor'), 'tinymce5') && rex_addon::get('tinymce5') instanceof rex_addon && rex_addon::get('tinymce5')->isAvailable()) {
            $wysiwyg_class = ' tiny5-editor" data-profile="default';
            $tinymce5_profiles = \TinyMCE5\Handler\TinyMCE5DatabaseHandler::getAllProfiles(); /** @phpstan-ignore-line */
            if (is_array($tinymce5_profiles)) {
                foreach ($tinymce5_profiles as $profile_infos) {
                    if (is_array($profile_infos) && rex_config::get('d2u_helper', 'editor') === 'tinymce5_'. $profile_infos['name']) {
                        $wysiwyg_class = ' tiny5-editor" data-profile="'. $profile_infos['name'];
                        break;
                    }
                }
            }
        } elseif (str_contains((string) rex_config::get('d2u_helper', 'editor'), 'tinymce') && rex_addon::get('tinymce') instanceof rex_addon && rex_addon::get('tinymce')->isAvailable()) {
            $wysiwyg_class = ' tiny-editor" data-profile="default';
            $tinymce_profiles = \TinyMCE\Handler\TinyMCEDatabaseHandler::getAllProfiles();
            if (is_array($tinymce_profiles)) {
                foreach ($tinymce_profiles as $profile_infos) {
                    if (rex_config::get('d2u_helper', 'editor') === 'tinymce_'. $profile_infos['name']) {
                        $wysiwyg_class = ' tiny5-editor" data-profile="'. $profile_infos['name'];
                        break;
                    }
                }
            }
        } elseif ('redactor2' === rex_config::get('d2u_helper', 'editor') && rex_addon::get('redactor2')->isAvailable()) {
            $wysiwyg_class = ' redactorEditor2-full';
        } elseif (str_contains((string) rex_config::get('d2u_helper', 'editor'), 'cke5-editor') && rex_addon::get('cke5')->isAvailable()) {
            $wysiwyg_class = ' cke5-editor" data-lang="'. \Cke5\Utils\Cke5Lang::getUserLang(); /** @phpstan-ignore-line */
            $cke5_profiles = \Cke5\Creator\Cke5ProfilesApi::getAllProfiles();  /** @phpstan-ignore-line */
            if (is_array($cke5_profiles)) {
                foreach ($cke5_profiles as $cke5_profile) {
                    if (is_array($cke5_profile) && array_key_exists('name', $cke5_profile) && rex_config::get('d2u_helper', 'editor') === 'cke5-editor_'. $cke5_profile['name']) {
                        $wysiwyg_class = ' cke5-editor" data-profile="'. $cke5_profile['name'] .'" data-lang="'. \Cke5\Utils\Cke5Lang::getUserLang(); /** @phpstan-ignore-line */
                        break;
                    }
                }
            }
        } elseif (str_contains((string) rex_config::get('d2u_helper', 'editor'), 'ckeditor') && rex_addon::get('ckeditor')->isAvailable()) {
            $wysiwyg_class = ' ckeditor" data-ckeditor-profile="standard';
            if (method_exists(rex_ckeditor::class, 'getProfiles')) { /** @phpstan-ignore-line */
                $ckeditor_profiles = \rex_ckeditor::getProfiles(); /** @phpstan-ignore-line */
                if (is_array($ckeditor_profiles)) {
                    foreach ($ckeditor_profiles as $cke_profile_name) {
                        if (rex_config::get('d2u_helper', 'editor') === 'ckeditor_'. $cke_profile_name) {
                            $wysiwyg_class = ' ckeditor" data-ckeditor-profile="'. $cke_profile_name;
                            break;
                        }
                    }
                }
            }
        } elseif ('markitup' === rex_config::get('d2u_helper', 'editor') && rex_addon::get('markitup')->isAvailable()) {
            $wysiwyg_class .= ' markitupEditor-markdown_full';
        } elseif ('markitup_textile' === rex_config::get('d2u_helper', 'editor') && rex_addon::get('markitup')->isAvailable()) {
            $wysiwyg_class .= ' markitupEditor-textile_full';
        }

        return $wysiwyg_class;
    }

    /**
     * Returns CSS for D2U specific forms.
     * @return string HTML String with css tag
     */
    public static function getCSS()
    {
        $css =
'<style>
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
	input:invalid, select:invalid, textarea:invalid {
		background-color: pink;
	}
</style>';
        return $css;
    }

    /**
     * Returns JS for D2U specific forms.
     * @return string HTML String with js tag
     */
    public static function getJS()
    {
        $js =
"<script>
	// Hide or show detail fields of languages
	function toggleClangDetailsView(clang_id) {
		if ($(\"select[name='form[lang][\" + clang_id + \"][translation_needs_update]']\").val() === 'delete') {
			$('#details_clang_' + clang_id).slideUp();
		}
		else {
			$('#details_clang_' + clang_id).slideDown();
		};
	}

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
     * Opens all D2U specific fieldsets.
     * @return string HTML String with js tag
     */
    public static function getJSOpenAll()
    {
        $js =
"<script>
	$('legend').each(function() {
		$(this).addClass('open');
		$(this).next('.panel-body-wrapper.slide').slideToggle();
	});
</script>";
        return $js;
    }

    /**
     * Prints a checkbox field.
     * @param string $message_id rex_i18n message id for the label text
     * @param string $fieldname input field name
     * @param string $value field value
     * @param bool $checked true if checked
     * @param bool $readonly true if field should have readonly attribute
     */
    public static function form_checkbox($message_id, $fieldname, $value, $checked = false, $readonly = false): void
    {
        echo '<dl class="rex-form-group form-group" id="'. $fieldname .'">';
        echo '<dt><input class="form-control d2u_helper_toggle" type="checkbox" name="' . $fieldname . '" value="' . $value . '"'
            .($checked ? ' checked="checked"' : '') . ($readonly ? ' disabled' : '') .' />';
        if ($readonly && $checked) {
            echo '<input type="hidden" name="'. $fieldname .'" value="' . $value . '">';
        }
        echo '</dt>';
        echo '<dd><label>' . rex_i18n::msg($message_id) . '</label></dd>';
        echo '</dl>';
    }

    /**
     * Prints a row with an infotext.
     * @param string $message_id rex_i18n message id for the info text
     * @param string $fieldname input field name
     */
    public static function form_infotext($message_id, $fieldname): void
    {
        echo '<dl class="rex-form-group form-group" id="'. $fieldname .'">';
        echo '<dt><label></label></dt>';
        echo '<dd>' . htmlspecialchars_decode(rex_i18n::msg($message_id)) . '</dd>';
        echo '</dl>';
    }

    /**
     * Prints a row with an input field.
     * @param string $message_id rex_i18n message id for the label text
     * @param string $fieldname input field name
     * @param string|int $value field value
     * @param bool $required true if field should have required attribute
     * @param bool $readonly true if field should have readonly attribute
     * @param string $type HTML5 input type, e.g. text, number or email
     */
    public static function form_input($message_id, $fieldname, $value, $required = false, $readonly = false, $type = 'text'): void
    {
        $field_id = str_replace('[', '-', str_replace(']', '', $fieldname));
        echo '<dl class="rex-form-group form-group" id="'. $fieldname .'">';
        $label = '<label>' . rex_i18n::msg($message_id) . '</label>';
        $input = '';
        if ('color' === $type) {
            $input .= '<input class="form-control d2u_helper_color_text" type="text" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" '
                . 'value="' . str_replace('"', "'", (string) $value) . '" id="text-' . $field_id . '" onChange="document.getElementById(\'color-' . $field_id . '\').value = this.value">';
        }
        $input .= '<input class="form-control'. ('color' === $type ? ' d2u_helper_color' : '') .'" type="' . $type . '" name="' . $fieldname . '" id="color-' . $field_id . '" value="' . str_replace('"', "'", (string) $value) . '"';
        if ($required && true !== $readonly) {
            $input .= ' required';
        }
        if ($readonly) {
            $input .= ' readonly';
        }
        if ('color' === $type || 'number' === $type) {
            $input .= ' style="max-width: 150px;"';
            $input .= ' onChange="document.getElementById(\'text-' . $field_id . '\').value = this.value"';
        }
        if ('date' === $type) {
            $input .= ' placeholder="Format: JJJJ-MM-TT"';
        }
        $input .= '/>';
        echo '<dt>'. ('color' === $type ? $input : $label) .'</dt>';
        echo '<dd'. ('color' === $type ? ' class="d2u_helper_color_desc"' : '') .'>'. ('color' === $type ? $label : $input) .'</dd>';
        echo '</dl>';
    }

    /**
     * Prints a row with an link map field.
     * @param string $message_id rex_i18n message id for the label text
     * @param string $fieldname input field name (without REX_LINK_NAME part)
     * @param int $article_id ID of the selected article
     * @param int $clang_id ID of the selected language
     * @param bool $readonly true if field should have readonly attribute
     */
    public static function form_linkfield($message_id, $fieldname, $article_id, $clang_id, $readonly = false): void
    {
        if (!in_array($clang_id, rex_clang::getAllIds(), true)) {
            $clang_id = rex_clang::getStartId();
        }
        echo '<dl class="rex-form-group form-group" id="LINK_'. $fieldname .'">';
        echo '<dt><label>' . rex_i18n::msg($message_id) . '</label></dt>';
        echo '<dd><div class="input-group">';
        $article = rex_article::get($article_id, $clang_id);
        $article_name = $article instanceof rex_article ? $article->getValue('name') : '';
        echo '<input class="form-control" type="text" name="REX_LINK_NAME[' . $fieldname . ']" value="' . $article_name . '" id="REX_LINK_' . $fieldname . '_NAME" readonly="readonly">';
        echo '<input type="hidden" name="REX_INPUT_LINK[' . $fieldname . ']" id="REX_LINK_' . $fieldname . '" value="' . $article_id . '">';
        echo '<span class="input-group-btn">';
        if (!$readonly) {
            $js_onclick_open = "openLinkMap('REX_LINK_" . $fieldname . "', '&category_id=" . $article_id . '&clang=' . $clang_id . "');return false;";
            echo '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_open . '" title="' . rex_i18n::msg('var_link_open') . '"><i class="rex-icon rex-icon-open-linkmap"></i></a>';
            $js_onclick_delete = "deleteREXLink('" . $fieldname . "');return false;";
            echo '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_delete . '" title="' . rex_i18n::msg('var_link_delete') . '"><i class="rex-icon rex-icon-delete-link"></i></a>';
        }
        echo '</span>';
        echo '</div><div class="rex-js-media-preview"></div></dd>';
        echo '</dl>';
    }

    /**
     * Prints a row with an link map list field.
     * @param string $message_id rex_i18n message id for the label text
     * @param int $fieldnumber input field name (without REX_MEDIALIST_SELECT part)
     * @param int[] $article_ids ID of the selected articles
     * @param int $clang_id ID of the selected language
     * @param bool $readonly true if field should have readonly attribute
     */
    public static function form_linklistfield($message_id, $fieldnumber, $article_ids, $clang_id, $readonly = false): void
    {
        echo '<dl class="rex-form-group form-group" id="LINKLIST_'. $fieldnumber .'">';
        echo '<dt><label>' . rex_i18n::msg($message_id) . '</label></dt>';
        echo '<dd><div class="input-group">';
        echo '<select class="form-control" name="REX_LINKLIST_SELECT[' . $fieldnumber . ']" id="REX_LINKLIST_SELECT_' . $fieldnumber . '" size="10" style="margin: 0">';
        foreach ($article_ids as $article_id) {
            $article = rex_article::get($article_id, $clang_id);
            if ($article instanceof rex_article) {
                echo '<option value="'. $article_id .'">'. $article->getValue('name') .' ['. $article_id .']</option>';
            }
        }
        echo '</select>';
        echo '<input type="hidden" name="REX_INPUT_LINKLIST[' . $fieldnumber . ']" id="REX_LINKLIST_' . $fieldnumber . '" value="' . implode(',', $article_ids) . '">';
        echo '<span class="input-group-addon"><div class="btn-group-vertical">';
        if (!$readonly) {
            echo self::getLinkPositionButtons($fieldnumber);
            echo '</div>';
            echo '<div class="btn-group-vertical">';
            $js_onclick_open = 'openREXLinklist(' . $fieldnumber . ", '&clang=" . $clang_id . "');return false;";
            echo '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_open . '" title="' . rex_i18n::msg('var_link_open') . '"><i class="rex-icon rex-icon-open-linkmap"></i></a>';
            $js_onclick_delete = 'deleteREXLinklist(' . $fieldnumber . ');return false;';
            echo '<a href="#" class="btn btn-popup" onclick="' . $js_onclick_delete . '" title="' . rex_i18n::msg('var_link_delete') . '"><i class="rex-icon rex-icon-delete-link"></i></a>';
        }
        echo '</div></span>';
        echo '</div><div class="rex-js-media-preview"></div></dd>';
        echo '</dl>';
    }

    /**
     * Prints a row with an media input field.
     * @param string $message_id rex_i18n message id for the label text
     * @param string $fieldname input field name (without REX_INPUT_MEDIA part)
     * @param string $value field value
     * @param bool $readonly true if field should have readonly attribute
     * @param string $filetypes for allowed filetypes
     * @param int $category_id for default media-category
     */
    public static function form_mediafield($message_id, $fieldname, $value, $readonly = false, $filetypes = '', $category_id = 0): void
    {
        $filetypes = strtolower(str_replace(' ', '', $filetypes));
        echo '<dl class="rex-form-group form-group" id="MEDIA_'. $fieldname .'">';
        echo '<dt><label>' . rex_i18n::msg($message_id) . '</label></dt>';
        echo '<dd><div class="input-group">';
        echo '<input class="form-control" type="text" name="REX_INPUT_MEDIA[' . $fieldname . ']" value="' . $value . '" id="REX_MEDIA_' . $fieldname . '" readonly="readonly">';
        echo '<span class="input-group-btn">';
        if (!$readonly) {
            echo self::getMediaManagingButtons($fieldname, false, $filetypes, $category_id);
        }
        echo '</span>';
        echo '</div><div class="rex-js-media-preview"></div></dd>';
        echo '</dl>';
    }

    /**
     * Prints a row with an medialist input field.
     * @param string $message_id rex_i18n message id for the label text
     * @param int $fieldnumber input field name (without REX_MEDIALIST_SELECT part)
     * @param string[] $values field values
     * @param bool $readonly true if field should have readonly attribute
     */
    public static function form_medialistfield($message_id, $fieldnumber, $values, $readonly = false): void
    {
        /*
                $wdgtClass = ' rex-js-widget-imglist rex-js-widget-preview rex-js-widget-tooltip';
                if (rex_addon::get('media_manager')->isAvailable()) {
                    $wdgtClass .= ' rex-js-widget-preview-media-manager';
                }

                $thumbnails = '';
                $options = '';
                if (is_array($values)) {
                    foreach ($values as $key => $file) {
                        if ($file != '') {

                            $url = rex_url::backendController(['rex_media_type' => 'rex_medialistbutton_preview', 'rex_media_file' => $file]);
                            if (pathinfo($file, PATHINFO_EXTENSION) === 'svg') {
                                $url = rex_url::media($file);
                            }
                            $thumbnails .= '<li data-key="' . $key . '" value="' . $file . '" data-value="' . $file . '"><img class="thumbnail" src="' . $url . '" /></li>';

                            $options .= '<option data-key="' . $key . '" value="' . $file . '">' . $file . '</option>';
                        }
                    }
                }

                $disabled = ' disabled';
                if (rex::getUser()->getComplexPerm('media')->hasMediaPerm()) {
                    $disabled = '';
                }

                $id = str_replace(array('][', '[', ']'), '', $fieldnumber);

                $e = [];
                $e['before'] = '<div class="rex-js-widget custom-imglist ' . $wdgtClass . '" data-widget-id="' . $id . '">';
                $e['field'] = '<ul class="form-control thumbnail-list" id="REX_IMGLIST_' . $id . '">' . $thumbnails . '</ul><select class="form-control" name="REX_MEDIALIST_SELECT[' . $id . ']" id="REX_MEDIALIST_SELECT_' . $id . '" size="10">' . $options . '</select><input type="hidden" name="' . $name . '" id="REX_MEDIALIST_' . $id . '" value="' . $value . '" />';
                $e['functionButtons'] = ($readonly ? '' : '
                        <a href="#" class="btn btn-popup open" title="' . rex_i18n::msg('var_media_open') . '"' . $disabled . '><i class="rex-icon rex-icon-open-mediapool"></i></a>
                        <a href="#" class="btn btn-popup add" title="' . rex_i18n::msg('var_media_new') . '"' . $disabled . '><i class="rex-icon rex-icon-add-media"></i></a>
                        <a href="#" class="btn btn-popup delete" title="' . rex_i18n::msg('var_media_remove') . '"' . $disabled . '><i class="rex-icon rex-icon-delete-media"></i></a>
                        <a href="#" class="btn btn-popup view" title="' . rex_i18n::msg('var_media_view') . '"' . $disabled . '><i class="rex-icon rex-icon-view-media"></i></a>');
                $e['after'] = '<div class="rex-js-media-preview"></div></div>';

                $fragment = new rex_fragment();
                $fragment->setVar('elements', [$e], false);
                print $fragment->parse('core/form/widget_list.php');
        */

        echo '<dl class="rex-form-group form-group" id="MEDIALIST_'. $fieldnumber .'">';
        echo '<dt><label>' . rex_i18n::msg($message_id) . '</label></dt>';
        echo '<dd><div class="input-group">';
        echo '<select class="form-control" name="REX_MEDIALIST_SELECT[' . $fieldnumber . ']" id="REX_MEDIALIST_SELECT_' . $fieldnumber . '" size="10" style="margin: 0">';
        foreach ($values as $value) {
            echo '<option value="' . $value . '">' . $value . '</option>';
        }
        echo '</select>';
        echo '<input type="hidden" name="REX_INPUT_MEDIALIST[' . $fieldnumber . ']" id="REX_MEDIALIST_' . $fieldnumber . '" value="' . implode(',', $values) . '">';
        echo '<span class="input-group-addon">';
        if (!$readonly) {
            echo '<div class="btn-group-vertical">' . self::getMediaPositionButtons($fieldnumber) . '</div>';
            echo '<div class="btn-group-vertical">' . self::getMediaManagingButtons($fieldnumber, true) . '</div>';
        }
        echo '</span>';
        echo '</div><div class="rex-js-media-preview"></div></dd>';
        echo '</dl>';
    }

    /**
     * Prints a select field.
     * @param string $message_id rex_i18n message id for the label text
     * @param string $fieldname Select field name
     * @param array<string|int, string|int>|array<string|int, string|int> $values field values
     * @param array<string|int, string|int>|array<string|int, string|int> $selected_values Preselected value
     * @param int $size Size of the select field (parameter is no more used)
     * @param bool $multiple true if multiple selections are allowed
     * @param bool $readonly true if field should have readonly attribute
     */
    public static function form_select($message_id, $fieldname, $values, $selected_values = [], $size = 1, $multiple = false, $readonly = false): void
    {
        echo '<dl class="rex-form-group form-group" id="'. $fieldname .'">';
        echo '<dt><label>' . rex_i18n::msg($message_id) . '</label></dt>';
        echo '<dd>';
        $multiple_attr = $multiple ? ' multiple="multiple"' : '';
        if ($readonly) {
            // Submit array
            foreach ($selected_values as $selected_value) {
                echo '<input type="hidden" name="'. $fieldname .'" value="'. $selected_value .'">';
            }
            echo '<select class="form-control selectpicker" name="disabled" disabled '. $multiple_attr .'>';
        } else {
            echo '<select class="form-control selectpicker" name="'. $fieldname . '" '. $multiple_attr .'>';
        }
        foreach ($values as $key => $value) {
            $selected = '';
            if (in_array($key, $selected_values, true)) {
                $selected = ' selected="selected"';
            }
            echo '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
        }
        echo '</select>';
        echo '</dl>';
    }

    /**
     * Prints a row with an textare.
     * @param string $message_id rex_i18n message id for the label text
     * @param string $fieldname textarea field name
     * @param string $value textarea value
     * @param int $rows Number rows
     * @param bool $required true if field should have required attribute. If
     * $use_wysiwyg is true, $required is automatically false
     * @param bool $readonly true if field should have readonly attribute
     * @param bool $use_wysiwyg Use WYSIWYG Editor
     */
    public static function form_textarea($message_id, $fieldname, $value, $rows = 5, $required = false, $readonly = false, $use_wysiwyg = true): void
    {
        echo '<dl class="rex-form-group form-group" id="'. $fieldname .'">';
        echo '<dt><label>' . rex_i18n::msg($message_id) . '</label></dt>';
        $wysiwyg_class = ' ';
        if ($use_wysiwyg) {
            $wysiwyg_class .= self::getWYSIWYGEditorClass();
        }
        if ($readonly) {
            echo '<dd><div class="form-control" style="height: 100px;overflow-y: scroll">'. $value .'</div>'
                . '<input type="hidden" name="' . $fieldname . '" value="'. str_replace('"', "'", $value) .'"></dd>';
        } else {
            echo '<dd><textarea cols="1" rows="' . $rows . '" class="form-control' . $wysiwyg_class . '" name="' . $fieldname . '" data-lang="de"';
            // Required can only be activated if WYSIWYG Editor is not activated
            if ($required && !$use_wysiwyg) {
                echo ' required';
            }
            echo '>' . $value . '</textarea></dd>';
        }
        echo '</dl>';
    }

    /**
     * Regenerates cache of URL addon.
     * @param string $namespace Profile namespace, if URLs for only one profile
     * should be generated. This works only for url Addon Version >=2. If
     * namespace does not exist, nothing happens.
     */
    public static function generateUrlCache($namespace = ''): void
    {
        if (\rex_addon::get('url')->isAvailable() && \rex_version::compare(\rex_addon::get('url')->getVersion(), '2.0', '>=')) {
            // Delete url addon cache file
            \Url\Cache::deleteProfiles();
            // Read profile
            $profiles = '' !== $namespace ? \Url\Profile::getByNamespace($namespace) : \Url\Profile::getAll();
            foreach ($profiles as $profile) {
                // generate URLs
                $profile->deleteUrls();
                $profile->buildUrls();
            }
        }
    }

    /**
     * Updates url addon scheme article id.
     * @param string $table_name Table/view name (version 1.x) or namespace (version 2.x) used for url scheme. Parameter is used as identifier.
     * @param int $article_id Redaxo article id
     */
    public static function update_url_scheme($table_name, $article_id): void
    {
        if (rex_addon::get('url')->isAvailable() && rex_version::compare(\rex_addon::get('url')->getVersion(), '2.0', '>=')) {
            $sql = rex_sql::factory();
            // url version 2.x
            $query = 'UPDATE `'. \rex::getTablePrefix() .'url_generator_profile` SET `article_id` = '. $article_id .' '
                ."WHERE `table_name` LIKE '%". $table_name ."'";
            $sql->setQuery($query);
            $query = 'SELECT namespace FROM  `'. \rex::getTablePrefix() .'url_generator_profile` '
                ."WHERE `table_name` LIKE '%". $table_name ."'";
            $sql->setQuery($query);
            if ($sql->getRows() > 0) {
                self::generateUrlCache((string) $sql->getValue('namespace'));
            }
        }
    }
}
