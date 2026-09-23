<?php

use TobiasKrais\D2UHelper\AiTranslationHelper;
use TobiasKrais\D2UHelper\SeoTranslator;

/**
 * Backend endpoint for the in-page (AJAX) actions on the
 * "Redaxo Kategorien & SEO" tab of the translation helper.
 *
 * POST (CSRF): performs one SEO action (translate|sync|align|align_status|
 * align_image) for a single article from the configured source language into
 * the target language and returns the refreshed row cells so the JS can update
 * the row in place without reloading the page.
 *
 * Responds with JSON.
 */
class rex_api_d2u_helper_seo_translate extends rex_api_function
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

        if (!rex_csrf_token::factory('d2u_helper_seo_translate')->isValid()) {
            $sendJson(['success' => false, 'message' => rex_i18n::msg('csrf_token_invalid')], 403);
        }

        $articleId = rex_request('article_id', 'int', 0);
        $action = rex_request('seo_action', 'string', '');
        $targetClang = rex_request('target_clang', 'int', 0);
        $sourceClang = (int) rex_config::get('d2u_helper', 'default_lang', rex_clang::getStartId());

        if (!in_array($action, ['translate', 'sync', 'align', 'align_status', 'align_image'], true)) {
            $sendJson(['success' => false, 'message' => rex_i18n::msg('d2u_helper_translations_ai_invalid_request')], 400);
        }

        if ($articleId <= 0 || $targetClang <= 0 || $sourceClang <= 0 || $sourceClang === $targetClang) {
            $sendJson(['success' => false, 'message' => rex_i18n::msg('d2u_helper_translations_ai_invalid_request')], 400);
        }

        if ('translate' === $action && !AiTranslationHelper::isAvailable()) {
            $sendJson(['success' => false, 'message' => rex_i18n::msg('d2u_helper_translations_ai_not_configured')], 400);
        }

        $result = match ($action) {
            'translate' => SeoTranslator::translateArticleSeo($articleId, $sourceClang, $targetClang),
            'sync' => SeoTranslator::syncArticleSeo($articleId, $sourceClang, $targetClang),
            'align' => SeoTranslator::alignArticleSeo($articleId, $sourceClang, $targetClang),
            'align_status' => SeoTranslator::alignStatusArticleSeo($articleId, $sourceClang, $targetClang),
            'align_image' => SeoTranslator::alignImageArticleSeo($articleId, $sourceClang, $targetClang),
        };

        $row = SeoTranslator::getSeoRow($articleId, $sourceClang, $targetClang);
        $cells = null !== $row ? SeoTranslator::renderCells($row, $articleId) : [];

        $sendJson([
            'success' => (bool) ($result['success'] ?? false),
            'name' => (string) ($result['name'] ?? ''),
            'message' => (string) ($result['message'] ?? ''),
            'cells' => $cells,
        ], ($result['success'] ?? false) ? 200 : 400);

        // Unreachable, satisfies the return type.
        return new rex_api_result(true);
    }
}
