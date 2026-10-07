<?php

namespace TobiasKrais\D2UHelper;

use rex;
use rex_article;
use rex_article_cache;
use rex_category;
use rex_clang;
use rex_config;
use rex_i18n;
use rex_logger;
use rex_media;
use rex_sql;
use rex_url;
use rex_user;

/**
 * Translates REDAXO article slices from a source language into a target
 * language, storing real, editable slices per clang. Plugs into the d2u_helper
 * translation helper as the "Redaxo Artikel" tab.
 *
 * Status detection and the source<->target correspondence are positional: a
 * target slice belongs to a source slice when they share article, ctype and
 * priority. "missing" means the target slice does not exist yet; "update" (stale)
 * means the source slice was changed after the target slice was last written
 * (compared via updatedate). No extra tracking table is used.
 */
class SliceTranslator
{
    private const VALUE_COLUMNS = 20;

    /**
     * Rows for the "REDAXO article contents" table: every article that has slices
     * in the source language, plus all of its ancestor categories so the tree is
     * visible. Ancestor rows without own content are structural (name only).
     *
     * Each content row carries the counts the table columns show:
     * - noContent: the target language has no slice for this article at all
     * - missing:   source slices without a positional target counterpart
     * - stale:     target slices older than their source (need an update)
     *
     * @return list<array{id: int, name: string, level: int, path: list<int>, hasContent: bool, noContent: bool, missing: int, stale: int, hasChildren: bool, isCategory: bool, sourceOnline: bool, targetOnline: bool, hasPdfMedia: bool, pdfSliceId: int}>
     */
    public static function getArticleContentRows(int $sourceClang, int $targetClang): array
    {
        if ($sourceClang <= 0 || $targetClang <= 0 || $sourceClang === $targetClang) {
            return [];
        }

        $table = rex::getTable('article_slice');
        $rows = rex_sql::factory()->getArray(
            'SELECT s.article_id AS article_id,
                    COUNT(*) AS source_count,
                    SUM(CASE WHEN t.id IS NULL THEN 1 ELSE 0 END) AS missing_count,
                    SUM(CASE WHEN t.id IS NOT NULL AND s.updatedate > t.updatedate THEN 1 ELSE 0 END) AS stale_count,
                    SUM(CASE WHEN t.id IS NOT NULL THEN 1 ELSE 0 END) AS target_count
             FROM ' . $table . ' s
             LEFT JOIN ' . $table . ' t
               ON t.article_id = s.article_id
              AND t.ctype_id = s.ctype_id
              AND t.priority = s.priority
              AND t.clang_id = :target
              AND t.revision = 0
             WHERE s.clang_id = :source AND s.revision = 0
             GROUP BY s.article_id',
            [':source' => $sourceClang, ':target' => $targetClang],
        );

        // Source-language article ids whose slices reference a PDF file (media
        // columns or /media links in HTML values) — used to hint in the list that
        // a page may carry documents needing a translated version.
        $pdfCols = [];
        for ($i = 1; $i <= 10; ++$i) {
            $pdfCols[] = 'media' . $i;
            $pdfCols[] = 'medialist' . $i;
        }
        for ($i = 1; $i <= 20; ++$i) {
            $pdfCols[] = 'value' . $i;
        }
        $pdfArticleIds = [];
        $pdfRows = rex_sql::factory()->getArray(
            'SELECT article_id, id FROM ' . $table . ' WHERE clang_id = :source AND revision = 0 AND '
                . 'LOWER(CONCAT_WS(\' \', ' . implode(', ', $pdfCols) . ')) LIKE :pdf '
                . 'ORDER BY article_id, priority, id',
            [':source' => $sourceClang, ':pdf' => '%.pdf%'],
        );
        foreach ($pdfRows as $pdfRow) {
            $aid = (int) $pdfRow['article_id'];
            // First (lowest priority/id) PDF slice per article, for the jump link.
            if (!isset($pdfArticleIds[$aid])) {
                $pdfArticleIds[$aid] = (int) $pdfRow['id'];
            }
        }

        $status = [];
        foreach ($rows as $row) {
            $status[(int) $row['article_id']] = [
                'missing' => (int) $row['missing_count'],
                'stale' => (int) $row['stale_count'],
                'noContent' => 0 === (int) $row['target_count'],
            ];
        }

        if (0 === count($status)) {
            return [];
        }

        // Walk the REDAXO structure iteratively so the tree order and the level
        // per node come straight from the structure. Categories are ordered by
        // catpriority, articles within a category by priority — the two live in
        // separate priority spaces, which is why a flat mixed sort key put a
        // top-level article among the top-level categories. Every category is
        // shown (even without own content), so the tree matches the SEO tab;
        // articles are limited to those that carry translatable slices.
        $list = [];
        self::appendStructureNodes($list, 0, 0, [], $sourceClang, $targetClang, $status, $pdfArticleIds);

        // A node is a collapsible category only when the following node sits one
        // level deeper — i.e. it actually has visible children in this tree.
        $count = count($list);
        foreach ($list as $i => &$row) {
            $row['hasChildren'] = $row['isCategory']
                && $i + 1 < $count
                && (int) $list[$i + 1]['level'] > (int) $row['level'];
        }
        unset($row);

        return $list;
    }

