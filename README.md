# D2U Helper - Redaxo 5 Helferaddon

Dieses Redaxo Addon bietet verschiedene Helferchen für Redaxo und andere Addon. D2U ist eine Kurzform von "Design to (2) Use", der Domain des Addonautors. Dieses Addon bietet Backend und Frontend Klassen, Übersetzungen fürs Redaxo Backend und das Sprog Addon und anderes, was in weiteren Addons des Autos genutzt wird. Darüber hinaus bietet das Addon einen einfache Möglichkeit eine Webseite mit Redaxo einzurichten, die ganz besonders für Redaxo Einsteiger interessant ist.

Die Frontend Klasse `FrontendHelper` bietet die Methode `getAlternateURLs()`. Diese stellt alternative URLs in anderen Sprachen zur aktuellen URL zur Verfügung. Um andere Addons in diese Methode mit einzubinden, gibt es ab Version 2 dieses Addons den Extension Point `D2U_HELPER_ALTERNATE_URLS` nutzen. Dieser übergibt 2 Parameter: `url_namespace` beinhaltet den Namespace des URL Addons. `url_id` beinhaltet die ID des Datensatzes des URL Addons.
Der Rückgabearray, der zu dem bestehenden Array hinzugefügt werden muss, sieht wie folgt aus:

```php
[
    'redaxo_clang_id' => 'Alternative URL'
];
```

Eine weitere Methoder der Frontend Klasse `FrontendHelper` ist `getBreadcrumbs()`. Diese stellt die Breadcrumbs als HTML String zur Verfügung. Um andere Addons in diese Methode mit einzubinden, gibt es ab Version 2 dieses Addons den Extension Point `D2U_HELPER_BREADCRUMBS` nutzen. Dieser übergibt 2 Parameter: `url_namespace` beinhaltet den Namespace des URL Addons. `url_id` beinhaltet die ID des Datensatzes des URL Addons.
Der Rückgabearray, der zu dem bestehenden Array hinzugefügt werden muss, sieht wie folgt aus:

```php
[
    'Breadcrumb'
];
```

## Modulverwaltung

Die Modulverwaltung bietet grundlegende Redaxo Module an. Diese können mit einem Klick installiert und bei Addonupdate automatisch aktualisiert werden. Die Module nutzen alle Bootstrap Version 4. Die Module harmonieren mit dem Templates des Addons. Eine Demoseite einiger Module findet man hier: <https://test.design-to-use.de/de/>.

## Templateverwaltung

Die Templateverwaltung bietet ein paar Redaxo Templates an, die ebenfalls mit einem Klick installiert und bei Addonupdate automatisch aktualisiert werden können.

## Übersetzungshilfe

Diese Seite ist nur sichtbar, wenn die Redaxo Installation mehrere Sprachen nutzt. Dann bietet sie für mehrsprachige Addons von D2U eine Übersetzungshilfe an. Dabei muss der Nutzer einem Datensatz in einem der D2U Addons öffnen und im Bereich der jeweiligen Sprache einstellen, ob die Übersetzung aktuell ist, aktualisiert werden muss oder gar nicht gewünscht ist. Aufgrund dieser Daten erstellt dieses Plugin eine Liste aller fehlenden oder zu aktualisierenden Übersetzungen.

Auch andere Addons können sich in diese Liste einklinken. Dazu müssen Sie den Extension Point `D2U_HELPER_TRANSLATION_LIST` nutzen. Dieser übergibt 3 Parameter: `source_clang_id` beinhaltet die Redaxo Sprach-ID, die die Basis der Übersetzung ist. `target_clang_id` ist die Redaxo Sprach-ID, die das Ziel der Übersetzung ist. In dieser Sprache wird geprüft, ob Übersetzungen fehlen oder zu Aktualisieren sind. `filter_type` hat entweder den Wert 'update' oder 'missing'. Wenn 'update' angegeben ist, wird nach zu aktualisierenden und bei 'missing' nach fehlenden Übersetzungen gesucht.
Der Rückgabearray, der zu dem bestehenden Array hinzugefügt werden muss, sieht wie folgt aus:

```php
[
    'addon_name' => 'Name des Addons',
    'pages' => [
        [
            'title' => 'Name der Seite',
            'icon' => 'FontAwesome Icon der Seite',
            'html' => 'HTML Code der die Liste (ul) mitsamt Links zu den Backendseiten der Übersetzungen enthält'
        ]
    ]
];
```

Für die einzelnen Listeneinträge (`<li>`) sollte statt eigenem Markup die Methode `TobiasKrais\D2UHelper\BackendHelper::getTranslationItem($addon, $type, $id, $name, $editUrl)` genutzt werden. Sie erzeugt den Link zur Bearbeitungsseite und – sofern KI-Übersetzung verfügbar ist – ein Übersetzen-Icon mit den nötigen Datenattributen.

### KI-Übersetzung (`D2U_HELPER_TRANSLATE_OBJECT`)

Ist das Addon [`ai_platform`](https://github.com/FriendsOfREDAXO/ai_platform) installiert und ein Standard-Textprofil konfiguriert (`AiTranslationHelper::isAvailable()`), zeigt die Übersetzungshilfe hinter jedem Eintrag ein Übersetzen-Icon und einen Button „Alle mit KI übersetzen". Beim Klick ruft ein zentraler Backend-Endpunkt (`index.php?rex-api-call=d2u_helper_translate`) den Extension Point `D2U_HELPER_TRANSLATE_OBJECT`. So bleibt die addonspezifische Übersetzungslogik im jeweiligen Addon.

Der Extension Point übergibt die Parameter `addon`, `type`, `id`, `source_clang_id` und `target_clang_id`. Ein Addon registriert einen Handler, prüft ob `addon` sein eigener Schlüssel ist, übersetzt das Objekt und gibt ein Array zurück:

```php
rex_extension::register('D2U_HELPER_TRANSLATE_OBJECT', static function (rex_extension_point $ep) {
    $params = $ep->getParams();
    if ('mein_addon' !== $params['addon']) {
        return $ep->getSubject(); // nicht zuständig
    }

    // passendes Objekt anhand von $params['type'] und $params['id'] laden,
    // Felder mit AiTranslationHelper::translateFields() übersetzen, in der
    // Zielsprache ($params['target_clang_id']) speichern und
    // translation_needs_update auf 'no' setzen.

    return [
        'success' => true,
        'name' => $uebersetzterName,
        'message' => '',
    ];
});
```

Zum Übersetzen der einzelnen Felder steht `TobiasKrais\D2UHelper\AiTranslationHelper::translateFields($fields, $sourceClangId, $targetClangId)` bereit. `$fields` ist eine Map `['feldname' => ['value' => 'Text', 'html' => false]]`; alle Felder werden in einem Aufruf übersetzt, HTML bleibt erhalten.

## Autor

Autor des Addons ist [Tobias Krais](https://github.com/TobiasKrais/)
