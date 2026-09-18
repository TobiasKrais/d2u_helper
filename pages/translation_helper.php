<?php

use TobiasKrais\D2UHelper\BackendHelper;

$csrfToken = BackendHelper::getPageCsrfToken();
$invalidCsrf = false;
if ((
    'save' === filter_input(INPUT_POST, 'btn_save')
    || 'Speichern' === rex_request::request('btn_save', 'string')
    || 1 === (int) filter_input(INPUT_POST, 'btn_save')
    || 1 === (int) filter_input(INPUT_POST, 'btn_apply')
    || 1 === (int) filter_input(INPUT_POST, 'btn_delete', FILTER_VALIDATE_INT)
) && !$csrfToken->isValid()) {
    echo rex_view::error(rex_i18n::msg('csrf_token_invalid'));
    $invalidCsrf = true;
}
// Set Session
if ('' === rex_session('d2u_helper_translation')) {
    $default_settings = ['clang_id' => rex_clang::getStartId(), 'filter' => 'update'];
    rex_request::setSession('d2u_helper_translation', $default_settings);
}

// Save form in session
if (!$invalidCsrf && 'save' === filter_input(INPUT_POST, 'btn_save')) {
    $settings = rex_post('settings', 'array', []);
    rex_request::setSession('d2u_helper_translation', $settings);
}

?>

<h2><?= rex_i18n::msg('d2u_helper_meta_translations') ?></h2>
<p><?= rex_i18n::msg('d2u_helper_translations_description') ?></p>

<?php
if (1 === count(rex_clang::getAll())) {
    echo rex_view::warning(rex_i18n::msg('d2u_helper_translations_none'));
} else {
    $source_clang_id = (int) rex_config::get('d2u_helper', 'default_lang');
    $target_clang_id = is_array(rex_session('d2u_helper_translation')) && array_key_exists('clang_id', rex_session('d2u_helper_translation')) ? (int) rex_session('d2u_helper_translation')['clang_id'] : rex_clang::getStartId();
    $filter_type = is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) ? (string) rex_session('d2u_helper_translation')['filter'] : 'update';
    $d2u_translation_mode = (($d2u_translation_mode ?? 'addons') === 'articles') ? 'articles' : 'addons';
?>
	<form action="<?= BackendHelper::getCurrentBackendPage([], ['message', 'message_type']) ?>" method="post">
		<?= $csrfToken->getHiddenField() ?>
		<div class="panel panel-edit">
			<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_helper_translations_filter') ?></div></header>
			<div class="panel-body">
				<?php
                    // Language selection
                    $lang_options = [];
                    if (count(rex_clang::getAll()) > 1) {
                        foreach (rex_clang::getAll() as $rex_clang) {
                            if ($source_clang_id !== $rex_clang->getId() && rex::getUser() instanceof rex_user &&
                                    (\rex::getUser()->isAdmin() || \rex::getUser()->getComplexPerm('clang') instanceof rex_clang_perm && \rex::getUser()->getComplexPerm('clang')->hasPerm($rex_clang->getId()))) {
                                $lang_options[$rex_clang->getId()] = $rex_clang->getName();
                            }
                        }
                    }
                    if (!in_array($target_clang_id, array_keys($lang_options))) {
                        $target_clang_id = array_keys($lang_options)[0];
                    }
                    BackendHelper::form_select('d2u_helper_translations_language', 'settings[clang_id]', $lang_options, [$target_clang_id]);

                    if ('addons' === $d2u_translation_mode) {
                        $filter_options = [
                            'update' => rex_i18n::msg('d2u_helper_translations_filter_update'),
                            'missing' => rex_i18n::msg('d2u_helper_translations_filter_missing'),
                        ];
                        BackendHelper::form_select('d2u_helper_translations_filter_select', 'settings[filter]', $filter_options, [$filter_type]);
                    }
                ?>
			</div>
			<footer class="panel-footer">
				<div class="rex-form-panel-footer">
					<div class="btn-toolbar">
						<button class="btn btn-save rex-form-aligned" type="submit" name="btn_save" value="save"><?= rex_i18n::msg('d2u_helper_translations_apply') ?></button>
					</div>
				</div>
			</footer>
		</div>
	</form>