    /**
     * Recursively append the translatable structure below one category to $list,
     * in REDAXO tree order: first the category's own subcategories (by
     * catpriority) — each rendered as a category header (its start article) with
     * its subtree — then the category's non-start articles (by priority) that
     * carry content. Every category is shown; contentless categories are
     * structural rows (name only).
     *
     * @param list<array<string, mixed>> $list
     * @param list<int> $path ancestor category ids of $parentCatId, root first
     * @param array<int, array{missing: int, stale: int, noContent: bool}> $status
     * @param array<int, int> $pdfArticleIds
     */
    private static function appendStructureNodes(array &$list, int $parentCatId, int $level, array $path, int $sourceClang, int $targetClang, array $status, array $pdfArticleIds): void
    {
        $categories = 0 === $parentCatId
            ? rex_category::getRootCategories(false, $sourceClang)
            : (rex_category::get($parentCatId, $sourceClang) instanceof rex_category
                ? rex_category::get($parentCatId, $sourceClang)->getChildren(false)
                : []);

        foreach ($categories as $category) {
            $catId = (int) $category->getId();
            // In REDAXO a category's id equals its start article's id.
            $startArticleId = $catId;

            // Category header row = the category's start article.
            $startArticle = rex_article::get($startArticleId, $sourceClang);
            if ($startArticle instanceof rex_article) {
                $list[] = self::buildNodeRow($startArticle, $startArticleId, $level, $path, $sourceClang, $targetClang, $status, $pdfArticleIds, true);
            }

            // Descend into subcategories first, then this category's articles.
            $childPath = $path;
            $childPath[] = $catId;
            self::appendStructureNodes($list, $catId, $level + 1, $childPath, $sourceClang, $targetClang, $status, $pdfArticleIds);
            self::appendCategoryArticles($list, $catId, $startArticleId, $level + 1, $childPath, $sourceClang, $targetClang, $status, $pdfArticleIds);
        }

        // Root-level (parentCatId 0) non-start articles that carry content.
        if (0 === $parentCatId) {
            self::appendCategoryArticles($list, 0, 0, 0, [], $sourceClang, $targetClang, $status, $pdfArticleIds);
        }
    }

    /**
     * Append the non-start articles of one category (by priority) that carry
     * content, as article rows.
     *
     * @param list<array<string, mixed>> $list
     * @param list<int> $path ancestor category ids of the article, root first
     * @param array<int, array{missing: int, stale: int, noContent: bool}> $status
     * @param array<int, int> $pdfArticleIds
     */
    private static function appendCategoryArticles(array &$list, int $catId, int $startArticleId, int $level, array $path, int $sourceClang, int $targetClang, array $status, array $pdfArticleIds): void
    {
        $articles = 0 === $catId
            ? rex_article::getRootArticles(false, $sourceClang)
            : (rex_category::get($catId, $sourceClang) instanceof rex_category
                ? rex_category::get($catId, $sourceClang)->getArticles(false)
                : []);

        foreach ($articles as $article) {
            $articleId = (int) $article->getId();
            // The start article is already rendered as the category header.
            if ($articleId === $startArticleId) {
                continue;
            }
            if (!isset($status[$articleId])) {
                continue;
            }
            $list[] = self::buildNodeRow($article, $articleId, $level, $path, $sourceClang, $targetClang, $status, $pdfArticleIds, false);
        }
    }

    /**
     * Build a single tree row for the "REDAXO article contents" table.
     *
     * @param list<int> $path ancestor category ids, root first
     * @param array<int, array{missing: int, stale: int, noContent: bool}> $status
     * @param array<int, int> $pdfArticleIds
     * @return array<string, mixed>
     */
    private static function buildNodeRow(rex_article $article, int $id, int $level, array $path, int $sourceClang, int $targetClang, array $status, array $pdfArticleIds, bool $isCategory): array
    {
        $targetArticle = rex_article::get($id, $targetClang);

        return [
            'id' => $id,
            // Category start articles show the category name (catname) instead of
            // the article name, matching the REDAXO structure tree.
            'name' => self::displayName($article, $id),
            'level' => $level,
            'path' => $path,
            'isCategory' => $isCategory,
            // Online status of the source and the target language version, so the
            // list can show the target status and flag a deviation from the source.
            'sourceOnline' => $article->isOnline(),
            'targetOnline' => $targetArticle instanceof rex_article && $targetArticle->isOnline(),
            'hasContent' => isset($status[$id]),
            'noContent' => $status[$id]['noContent'] ?? false,
            'missing' => $status[$id]['missing'] ?? 0,
            'stale' => $status[$id]['stale'] ?? 0,
            'hasChildren' => $isCategory,
            'hasPdfMedia' => isset($pdfArticleIds[$id]),
            'pdfSliceId' => $pdfArticleIds[$id] ?? 0,
        ];
    }

