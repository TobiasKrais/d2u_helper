<?php

use TobiasKrais\D2UHelper\AiTranslationHelper;
use TobiasKrais\D2UHelper\SliceTranslator;

/**
 * Backend endpoint for the in-editor slice translation button.
 *
 * Two actions:
 *  - action=fields (GET):  returns the base language and the ids of the current
 *    article/clang slices whose module declares translatable fields, so the JS
 *    knows which slices get a button.
 *  - action=translate (POST, CSRF): translates one slice's declared fields from
 *    the base language into the current language.
 *
 * Responds with JSON.
 */
class rex_api_d2u_helper_slice_translate extends rex_api_function
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

        $action = rex_request('action', 'string', '');

        if ('fields' === $action) {
            $articleId = rex_request('article_id', 'int', 0);
            $clangId = rex_request('clang', 'int', 0);
            $sourceClang = (int) rex_config::get('d2u_helper', 'default_lang', rex_clang::getStartId());

            $slices = [];
            if (AiTranslationHelper::isAvailable() && $clangId > 0 && $clangId !== $sourceClang) {
                $slices = SliceTranslator::getTranslatableSliceIds($articleId, $clangId);
            }

            $sendJson(['success' => true, 'source_clang' => $sourceClang, 'slices' => $slices]);
        }

        if ('translate' === $action) {
            if (!rex_csrf_token::factory('d2u_helper_slice_translate')->isValid()) {
                $sendJson(['success' => false, 'message' => rex_i18n::msg('csrf_token_invalid')], 403);
            }

            $sliceId = rex_request('slice_id', 'int', 0);
            $clangId = rex_request('clang', 'int', 0);
            $result = SliceTranslator::translateSliceById($sliceId, $clangId);

            $sendJson($result, ($result['success'] ?? false) ? 200 : 400);
        }

        $sendJson(['success' => false, 'message' => rex_i18n::msg('d2u_helper_translations_ai_invalid_request')], 400);

        // Unreachable, satisfies the return type.
        return new rex_api_result(true);
    }
}
