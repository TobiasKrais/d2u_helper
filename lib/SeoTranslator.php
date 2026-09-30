<?php

namespace TobiasKrais\D2UHelper;

use rex;
use rex_addon;
use rex_api_exception;
use rex_article;
use rex_article_cache;
use rex_article_service;
use rex_category;
use rex_clang;
use rex_i18n;
use rex_sql;

/**
 * Translates and synchronises the SEO relevant meta fields of REDAXO categories
 * and articles between languages: the page title (name / catname) and — when the
 * yrewrite addon is installed — the yrewrite title, description and image. Powers
 * the "Redaxo Kategorien & SEO" tab of the translation helper.
 *
 * All values live as per clang columns on rex_article, so writing is a direct
 * column update plus an article cache purge (rex_article_service::editArticle
 * only handles name/template/priority, not the yrewrite_* / catname columns).
 */
class SeoTranslator
{
    /**
     * Whether the yrewrite SEO columns are available.
     */
    public static function isYrewriteAvailable(): bool
    {
        return rex_addon::get('yrewrite')->isAvailable();
    }

    /**
     * Ordered map of the translatable SEO fields and their value type.
     * The yrewrite fields are only present when yrewrite is installed.
     *
     * @return array<string,array{type:string}>
     */
    public static function seoFields(): array
    {
        $fields = ['title' => ['type' => 'text']];
        if (self::isYrewriteAvailable()) {
            $fields['yrewrite_title'] = ['type' => 'text'];
            $fields['yrewrite_description'] = ['type' => 'text'];
            $fields['yrewrite_image'] = ['type' => 'media'];
        }

        return $fields;
    }