    /**
     * Slice status of a single article: whether the target has no slices yet,
     * how many source slices are missing in the target and how many are stale.
     *
     * @return array{noContent: bool, missing: int, stale: int}
     */
    public static function getArticleContentStatus(int $articleId, int $sourceClang, int $targetClang): array
    {
        $default = ['noContent' => false, 'missing' => 0, 'stale' => 0];
        if ($articleId <= 0 || $sourceClang <= 0 || $targetClang <= 0 || $sourceClang === $targetClang) {
            return $default;
        }

        $table = rex::getTable('article_slice');
        $rows = rex_sql::factory()->getArray(
            'SELECT COUNT(*) AS source_count,
                    SUM(CASE WHEN t.id IS NULL THEN 1 ELSE 0 END) AS missing_count,
                    SUM(CASE WHEN t.id IS NOT NULL AND s.updatedate > t.updatedate THEN 1 ELSE 0 END) AS stale_count,
                    SUM(CASE WHEN t.id IS NOT NULL THEN 1 ELSE 0 END) AS target_count
             FROM ' . $table . ' s
             LEFT JOIN ' . $table . ' t
               ON t.article_id = s.article_id
              AND t.ctype_id = s.ctype_id
              AND t.priority = s.priority
              AND t.clang_id = :target
              AND t.revision = 0
             WHERE s.article_id = :id AND s.clang_id = :source AND s.revision = 0',
            [':id' => $articleId, ':source' => $sourceClang, ':target' => $targetClang],
        );

        if (0 === count($rows) || 0 === (int) $rows[0]['source_count']) {
            return $default;
        }

        return [
            'noContent' => 0 === (int) $rows[0]['target_count'],
            'missing' => (int) $rows[0]['missing_count'],
            'stale' => (int) $rows[0]['stale_count'],
        ];
    }

    /**
     * Renders the four status pieces of an article row (status icon in the name
     * cell plus the three action cells) so the page and the AJAX endpoint stay
     * in sync. Buttons carry both the form name/value and a data attribute, so
     * they work as a plain submit and via the in-page AJAX handler.
     *
     * @param array{noContent: bool, missing: int, stale: int} $status
     * @return array{icon: string, nocontent: string, missing: string, stale: string}
     */
    public static function renderArticleStatusCells(int $articleId, array $status, bool $aiAvailable): array
    {
        $btn = static function (string $mode, string $icon, string $label, string $style) use ($articleId): string {
            $val = $articleId . ':' . $mode;
            return '<button type="submit" name="d2u_action" value="' . $val . '" data-d2u-article-action="' . $val . '" class="btn ' . $style . ' btn-xs" title="' . rex_escape($label) . '" aria-label="' . rex_escape($label) . '"><i class="rex-icon ' . $icon . '"></i></button>';
        };
        $cell = static function (string $badge, string $button): string {
            return '<div style="display:flex;align-items:center;justify-content:center;gap:8px">' . $badge . $button . '</div>';
        };
        $none = '<span class="text-muted">–</span>';

        $noContent = (bool) $status['noContent'];
        $missing = (int) $status['missing'];
        $stale = (int) $status['stale'];
        $done = !$noContent && 0 === $missing && 0 === $stale;

        $icon = $done
            ? '<i class="rex-icon fa-check text-success" title="' . rex_escape(rex_i18n::msg('d2u_helper_article_state_uptodate')) . '"></i> '
            : '<span class="label label-info" title="' . rex_escape(rex_i18n::msg('d2u_helper_article_state_todo')) . '">&ne;</span> ';

        return [
            'icon' => $icon,
            'nocontent' => $noContent
                ? $cell('<span class="label label-danger">' . rex_i18n::msg('d2u_helper_article_state_nocontent') . '</span>', $aiAvailable ? $btn('all', 'fa-language', rex_i18n::msg('d2u_helper_article_action_all') . ' – ' . rex_i18n::msg('d2u_helper_article_hint_nocontent'), 'btn-primary') : '')
                : $none,
            'missing' => $missing > 0
                ? $cell('<span class="label label-warning">' . $missing . '</span>', $aiAvailable ? $btn('missing', 'fa-plus', rex_i18n::msg('d2u_helper_article_action_missing') . ' – ' . rex_i18n::msg('d2u_helper_article_hint_missing'), 'btn-default') : '')
                : $none,
            'stale' => $stale > 0
                ? $cell('<span class="label label-info">' . $stale . '</span>', $aiAvailable ? $btn('stale', 'fa-refresh', rex_i18n::msg('d2u_helper_article_action_update') . ' – ' . rex_i18n::msg('d2u_helper_article_hint_update'), 'btn-default') : '')
                : $none,
        ];
    }

    /**
     * Display name for an article: the category name (catname) for category start
     * articles, otherwise the article name; a "#<id>" fallback when missing.
     */
    private static function displayName(?rex_article $article, int $articleId): string
    {
        if (!$article instanceof rex_article) {
            return '#' . $articleId;
        }
        if ($article->isStartArticle() && '' !== (string) $article->getValue('catname')) {
            return (string) $article->getValue('catname');
        }

        return (string) $article->getName();
    }

