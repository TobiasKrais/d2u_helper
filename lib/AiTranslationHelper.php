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
            . 'The response MUST be valid JSON: escape newlines inside string values as \n, '
            . 'tabs as \t and double quotes as \\". '
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
            // Fallback fuer Slices mit genau EINEM uebersetzbaren Feld (z. B. der
            // einfache Texteditor, Modul 010): Das Modell liefert dann oft entweder nur
            // den uebersetzten Text ODER ein leicht kaputtes JSON-Objekt {"f1":"..."}
            // mit echten Zeilenumbruechen im String (= ungueltiges JSON, decode scheitert).
            if (1 === count($fields)) {
                $onlyKey = (string) array_key_first($fields);
                $plain = self::stripResponseWrapping($response);
                // Kaputtes {"key":"..."}: Wert per Regex loesen und JSON-Escapes aufloesen.
                if (1 === preg_match('/^\s*\{\s*"' . preg_quote($onlyKey, '/') . '"\s*:\s*"(.*)"\s*\}\s*$/s', $plain, $m)) {
                    return [$onlyKey => self::unescapeJsonString($m[1])];
                }
                // Reiner Text ohne JSON-Huelle: direkt als Wert verwenden.
                if ('' !== trim($plain) && !str_starts_with(trim($plain), '{')) {
                    return [$onlyKey => $plain];
                }
            }
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
     * Resolve the common JSON string escape sequences in a raw value that was
     * pulled out of a broken JSON object (model emitted literal newlines, so
     * json_decode could not be used).
     * @param string $value Raw escaped value
     * @return string Unescaped value
     */
    private static function unescapeJsonString(string $value): string
    {
        return strtr($value, [
            '\\n' => "\n",
            '\\r' => "\r",
            '\\t' => "\t",
            '\\"' => '"',
            '\\/' => '/',
            '\\\\' => '\\',
        ]);
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
        if (!is_array($data)) {
            // Haeufigster Modell-Fehler: rohe Zeilenumbrueche/Tabs innerhalb der
            // String-Werte (z. B. im HTML) -> ungueltiges JSON. Reparieren und erneut
            // dekodieren, bevor aufgegeben wird.
            $data = json_decode(self::repairJson($json), true);
        }
        return is_array($data) ? $data : null;
    }

    /**
     * Escape raw control characters (newline, carriage return, tab) that appear
     * INSIDE JSON string values, which the model sometimes emits unescaped and
     * which make the whole response invalid JSON. Structure outside strings is
     * left untouched. Byte-safe for UTF-8 (the handled characters are all ASCII
     * and never occur as UTF-8 continuation bytes).
     * @param string $json Possibly broken JSON string
     * @return string Repaired JSON string
     */
    private static function repairJson(string $json): string
    {
        $out = '';
        $inString = false;
        $escaped = false;
        $len = strlen($json);
        for ($i = 0; $i < $len; ++$i) {
            $ch = $json[$i];
            if ($escaped) {
                $out .= $ch;
                $escaped = false;
                continue;
            }
            if ('\\' === $ch) {
                $out .= $ch;
                $escaped = true;
                continue;
            }
            if ('"' === $ch) {
                $inString = !$inString;
                $out .= $ch;
                continue;
            }
            if ($inString) {
                if ("\n" === $ch) {
                    $out .= '\\n';
                    continue;
                }
                if ("\r" === $ch) {
                    $out .= '\\r';
                    continue;
                }
                if ("\t" === $ch) {
                    $out .= '\\t';
                    continue;
                }
            }
            $out .= $ch;
        }
        return $out;
    }
}