<?php
    if ('articles' === $d2u_translation_mode) {
        // Per-article action: translate the whole article, or only its missing
        // blocks. Runs before rendering so the refreshed counts show immediately.
        if (!$invalidCsrf) {
            $d2u_action_article = (int) filter_input(INPUT_POST, 'd2u_translate_article', FILTER_VALIDATE_INT);
            if ($d2u_action_article > 0) {
                if (!$csrfToken->isValid()) {
                    echo rex_view::error(rex_i18n::msg('csrf_token_invalid'));
                } else {
                    $d2u_mode = (string) filter_input(INPUT_POST, 'd2u_translate_mode');
                    $d2u_mode = in_array($d2u_mode, ['all', 'missing', 'stale'], true) ? $d2u_mode : 'all';
                    $d2u_result = \TobiasKrais\D2UHelper\SliceTranslator::translateArticle($d2u_action_article, $source_clang_id, $target_clang_id, $d2u_mode);
                    echo $d2u_result['success']
                        ? rex_view::success(rex_i18n::msg('d2u_helper_article_translate_done', rex_escape($d2u_result['name'])))
                        : rex_view::warning($d2u_result['message']);
                }
            }
        }

        $d2u_rows = \TobiasKrais\D2UHelper\SliceTranslator::getArticleContentRows($source_clang_id, $target_clang_id);
        $d2u_ai_available = \TobiasKrais\D2UHelper\AiTranslationHelper::isAvailable();

        if (0 === count($d2u_rows)) {
            echo rex_view::info(rex_i18n::msg('d2u_helper_article_none'));
        } else {
            if (!$d2u_ai_available) {
                echo rex_view::info(rex_i18n::msg('d2u_helper_translations_ai_not_configured'));
            }
            $d2u_form_action = rex_escape(BackendHelper::getCurrentBackendPage([], ['message', 'message_type']));
            echo '<div class="panel panel-edit">';
            echo '<header class="panel-heading"><div class="panel-title">'. rex_i18n::msg('d2u_helper_translations_tab_articles') .'</div></header>';
            echo '<div class="table-responsive">';
            echo '<table class="table table-striped table-hover" style="margin-bottom:0">';
            echo '<thead><tr>'
                . '<th>'. rex_i18n::msg('d2u_helper_article_col_name') .'</th>'
                . '<th class="text-center">'. rex_i18n::msg('d2u_helper_article_col_nocontent') .'</th>'
                . '<th class="text-center">'. rex_i18n::msg('d2u_helper_article_col_missing') .'</th>'
                . '<th class="text-center">'. rex_i18n::msg('d2u_helper_article_col_update') .'</th>'
                . '<th>'. rex_i18n::msg('d2u_helper_article_col_actions') .'</th>'
                . '</tr></thead><tbody>';
            foreach ($d2u_rows as $d2u_row) {
                $d2u_indent = str_repeat('<span style="display:inline-block;width:18px"></span>', (int) $d2u_row['level']);
                // rex_url::backendPage() already escapes the argument separator, so the
                // URL must NOT be passed through rex_escape() (that would double-encode &).
                $d2u_edit_url = rex_url::backendPage('content/edit', ['article_id' => (int) $d2u_row['id'], 'clang' => $target_clang_id, 'mode' => 'edit']);
                $d2u_link = '<a href="'. $d2u_edit_url .'">'. rex_escape($d2u_row['name']) .'</a>';
                echo '<tr>';
                echo '<td>'. $d2u_indent . ($d2u_row['hasContent'] ? $d2u_link : '<span class="text-muted">'. $d2u_link .'</span>') .'</td>';
                if ($d2u_row['hasContent']) {
                    echo '<td class="text-center">'. ($d2u_row['noContent'] ? '<span class="label label-danger">'. rex_i18n::msg('d2u_helper_article_state_nocontent') .'</span>' : '<span class="text-muted">–</span>') .'</td>';
                    echo '<td class="text-center">'. ((int) $d2u_row['missing'] > 0 ? '<span class="label label-warning">'. (int) $d2u_row['missing'] .'</span>' : '<span class="text-muted">–</span>') .'</td>';
                    echo '<td class="text-center">'. ((int) $d2u_row['stale'] > 0 ? '<span class="label label-info">'. (int) $d2u_row['stale'] .'</span>' : '<span class="text-muted">–</span>') .'</td>';
                    echo '<td>';
                    if ($d2u_ai_available) {
                        echo '<form action="'. $d2u_form_action .'" method="post" style="display:inline-block;margin:0 4px 4px 0">'
                            . $csrfToken->getHiddenField()
                            . '<input type="hidden" name="d2u_translate_article" value="'. (int) $d2u_row['id'] .'">'
                            . '<input type="hidden" name="d2u_translate_mode" value="all">'
                            . '<button type="submit" class="btn btn-primary btn-xs"><i class="rex-icon fa-language"></i> '. rex_i18n::msg('d2u_helper_article_action_all') .'</button>'
                            . '</form>';
                        if ((int) $d2u_row['missing'] > 0) {
                            echo '<form action="'. $d2u_form_action .'" method="post" style="display:inline-block;margin:0 4px 4px 0">'
                                . $csrfToken->getHiddenField()
                                . '<input type="hidden" name="d2u_translate_article" value="'. (int) $d2u_row['id'] .'">'
                                . '<input type="hidden" name="d2u_translate_mode" value="missing">'
                                . '<button type="submit" class="btn btn-default btn-xs"><i class="rex-icon fa-plus"></i> '. rex_i18n::msg('d2u_helper_article_action_missing') .'</button>'
                                . '</form>';
                        }
                        if ((int) $d2u_row['stale'] > 0) {
                            echo '<form action="'. $d2u_form_action .'" method="post" style="display:inline-block;margin:0 4px 4px 0">'
                                . $csrfToken->getHiddenField()
                                . '<input type="hidden" name="d2u_translate_article" value="'. (int) $d2u_row['id'] .'">'
                                . '<input type="hidden" name="d2u_translate_mode" value="stale">'
                                . '<button type="submit" class="btn btn-default btn-xs"><i class="rex-icon fa-refresh"></i> '. rex_i18n::msg('d2u_helper_article_action_update') .'</button>'
                                . '</form>';
                        }
                    }
                    echo '</td>';
                } else {
                    echo '<td colspan="4"></td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table></div></div>';
        }
    } else {
        /**
         * Extension point for translation list.
         * @param array $subject List of addons and their pages with translation status
         * @param array $params Parameters
         * @param int $params['source_clang_id'] Source clang id
         * @param int $params['target_clang_id'] Target clang id
         * @param string $params['filter_type'] Filter type
         * @return array List of addons and their pages with translation status. Example:
         * [
         *      [
         *          'addon_name' => 'addon name',
         *          'pages' => [
         *              [
         *                  'title' => 'addon page title',
         *                  'icon' => 'FontAwesome page icon',
         *                  'html' => 'ul html code containing links to the backend pages with the translations'
         *              ]
         *      ]
         * ]
         */
        $translation_list = rex_extension::registerPoint(new rex_extension_point(name: 'D2U_HELPER_TRANSLATION_LIST', params: ['source_clang_id' => $source_clang_id, 'target_clang_id' => $target_clang_id, 'filter_type' => $filter_type]));
    }

    if ('addons' === $d2u_translation_mode) {
    // When AI translation is available and there is at least one object to
    // translate, offer a "translate all" button and load the helper JS. The
    // per-object trigger icons are rendered by the addons via
    // BackendHelper::getTranslationItem().
    $d2u_ai_available = \TobiasKrais\D2UHelper\AiTranslationHelper::isAvailable();
    $d2u_has_items = false;
    if (is_array($translation_list)) {
        foreach ($translation_list as $translation_list_item) {
            if (isset($translation_list_item['pages']) && is_array($translation_list_item['pages']) && count($translation_list_item['pages']) > 0) {
                $d2u_has_items = true;
                break;
            }
        }
    }
    if ($d2u_ai_available && $d2u_has_items) {
        // Load the helper JS via a direct script tag in the body. rex_view::addJsFile()
        // would be too late here: the backend <head> (with rex_view::getJsFiles()) is
        // already rendered before this page include runs, so the file would never load.
        // Append a filemtime cache-buster so updated JS is picked up (no version query otherwise).
        $d2u_th_js_file = rex_path::addonAssets('d2u_helper', 'translation_helper.js');
        $d2u_th_js_url = rex_url::addonAssets('d2u_helper', 'translation_helper.js');
        $d2u_th_js_url .= is_file($d2u_th_js_file) ? '?buster='. filemtime($d2u_th_js_file) : '';
        echo '<script src="'. rex_escape($d2u_th_js_url) .'"></script>';
        echo '<div id="d2u-translation-config" style="display:none"'
            . ' data-url="'. rex_escape(rex_url::backendController(['rex-api-call' => 'd2u_helper_translate'])) .'"'
            . ' data-source-clang-id="'. $source_clang_id .'"'
            . ' data-target-clang-id="'. $target_clang_id .'"'
            . ' data-msg-translating="'. rex_escape(rex_i18n::msg('d2u_helper_translations_ai_translating')) .'"'
            . ' data-msg-error="'. rex_escape(rex_i18n::msg('d2u_helper_translations_ai_error')) .'">'
            . rex_csrf_token::factory('d2u_helper_translate')->getHiddenField()
            . '</div>';
        echo '<p><button type="button" id="d2u-translate-all" class="btn btn-primary"><i class="rex-icon fa-language"></i> '. rex_i18n::msg('d2u_helper_translations_ai_translate_all') .'</button></p>';
    }
    elseif (!$d2u_ai_available && $d2u_has_items) {
        // AI translation is not available (ai_platform missing/inactive or no
        // default text profile) but there are translations to do: hint the user.
        echo rex_view::info(rex_i18n::msg('d2u_helper_translations_ai_not_configured'));
    }

    if (is_array($translation_list) && count($translation_list) > 0) {
        foreach ($translation_list as $translation_list_item) {
            if (count($translation_list_item['pages']) > 0) {
                echo '<div class="panel panel-edit">';
                echo '<header class="panel-heading"><div class="panel-title">'. $translation_list_item['addon_name'] .'</div></header>';
                echo '<div class="panel-body">';
                foreach ($translation_list_item['pages'] as $page) {
                    echo '<fieldset>';
                    echo '<legend><small><i class="rex-icon '. $page['icon'] .'"></i></small> '.$page['title'];
                    if ($d2u_ai_available) {
                        // Per-category button: translates only the items inside this fieldset.
                        echo ' <button type="button" class="btn btn-primary btn-xs d2u-translate-category"><i class="rex-icon fa-language"></i> '. rex_i18n::msg('d2u_helper_translations_ai_translate_category') .'</button>';
                    }
                    echo '</legend>';
                    echo '<div class="panel-body-wrapper slide">'. $page['html'] . '</div>';
                    echo '</fieldset>';
                }
                echo '</div>';
                echo '</div>';
            }
        }
    }
    else {
        echo 'update' === $filter_type ? rex_i18n::msg('d2u_helper_translations_uptodate_update') : rex_i18n::msg('d2u_helper_translations_uptodate_missing');
    }
    }

    echo BackendHelper::getCSS();
    echo BackendHelper::getJS();
    echo BackendHelper::getJSOpenAll();
}