    /**
     * Translate the slices of one article from source to target language.
     *
     * Mode selects which source slices are processed:
     * - 'all':     every source slice (existing target slices are overwritten)
     * - 'missing': only source slices without a target counterpart yet
     * - 'stale':   only target slices older than their source (re-translated)
     *
     * @return array{success: bool, name: string, message: string}
     */
    public static function translateArticle(int $articleId, int $sourceClang, int $targetClang, string $mode = 'all'): array
    {
        $article = rex_article::get($articleId, $sourceClang);
        $name = self::displayName($article, $articleId);

        if ($articleId <= 0 || $sourceClang <= 0 || $targetClang <= 0 || $sourceClang === $targetClang) {
            return ['success' => false, 'name' => $name, 'message' => rex_i18n::msg('d2u_helper_slice_translation_invalid')];
        }
        if (!AiTranslationHelper::isAvailable()) {
            return ['success' => false, 'name' => $name, 'message' => rex_i18n::msg('d2u_helper_translations_ai_not_configured')];
        }

        $table = rex::getTable('article_slice');
        $srcSlices = rex_sql::factory()->getArray(
            'SELECT * FROM ' . $table . ' WHERE article_id = :a AND clang_id = :c AND revision = 0 ORDER BY ctype_id, priority',
            [':a' => $articleId, ':c' => $sourceClang],
        );

        if (0 === count($srcSlices)) {
            return ['success' => false, 'name' => $name, 'message' => rex_i18n::msg('d2u_helper_slice_translation_no_source')];
        }

        // "Komplett kopieren und uebersetzen": Zielsprache komplett neu aufbauen —
        // alle vorhandenen Ziel-Slices zuerst loeschen, danach jeden Quell-Slice frisch
        // kopieren und uebersetzen. So bleiben keine Reste zurueck und es entstehen keine
        // Prioritaets-Kollisionen aus einem frueheren Uebersetzungsstand.
        if ('all' === $mode) {
            rex_sql::factory()->setQuery(
                'DELETE FROM ' . $table . ' WHERE article_id = :a AND clang_id = :c AND revision = 0',
                [':a' => $articleId, ':c' => $targetClang],
            );
        }

        try {
            foreach ($srcSlices as $src) {
                self::translateSlice($src, $sourceClang, $targetClang, $mode);
            }
        } catch (\Throwable $e) {
            rex_logger::logException($e);
            return ['success' => false, 'name' => $name, 'message' => rex_i18n::msg('d2u_helper_translations_ai_error')];
        }

        rex_article_cache::delete($articleId);

        return ['success' => true, 'name' => $name, 'message' => ''];
    }

    /**
     * Rebuild the target slice from its source counterpart, translating the
     * translatable value fields and copying everything else unchanged.
     *
     * @param array<string, mixed> $src Source slice row
     */
    private static function translateSlice(array $src, int $sourceClang, int $targetClang, string $mode = 'all'): void
    {
        $table = rex::getTable('article_slice');

        // Media columns are fetched from the target too so a manually set target
        // PDF (media/medialist referencing a .pdf) is preserved on update instead
        // of being overwritten by the source — everything else is overwritten.
        $mediaCols = [];
        for ($i = 1; $i <= 10; ++$i) {
            $mediaCols[] = 'media' . $i;
            $mediaCols[] = 'medialist' . $i;
        }
        $existing = rex_sql::factory()->getArray(
            'SELECT id, updatedate, ' . implode(', ', $mediaCols) . ' FROM ' . $table . ' WHERE article_id = :a AND clang_id = :c AND ctype_id = :ct AND priority = :p AND revision = 0 LIMIT 1',
            [
                ':a' => (int) $src['article_id'],
                ':c' => $targetClang,
                ':ct' => (int) $src['ctype_id'],
                ':p' => (int) $src['priority'],
            ],
        );
        $hasTarget = count($existing) > 0;

        // Skip work (and the AI call) for slices the selected mode does not touch:
        // 'missing' only creates new target slices; 'stale' only refreshes target
        // slices whose source has changed since the last translation.
        if ('missing' === $mode && $hasTarget) {
            return;
        }
        if ('stale' === $mode) {
            if (!$hasTarget) {
                return;
            }
            $srcUpdated = (string) ($src['updatedate'] ?? '');
            $tgtUpdated = (string) ($existing[0]['updatedate'] ?? '');
            if ('' === $srcUpdated || $srcUpdated <= $tgtUpdated) {
                return;
            }
        }

        // Prefer the module's d2u_translate marker so only the declared text fields are
        // translated and configuration values (toggles, positions, colours like
        // "true"/"right"/"grey") are copied verbatim instead of being sent to the AI.
        // A present marker is authoritative even when it lists no field: the module then
        // declares "nothing translatable here", which switches OFF the heuristic
        // auto-detection (otherwise an anchor name or a connection key would be
        // translated). Only modules without any marker fall back to auto-detection.
        $moduleId = (int) ($src['module_id'] ?? 0);
        $specs = self::getModuleMarkerFields($moduleId);
        $translated = [];
        if (self::moduleHasTranslateMarker($moduleId)) {
            [$batch, $plan] = self::buildMarkerBatch($src, $specs);
            $translatedBatch = [];
            if (count($batch) > 0) {
                try {
                    $translatedBatch = AiTranslationHelper::translateFields($batch, $sourceClang, $targetClang);
                } catch (\Throwable $e) {
                    rex_logger::logException($e);
                }
            }
            $translated = self::rebuildMarkerWrites($plan, $translatedBatch);
        } else {
            $fields = [];
            for ($i = 1; $i <= self::VALUE_COLUMNS; ++$i) {
                $value = (string) ($src['value' . $i] ?? '');
                $isHtml = self::classifyValue($value);
                if (null !== $isHtml) {
                    $fields['value' . $i] = ['value' => $value, 'html' => $isHtml];
                }
            }
            if (count($fields) > 0) {
                try {
                    $translated = AiTranslationHelper::translateFields($fields, $sourceClang, $targetClang);
                } catch (\Throwable $e) {
                    // AI-Fehler bei diesem Slice (z. B. Timeout/Rate-Limit): Slice wird trotzdem
                    // (unuebersetzt) kopiert, damit der Durchlauf nicht nach dem ersten Fehler
                    // abbricht und die uebrigen Slices erhalten bleiben.
                    rex_logger::logException($e);
                }
            }
        }

        $login = rex::getUser() instanceof rex_user ? rex::getUser()->getLogin() : 'd2u_helper';

        $sql = rex_sql::factory();
        $sql->setTable($table);

        // Copy all columns from the source except identity and audit fields.
        // Translatable value columns are overwritten with their translation, and
        // media references are remapped to the target language where a target-
        // language file exists (see remapMediaFilename, language-suffix convention).
        foreach ($src as $col => $value) {
            if (in_array($col, ['id', 'clang_id', 'createdate', 'createuser', 'updatedate', 'updateuser'], true)) {
                continue;
            }
            $value = (string) $value;
            if (isset($translated[$col])) {
                $value = (string) $translated[$col];
            }
            if (1 === preg_match('/^media\d+$/', $col)) {
                // Preserve a manually set target PDF; otherwise remap the source.
                if ($hasTarget && false !== stripos((string) ($existing[0][$col] ?? ''), '.pdf')) {
                    $value = (string) $existing[0][$col];
                } elseif ('' !== trim($value)) {
                    $value = self::remapMediaFilename(trim($value), $sourceClang, $targetClang);
                }
            } elseif (1 === preg_match('/^medialist\d+$/', $col)) {
                if ($hasTarget && false !== stripos((string) ($existing[0][$col] ?? ''), '.pdf')) {
                    $value = (string) $existing[0][$col];
                } else {
                    $value = self::remapMediaList($value, $sourceClang, $targetClang);
                }
            } elseif (1 === preg_match('/^value\d+$/', $col) && '' !== $value) {
                $value = self::remapMediaUrlsInHtml($value, $sourceClang, $targetClang);
            }
            $sql->setValue($col, $value);
        }

        $sql->setValue('clang_id', $targetClang);
        $sql->setRawValue('updatedate', 'NOW()');
        $sql->setValue('updateuser', $login);

        if (count($existing) > 0) {
            $sql->setWhere(['id' => (int) $existing[0]['id']]);
            $sql->update();
        } else {
            $sql->setRawValue('createdate', 'NOW()');
            $sql->setValue('createuser', $login);
            $sql->insert();
        }
    }

