<?php
/*
 * Generic schema.org structured data (JSON-LD) module.
 * Emits a JSON-LD block in the body of the page, built from the entered fields and an optional
 * free JSON object merged on top. Only non-empty fields are included. Nothing is emitted without
 * a @type and a name.
 */

$sd_type = trim(htmlspecialchars_decode('REX_VALUE[1]')); /** @phpstan-ignore-line */
$sd_name = trim(htmlspecialchars_decode('REX_VALUE[2]')); /** @phpstan-ignore-line */

if ('' !== $sd_type && '' !== $sd_name) {
    // Base URL: prefer the yrewrite domain, otherwise the REDAXO server setting (same logic as
    // FrontendHelper::generateStructuredData()).
    $sd_base = rtrim((string) rex::getServer(), '/');
    if (rex_addon::get('yrewrite')->isAvailable() && class_exists('rex_yrewrite')) {
        $sd_domain = rex_yrewrite::getCurrentDomain();
        if ($sd_domain instanceof rex_yrewrite_domain && '' !== $sd_domain->getUrl()) {
            $sd_base = rtrim($sd_domain->getUrl(), '/');
        }
    }

    $sd_make_absolute = static function (string $url) use ($sd_base): string {
        $url = trim($url);
        if ('' === $url || 1 === preg_match('#^https?://#i', $url)) {
            return $url;
        }
        return $sd_base . '/' . ltrim($url, '/');
    };

    $sd_data = [
        '@context' => 'https://schema.org',
        '@type' => $sd_type,
        'name' => $sd_name,
    ];

    $sd_description = trim(htmlspecialchars_decode('REX_VALUE[3]')); /** @phpstan-ignore-line */
    if ('' !== $sd_description) {
        $sd_data['description'] = $sd_description;
    }

    // URL of the thing: defaults to the current article (the page this module sits on) when empty.
    $sd_url = $sd_make_absolute(htmlspecialchars_decode('REX_VALUE[4]')); /** @phpstan-ignore-line */
    if ('' === $sd_url) {
        $sd_current_article = rex_article::getCurrent();
        if ($sd_current_article instanceof rex_article) {
            $sd_url = $sd_make_absolute($sd_current_article->getUrl());
        }
    }
    if ('' !== $sd_url) {
        $sd_data['url'] = $sd_url;
    }

    // Image from the media pool (REX_MEDIA widget 1).
    $sd_image_file = 'REX_MEDIA[1]'; /** @phpstan-ignore-line */
    if ('' !== $sd_image_file) {
        $sd_data['image'] = $sd_make_absolute(rex_url::media($sd_image_file));
    }

    $sd_telephone = trim(htmlspecialchars_decode('REX_VALUE[6]')); /** @phpstan-ignore-line */
    if ('' !== $sd_telephone) {
        $sd_data['telephone'] = $sd_telephone;
    }

    $sd_email = trim(htmlspecialchars_decode('REX_VALUE[7]')); /** @phpstan-ignore-line */
    if ('' !== $sd_email) {
        $sd_data['email'] = $sd_email;
    }

    $sd_price_range = trim(htmlspecialchars_decode('REX_VALUE[8]')); /** @phpstan-ignore-line */
    if ('' !== $sd_price_range) {
        $sd_data['priceRange'] = $sd_price_range;
    }

    // Postal address.
    $sd_street = trim(htmlspecialchars_decode('REX_VALUE[9]')); /** @phpstan-ignore-line */
    $sd_zip_city = trim(htmlspecialchars_decode('REX_VALUE[10]')); /** @phpstan-ignore-line */
    $sd_country = trim(htmlspecialchars_decode('REX_VALUE[11]')); /** @phpstan-ignore-line */
    $sd_address = ['@type' => 'PostalAddress'];
    if ('' !== $sd_street) {
        $sd_address['streetAddress'] = $sd_street;
    }
    if ('' !== $sd_zip_city) {
        if (1 === preg_match('/^\s*(\S+)\s+(.+)$/', $sd_zip_city, $sd_zc)) {
            $sd_address['postalCode'] = $sd_zc[1];
            $sd_address['addressLocality'] = trim($sd_zc[2]);
        } else {
            $sd_address['addressLocality'] = $sd_zip_city;
        }
    }
    if ('' !== $sd_country) {
        $sd_address['addressCountry'] = $sd_country;
    }
    if (count($sd_address) > 1) {
        $sd_data['address'] = $sd_address;
    }

    // Geo coordinates.
    $sd_latitude = (float) str_replace(',', '.', trim(htmlspecialchars_decode('REX_VALUE[12]'))); /** @phpstan-ignore-line */
    $sd_longitude = (float) str_replace(',', '.', trim(htmlspecialchars_decode('REX_VALUE[13]'))); /** @phpstan-ignore-line */
    if (0.0 !== $sd_latitude && 0.0 !== $sd_longitude) {
        $sd_data['geo'] = [
            '@type' => 'GeoCoordinates',
            'latitude' => $sd_latitude,
            'longitude' => $sd_longitude,
        ];
    }

    // Opening hours: one rule per line, e.g. "Mo-Fr 09:00-18:00".
    $sd_hours_raw = trim(htmlspecialchars_decode('REX_VALUE[14]')); /** @phpstan-ignore-line */
    if ('' !== $sd_hours_raw) {
        $sd_hours = [];
        foreach (preg_split('/\r\n|\r|\n/', $sd_hours_raw) as $sd_hours_line) {
            $sd_hours_line = trim((string) $sd_hours_line);
            if ('' !== $sd_hours_line) {
                $sd_hours[] = $sd_hours_line;
            }
        }
        if (count($sd_hours) > 0) {
            $sd_data['openingHours'] = count($sd_hours) === 1 ? $sd_hours[0] : $sd_hours;
        }
    }

    // Curated per-type properties: the backend builds a ready schema.org JSON fragment from the
    // friendly fields and stores it here. Merged like the advanced field below.
    $sd_curated_raw = trim(htmlspecialchars_decode('REX_VALUE[16]')); /** @phpstan-ignore-line */
    if ('' !== $sd_curated_raw) {
        $sd_curated = json_decode($sd_curated_raw, true);
        if (is_array($sd_curated)) {
            $sd_data = array_replace_recursive($sd_data, $sd_curated);
        }
    }

    // Optional free JSON object, merged on top (can add type-specific fields and override above).
    $sd_additional_raw = trim(htmlspecialchars_decode('REX_VALUE[15]')); /** @phpstan-ignore-line */
    if ('' !== $sd_additional_raw) {
        $sd_additional = json_decode($sd_additional_raw, true);
        if (is_array($sd_additional)) {
            $sd_data = array_replace_recursive($sd_data, $sd_additional);
        }
    }

    $sd_json = json_encode($sd_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if (false !== $sd_json) {
        if (rex::isBackend()) {
            // Backend only: show a human-readable summary of the entered data. The invisible JSON-LD
            // script would otherwise render nothing for editors in the slice view.
            $sd_render = static function ($value) use (&$sd_render): string {
                if (is_array($value)) {
                    $is_list = array_keys($value) === range(0, count($value) - 1);
                    $html = '<ul style="margin:2px 0 2px 18px; padding:0;">';
                    foreach ($value as $key => $sub) {
                        $label = $is_list ? '' : '<strong>' . htmlspecialchars((string) $key) . ':</strong> ';
                        $html .= '<li style="list-style:disc;">' . $label . $sd_render($sub) . '</li>';
                    }
                    return $html . '</ul>';
                }
                if (is_bool($value)) {
                    return $value ? 'ja' : 'nein';
                }
                return htmlspecialchars((string) $value);
            };
            echo '<div style="border:1px solid rgba(127,127,127,.4); border-radius:4px; padding:10px; background:rgba(127,127,127,.08); color:inherit;">';
            echo '<div style="margin-bottom:6px;"><strong>Strukturierte Daten (schema.org JSON-LD)</strong> &ndash; nur im Backend sichtbar; im Frontend wird daraus der JSON-LD-Code ausgegeben.</div>';
            echo $sd_render($sd_data);
            echo '</div>';
        } else {
            // Prevent </script> breakout; JSON-LD itself needs no HTML escaping.
            echo '<script type="application/ld+json">' . PHP_EOL . str_replace('<', '\u003C', $sd_json) . PHP_EOL . '</script>' . PHP_EOL;
        }
    }
} elseif (rex::isBackend()) {
    echo '<div style="border:1px dashed rgba(127,127,127,.5); border-radius:4px; padding:10px; color:inherit; opacity:.75;">Strukturierte Daten: bitte mindestens Typ und Name ausf&uuml;llen, damit im Frontend etwas ausgegeben wird.</div>';
}
