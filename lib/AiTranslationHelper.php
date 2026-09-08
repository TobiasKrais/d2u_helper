<?php

namespace TobiasKrais\D2UHelper;

use rex_addon;
use rex_clang;
use rex_config;
use rex_exception;

/**
 * @api
 * Central helper that connects the D2U translation helper to the ai_platform
 * addon. It keeps the whole AI integration (availability check, prompt
 * building, batching and error handling) in one place so the individual
 * D2U addons only have to declare which of their fields are translatable.
 */
class AiTranslationHelper
{
    /**
     * Whether AI based translation is available: the ai_platform addon must be
     * installed and available and a default text profile must be configured.
     * @return bool true if AI translation can be used
     */
    public static function isAvailable(): bool
    {
        $addon = rex_addon::get('ai_platform');
        if (!$addon->isAvailable()) {
            return false;
        }
        if (!class_exists(\FriendsOfRedaxo\AiPlatform\Service::class)) {
            return false;
        }
        return (int) rex_config::get('ai_platform', 'default_text_profile', 0) > 0;
    }

    /**
     * Translate a set of fields from a source language into a target language.
     *
     * All fields are translated in a single batched request (a JSON object in,
     * a JSON object out) to keep costs and latency low. HTML fields keep their
     * markup; only the human readable text is translated.
     *
     * @param array<string,array{value:string,html?:bool}> $fields Map of field
     *        key => ['value' => text, 'html' => bool]. The keys are preserved.
     * @param int $sourceClangId Redaxo clang id of the source language
     * @param int $targetClangId Redaxo clang id of the target language
     * @return array<string,string> Map of field key => translated text. Fields
     *         missing from the model response fall back to the source value.
     * @throws rex_exception if AI is not available or the response is unusable
     */
    public static function translateFields(array $fields, int $sourceClangId, int $targetClangId): array
    {
        if (0 === count($fields)) {
            return [];
        }
        if (!self::isAvailable()) {
            throw new rex_exception('ai_platform is not available or no default text profile is configured.');
        }

        $sourceClang = rex_clang::get($sourceClangId);
        $targetClang = rex_clang::get($targetClangId);
        $sourceLang = $sourceClang instanceof rex_clang ? $sourceClang->getName() : (string) $sourceClangId;
        $targetLang = $targetClang instanceof rex_clang ? $targetClang->getName() : (string) $targetClangId;

        $payload = [];
        $htmlKeys = [];
        foreach ($fields as $key => $field) {
            $payload[$key] = (string) $field['value'];
            if (isset($field['html']) && true === $field['html']) {
                $htmlKeys[] = $key;
            }
        }

        $system = 'You are a professional translator for website content. '
            . 'Translate each JSON string value from '. $sourceLang .' to '. $targetLang .'. '
            . 'Return ONLY a valid JSON object with exactly the same keys and the translated values. '
            . 'No markdown, no code fences, no explanation. Do not add or remove keys. '
            . 'Preserve any HTML tags, attributes and entities exactly and translate only the '
            . 'human readable text between the tags. Keep placeholders such as %s, %d or {name} unchanged.';
        if (count($htmlKeys) > 0) {
            $system .= ' The following keys contain HTML markup: '. implode(', ', $htmlKeys) .'.';
        }

        $prompt = 'Translate the values in this JSON object:'. "\n"
            . (string) json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        try {
            $service = \FriendsOfRedaxo\AiPlatform\Service::getInstance();
            $response = $service->generateText($prompt, $system, null);
        } catch (\Throwable $e) {
            throw new rex_exception('AI translation request failed: '. $e->getMessage(), $e);
        }

        $translated = self::decodeJsonObject($response);
        if (null === $translated) {
            throw new rex_exception('AI translation returned an invalid response.');
        }

        $result = [];
        foreach ($fields as $key => $field) {
            $result[$key] = array_key_exists($key, $translated)
                ? (string) $translated[$key]
                : (string) $field['value'];
        }
        return $result;
    }

    /**
     * Decode a JSON object from a model response, tolerating code fences and
     * surrounding text.
     * @param string $response Raw model response
     * @return array<string,mixed>|null Decoded object or null on failure
     */
    private static function decodeJsonObject(string $response): ?array
    {
        $response = trim($response);
        if (str_starts_with($response, '```')) {
            $response = (string) preg_replace('/^```[a-zA-Z]*\s*/', '', $response);
            $response = (string) preg_replace('/\s*```$/', '', $response);
            $response = trim($response);
        }

        $start = strpos($response, '{');
        $end = strrpos($response, '}');
        if (false === $start || false === $end || $end <= $start) {
            return null;
        }

        $json = substr($response, $start, $end - $start + 1);
        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }
}