    /**
     * Whether an article's source-language slices reference any PDF file (media
     * columns or /media links in HTML values). Used by the bulk translate to
     * optionally skip pages that carry documents.
     *
     * @api
     */
    public static function articleHasPdfMedia(int $articleId, int $sourceClang): bool
    {
        if ($articleId <= 0 || $sourceClang <= 0) {
            return false;
        }
        $cols = [];
        for ($i = 1; $i <= 10; ++$i) {
            $cols[] = 'media' . $i;
            $cols[] = 'medialist' . $i;
        }
        for ($i = 1; $i <= 20; ++$i) {
            $cols[] = 'value' . $i;
        }
        $rows = rex_sql::factory()->getArray(
            'SELECT 1 FROM ' . rex::getTable('article_slice') . ' WHERE article_id = :a AND clang_id = :c AND revision = 0 AND '
                . 'LOWER(CONCAT_WS(\' \', ' . implode(', ', $cols) . ')) LIKE :pdf LIMIT 1',
            [':a' => $articleId, ':c' => $sourceClang, ':pdf' => '%.pdf%'],
        );

        return count($rows) > 0;
    }

    /**
     * Remaps a comma-separated media list (medialist columns) to the target
     * language, entry by entry.
     */
    private static function remapMediaList(string $value, int $sourceClang, int $targetClang): string
    {
        if ('' === trim($value)) {
            return $value;
        }
        $parts = array_map(static function (string $f) use ($sourceClang, $targetClang): string {
            $f = trim($f);
            return '' !== $f ? self::remapMediaFilename($f, $sourceClang, $targetClang) : $f;
        }, explode(',', $value));

        return implode(',', $parts);
    }

    /**
     * Remaps media URLs inside an HTML value ("/media/<filename>") to their
     * target-language equivalent.
     */
    private static function remapMediaUrlsInHtml(string $html, int $sourceClang, int $targetClang): string
    {
        return (string) preg_replace_callback('#/media/([^\s"\'<>)]+)#', static function (array $m) use ($sourceClang, $targetClang): string {
            return '/media/' . self::remapMediaFilename($m[1], $sourceClang, $targetClang);
        }, $html);
    }

    /**
     * Remaps a single media filename to its target-language equivalent using the
     * language-suffix convention ("flyer_en.pdf" -> "flyer_de.pdf", suffix = the
     * clang short code). The remap is only applied when the resulting file
     * actually exists in the media pool; otherwise the original filename is kept.
     *
     * @api
     */
    public static function remapMediaFilename(string $filename, int $sourceClang, int $targetClang): string
    {
        $filename = trim($filename);
        if ('' === $filename) {
            return $filename;
        }

        $candidate = self::mediaSuffixCandidate($filename, $sourceClang, $targetClang);
        if ('' !== $candidate && rex_media::get($candidate) instanceof rex_media) {
            return $candidate;
        }

        return $filename;
    }

