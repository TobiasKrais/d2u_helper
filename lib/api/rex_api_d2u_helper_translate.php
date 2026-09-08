<?php

use TobiasKrais\D2UHelper\AiTranslationHelper;

/**
 * Backend AJAX endpoint that triggers the AI translation of a single object.
 *
 * It does not know the concrete addon models. Instead it fires the extension
 * point {@see D2U_HELPER_TRANSLATE_OBJECT} so every D2U addon can translate its
 * own objects (mirrors the D2U_HELPER_TRANSLATION_LIST pattern).
 *
 * Request parameters:
 *  - addon:            addon key that owns the object (e.g. 'd2u_news')
 *  - type:             model identifier inside the addon (e.g. 'news')
 *  - id:               object id
 *  - source_clang_id:  source language clang id
 *  - target_clang_id:  target language clang id
 *  - _csrf_token:      CSRF token (id 'd2u_helper_translate')
 *
 * Responds with JSON: { success: bool, name: string, message: string }.
 */
class rex_api_d2u_helper_translate extends rex_api_function
{
    /** @var bool Endpoint requires a logged in backend user. */
    protected $published = false;

    public function execute(): rex_api_result
    {
        $sendJson = static function (array $data): void {
            rex_response::cleanOutputBuffers();
            rex_response::sendJson($data);
            exit;
        };

        $user = rex::getUser();
        if (!$user instanceof rex_user || !$user->hasPerm('d2u_helper[translation_helper]')) {
            rex_response::setStatus(rex_response::HTTP_FORBIDDEN);
            $sendJson(['success' => false, 'name' => '', 'message' => rex_i18n::msg('d2u_helper_translations_ai_no_permission')]);
        }

        if (!rex_csrf_token::factory('d2u_helper_translate')->isValid()) {
            rex_response::setStatus(rex_response::HTTP_FORBIDDEN);
            $sendJson(['success' => false, 'name' => '', 'message' => rex_i18n::msg('csrf_token_invalid')]);
        }

        if (!AiTranslationHelper::isAvailable()) {
            $sendJson(['success' => false, 'name' => '', 'message' => rex_i18n::msg('d2u_helper_translations_ai_not_configured')]);
        }

        $addon = rex_request('addon', 'string', '');
        $type = rex_request('type', 'string', '');
        $id = rex_request('id', 'int', 0);
        $sourceClangId = rex_request('source_clang_id', 'int', 0);
        $targetClangId = rex_request('target_clang_id', 'int', 0);

        if ('' === $addon || '' === $type || $id <= 0 || $sourceClangId <= 0 || $targetClangId <= 0 || $sourceClangId === $targetClangId) {
            $sendJson(['success' => false, 'name' => '', 'message' => rex_i18n::msg('d2u_helper_translations_ai_invalid_request')]);
        }

        /**
         * Extension point for translating a single object with AI.
         * @param array{success:bool,name:string,message:string} $subject Result
         * @param array $params addon, type, id, source_clang_id, target_clang_id
         * @return array{success:bool,name:string,message:string}
         */
        $result = rex_extension::registerPoint(new rex_extension_point(
            'D2U_HELPER_TRANSLATE_OBJECT',
            ['success' => false, 'name' => '', 'message' => rex_i18n::msg('d2u_helper_translations_ai_no_handler')],
            [
                'addon' => $addon,
                'type' => $type,
                'id' => $id,
                'source_clang_id' => $sourceClangId,
                'target_clang_id' => $targetClangId,
            ]
        ));

        if (!is_array($result)) {
            $result = ['success' => false, 'name' => '', 'message' => rex_i18n::msg('d2u_helper_translations_ai_no_handler')];
        }
        $result += ['success' => false, 'name' => '', 'message' => ''];

        $sendJson($result);

        // Unreachable, satisfies the return type.
        return new rex_api_result(true);
    }
}
