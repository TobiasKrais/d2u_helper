# D2U Helper - Agent Notes

Nur projektspezifische Regeln, die für KI-Arbeit relevant sind.

## Kernregeln

- Namespace für Addon-Klassen: `TobiasKrais\D2UHelper`
- Einrückung: 4 Spaces in PHP-Klassen, Tabs in Modulen und Templates
- Kommentare nur auf Englisch
- Frontend-Labels über `Sprog\Wildcard::get()`, Backend-Labels über `rex_i18n::msg()` mit Keys aus `lang/`

## Wichtige Projekthinweise

- Backend-Translation-Keys müssen in allen Sprachdateien unter `lang/` synchron bleiben.
- Für `d2u_machinery`-Integrationen die Verfügbarkeit über `FrontendHelper::isD2UMachineryExtensionActive()` prüfen, nicht über alte Plugin-Checks.
- In BS5-Templates und -Modulen Farbwerte über die d2u_helper CSS-Variablen lösen. Keine hartcodierten Dark-Mode-Overrides für Farben einführen, wenn bereits `var(...)` genutzt wird.
- Wenn Module geändert werden, Changelog in `pages/help.changelog.php` prüfen oder aktualisieren und die Revisionsnummer in `lib/ModuleManager.php` nur einmal pro Release erhöhen.
- Wenn Templates geändert werden, Changelog in `pages/help.changelog.php` prüfen oder aktualisieren und die Revisionsnummer in `lib/TemplateManager.php` nur einmal pro Release erhöhen.
- Versionshinweise für Module und Templates: Wenn die Zielversion im Changelog bereits `-DEV` trägt, innerhalb derselben Entwicklungsphase keine weitere Revisionsnummer für dasselbe Modul oder Template hochzählen. Erst mit der nächsten Release-Version wieder erneut erhöhen.
- In Changelog-Dateien, AGENTS.md und README.md echte Umlaute (ä, ö, ü, Ä, Ö, Ü, ß) verwenden und nicht als ae/oe/ue/Ae/Oe/Ue/ss umschreiben.

## Pflege

- Diese Datei kurz und handlungsorientiert halten.
- Neue Einträge nur aufnehmen, wenn sie wiederkehrende Stolperfallen, verbindliche Projektkonventionen oder agentenrelevante Workflows betreffen.