    /**
     * Builds the language-suffix candidate for a media filename, e.g.
     * "flyer_en.pdf" -> "flyer_de.pdf". Returns '' when the filename does not
     * carry the source-language suffix or the languages cannot be resolved.
     */
    private static function mediaSuffixCandidate(string $filename, int $sourceClang, int $targetClang): string
    {
        $srcCode = self::clangShortCode($sourceClang);
        $tgtCode = self::clangShortCode($targetClang);
        if ('' === $srcCode || '' === $tgtCode || $srcCode === $tgtCode) {
            return '';
        }

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = '' !== $ext ? substr($filename, 0, -(strlen($ext) + 1)) : $filename;

        if (1 !== preg_match('/_' . preg_quote($srcCode, '/') . '$/i', $name)) {
            return '';
        }
        $newName = substr($name, 0, -(strlen($srcCode) + 1)) . '_' . $tgtCode;

        return $newName . ('' !== $ext ? '.' . $ext : '');
    }

    /**
     * Two-letter lowercase short code of a clang (e.g. "en_gb" -> "en").
     */
    private static function clangShortCode(int $clangId): string
    {
        $clang = rex_clang::get($clangId);
        if (!$clang instanceof rex_clang) {
            return '';
        }

        return strtolower(substr($clang->getCode(), 0, 2));
    }

    /**
     * Whether a module declares a `d2u_translate` marker at all, regardless of
     * whether it lists any field. A present-but-empty marker (`/* d2u_translate: *&#47;`)
     * is a deliberate "nothing to translate" declaration that must switch off the
     * heuristic auto-detection in the article translation.
     */
    public static function moduleHasTranslateMarker(int $moduleId): bool
    {
        static $cache = [];
        if (array_key_exists($moduleId, $cache)) {
            return $cache[$moduleId];
        }

        $has = false;
        if ($moduleId > 0) {
            $sql = rex_sql::factory();
            $sql->setQuery('SELECT output, input FROM ' . rex::getTable('module') . ' WHERE id = ?', [$moduleId]);
            if ($sql->getRows() > 0) {
                $source = (string) $sql->getValue('output') . "\n" . (string) $sql->getValue('input');
                $has = 1 === preg_match('#/\*\s*d2u_translate\s*:#i', $source);
            }
        }

        $cache[$moduleId] = $has;
        return $has;
    }

    /**
     * Field-to-handler map a module declares via a
     * `/* d2u_translate: 1, 2:html, 5:json(q,a) *&#47;` marker in its output or
     * input source. The marker is read from the module source, so it runs nothing.
     *
     * Handlers: `auto` (bare number, HTML auto-detected), `text`, `html`, and
     * `json` (translate only the listed object keys' string values, keep the
     * structure and re-encode — including base64-wrapped JSON such as the FAQ
     * field).
     *
     * @return array<int, array{handler: string, keys: list<string>}>
     */
    public static function getModuleMarkerFields(int $moduleId): array
    {
        static $cache = [];
        if (array_key_exists($moduleId, $cache)) {
            return $cache[$moduleId];
        }

        $map = [];
        if ($moduleId > 0) {
            $sql = rex_sql::factory();
            $sql->setQuery('SELECT output, input FROM ' . rex::getTable('module') . ' WHERE id = ?', [$moduleId]);
            if ($sql->getRows() > 0) {
                $source = (string) $sql->getValue('output') . "\n" . (string) $sql->getValue('input');
                if (1 === preg_match('#/\*\s*d2u_translate\s*:\s*(.+?)\*/#is', $source, $m)) {
                    preg_match_all('/(\d+)(?:\s*:\s*(text|html|json)\s*(?:\(([^)]*)\))?)?/i', $m[1], $tokens, PREG_SET_ORDER);
                    foreach ($tokens as $token) {
                        $number = (int) $token[1];
                        if ($number < 1 || $number > self::VALUE_COLUMNS || isset($map[$number])) {
                            continue;
                        }
                        $handler = strtolower($token[2] ?? '');
                        if ('' === $handler) {
                            $handler = 'auto';
                        }
                        $keys = [];
                        if ('json' === $handler && isset($token[3]) && '' !== trim($token[3])) {
                            foreach (preg_split('/[,\s]+/', trim($token[3])) ?: [] as $key) {
                                $key = trim($key);
                                if ('' !== $key) {
                                    $keys[] = $key;
                                }
                            }
                        }
                        $map[$number] = ['handler' => $handler, 'keys' => $keys];
                    }
                }
            }
        }

        ksort($map);
        $cache[$moduleId] = $map;

        return $map;
    }

    /**
     * Slice ids of the given article/clang whose module declares translatable
     * fields — the slices that get a translate button in the editor.
     *
     * @return list<int>
     */
    public static function getTranslatableSliceIds(int $articleId, int $clangId): array
    {
        if ($articleId <= 0 || $clangId <= 0) {
            return [];
        }

        $rows = rex_sql::factory()->getArray(
            'SELECT id, module_id FROM ' . rex::getTable('article_slice') . ' WHERE article_id = :a AND clang_id = :c AND revision = 0',
            [':a' => $articleId, ':c' => $clangId],
        );

        $ids = [];
        foreach ($rows as $row) {
            if (count(self::getModuleMarkerFields((int) $row['module_id'])) > 0) {
                $ids[] = (int) $row['id'];
            }
        }

        return $ids;
    }