    /**
     * Only the text fields (translatable via AI); the image is copied, not translated.
     *
     * @return list<string>
     */
    private static function textFields(): array
    {
        $keys = [];
        foreach (self::seoFields() as $key => $def) {
            if ('text' === $def['type']) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * Tree of every category and article with the SEO field values of the source
     * and the target language plus the online status, so the list can show the
     * target values and flag deviations from the source.
     *
     * @return list<array{id:int,name:string,level:int,path:list<int>,isCategory:bool,hasChildren:bool,stale:bool,sourceOnline:bool,targetOnline:bool,fields:array<string,array{type:string,source:string,target:string}>}>
     */
    public static function getSeoRows(int $sourceClang, int $targetClang): array
    {
        if ($sourceClang <= 0 || $targetClang <= 0 || $sourceClang === $targetClang) {
            return [];
        }

        // Walk the REDAXO structure iteratively so tree order and level come
        // straight from the structure: categories by catpriority, articles within
        // a category by priority — the two live in separate priority spaces, so a
        // flat "ORDER BY priority, id" would sort sibling categories by id.
        $list = [];
        self::appendSeoNodes($list, 0, $sourceClang, $targetClang);

        // A node is a collapsible category only when the next node sits one level
        // deeper — i.e. it actually has visible children in this tree.
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
     * Recursively append the SEO rows below one category in REDAXO tree order:
     * each subcategory (by catpriority) rendered as its start-article header with
     * its subtree, then the category's plain articles (by priority). At the root
     * level the plain articles come last, after the whole category tree.
     *
     * @param list<array<string, mixed>> $list
     */
    private static function appendSeoNodes(array &$list, int $parentCatId, int $sourceClang, int $targetClang): void
    {
        $categories = 0 === $parentCatId
            ? rex_category::getRootCategories(false, $sourceClang)
            : (rex_category::get($parentCatId, $sourceClang) instanceof rex_category
                ? rex_category::get($parentCatId, $sourceClang)->getChildren(false)
                : []);

        foreach ($categories as $category) {
            $catId = (int) $category->getId();
            // In REDAXO a category's id equals its start article's id.
            $seoRow = self::getSeoRow($catId, $sourceClang, $targetClang);
            if (null !== $seoRow) {
                $list[] = $seoRow;
            }
            self::appendSeoNodes($list, $catId, $sourceClang, $targetClang);
            self::appendSeoCategoryArticles($list, $catId, $catId, $sourceClang, $targetClang);
        }

        if (0 === $parentCatId) {
            self::appendSeoCategoryArticles($list, 0, 0, $sourceClang, $targetClang);
        }
    }

    /**
     * Append the plain (non-start) articles of one category, by priority.
     *
     * @param list<array<string, mixed>> $list
     */
    private static function appendSeoCategoryArticles(array &$list, int $catId, int $startArticleId, int $sourceClang, int $targetClang): void
    {
        $articles = 0 === $catId
            ? rex_article::getRootArticles(false, $sourceClang)
            : (rex_category::get($catId, $sourceClang) instanceof rex_category
                ? rex_category::get($catId, $sourceClang)->getArticles(false)
                : []);

        foreach ($articles as $article) {
            $articleId = (int) $article->getId();
            if ($articleId === $startArticleId) {
                continue;
            }
            $seoRow = self::getSeoRow($articleId, $sourceClang, $targetClang);
            if (null !== $seoRow) {
                $list[] = $seoRow;
            }
        }
    }

    /**
     * Builds the SEO status of a single article (source vs. target language),
     * used by the list and by the AJAX endpoint to refresh one row.
     *
     * @return array{id:int,name:string,level:int,path:list<int>,isCategory:bool,hasChildren:bool,stale:bool,sourceOnline:bool,targetOnline:bool,fields:array<string,array{type:string,source:string,target:string}>}|null
     */
    public static function getSeoRow(int $articleId, int $sourceClang, int $targetClang): ?array
    {
        if ($articleId <= 0 || $sourceClang <= 0 || $targetClang <= 0 || $sourceClang === $targetClang) {
            return null;
        }
        $src = rex_article::get($articleId, $sourceClang);
        if (!$src instanceof rex_article) {
            return null;
        }
        $tgt = rex_article::get($articleId, $targetClang);
        $isCategory = $src->isStartArticle();
        $ancestors = self::ancestorIds($src, $sourceClang);

        $rowFields = [];
        foreach (self::seoFields() as $key => $def) {
            $rowFields[$key] = [
                'type' => $def['type'],
                'source' => self::readField($src, $isCategory, $key),
                'target' => $tgt instanceof rex_article ? self::readField($tgt, $isCategory, $key) : '',
            ];
        }

        // A translated field naturally differs from its source, so text comparison
        // is useless. Instead the target is considered outdated when the source
        // article was changed after the target's last change (change-date based).
        $sourceUpdated = (int) $src->getValue('updatedate');
        $targetUpdated = $tgt instanceof rex_article ? (int) $tgt->getValue('updatedate') : 0;
        $stale = $tgt instanceof rex_article && $sourceUpdated > $targetUpdated;

        return [
            'id' => $articleId,
            'name' => self::displayName($src, $isCategory),
            'level' => count($ancestors),
            'path' => $ancestors,
            'isCategory' => $isCategory,
            'hasChildren' => false,
            'stale' => $stale,
            'sourceOnline' => $src->isOnline(),
            'targetOnline' => $tgt instanceof rex_article && $tgt->isOnline(),
            'fields' => $rowFields,
        ];
    }

    /**
     * Aligns only the online status of the target with the source.
     *
     * @return array{success:bool,name:string,message:string}
     */
    public static function alignStatusArticleSeo(int $articleId, int $sourceClang, int $targetClang): array
    {
        $src = rex_article::get($articleId, $sourceClang);
        $tgt = rex_article::get($articleId, $targetClang);
        $name = $src instanceof rex_article ? self::displayName($src, $src->isStartArticle()) : ('#' . $articleId);
        if (!$src instanceof rex_article || !$tgt instanceof rex_article || $articleId <= 0 || $sourceClang <= 0 || $targetClang <= 0 || $sourceClang === $targetClang) {
            return ['success' => false, 'name' => $name, 'message' => rex_i18n::msg('d2u_helper_translations_ai_invalid_request')];
        }
        if ((int) $src->getValue('status') !== (int) $tgt->getValue('status')) {
            try {
                rex_article_service::articleStatus($articleId, $targetClang, (int) $src->getValue('status'));
            } catch (rex_api_exception $e) {
                return ['success' => false, 'name' => $name, 'message' => $e->getMessage()];
            }
        }

        return ['success' => true, 'name' => $name, 'message' => ''];
    }

    /**
     * Copies only the SEO image (yrewrite_image) from the source into the target.
     *
     * @return array{success:bool,name:string,message:string}
     */
    public static function alignImageArticleSeo(int $articleId, int $sourceClang, int $targetClang): array
    {
        $src = rex_article::get($articleId, $sourceClang);
        $name = $src instanceof rex_article ? self::displayName($src, $src->isStartArticle()) : ('#' . $articleId);
        if (!$src instanceof rex_article || $articleId <= 0 || $sourceClang <= 0 || $targetClang <= 0 || $sourceClang === $targetClang || !self::isYrewriteAvailable()) {
            return ['success' => false, 'name' => $name, 'message' => rex_i18n::msg('d2u_helper_translations_ai_invalid_request')];
        }
        self::writeField($articleId, $targetClang, $src->isStartArticle(), 'yrewrite_image', self::readField($src, $src->isStartArticle(), 'yrewrite_image'));

        return ['success' => true, 'name' => $name, 'message' => ''];
    }

    /**
     * Renders the status cells of one row (online, SEO image, text fields).
     * The contextual "align" actions are only emitted when there is a
     * difference. Shared by the list and the AJAX endpoint so both stay in sync.
     *
     * @param array{stale:bool,sourceOnline:bool,targetOnline:bool,fields:array<string,array{type:string,source:string,target:string}>} $row
     * @return array<string,string> Keyed by cell: 'online', optional 'yrewrite_image', and each text field key.
     */
    public static function renderCells(array $row, int $articleId): array
    {
        $cells = [];

        // Online status + contextual "align status" action (only when it differs).
        $badge = $row['targetOnline']
            ? '<span class="label label-success">' . rex_i18n::msg('d2u_helper_article_status_online') . '</span>'
            : '<span class="label label-default">' . rex_i18n::msg('d2u_helper_article_status_offline') . '</span>';
        if ($row['sourceOnline'] !== $row['targetOnline']) {
            $badge .= ' ' . self::alignButton($articleId, 'align_status', rex_i18n::msg('d2u_helper_article_status_source', $row['sourceOnline'] ? rex_i18n::msg('d2u_helper_article_status_online') : rex_i18n::msg('d2u_helper_article_status_offline')));
        }
        $cells['online'] = $badge;

        // SEO image cell (only when yrewrite provides it): the image is copied
        // verbatim, so plain equality is meaningful here.
        if (isset($row['fields']['yrewrite_image'])) {
            $imgSrc = (string) $row['fields']['yrewrite_image']['source'];
            $imgTgt = (string) $row['fields']['yrewrite_image']['target'];
            if ('' === $imgSrc && '' === $imgTgt) {
                $cells['yrewrite_image'] = '<span class="text-muted">–</span>';
            } elseif ($imgSrc === $imgTgt) {
                $cells['yrewrite_image'] = '<span class="rex-icon fa-check text-success" title="' . rex_escape($imgSrc) . '"></span>';
            } else {
                $tip = rex_escape(($imgSrc === '' ? '—' : $imgSrc) . '  →  ' . ($imgTgt === '' ? '—' : $imgTgt));
                $cells['yrewrite_image'] = '<span class="label label-info" title="' . $tip . '">' . ('' === $imgTgt ? rex_i18n::msg('d2u_helper_seo_state_missing') : '&ne;') . '</span> '
                    . self::alignButton($articleId, 'align_image', rex_i18n::msg('d2u_helper_seo_col_yrewrite_image'));
            }
        }

        // Text fields: a translation always differs, so use change-date staleness.
        foreach (self::textFields() as $key) {
            if (!isset($row['fields'][$key])) {
                continue;
            }
            $src = (string) $row['fields'][$key]['source'];
            $tgt = (string) $row['fields'][$key]['target'];
            if ('' === $src && '' === $tgt) {
                $cells[$key] = '<span class="text-muted">–</span>';
                continue;
            }
            $tip = rex_escape(($src === '' ? '—' : $src) . '  →  ' . ($tgt === '' ? '—' : $tgt));
            if ('' === $tgt) {
                $cells[$key] = '<span class="label label-warning" title="' . $tip . '">' . rex_i18n::msg('d2u_helper_seo_state_missing') . '</span>';
            } elseif ($row['stale']) {
                $cells[$key] = '<span class="label label-info" title="' . rex_escape(rex_i18n::msg('d2u_helper_seo_state_outdated')) . '">' . rex_i18n::msg('d2u_helper_seo_state_outdated') . '</span>';
            } else {
                $cells[$key] = '<span class="rex-icon fa-check text-success" title="' . $tip . '"></span>';
            }
        }

        return $cells;
    }

    /**
     * A contextual "align" action button (submit fallback + AJAX hook).
     */
    private static function alignButton(int $articleId, string $action, string $title): string
    {
        return '<button type="submit" name="d2u_seo_action" value="' . $articleId . ':' . $action . '"'
            . ' class="btn btn-xs btn-default d2u-seo-ajax" data-seo-id="' . $articleId . '" data-seo-action="' . $action . '"'
            . ' title="' . rex_escape($title) . '"><i class="rex-icon fa-random"></i></button>';
    }

    /**
     * AI-translates the text SEO fields of one article from the source into the
     * target language and stores them. The image is not touched (use sync).
     *
     * @return array{success:bool,name:string,message:string}
     */
    public static function translateArticleSeo(int $articleId, int $sourceClang, int $targetClang): array
    {
        $src = rex_article::get($articleId, $sourceClang);
        $name = $src instanceof rex_article ? self::displayName($src, $src->isStartArticle()) : ('#' . $articleId);
        if (!$src instanceof rex_article || $articleId <= 0 || $sourceClang <= 0 || $targetClang <= 0 || $sourceClang === $targetClang) {
            return ['success' => false, 'name' => $name, 'message' => rex_i18n::msg('d2u_helper_translations_ai_invalid_request')];
        }
        if (!AiTranslationHelper::isAvailable()) {
            return ['success' => false, 'name' => $name, 'message' => rex_i18n::msg('d2u_helper_translations_ai_not_configured')];
        }

        $isCategory = $src->isStartArticle();
        $payload = [];
        foreach (self::textFields() as $key) {
            $value = self::readField($src, $isCategory, $key);
            if ('' !== $value) {
                $payload[$key] = ['value' => $value, 'html' => false];
            }
        }

        if (0 === count($payload)) {
            return ['success' => true, 'name' => $name, 'message' => rex_i18n::msg('d2u_helper_seo_nothing_to_translate')];
        }

        try {
            $translated = AiTranslationHelper::translateFields($payload, $sourceClang, $targetClang);
        } catch (\Throwable $e) {
            return ['success' => false, 'name' => $name, 'message' => rex_i18n::msg('d2u_helper_translations_ai_error')];
        }

        foreach ($translated as $key => $value) {
            self::writeField($articleId, $targetClang, $isCategory, $key, (string) $value);
        }

        return ['success' => true, 'name' => $name, 'message' => ''];
    }

    /**
     * Copies all SEO fields (including the image) from the source into the target
     * language for one article.
     *
     * @return array{success:bool,name:string,message:string}
     */
    public static function syncArticleSeo(int $articleId, int $sourceClang, int $targetClang): array
    {
        $src = rex_article::get($articleId, $sourceClang);
        $name = $src instanceof rex_article ? self::displayName($src, $src->isStartArticle()) : ('#' . $articleId);
        if (!$src instanceof rex_article || $articleId <= 0 || $sourceClang <= 0 || $targetClang <= 0 || $sourceClang === $targetClang) {
            return ['success' => false, 'name' => $name, 'message' => rex_i18n::msg('d2u_helper_translations_ai_invalid_request')];
        }

        $isCategory = $src->isStartArticle();
        foreach (array_keys(self::seoFields()) as $key) {
            self::writeField($articleId, $targetClang, $isCategory, $key, self::readField($src, $isCategory, $key));
        }

        return ['success' => true, 'name' => $name, 'message' => ''];
    }

    /**
     * Aligns the non-translatable data of one article: sets the target online
     * status to match the source and copies the SEO image (yrewrite_image) from
     * the source into the target language.
     *
     * @return array{success:bool,name:string,message:string}
     */
    public static function alignArticleSeo(int $articleId, int $sourceClang, int $targetClang): array
    {
        $src = rex_article::get($articleId, $sourceClang);
        $tgt = rex_article::get($articleId, $targetClang);
        $name = $src instanceof rex_article ? self::displayName($src, $src->isStartArticle()) : ('#' . $articleId);
        if (!$src instanceof rex_article || !$tgt instanceof rex_article || $articleId <= 0 || $sourceClang <= 0 || $targetClang <= 0 || $sourceClang === $targetClang) {
            return ['success' => false, 'name' => $name, 'message' => rex_i18n::msg('d2u_helper_translations_ai_invalid_request')];
        }

        // online status
        if ((int) $src->getValue('status') !== (int) $tgt->getValue('status')) {
            try {
                rex_article_service::articleStatus($articleId, $targetClang, (int) $src->getValue('status'));
            } catch (rex_api_exception $e) {
                return ['success' => false, 'name' => $name, 'message' => $e->getMessage()];
            }
        }

        // SEO image (only when yrewrite provides that field)
        if (self::isYrewriteAvailable()) {
            self::writeField($articleId, $targetClang, $src->isStartArticle(), 'yrewrite_image', self::readField($src, $src->isStartArticle(), 'yrewrite_image'));
        }

        return ['success' => true, 'name' => $name, 'message' => ''];
    }

    /**
     * Reads a single SEO field from an article object.
     */
    private static function readField(rex_article $article, bool $isCategory, string $field): string
    {
        if ('title' === $field) {
            return $isCategory ? (string) $article->getValue('catname') : (string) $article->getName();
        }

        return (string) $article->getValue($field);
    }

    /**
     * Writes a single SEO field to the article's clang row and purges its cache.
     */
    private static function writeField(int $articleId, int $clang, bool $isCategory, string $field, string $value): void
    {
        $column = 'title' === $field ? ($isCategory ? 'catname' : 'name') : $field;

        $sql = rex_sql::factory();
        $sql->setTable(rex::getTable('article'));
        $sql->setWhere(['id' => $articleId, 'clang_id' => $clang]);
        $sql->setValue($column, $value);
        $sql->addGlobalUpdateFields();
        $sql->update();

        rex_article_cache::delete($articleId, $clang);
    }

    /**
     * Display name: category name (catname) for category start articles, else the
     * article name.
     */
    private static function displayName(rex_article $article, bool $isCategory): string
    {
        if ($isCategory) {
            $catname = (string) $article->getValue('catname');
            if ('' !== $catname) {
                return $catname;
            }
        }

        return (string) $article->getName();
    }

    /**
     * Ancestor category ids of an article, root first, excluding the article itself.
     *
     * @return list<int>
     */
    private static function ancestorIds(rex_article $article, int $clang): array
    {
        $path = [];
        $parentId = (int) $article->getParentId();
        $guard = 0;
        while ($parentId > 0 && $guard++ < 100) {
            $path[] = $parentId;
            $parent = rex_article::get($parentId, $clang);
            if (!$parent instanceof rex_article) {
                break;
            }
            $parentId = (int) $parent->getParentId();
        }

        return array_reverse($path);
    }
}
