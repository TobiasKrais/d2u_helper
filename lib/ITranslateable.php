<?php

namespace TobiasKrais\D2UHelper;

/**
 * @api
 * Opt-in interface for objects that can translate themselves from a source
 * language into their own (target) language using {@see AiTranslationHelper}.
 *
 * It is intentionally separate from {@see ITranslationHelper} so implementing
 * AI translation stays optional and does not force a change on every existing
 * ITranslationHelper implementation.
 */
interface ITranslateable
{
    /**
     * Translate this object from the given source language into the language
     * this object was loaded with and store the result.
     *
     * Implementations load the source values, translate the translatable
     * fields via {@see AiTranslationHelper::translateFields()}, store them for
     * the target language and clear the "translation needs update" flag.
     * @param int $sourceClangId Redaxo clang id of the source language
     * @return bool true on success
     */
    public function translateFrom(int $sourceClangId): bool;
}
