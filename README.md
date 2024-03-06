# D2U Helper - Redaxo 5 Helferaddon

Dieses Redaxo Addon bietet verschiedene Helferchen für Redaxo und andere Addon. D2U ist eine Kurzform von "Design to (2) Use", der Domain des Addonautors. Dieses Addon bietet Backend und Frontend Klassen, Übersetzungen fürs Redaxo Backend und das Sprog Addon und anderes, was in weiteren Addons des Autos genutzt wird. Darüber hinaus bietet das Addon einen einfache Möglichkeit eine Webseite mit Redaxo einzurichten, die ganz besonders für Redaxo Einsteiger interessant ist.

Die Frontend Klasse `FrontendHelper` bietet die Methode `getAlternateURLs()`. Diese stellt alternative URLs in anderen Sprachen zur aktuellen URL zur Verfügung. Um andere Addons in diese Methode mit einzubinden, gibt es ab Version 2 dieses Addons den Extension Point `D2U_HELPER_ALTERNATE_URLS_LIST` nutzen. Dieser übergibt 2 Parameter: `url_namespace` beinhaltet den Namespace des URL Addons. `url_id` beinhaltet die ID des Datensatzes des URL Addons.
Der Rückgabearray, der zu dem bestehenden Array hinzugefügt werden muss, sieht wie folgt aus:

```php
[
    'redaxo_clang_id' => 'Alternative URL'
];
```

Eine weitere Methoder der Frontend Klasse `FrontendHelper` ist `getBreadcrumbs()`. Diese stellt die Breadcrumbs als HTML String zur Verfügung. Um andere Addons in diese Methode mit einzubinden, gibt es ab Version 2 dieses Addons den Extension Point `D2U_HELPER_BREADCRUMB_LIST` nutzen. Dieser übergibt 2 Parameter: `url_namespace` beinhaltet den Namespace des URL Addons. `url_id` beinhaltet die ID des Datensatzes des URL Addons.
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

## Autor

Autor des Addons ist [Tobias Krais](https://github.com/TobiasKrais/)
