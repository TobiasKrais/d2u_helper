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
    $d2u_translation_mode = in_array($d2u_translation_mode ?? 'articles', ['articles', 'addons', 'seo'], true) ? ($d2u_translation_mode ?? 'articles') : 'articles';
?>
	<form action="<?= BackendHelper::getCurrentBackendPage([], ['message', 'message_type']) ?>" method="post" id="d2u-translation-filter-form">
		<?= $csrfToken->getHiddenField() ?>
		<input type="hidden" name="btn_save" value="save">
		<div class="panel panel-edit">
			<header class="panel-heading"><div class="panel-title"><?= rex_i18n::msg('d2u_helper_translations_filter') ?></div></header>
			<div class="panel-body">
				<p class="text-muted" style="margin-bottom:15px;">
					<b><?= rex_i18n::msg('d2u_helper_translations_source_language') ?>:</b>
					<?= rex_escape(rex_clang::get($source_clang_id) instanceof rex_clang ? rex_clang::get($source_clang_id)->getName() : (string) $source_clang_id) ?>
					<br><small><?= rex_i18n::msg('d2u_helper_translations_source_hint') ?></small>
				</p>
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
		</div>
	</form>
	<script>
		(function () {
			var form = document.getElementById('d2u-translation-filter-form');
			if (!form) { return; }
			var sel = form.querySelector('select[name="settings[clang_id]"]');
			if (sel) {
				sel.addEventListener('change', function () { form.submit(); });
			}
		})();
	</script>
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
                $d2u_skipped_pdf = 0;
                if (1 === (int) filter_input(INPUT_POST, 'd2u_skip_pdf', FILTER_VALIDATE_INT)) {
                    $d2u_ids = array_values(array_filter($d2u_ids, static function (int $id) use ($source_clang_id, &$d2u_skipped_pdf): bool {
                        if (\TobiasKrais\D2UHelper\SliceTranslator::articleHasPdfMedia($id, $source_clang_id)) {
                            ++$d2u_skipped_pdf;
                            return false;
                        }
                        return true;
                    }));
                }
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
                    if ($d2u_skipped_pdf > 0) {
                        echo rex_view::info(rex_i18n::msg('d2u_helper_article_skip_pdf_done', $d2u_skipped_pdf));
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
                echo '<label class="checkbox-inline" style="margin-left:10px;margin-bottom:0"><input type="checkbox" name="d2u_skip_pdf" value="1" id="d2u-skip-pdf" checked> '. rex_i18n::msg('d2u_helper_article_skip_pdf') .'</label>';
                echo '</div>';
                echo '<div id="d2u-article-feedback" class="alert" style="display:none;margin-bottom:10px" role="status" aria-live="polite"></div>';
                echo '</div>';
            }
            echo '<table class="table table-striped table-hover" style="margin-bottom:0">';
            echo '<thead><tr>'
                . '<th style="width:1%"><input type="checkbox" id="d2u-select-all" title="'. rex_escape(rex_i18n::msg('d2u_helper_article_select_all')) .'"></th>'
                . '<th>'. rex_i18n::msg('d2u_helper_article_col_name') .'</th>'
                . '<th class="text-center">'. rex_i18n::msg('d2u_helper_article_col_status') .'</th>'
                . '<th class="text-center" title="'. rex_escape(rex_i18n::msg('d2u_helper_article_pdf_media_hint')) .'"><i class="rex-icon fa-file-pdf-o"></i></th>'
                . '<th class="text-center">'. rex_i18n::msg('d2u_helper_article_col_nocontent') .'</th>'
                . '<th class="text-center">'. rex_i18n::msg('d2u_helper_article_col_missing') .'</th>'
                . '<th class="text-center">'. rex_i18n::msg('d2u_helper_article_col_update') .'</th>'
                . '</tr></thead><tbody>';
            $d2u_any_pdf = false;
            foreach ($d2u_rows as $d2u_pdf_probe) {
                if (!empty($d2u_pdf_probe['hasPdfMedia'])) {
                    $d2u_any_pdf = true;
                    break;
                }
            }
            foreach ($d2u_rows as $d2u_row) {
                $d2u_id = (int) $d2u_row['id'];
                // Each level indents by exactly one toggle-slot width so rows with and
                // without a collapse arrow line up.
                $d2u_indent = str_repeat('<span style="display:inline-block;width:26px"></span>', (int) $d2u_row['level']);
                // rex_url::backendPage() already escapes the argument separator, so the
                // URL must NOT be passed through rex_escape() (that would double-encode &).
                $d2u_edit_url = rex_url::backendPage('content/edit', ['article_id' => $d2u_id, 'clang' => $target_clang_id, 'mode' => 'edit']);
                $d2u_name = rex_escape($d2u_row['name']);
                $d2u_path_attr = rex_escape(implode(',', $d2u_row['path']));
                // Fixed-width toggle slot: a real button when the node has children,
                // otherwise an empty spacer of the same width (keeps alignment).
                $d2u_toggle = !empty($d2u_row['hasChildren'])
                    ? '<button type="button" class="btn btn-xs btn-default d2u-collapse-toggle" data-collapse-id="'. $d2u_id .'" title="'. rex_escape(rex_i18n::msg('d2u_helper_article_toggle')) .'" style="width:22px;padding:0;margin-right:4px"><i class="rex-icon fa-caret-down"></i></button>'
                    : '<span style="display:inline-block;width:26px"></span>';
                echo '<tr data-article-id="'. $d2u_id .'" data-path="'. $d2u_path_attr .'">';
                // Type icon: folder (category with children), empty folder (leaf
                // category) or article.
                if (!empty($d2u_row['isCategory'])) {
                    $d2u_type_icon = !empty($d2u_row['hasChildren'])
                        ? '<i class="rex-icon fa-folder text-muted" title="'. rex_escape(rex_i18n::msg('d2u_helper_article_type_category')) .'"></i> '
                        : '<i class="rex-icon fa-folder-o text-muted" title="'. rex_escape(rex_i18n::msg('d2u_helper_article_type_category_empty')) .'"></i> ';
                } else {
                    $d2u_type_icon = '<i class="rex-icon fa-file-o text-muted" title="'. rex_escape(rex_i18n::msg('d2u_helper_article_type_article')) .'"></i> ';
                }
                $d2u_pdf_icon = '';
                if (!empty($d2u_row['hasPdfMedia'])) {
                    // Jump to the first PDF slice in the source language, where the file
                    // is guaranteed to exist and can be inspected in context.
                    $d2u_pdf_url = rex_url::backendPage('content/edit', ['article_id' => $d2u_id, 'clang' => $source_clang_id]) . '#slice' . (int) $d2u_row['pdfSliceId'];
                    $d2u_pdf_icon = '<a href="' . $d2u_pdf_url . '" title="' . rex_escape(rex_i18n::msg('d2u_helper_article_pdf_media_hint')) . '"><i class="rex-icon fa-file-pdf-o text-warning" style="margin-right:4px"></i></a>';
                }
                if ($d2u_row['hasContent']) {
                    $d2u_cells = \TobiasKrais\D2UHelper\SliceTranslator::renderArticleStatusCells($d2u_id, [
                        'noContent' => (bool) $d2u_row['noContent'],
                        'missing' => (int) $d2u_row['missing'],
                        'stale' => (int) $d2u_row['stale'],
                    ], $d2u_ai_available);
                    echo '<td><input type="checkbox" class="d2u-row-check" name="d2u_articles[]" value="'. $d2u_id .'"'. (!empty($d2u_row['hasPdfMedia']) ? ' data-has-pdf="1"' : '') .'></td>';
                    echo '<td>'. $d2u_indent . $d2u_toggle . $d2u_type_icon .'<a href="'. $d2u_edit_url .'">'. $d2u_name .'</a></td>';
                    echo '<td class="text-center"><span class="d2u-status-icon">'. $d2u_cells['icon'] .'</span></td>';
                    echo '<td class="text-center">'. $d2u_pdf_icon .'</td>';
                    echo '<td class="text-center d2u-cell-nocontent">'. $d2u_cells['nocontent'] .'</td>';
                    echo '<td class="text-center d2u-cell-missing">'. $d2u_cells['missing'] .'</td>';
                    echo '<td class="text-center d2u-cell-stale">'. $d2u_cells['stale'] .'</td>';
                } else {
                    // Structural ancestor row: name (+ collapse toggle) and online status.
                    echo '<td></td>';
                    echo '<td>'. $d2u_indent . $d2u_toggle . $d2u_type_icon .'<a href="'. $d2u_edit_url .'" class="text-muted">'. $d2u_name .'</a></td>';
                    echo '<td class="text-center"></td>';
                    echo '<td class="text-center">'. $d2u_pdf_icon .'</td>';
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
                . ($d2u_any_pdf ? '<br><i class="rex-icon fa-file-pdf-o text-warning"></i> '. rex_i18n::msg('d2u_helper_article_hint_pdf') : '')
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
    } elseif ('seo' === $d2u_translation_mode) {
        // Categories & SEO tab: translate/synchronise the page title and the yrewrite
        // SEO fields (title, description, image) and align the online status.
        if (!$invalidCsrf) {
            $d2u_seo_single = (string) filter_input(INPUT_POST, 'd2u_seo_action');
            $d2u_seo_bulk = (string) filter_input(INPUT_POST, 'd2u_seo_bulk');
            if (('' !== $d2u_seo_single || '' !== $d2u_seo_bulk) && !$csrfToken->isValid()) {
                echo rex_view::error(rex_i18n::msg('csrf_token_invalid'));
            } elseif ('' !== $d2u_seo_single) {
                [$d2u_sid, $d2u_smode] = array_pad(explode(':', $d2u_seo_single, 2), 2, '');
                $d2u_sid = (int) $d2u_sid;
                if ($d2u_sid > 0 && in_array($d2u_smode, ['translate', 'sync', 'align', 'align_status', 'align_image'], true)) {
                    $d2u_res = match ($d2u_smode) {
                        'translate' => \TobiasKrais\D2UHelper\SeoTranslator::translateArticleSeo($d2u_sid, $source_clang_id, $target_clang_id),
                        'align' => \TobiasKrais\D2UHelper\SeoTranslator::alignArticleSeo($d2u_sid, $source_clang_id, $target_clang_id),
                        'align_status' => \TobiasKrais\D2UHelper\SeoTranslator::alignStatusArticleSeo($d2u_sid, $source_clang_id, $target_clang_id),
                        'align_image' => \TobiasKrais\D2UHelper\SeoTranslator::alignImageArticleSeo($d2u_sid, $source_clang_id, $target_clang_id),
                        default => \TobiasKrais\D2UHelper\SeoTranslator::syncArticleSeo($d2u_sid, $source_clang_id, $target_clang_id),
                    };
                    echo $d2u_res['success']
                        ? rex_view::success(rex_i18n::msg('d2u_helper_seo_done', rex_escape($d2u_res['name'])))
                        : rex_view::warning($d2u_res['message']);
                }
            } elseif ('' !== $d2u_seo_bulk && in_array($d2u_seo_bulk, ['translate', 'sync', 'align'], true)) {
                $d2u_seo_ids = array_values(array_filter(array_map('intval', (array) rex_post('d2u_seo_ids', 'array', [])), static fn (int $v): bool => $v > 0));
                if (0 === count($d2u_seo_ids)) {
                    echo rex_view::info(rex_i18n::msg('d2u_helper_article_bulk_none'));
                } else {
                    $d2u_seo_ok = 0;
                    foreach ($d2u_seo_ids as $d2u_bid) {
                        $d2u_r = match ($d2u_seo_bulk) {
                            'translate' => \TobiasKrais\D2UHelper\SeoTranslator::translateArticleSeo($d2u_bid, $source_clang_id, $target_clang_id),
                            'align' => \TobiasKrais\D2UHelper\SeoTranslator::alignArticleSeo($d2u_bid, $source_clang_id, $target_clang_id),
                            default => \TobiasKrais\D2UHelper\SeoTranslator::syncArticleSeo($d2u_bid, $source_clang_id, $target_clang_id),
                        };
                        if ($d2u_r['success']) {
                            ++$d2u_seo_ok;
                        }
                    }
                    echo rex_view::success(rex_i18n::msg('d2u_helper_seo_bulk_done', $d2u_seo_ok));
                }
            }
        }

        $d2u_seo_rows = \TobiasKrais\D2UHelper\SeoTranslator::getSeoRows($source_clang_id, $target_clang_id);
        $d2u_ai_available = \TobiasKrais\D2UHelper\AiTranslationHelper::isAvailable();
        $d2u_seo_fields = \TobiasKrais\D2UHelper\SeoTranslator::seoFields();
        $d2u_seo_labels = [
            'title' => rex_i18n::msg('d2u_helper_seo_col_title'),
            'yrewrite_title' => rex_i18n::msg('d2u_helper_seo_col_yrewrite_title'),
            'yrewrite_description' => rex_i18n::msg('d2u_helper_seo_col_yrewrite_description'),
            'yrewrite_image' => rex_i18n::msg('d2u_helper_seo_col_yrewrite_image'),
        ];
        // The SEO image is not translated, so it is grouped with the online status
        // (non-translatable data) on the left; only the text fields are translatable.
        $d2u_has_image = array_key_exists('yrewrite_image', $d2u_seo_fields);
        $d2u_seo_text_fields = array_diff_key($d2u_seo_fields, ['yrewrite_image' => true]);

        if (0 === count($d2u_seo_rows)) {
            echo rex_view::info(rex_i18n::msg('d2u_helper_article_none'));
        } else {
            if (!$d2u_ai_available) {
                echo rex_view::info(rex_i18n::msg('d2u_helper_translations_ai_not_configured'));
            }
            $d2u_form_action = rex_escape(BackendHelper::getCurrentBackendPage([], ['message', 'message_type']));
            $d2u_seo_ajax_csrf = rex_csrf_token::factory('d2u_helper_seo_translate')->getValue();

            echo '<style>#d2u-seo-table thead th{position:sticky;top:0;z-index:2;background-color:#f5f5f5}@media(prefers-color-scheme:dark){#d2u-seo-table thead th{background-color:#2b2b2b}}</style>';
            echo '<form action="' . $d2u_form_action . '" method="post" id="d2u-seo-table"'
                . ' data-seo-ajax="1" data-seo-csrf="' . rex_escape($d2u_seo_ajax_csrf) . '" data-target-clang="' . $target_clang_id . '" data-seo-ai="' . ($d2u_ai_available ? '1' : '0') . '"'
                . ' data-seo-csrf-name="' . rex_escape(rex_csrf_token::PARAM) . '"'
                . ' data-msg-working="' . rex_escape(rex_i18n::msg('d2u_helper_seo_processing')) . '"'
                . ' data-msg-error="' . rex_escape(rex_i18n::msg('d2u_helper_translations_ai_error')) . '"'
                . ' data-msg-none="' . rex_escape(rex_i18n::msg('d2u_helper_article_bulk_none')) . '">';
            echo $csrfToken->getHiddenField();
            echo '<div class="panel panel-edit">';
            echo '<div id="d2u-seo-feedback" class="alert" style="display:none;margin:10px" role="status" aria-live="polite"></div>';
            echo '<div class="panel-body" style="padding-bottom:0"><div style="display:flex;align-items:center;flex-wrap:wrap;gap:6px;margin-bottom:10px">';
            echo '<strong style="margin-right:4px">' . rex_i18n::msg('d2u_helper_article_bulk_selected') . ':</strong>';
            if ($d2u_ai_available) {
                echo '<button type="submit" name="d2u_seo_bulk" value="translate" data-seo-bulk="translate" class="btn btn-xs btn-primary"><i class="rex-icon fa-language"></i> ' . rex_i18n::msg('d2u_helper_seo_action_translate') . '</button>';
            }
            echo '<button type="submit" name="d2u_seo_bulk" value="align" data-seo-bulk="align" class="btn btn-xs btn-default"><i class="rex-icon fa-random"></i> ' . rex_i18n::msg('d2u_helper_article_align_status') . '</button>';
            echo '</div></div>';
            echo '<div class="table-responsive"><table class="table table-striped table-hover" style="margin-bottom:0">';
            echo '<thead><tr>';
            echo '<th style="width:1%"><input type="checkbox" id="d2u-seo-select-all" title="' . rex_escape(rex_i18n::msg('d2u_helper_article_select_all')) . '"></th>';
            echo '<th>' . rex_i18n::msg('d2u_helper_article_col_name') . '</th>';
            echo '<th class="text-center">' . rex_i18n::msg('d2u_helper_article_col_online') . '</th>';
            if ($d2u_has_image) {
                echo '<th class="text-center">' . rex_escape($d2u_seo_labels['yrewrite_image']) . '</th>';
            }
            foreach ($d2u_seo_text_fields as $d2u_fkey => $d2u_fdef) {
                echo '<th class="text-center">' . rex_escape($d2u_seo_labels[$d2u_fkey] ?? $d2u_fkey) . '</th>';
            }
            echo '<th class="text-end">' . rex_i18n::msg('d2u_helper_article_col_actions') . '</th>';
            echo '</tr></thead><tbody>';
            foreach ($d2u_seo_rows as $d2u_row) {
                $d2u_id = (int) $d2u_row['id'];
                $d2u_cells = \TobiasKrais\D2UHelper\SeoTranslator::renderCells($d2u_row, $d2u_id);
                $d2u_indent = str_repeat('<span style="display:inline-block;width:26px"></span>', (int) $d2u_row['level']);
                $d2u_edit_url = rex_url::backendPage('content/edit', ['article_id' => $d2u_id, 'clang' => $target_clang_id, 'mode' => 'edit']);
                $d2u_name = rex_escape($d2u_row['name']);
                $d2u_path_attr = rex_escape(implode(',', $d2u_row['path']));
                $d2u_toggle = !empty($d2u_row['hasChildren'])
                    ? '<button type="button" class="btn btn-xs btn-default d2u-seo-toggle" data-collapse-id="' . $d2u_id . '" title="' . rex_escape(rex_i18n::msg('d2u_helper_article_toggle')) . '" style="width:22px;padding:0;margin-right:4px"><i class="rex-icon fa-caret-down"></i></button>'
                    : '<span style="display:inline-block;width:26px"></span>';
                if (!empty($d2u_row['isCategory'])) {
                    $d2u_type_icon = !empty($d2u_row['hasChildren'])
                        ? '<i class="rex-icon fa-folder text-muted" title="' . rex_escape(rex_i18n::msg('d2u_helper_article_type_category')) . '"></i> '
                        : '<i class="rex-icon fa-folder-o text-muted" title="' . rex_escape(rex_i18n::msg('d2u_helper_article_type_category_empty')) . '"></i> ';
                } else {
                    $d2u_type_icon = '<i class="rex-icon fa-file-o text-muted" title="' . rex_escape(rex_i18n::msg('d2u_helper_article_type_article')) . '"></i> ';
                }
                echo '<tr data-article-id="' . $d2u_id . '" data-seo-id="' . $d2u_id . '" data-path="' . $d2u_path_attr . '">';
                echo '<td><input type="checkbox" class="d2u-seo-check" name="d2u_seo_ids[]" value="' . $d2u_id . '"></td>';
                echo '<td>' . $d2u_indent . $d2u_toggle . $d2u_type_icon . '<a href="' . $d2u_edit_url . '">' . $d2u_name . '</a></td>';
                echo '<td class="text-center" data-seo-cell="online">' . $d2u_cells['online'] . '</td>';
                if ($d2u_has_image) {
                    echo '<td class="text-center" data-seo-cell="yrewrite_image">' . ($d2u_cells['yrewrite_image'] ?? '') . '</td>';
                }
                foreach ($d2u_seo_text_fields as $d2u_fkey => $d2u_fdef) {
                    echo '<td class="text-center" data-seo-cell="' . rex_escape($d2u_fkey) . '">' . ($d2u_cells[$d2u_fkey] ?? '') . '</td>';
                }
                echo '<td class="text-end text-nowrap">';
                if ($d2u_ai_available) {
                    echo '<button type="submit" name="d2u_seo_action" value="' . $d2u_id . ':translate" data-seo-id="' . $d2u_id . '" data-seo-action="translate" class="btn btn-xs btn-primary d2u-seo-ajax" title="' . rex_escape(rex_i18n::msg('d2u_helper_seo_action_translate')) . '"><i class="rex-icon fa-language"></i></button>';
                }
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div>';
            echo '<div class="panel-body"><small class="text-muted">' . rex_i18n::msg('d2u_helper_seo_hint') . '</small></div>';
            echo '</div></form>';
            // Collapse categories + select-all. Inline is fine in the REDAXO backend.
            echo '<script>(function(){'
                . 'var t=document.getElementById("d2u-seo-table");if(!t)return;'
                . 'var sa=document.getElementById("d2u-seo-select-all");'
                . 'if(sa)sa.addEventListener("change",function(){t.querySelectorAll(".d2u-seo-check").forEach(function(c){if(!c.closest("tr").hidden)c.checked=sa.checked;});});'
                . 'var col={};'
                . 'function apply(){t.querySelectorAll("tbody tr").forEach(function(tr){var own=tr.getAttribute("data-article-id");var p=(tr.getAttribute("data-path")||"").split(",").filter(Boolean);var h=p.some(function(id){return col[id]&&id!==own;});tr.hidden=h;if(h){var c=tr.querySelector(".d2u-seo-check");if(c)c.checked=false;}});}'
                . 't.querySelectorAll(".d2u-seo-toggle").forEach(function(b){b.addEventListener("click",function(){var id=b.getAttribute("data-collapse-id");col[id]=!col[id];b.querySelector("i").className="rex-icon "+(col[id]?"fa-caret-right":"fa-caret-down");apply();});});'
                . '})();</script>';
            echo '<script src="' . rex_escape(rex_url::addonAssets('d2u_helper', 'seo_translate.js')) . '"></script>';
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