    /**
     * Translate a single slice (identified by its own id in the target language)
     * using the field list its module declares. The source text is taken from the
     * positionally matching slice in the base language.
     *
     * @return array{success: bool, message: string}
     */
    public static function translateSliceById(int $sliceId, int $targetClang): array
    {
        if ($sliceId <= 0 || $targetClang <= 0) {
            return ['success' => false, 'message' => rex_i18n::msg('d2u_helper_slice_translation_invalid')];
        }
        if (!AiTranslationHelper::isAvailable()) {
            return ['success' => false, 'message' => rex_i18n::msg('d2u_helper_translations_ai_not_configured')];
        }

        $table = rex::getTable('article_slice');
        $targetRows = rex_sql::factory()->getArray('SELECT * FROM ' . $table . ' WHERE id = :id AND revision = 0 LIMIT 1', [':id' => $sliceId]);
        if (0 === count($targetRows)) {
            return ['success' => false, 'message' => rex_i18n::msg('d2u_helper_slice_translation_invalid')];
        }
        $target = $targetRows[0];
        if ((int) $target['clang_id'] !== $targetClang) {
            return ['success' => false, 'message' => rex_i18n::msg('d2u_helper_slice_translation_invalid')];
        }

        $sourceClang = (int) rex_config::get('d2u_helper', 'default_lang', rex_clang::getStartId());
        if ($sourceClang <= 0 || $sourceClang === $targetClang) {
            return ['success' => false, 'message' => rex_i18n::msg('d2u_helper_slice_translation_invalid')];
        }

        $specs = self::getModuleMarkerFields((int) $target['module_id']);
        if (0 === count($specs)) {
            return ['success' => false, 'message' => rex_i18n::msg('d2u_helper_slice_translation_no_fields')];
        }

        $sourceRows = rex_sql::factory()->getArray(
            'SELECT * FROM ' . $table . ' WHERE article_id = :a AND clang_id = :c AND ctype_id = :ct AND priority = :p AND revision = 0 LIMIT 1',
            [':a' => (int) $target['article_id'], ':c' => $sourceClang, ':ct' => (int) $target['ctype_id'], ':p' => (int) $target['priority']],
        );
        if (0 === count($sourceRows)) {
            return ['success' => false, 'message' => rex_i18n::msg('d2u_helper_slice_translation_no_source')];
        }
        $source = $sourceRows[0];

        // Build one batched payload for all declared fields plus a plan to rebuild
        // each value column from the response — so structured (JSON/FAQ) fields keep
        // their keys and structure and everything goes out in a single request.
        [$batch, $plan] = self::buildMarkerBatch($source, $specs);

        if (0 === count($batch)) {
            return ['success' => true, 'message' => ''];
        }

        try {
            $translated = AiTranslationHelper::translateFields($batch, $sourceClang, $targetClang);
        } catch (\Throwable $e) {
            rex_logger::logException($e);
            return ['success' => false, 'message' => rex_i18n::msg('d2u_helper_translations_ai_error')];
        }

        $writes = self::rebuildMarkerWrites($plan, $translated);

        if (0 === count($writes)) {
            return ['success' => true, 'message' => ''];
        }

        $login = rex::getUser() instanceof rex_user ? rex::getUser()->getLogin() : 'd2u_helper';
        $sql = rex_sql::factory();
        $sql->setTable($table);
        $sql->setWhere(['id' => $sliceId]);
        foreach ($writes as $col => $value) {
            $sql->setValue($col, $value);
        }
        $sql->setRawValue('updatedate', 'NOW()');
        $sql->setValue('updateuser', $login);
        $sql->update();

        rex_article_cache::delete((int) $target['article_id']);

        return ['success' => true, 'message' => ''];
    }

    /**
     * Build the batched AI payload and a rebuild plan from a slice's source row and
     * the module's declared d2u_translate field specs. Plain fields become one batch
     * entry; JSON/FAQ fields contribute one entry per translatable string so their
     * structure and (base64) encoding survive. Shared by the in-editor button and the
     * article translation so both treat markers identically.
     *
     * @param array<string, mixed> $source Source slice row
     * @param array<int, array{handler: string, keys: list<string>}> $specs Marker specs keyed by value number
     * @return array{0: array<string, array{value: string, html: bool}>, 1: list<array<string, mixed>>} [batch, plan]
     */
    private static function buildMarkerBatch(array $source, array $specs): array
    {
        $batch = [];
        $plan = [];
        foreach ($specs as $number => $spec) {
            $raw = (string) ($source['value' . $number] ?? '');
            if ('' === trim($raw)) {
                continue;
            }

            if ('json' === $spec['handler']) {
                [$decoded, $wasBase64] = self::decodeJsonValue($raw);
                if (null === $decoded) {
                    continue;
                }
                $strings = self::collectStrings($decoded, $spec['keys']);
                $batchKeys = [];
                foreach ($strings as $idx => $str) {
                    $key = 'f' . $number . '_' . $idx;
                    $batch[$key] = ['value' => $str, 'html' => 1 === preg_match('/<[a-z!\/][^>]*>/i', $str)];
                    $batchKeys[] = $key;
                }
                $plan[] = ['column' => 'value' . $number, 'type' => 'json', 'decoded' => $decoded, 'keys' => $spec['keys'], 'batch' => $batchKeys, 'base64' => $wasBase64];
            } else {
                $key = 'f' . $number;
                $html = 'html' === $spec['handler'] || ('auto' === $spec['handler'] && 1 === preg_match('/<[a-z!\/][^>]*>/i', $raw));
                $batch[$key] = ['value' => $raw, 'html' => $html];
                $plan[] = ['column' => 'value' . $number, 'type' => 'plain', 'batch' => [$key]];
            }
        }

        return [$batch, $plan];
    }

