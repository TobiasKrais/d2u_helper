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

        // Empty values need no translation — skip them. An empty ###section### can make
        // the model drop the markers entirely (seen with title-only records whose teaser
        // is empty), so they are never sent; their place in the result is kept as-is.
        $result = [];
        $toTranslate = [];
        foreach ($fields as $key => $field) {
            $value = (string) $field['value'];
            if ('' === trim($value)) {
                $result[$key] = $value;
            } else {
                $toTranslate[$key] = $field;
            }
        }
        if (0 === count($toTranslate)) {
            return $result;
        }

        // Transport is a marker format (###key### on its own line, then the value),
        // deliberately NOT JSON: slice values are HTML containing double quotes and
        // newlines, which a model regularly fails to escape inside a JSON string,
        // producing an unparseable response. With markers the HTML passes through
        // verbatim and only the lightweight section markers have to survive.
        $parts = [];
        foreach ($toTranslate as $key => $field) {
            $parts[] = '###' . $key . '###' . "\n" . (string) $field['value'];
        }

        $system = 'You are a professional translator for website content. '
            . 'The input is split into sections; each section starts with a marker line of the '
            . 'exact form ###key### on its own line, followed by that section\'s content. '
            . 'Translate ONLY the content of each section from '. $sourceLang .' to '. $targetLang .'. '
            . 'Return every section in the same order, each introduced by its unchanged ###key### '
            . 'marker line. Output nothing else: no explanation, no code fences, no extra markers. '
            . 'Preserve any HTML tags, attributes and entities exactly and translate only the '
            . 'human readable text between the tags. Keep placeholders such as %s, %d or {name} unchanged.';

        $prompt = implode("\n", $parts);

        try {
            $service = \FriendsOfRedaxo\AiPlatform\Service::getInstance();
            $response = $service->generateText($prompt, $system, null);
        } catch (\Throwable $e) {
            throw new rex_exception('AI translation request failed: '. $e->getMessage(), $e);
        }

        $parsed = self::parseMarkedSections($response);

        $anyFound = false;
        foreach ($toTranslate as $key => $field) {
            if (array_key_exists($key, $parsed)) {
                $result[$key] = $parsed[$key];
                $anyFound = true;
            } else {
                // Section missing from the response: keep the source value as fallback.
                $result[$key] = (string) $field['value'];
            }
        }

        if (!$anyFound) {
            // Only one section to translate: the model may have returned just the
            // translated text with no marker at all. Use the whole (fence-stripped)
            // response then.
            if (1 === count($toTranslate)) {
                $onlyKey = (string) array_key_first($toTranslate);
                $plain = self::stripResponseWrapping($response);
                if ('' !== trim($plain)) {
                    $result[$onlyKey] = $plain;
                    return $result;
                }
            }
            // Log the raw response (truncated) so an unparseable case can be diagnosed
            // without re-running — the caller usually only surfaces a generic message.
            \rex_logger::logError(E_WARNING, 'd2u_helper translation: unparseable AI response: ' . mb_substr(trim($response), 0, 1500), __FILE__, __LINE__);
            throw new rex_exception('AI translation returned an invalid response.');
        }

        return $result;
    }

    /**
     * Parse a marker-delimited translation response (###key### on its own line,
     * followed by that section's content) into a key => content map. Tolerant of a
     * varying number of hashes and surrounding whitespace on the marker line.
     * @param string $response Raw model response
     * @return array<string, string> Section key => translated content
     */
    private static function parseMarkedSections(string $response): array
    {
        $response = self::stripResponseWrapping($response);
        $out = [];
        if (0 === preg_match_all('/^[ \t]*#{2,}[ \t]*([A-Za-z0-9_]+)[ \t]*#{2,}[ \t]*$/m', $response, $m, PREG_OFFSET_CAPTURE)) {
            return $out;
        }
        $count = count($m[0]);
        for ($i = 0; $i < $count; ++$i) {
            $key = (string) $m[1][$i][0];
            $start = (int) $m[0][$i][1] + strlen((string) $m[0][$i][0]);
            $end = ($i + 1 < $count) ? (int) $m[0][$i + 1][1] : strlen($response);
            $value = substr($response, $start, $end - $start);
            $out[$key] = trim($value, "\r\n");
        }
        return $out;
    }

    /**
     * Remove surrounding markdown code fences from a model response.
     * @param string $response Raw model response
     * @return string Response without leading/trailing code fences
     */
    private static function stripResponseWrapping(string $response): string
    {
        $response = trim($response);
        if (str_starts_with($response, '```')) {
            $response = (string) preg_replace('/^```[a-zA-Z]*\s*/', '', $response);
            $response = (string) preg_replace('/\s*```$/', '', $response);
            $response = trim($response);
        }
        return $response;
    }
}
