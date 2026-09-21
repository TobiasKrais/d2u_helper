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

<?php
if (1 === count(rex_clang::getAll())) {
    echo rex_view::warning(rex_i18n::msg('d2u_helper_translations_none'));
} else {
    $source_clang_id = (int) rex_config::get('d2u_helper', 'default_lang');
    $target_clang_id = is_array(rex_session('d2u_helper_translation')) && array_key_exists('clang_id', rex_session('d2u_helper_translation')) ? (int) rex_session('d2u_helper_translation')['clang_id'] : rex_clang::getStartId();
    $filter_type = is_array(rex_session('d2u_helper_translation')) && array_key_exists('filter', rex_session('d2u_helper_translation')) ? (string) rex_session('d2u_helper_translation')['filter'] : 'update';
    $d2u_translation_mode = (($d2u_translation_mode ?? 'articles') === 'addons') ? 'addons' : 'articles';
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
        // Single (one button) and bulk (checkbox selection) actions share one form;
        // the pressed button carries the action via its name/value.
        if (!$invalidCsrf) {
            $d2u_single = (string) filter_input(INPUT_POST, 'd2u_action');
            $d2u_bulk_mode = (string) filter_input(INPUT_POST, 'd2u_bulk');
            $d2u_valid_modes = ['all', 'missing', 'stale'];
            if (('' !== $d2u_single || '' !== $d2u_bulk_mode) && !$csrfToken->isValid()) {
                echo rex_view::error(rex_i18n::msg('csrf_token_invalid'));
            } elseif ('' !== $d2u_single) {
                [$d2u_sid, $d2u_smode] = array_pad(explode(':', $d2u_single, 2), 2, '');
                $d2u_sid = (int) $d2u_sid;
                $d2u_smode = in_array($d2u_smode, $d2u_valid_modes, true) ? $d2u_smode : 'all';
                if ($d2u_sid > 0) {
                    $d2u_result = \TobiasKrais\D2UHelper\SliceTranslator::translateArticle($d2u_sid, $source_clang_id, $target_clang_id, $d2u_smode);
                    echo $d2u_result['success']
                        ? rex_view::success(rex_i18n::msg('d2u_helper_article_translate_done', rex_escape($d2u_result['name'])))
                        : rex_view::warning($d2u_result['message']);
                }
            } elseif ('' !== $d2u_bulk_mode && in_array($d2u_bulk_mode, $d2u_valid_modes, true)) {
                $d2u_ids = array_values(array_filter(array_map('intval', (array) rex_post('d2u_articles', 'array', [])), static fn (int $v): bool => $v > 0));
                if (0 === count($d2u_ids)) {
                    echo rex_view::info(rex_i18n::msg('d2u_helper_article_bulk_none'));
                } else {
                    $d2u_ok = 0;
                    $d2u_fail = 0;
                    foreach ($d2u_ids as $d2u_bid) {
                        $r = \TobiasKrais\D2UHelper\SliceTranslator::translateArticle($d2u_bid, $source_clang_id, $target_clang_id, $d2u_bulk_mode);
                        $r['success'] ? $d2u_ok++ : $d2u_fail++;
                    }
                    if ($d2u_ok > 0) {
                        echo rex_view::success(rex_i18n::msg('d2u_helper_article_bulk_done', $d2u_ok));
                    }
                    if ($d2u_fail > 0) {
                        echo rex_view::warning(rex_i18n::msg('d2u_helper_article_bulk_failed', $d2u_fail));
                    }
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

            echo '<style>#d2u-article-table thead th{position:sticky;top:0;z-index:2;background-color:#f5f5f5}@media(prefers-color-scheme:dark){#d2u-article-table thead th{background-color:#2b2b2b}}</style>';
            echo '<form action="'. $d2u_form_action .'" method="post" id="d2u-article-table">';
            echo $csrfToken->getHiddenField();
            echo '<div class="panel panel-edit">';
            echo '<header class="panel-heading"><div class="panel-title">'. rex_i18n::msg('d2u_helper_translations_tab_articles') .'</div></header>';
            if ($d2u_ai_available) {
                // Bulk toolbar: acts on the checked rows.
                echo '<div class="panel-body" style="padding-bottom:0">';
                echo '<div style="display:flex;align-items:center;flex-wrap:wrap;gap:6px;margin-bottom:10px">';
                echo '<strong style="margin-right:4px">'. rex_i18n::msg('d2u_helper_article_bulk_selected') .':</strong>';
                echo '<button type="submit" name="d2u_bulk" value="all" data-d2u-bulk="all" class="btn btn-xs btn-primary"><i class="rex-icon fa-language"></i> '. rex_i18n::msg('d2u_helper_article_action_all') .'</button>';
                echo '<button type="submit" name="d2u_bulk" value="missing" data-d2u-bulk="missing" class="btn btn-xs btn-default"><i class="rex-icon fa-plus"></i> '. rex_i18n::msg('d2u_helper_article_action_missing') .'</button>';
                echo '<button type="submit" name="d2u_bulk" value="stale" data-d2u-bulk="stale" class="btn btn-xs btn-default"><i class="rex-icon fa-refresh"></i> '. rex_i18n::msg('d2u_helper_article_action_update') .'</button>';
                echo '</div>';
                echo '<div id="d2u-article-feedback" class="alert" style="display:none;margin-bottom:10px" role="status" aria-live="polite"></div>';
                echo '</div>';
            }
            echo '<table class="table table-striped table-hover" style="margin-bottom:0">';
            echo '<thead><tr>'
                . '<th style="width:1%"><input type="checkbox" id="d2u-select-all" title="'. rex_escape(rex_i18n::msg('d2u_helper_article_select_all')) .'"></th>'
                . '<th>'. rex_i18n::msg('d2u_helper_article_col_name') .'</th>'
                . '<th class="text-center">'. rex_i18n::msg('d2u_helper_article_col_nocontent') .'</th>'
                . '<th class="text-center">'. rex_i18n::msg('d2u_helper_article_col_missing') .'</th>'
                . '<th class="text-center">'. rex_i18n::msg('d2u_helper_article_col_update') .'</th>'
                . '</tr></thead><tbody>';
            foreach ($d2u_rows as $d2u_row) {
                $d2u_id = (int) $d2u_row['id'];
                $d2u_indent = str_repeat('<span style="display:inline-block;width:18px"></span>', (int) $d2u_row['level']);
                // rex_url::backendPage() already escapes the argument separator, so the
                // URL must NOT be passed through rex_escape() (that would double-encode &).
                $d2u_edit_url = rex_url::backendPage('content/edit', ['article_id' => $d2u_id, 'clang' => $target_clang_id, 'mode' => 'edit']);
                $d2u_name = rex_escape($d2u_row['name']);
                $d2u_path_attr = rex_escape(implode(',', $d2u_row['path']));
                $d2u_toggle = !empty($d2u_row['hasChildren'])
                    ? '<button type="button" class="btn btn-xs btn-default d2u-collapse-toggle" data-collapse-id="'. $d2u_id .'" title="'. rex_escape(rex_i18n::msg('d2u_helper_article_toggle')) .'" style="margin-right:4px;padding:0 5px"><i class="rex-icon fa-caret-down"></i></button>'
                    : '';
                echo '<tr data-article-id="'. $d2u_id .'" data-path="'. $d2u_path_attr .'">';
                $d2u_cat_icon = !empty($d2u_row['isCategory']) ? '<i class="rex-icon rex-icon-category text-muted"></i> ' : '';
                if ($d2u_row['hasContent']) {
                    $d2u_cells = \TobiasKrais\D2UHelper\SliceTranslator::renderArticleStatusCells($d2u_id, [
                        'noContent' => (bool) $d2u_row['noContent'],
                        'missing' => (int) $d2u_row['missing'],
                        'stale' => (int) $d2u_row['stale'],
                    ], $d2u_ai_available);
                    echo '<td><input type="checkbox" class="d2u-row-check" name="d2u_articles[]" value="'. $d2u_id .'"></td>';
                    echo '<td>'. $d2u_indent . $d2u_toggle . $d2u_cat_icon .'<span class="d2u-status-icon">'. $d2u_cells['icon'] .'</span><a href="'. $d2u_edit_url .'">'. $d2u_name .'</a></td>';
                    echo '<td class="text-center d2u-cell-nocontent">'. $d2u_cells['nocontent'] .'</td>';
                    echo '<td class="text-center d2u-cell-missing">'. $d2u_cells['missing'] .'</td>';
                    echo '<td class="text-center d2u-cell-stale">'. $d2u_cells['stale'] .'</td>';
                } else {
                    // Structural ancestor row: name (+ collapse toggle) only.
                    echo '<td></td>';
                    echo '<td>'. $d2u_indent . $d2u_toggle .'<i class="rex-icon rex-icon-category text-muted"></i> <a href="'. $d2u_edit_url .'" class="text-muted">'. $d2u_name .'</a></td>';
                    echo '<td colspan="3"></td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table>';
            echo '<div class="panel-body"><small class="text-muted">'
                . '<strong>'. rex_i18n::msg('d2u_helper_article_hint_intro') .'</strong><br>'
                . rex_i18n::msg('d2u_helper_article_hint_nocontent') .'<br>'
                . rex_i18n::msg('d2u_helper_article_hint_missing') .'<br>'
                . rex_i18n::msg('d2u_helper_article_hint_update')
                . '</small></div>';
            echo '</div></form>';
            // Collapse categories + select-all. Inline is fine in the REDAXO backend.
            echo '<script>(function(){'
                . 'var t=document.getElementById("d2u-article-table");if(!t)return;'
                . 'var sa=document.getElementById("d2u-select-all");'
                . 'if(sa)sa.addEventListener("change",function(){t.querySelectorAll(".d2u-row-check").forEach(function(c){if(!c.closest("tr").hidden)c.checked=sa.checked;});});'
                . 'var col={};'
                . 'function apply(){t.querySelectorAll("tbody tr").forEach(function(tr){var own=tr.getAttribute("data-article-id");var p=(tr.getAttribute("data-path")||"").split(",").filter(Boolean);var h=p.some(function(id){return col[id]&&id!==own;});tr.hidden=h;if(h){var c=tr.querySelector(".d2u-row-check");if(c)c.checked=false;}});}'
                . 't.querySelectorAll(".d2u-collapse-toggle").forEach(function(b){b.addEventListener("click",function(){var id=b.getAttribute("data-collapse-id");col[id]=!col[id];b.querySelector("i").className="rex-icon "+(col[id]?"fa-caret-right":"fa-caret-down");apply();});});'
                . '})();</script>';

            // AJAX article translation: update the acted row in place instead of a
            // full page reload. Buttons stay real submits, so it degrades gracefully.
            if ($d2u_ai_available) {
                $d2u_atjs_file = rex_path::addonAssets('d2u_helper', 'article_translate.js');
                $d2u_atjs_url = rex_url::addonAssets('d2u_helper', 'article_translate.js');
                $d2u_atjs_url .= is_file($d2u_atjs_file) ? '?buster='. filemtime($d2u_atjs_file) : '';
                echo '<div id="d2u-article-translate-config" style="display:none"'
                    . ' data-url="'. rex_escape(rex_url::backendController(['rex-api-call' => 'd2u_helper_article_translate'])) .'"'
                    . ' data-target-clang="'. $target_clang_id .'"'
                    . ' data-msg-translating="'. rex_escape(rex_i18n::msg('d2u_helper_translations_ai_translating')) .'"'
                    . ' data-msg-error="'. rex_escape(rex_i18n::msg('d2u_helper_translations_ai_error')) .'"'
                    . ' data-msg-done="'. rex_escape(rex_i18n::msg('d2u_helper_article_ajax_done')) .'"'
                    . ' data-msg-bulk-none="'. rex_escape(rex_i18n::msg('d2u_helper_article_bulk_none')) .'"'
                    . ' data-msg-cancel="'. rex_escape(rex_i18n::msg('d2u_helper_article_bulk_cancel')) .'"'
                    . ' data-msg-cancelled="'. rex_escape(rex_i18n::msg('d2u_helper_article_bulk_cancelled')) .'">'
                    . rex_csrf_token::factory('d2u_helper_article_translate')->getHiddenField()
                    . '</div>';
                echo '<script src="'. rex_escape($d2u_atjs_url) .'"></script>';
            }
        }

        // Footer: explain how to make slices of custom modules translatable.
        echo '<div class="panel panel-info"><div class="panel-body"><small class="text-muted">'
            . '<strong>'. rex_i18n::msg('d2u_helper_article_module_setup_title') .'</strong><br>'
            . rex_i18n::msg('d2u_helper_article_module_setup_text', '<code>/* d2u_translate: 1:text, 2:html */</code>')
            . '</small></div></div>';
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
        // No filter UI any more: ask the addons for both the missing and the
        // to-update objects and keep them side by side (own table column each).
        $translation_list = [];
        $d2u_addon_index = [];
        foreach (['missing' => 'missingHtml', 'update' => 'updateHtml'] as $d2u_ft => $d2u_key) {
            $d2u_list = rex_extension::registerPoint(new rex_extension_point(name: 'D2U_HELPER_TRANSLATION_LIST', params: ['source_clang_id' => $source_clang_id, 'target_clang_id' => $target_clang_id, 'filter_type' => $d2u_ft]));
            if (!is_array($d2u_list)) {
                continue;
            }
            foreach ($d2u_list as $d2u_addon) {
                $d2u_aname = (string) ($d2u_addon['addon_name'] ?? '');
                if (!isset($d2u_addon_index[$d2u_aname])) {
                    $d2u_addon_index[$d2u_aname] = count($translation_list);
                    $translation_list[] = ['addon_name' => $d2u_aname, 'pages' => []];
                }
                $d2u_ai = $d2u_addon_index[$d2u_aname];
                foreach ((array) ($d2u_addon['pages'] ?? []) as $d2u_page) {
                    $d2u_title = (string) ($d2u_page['title'] ?? '');
                    $d2u_found = null;
                    foreach ($translation_list[$d2u_ai]['pages'] as $d2u_pk => $d2u_existing) {
                        if (($d2u_existing['title'] ?? '') === $d2u_title) {
                            $d2u_found = $d2u_pk;
                            break;
                        }
                    }
                    if (null === $d2u_found) {
                        $translation_list[$d2u_ai]['pages'][] = ['title' => $d2u_title, 'icon' => (string) ($d2u_page['icon'] ?? ''), 'missingHtml' => '', 'updateHtml' => ''];
                        $d2u_found = count($translation_list[$d2u_ai]['pages']) - 1;
                    }
                    $translation_list[$d2u_ai]['pages'][$d2u_found][$d2u_key] = (string) ($d2u_page['html'] ?? '');
                }
            }
        }
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
        echo '<p>'
            . '<button type="button" id="d2u-translate-all" class="btn btn-primary"><i class="rex-icon fa-language"></i> '. rex_i18n::msg('d2u_helper_translations_ai_translate_all') .'</button> '
            . '<button type="button" id="d2u-translate-missing" class="btn btn-default"><i class="rex-icon fa-plus"></i> '. rex_i18n::msg('d2u_helper_translations_ai_translate_missing') .'</button> '
            . '<button type="button" id="d2u-translate-update" class="btn btn-default"><i class="rex-icon fa-refresh"></i> '. rex_i18n::msg('d2u_helper_translations_ai_translate_update') .'</button>'
            . '</p>';
    }
    elseif (!$d2u_ai_available && $d2u_has_items) {
        // AI translation is not available (ai_platform missing/inactive or no
        // default text profile) but there are translations to do: hint the user.
        echo rex_view::info(rex_i18n::msg('d2u_helper_translations_ai_not_configured'));
    }

    $d2u_has_rows = false;
    if (is_array($translation_list)) {
        foreach ($translation_list as $translation_list_item) {
            foreach ($translation_list_item['pages'] as $page) {
                if ('' !== trim((string) ($page['missingHtml'] ?? '')) || '' !== trim((string) ($page['updateHtml'] ?? ''))) {
                    $d2u_has_rows = true;
                    break 2;
                }
            }
        }
    }
    if ($d2u_has_rows) {
        echo '<style>#d2u-addon-table thead th{position:sticky;top:0;z-index:2;background-color:#f5f5f5}@media(prefers-color-scheme:dark){#d2u-addon-table thead th{background-color:#2b2b2b}}</style>';
        echo '<div class="panel panel-edit">';
        echo '<table class="table table-striped" id="d2u-addon-table">';
        echo '<thead><tr>'
            . '<th>'. rex_i18n::msg('d2u_helper_addon_col_addon') .'</th>'
            . '<th>'. rex_i18n::msg('d2u_helper_addon_col_area') .'</th>'
            . '<th>'. rex_i18n::msg('d2u_helper_addon_col_missing') .'</th>'
            . '<th>'. rex_i18n::msg('d2u_helper_addon_col_update') .'</th>'
            . '</tr></thead><tbody>';
        $d2u_catbtn = '<button type="button" class="btn btn-primary btn-xs d2u-translate-category" style="margin-bottom:6px"><i class="rex-icon fa-language"></i> '. rex_i18n::msg('d2u_helper_translations_ai_translate_category') .'</button><br>';
        foreach ($translation_list as $translation_list_item) {
            foreach ($translation_list_item['pages'] as $page) {
                $d2u_mh = trim((string) ($page['missingHtml'] ?? ''));
                $d2u_uh = trim((string) ($page['updateHtml'] ?? ''));
                if ('' === $d2u_mh && '' === $d2u_uh) {
                    continue;
                }
                echo '<tr>';
                echo '<td>'. $translation_list_item['addon_name'] .'</td>';
                echo '<td class="text-nowrap"><i class="rex-icon '. $page['icon'] .'"></i> '. $page['title'] .'</td>';
                // Each column is its own translate scope so the missing and the
                // to-update objects can be translated separately.
                echo '<td class="d2u-translate-category-scope d2u-scope-missing">'. ($d2u_ai_available && '' !== $d2u_mh ? $d2u_catbtn : '') . ('' !== $d2u_mh ? $d2u_mh : '<span class="text-muted">–</span>') .'</td>';
                echo '<td class="d2u-translate-category-scope d2u-scope-update">'. ($d2u_ai_available && '' !== $d2u_uh ? $d2u_catbtn : '') . ('' !== $d2u_uh ? $d2u_uh : '<span class="text-muted">–</span>') .'</td>';
                echo '</tr>';
            }
        }
        echo '</tbody></table></div>';
    }
    else {
        echo rex_view::info(rex_i18n::msg('d2u_helper_translations_uptodate_all'));
    }
    }

    echo BackendHelper::getCSS();
    echo BackendHelper::getJS();
    echo BackendHelper::getJSOpenAll();
}