<?php

use TobiasKrais\D2UHelper\AiTranslationHelper;
use TobiasKrais\D2UHelper\SliceTranslator;

/**
 * Backend endpoint for the in-page (AJAX) article translation on the
 * "Redaxo Artikel Inhalte" tab of the translation helper.
 *
 * POST (CSRF): translates one article's slices for the given mode
 * (all|missing|stale) from the configured source language into the target
 * language and returns the refreshed row status plus the rendered status cells
 * so the JS can update the row in place without reloading the page.
 *
 * Responds with JSON.
 */
class rex_api_d2u_helper_article_translate extends rex_api_function
{
    /** @var bool Endpoint requires a logged in backend user. */
    protected $published = false;

    public function execute(): rex_api_result
    {
        $sendJson = static function (array $data, int $status = 200): void {
            rex_response::cleanOutputBuffers();
            if (403 === $status) {
                rex_response::setStatus(rex_response::HTTP_FORBIDDEN);
            } elseif (200 !== $status) {
                rex_response::setStatus(rex_response::HTTP_BAD_REQUEST);
            }
            rex_response::sendJson($data);
            exit;
        };

        $user = rex::getUser();
        if (!$user instanceof rex_user || !$user->hasPerm('d2u_helper[translation_helper]')) {
            $sendJson(['success' => false, 'message' => rex_i18n::msg('d2u_helper_translations_ai_no_permission')], 403);
        }

        if (!rex_csrf_token::factory('d2u_helper_article_translate')->isValid()) {
            $sendJson(['success' => false, 'message' => rex_i18n::msg('csrf_token_invalid')], 403);
        }

        if (!AiTranslationHelper::isAvailable()) {
            $sendJson(['success' => false, 'message' => rex_i18n::msg('d2u_helper_translations_ai_not_configured')], 400);
        }

        $articleId = rex_request('article_id', 'int', 0);
        $mode = rex_request('mode', 'string', 'all');
        $targetClang = rex_request('target_clang', 'int', 0);
        $sourceClang = (int) rex_config::get('d2u_helper', 'default_lang', rex_clang::getStartId());

        if (!in_array($mode, ['all', 'missing', 'stale'], true)) {
            $mode = 'all';
        }

        if ($articleId <= 0 || $targetClang <= 0 || $sourceClang <= 0 || $sourceClang === $targetClang) {
            $sendJson(['success' => false, 'message' => rex_i18n::msg('d2u_helper_translations_ai_invalid_request')], 400);
        }

        $result = SliceTranslator::translateArticle($articleId, $sourceClang, $targetClang, $mode);

        $status = SliceTranslator::getArticleContentStatus($articleId, $sourceClang, $targetClang);
        $cells = SliceTranslator::renderArticleStatusCells($articleId, $status, true);
        $done = !$status['noContent'] && 0 === $status['missing'] && 0 === $status['stale'];

        $sendJson([
            'success' => (bool) ($result['success'] ?? false),
            'name' => (string) ($result['name'] ?? ''),
            'message' => (string) ($result['message'] ?? ''),
            'status' => $status,
            'done' => $done,
            'cells' => $cells,
        ], ($result['success'] ?? false) ? 200 : 400);

        // Unreachable, satisfies the return type.
        return new rex_api_result(true);
    }
}
