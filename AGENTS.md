# D2U Helper - Agent Notes

Rules only. Short. Actionable.

## Core Rules

- Namespace: `TobiasKrais\D2UHelper`
- PHP classes: 4 spaces. Modules and templates: tabs
- Comments only in English
- Frontend labels via `Sprog\Wildcard::get()`, backend labels via `rex_i18n::msg()` with keys from `lang/`

## When Changing

- Keep backend translation keys in sync across all files under `lang/`
- For `d2u_machinery` integrations always use `FrontendHelper::isD2UMachineryExtensionActive()`, never old plugin checks
- In BS5 templates and modules, solve colors through d2u_helper CSS variables. Do not add hardcoded dark-mode overrides when `var(...)` already exists.
- For module changes: check or update changelog in `pages/help.changelog.php`, raise revision in `lib/ModuleManager.php` only once per release
- For template changes: check or update changelog in `pages/help.changelog.php`, raise revision in `lib/TemplateManager.php` only once per release
- If target version in changelog already has `-DEV`: do not raise module or template revision again in same phase
- Use real umlauts in changelog files, AGENTS.md, and README.md

## Maintenance

- Keep only recurring pitfalls, fixed conventions, and agent-relevant workflows here