    /**
     * Rebuild the translated value columns from a plan (see buildMarkerBatch) and the
     * model's translation map. JSON fields are reassembled (and re-encoded/base64) so
     * only the declared keys change; plain fields take their single translated value.
     *
     * @param list<array<string, mixed>> $plan Rebuild plan
     * @param array<string, string> $translated Translation map keyed by batch key
     * @return array<string, string> Map of value column => translated value
     */
    private static function rebuildMarkerWrites(array $plan, array $translated): array
    {
        $writes = [];
        foreach ($plan as $item) {
            if ('json' === $item['type']) {
                $ordered = [];
                foreach ($item['batch'] as $key) {
                    $ordered[] = $translated[$key] ?? '';
                }
                $index = 0;
                $rebuilt = self::applyStrings($item['decoded'], $item['keys'], $ordered, $index);
                $encoded = (string) json_encode($rebuilt, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $writes[$item['column']] = $item['base64'] ? base64_encode($encoded) : $encoded;
            } else {
                $key = $item['batch'][0];
                if (array_key_exists($key, $translated)) {
                    $writes[$item['column']] = $translated[$key];
                }
            }
        }

        return $writes;
    }

    /**
     * Decode a slice value that holds JSON, tolerating base64 wrapping (as the
     * FAQ field uses).
     *
     * @return array{0: array<int|string, mixed>|null, 1: bool} Decoded array (or null) and whether it was base64
     */
    private static function decodeJsonValue(string $raw): array
    {
        $trim = trim($raw);

        $decoded = base64_decode($trim, true);
        if (false !== $decoded && '' !== $decoded) {
            $json = json_decode($decoded, true);
            if (is_array($json)) {
                return [$json, true];
            }
        }

        $json = json_decode($trim, true);
        if (is_array($json)) {
            return [$json, false];
        }

        return [null, false];
    }

    /**
     * Collect, in a deterministic order, the string values under the given object
     * keys (all string leaves when no keys are given). Structure and non-matching
     * keys are ignored.
     *
     * @param mixed $node
     * @param list<string> $keys
     * @return list<string>
     */
    private static function collectStrings(mixed $node, array $keys): array
    {
        $out = [];
        if (is_array($node)) {
            $isList = array_is_list($node);
            foreach ($node as $key => $value) {
                if (!$isList && is_string($value) && ([] === $keys || in_array((string) $key, $keys, true))) {
                    if ('' !== trim($value)) {
                        $out[] = $value;
                    }
                } elseif (is_array($value)) {
                    foreach (self::collectStrings($value, $keys) as $collected) {
                        $out[] = $collected;
                    }
                }
            }
        }

        return $out;
    }

    /**
     * Apply translated strings back onto the structure in the same order
     * {@see collectStrings()} produced them.
     *
     * @param mixed $node
     * @param list<string> $keys
     * @param list<string> $translated
     * @return mixed
     */
    private static function applyStrings(mixed $node, array $keys, array $translated, int &$index): mixed
    {
        if (is_array($node)) {
            $isList = array_is_list($node);
            foreach ($node as $key => $value) {
                if (!$isList && is_string($value) && ([] === $keys || in_array((string) $key, $keys, true))) {
                    if ('' !== trim($value)) {
                        $node[$key] = $translated[$index] ?? $value;
                        ++$index;
                    }
                } elseif (is_array($value)) {
                    $node[$key] = self::applyStrings($value, $keys, $translated, $index);
                }
            }
        }

        return $node;
    }

    /**
     * Decide whether a slice value should be translated.
     *
     * @return bool|null null = skip, true = translate as HTML, false = plain text
     */
    private static function classifyValue(string $value): ?bool
    {
        $trim = trim($value);
        if ('' === $trim) {
            return null;
        }
        if (is_numeric($trim)) {
            return null;
        }
        if (str_starts_with($trim, 'rex://')) {
            return null;
        }
        // MBlock / MForm and other JSON structures — skip to avoid corrupting them.
        if ((str_starts_with($trim, '{') || str_starts_with($trim, '[')) && null !== json_decode($trim, true)) {
            return null;
        }
        // A single media / file reference.
        if (1 === preg_match('/^[^\s<>]+\.(jpe?g|png|gif|webp|svg|bmp|ico|pdf|zip|rar|docx?|xlsx?|pptx?|mp4|mp3|avi|mov)$/i', $trim)) {
            return null;
        }
        // Nothing translatable without at least one letter.
        if (1 !== preg_match('/\p{L}/u', $trim)) {
            return null;
        }

        return 1 === preg_match('/<[a-z!\/][^>]*>/i', $trim);
    }
}
