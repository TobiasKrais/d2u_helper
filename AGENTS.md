# D2U Helper - Redaxo Addon

A comprehensive helper addon for Redaxo 5 CMS that provides Bootstrap 4 and 5 based templates, modules, navigation menus, backend/frontend helper classes, and translation management for D2U addons.

## Tech Stack

- **Language:** PHP >= 8.1
- **CMS:** Redaxo >= 5.19.0
- **Frontend Framework:** Bootstrap 4.6.2 (templates 00-1, 01-1, 02-1, 03-1, 03-2, 04-1, 04-2, 04-3, 05-1, 06-1), Bootstrap 5.3 (templates 00-2, 01-2, 02-2, 02-3, 03-3, 03-4, 04-4, 05-2, 06-2)
- **Namespace:** `TobiasKrais\D2UHelper`

## Project Structure

```text
d2u_helper/
├── boot.php               # Addon bootstrap (extension points, frontend CSS/JS delivery)
├── install.php             # Installation (media manager types, settings, migrations)
├── update.php              # Update (calls install.php)
├── uninstall.php           # Cleanup (media manager types, metainfo, sprog wildcards)
├── package.yml             # Addon configuration, version, dependencies
├── README.md
├── assets/
│   ├── bootstrap4/         # Bootstrap 4.6.2 CSS + JS bundle
│   ├── bootstrap5/         # Bootstrap 5.3 CSS + JS bundle
│   ├── FontAwesome/        # FontAwesome icons (CSS + webfonts)
│   ├── megamenu/           # Mega Menu navigation assets
│   ├── responsive-multilevelmenu/  # Responsive MultiLevel Menu assets
│   ├── slicknav/           # SlickNav navigation assets
│   ├── smartmenus/         # SmartMenus navigation assets
│   ├── modules/            # Module-specific assets (PhotoSphere, Maps, Leaflet)
│   └── template/           # Template CSS (header, footer, CTA box)
├── fragments/              # Reusable template fragments (head, nav, footer, etc.)
├── lang/                   # Backend translations (de_de, en_gb)
├── lib/                    # PHP classes
├── modules/                # 30 module variants in 14 groups (00-15)
├── pages/                  # Backend pages (settings, modules, templates, etc.)
└── templates/              # 19 template variants: 10 BS4 (00-1 to 06-1), 9 BS5 (00-2 to 06-2)
```

## Coding Conventions

- **Namespace:** `TobiasKrais\D2UHelper` for all classes
- **Naming:** camelCase for variables, PascalCase for classes
- **Indentation:** 4 spaces in PHP classes, tabs in template files
- **Comments:** English comments only
- **Frontend labels:** Use `Sprog\Wildcard::get()` backed by `LangHelper`, not `rex_i18n::msg()`
- **Backend labels:** Use `rex_i18n::msg()` with keys from `lang/` files

## Key Classes

| Class | Description |
| ----- | ----------- |
| `BackendHelper` | Static methods for backend form fields (checkbox, input, color pair, select, textarea, link, media) |
| `FrontendHelper` | Frontend utilities: alternate URLs, breadcrumbs, meta tags, CSS/JS aggregation, CSS compression |
| `FrontendNavigationBS5` | Bootstrap 5 navigation: menu items, language switcher (dropdown or modal), language modal, dark mode toggle. No jQuery dependency. |
| `FrontendNavigationMegaMenu` | Mega Menu navigation (CSS + JS generation, menu HTML). `getMenu()` for BS4, `getMenuItemsBS5()` for BS5 (outputs only `<li>` items without wrapper) |
| `FrontendNavigationResponsiveMultiLevel` | Responsive MultiLevel Menu (Codrops) |
| `FrontendNavigationSlickNav` | SlickNav mobile menu |
| `FrontendNavigationSmartmenu` | SmartMenus navigation |
| `LangHelper` | Sprog wildcard provider for 7 languages (DE, EN, FR, NL, ES, RU, ZH) |
| `ALangHelper` | Abstract base for Sprog wildcard management |
| `ACronJob` | Abstract base for CronJob management in D2U addons |
| `Module` | Module model: installation, update, autoupdate, pairing with Redaxo modules |
| `ModuleManager` | Manages all 30 D2U modules |
| `Template` | Template model: installation, update, autoupdate, CSS retrieval |
| `TemplateManager` | Manages all 10 D2U templates |
| `ITranslationHelper` | Interface for translation support in D2U addons |

## Architecture

### Frontend CSS/JS Delivery

The addon delivers dynamic CSS and JS via URL parameters in `boot.php`:

- `?d2u_helper=helper.css` → Module CSS + Menu CSS
- `?d2u_helper=helper.js&position=head` → Menu JS
- `?d2u_helper=helper.js&position=body` → Module JS
- `?d2u_helper=template.css&template_id=XX` → Template CSS + Custom CSS
- `?d2u_helper=custom.css` → Custom CSS only

jQuery and Bootstrap 4 are included directly in each template file (`templates/*/template.php`), not via central configuration.

### Extension Points

| Extension Point | Location | Purpose |
| --------------- | -------- | ------- |
| `D2U_HELPER_ALTERNATE_URLS` | FrontendHelper | Alternate URLs for multilingual pages |
| `D2U_HELPER_BREADCRUMBS` | FrontendHelper | Breadcrumb extension for other addons |
| `D2U_HELPER_TRANSLATION_LIST` | translation_helper.php | Translation list for addon integration |
| `OUTPUT_FILTER` | boot.php | Injects CSS/JS into head/body, replaces placeholder links, adds TOC |

### Placeholder Replacements

Templates and modules can use these placeholders (replaced in OUTPUT_FILTER):

- `+++LINK_PRIVACY_POLICY+++` → URL to privacy policy article
- `+++LINK_IMPRESS+++` → URL to impress article

To prevent automatic CSS/JS injection by boot.php, include CSS class `prevent_d2u_helper_styles` in the page output.

### CSS Color System

The addon provides configurable colors via addon settings. Colors are delivered as CSS custom properties (`:root` variables), generated by `FrontendHelper::generateCSSVariables()` and prepended to the template CSS in `sendD2UHelperTemplateCSS()`.

```css
/* Generated :root block (light mode) */
:root {
    --navi-color-bg: #c0392b;
    --navi-color-font: #ffffff;
    --navi-color-fontD9: #ffffffD9;
    --article-color-h: #c0392b;
    --article-color-h10: #c0392b1A;
    /* ... etc. */
}

/* Generated dark mode overrides */
[data-bs-theme="dark"] {
    --navi-color-bg: #1a1a2e;
    --navi-color-font: #e0e0e0;
    /* ... etc. */
}

/* In BS4 template styles.css — opaque nav */
nav { background-color: var(--navi-color-bg); }

/* In BS5 template styles.css — transparent nav with backdrop blur */
nav { background-color: color-mix(in srgb, var(--navi-color-bg) 85%, transparent); }
```

**Important:** Because the CSS variables automatically switch values between light and dark mode (via `generateCSSVariables()`), template CSS should avoid hardcoded `[data-bs-theme="dark"]` color overrides for properties that already use `var()`. The variable system handles the mode switch. Only override dark mode for non-variable properties (e.g. toggler icon inversion, text color on elements without a color variable).

**Transparency technique (BS5):** BS5 templates use `color-mix(in srgb, var(--name) XX%, transparent)` to create semi-transparent versions of configured colors. This preserves both the user's color settings and the template's frosted glass design. The same `color-mix()` expression automatically uses dark mode colors when `[data-bs-theme="dark"]` is active.

For the hamburger toggler icon, use CSS `mask` instead of `background-image` with hardcoded SVG colors:

```css
nav .navbar-toggler-icon {
    background: none;
    background-color: var(--navi-color-font);
    -webkit-mask: url("data:image/svg+xml,...") no-repeat center / contain;
    mask: url("data:image/svg+xml,...") no-repeat center / contain;
}
```

This renders the SVG shape in `var(--navi-color-font)`, which automatically adapts to both light and dark mode.

Available CSS variables: `--navi-color-bg`, `--navi-color-font`, `--navi-color-hover-bg`, `--navi-color-hover-font`, `--navi-color-fontD9`, `--subhead-color-bg`, `--subhead-color-font`, `--article-color-bg`, `--article-color-h`, `--article-color-box`, `--article-color-h10`, `--footer-color-bg`, `--footer-color-box`, `--footer-color-font`.

**Note:** `--article-color-h10` and `--navi-color-fontD9` are derived alpha variants (appending hex alpha `1A` ≈ 10% and `D9` ≈ 85% respectively).

**Legacy:** `FrontendHelper::applySettingsToCSS()` still runs on all CSS for backward compatibility, so old-style placeholders (e.g. `navi_color_bg`) continue to work in external addons. All CSS within d2u_helper itself has been migrated to `var()` syntax.

#### Dark Mode

Dark mode is supported for Bootstrap 5 templates. It uses Bootstrap 5's built-in `data-bs-theme` attribute on `<html>`.

- **Settings:** Each color field has a light (☀) and dark (☽) picker via `BackendHelper::form_input_color_pair()`. Dark mode config keys use `dark_` prefix (e.g. `dark_navi_color_bg`).
- **Logo:** A separate dark mode logo can be configured via `template_logo_dark` in addon settings. When set, the BS5 nav fragment renders both logos wrapped in `.logo-light` / `.logo-dark` spans, toggled via `[data-bs-theme="dark"]` CSS rules.
- **CSS:** `generateCSSVariables()` outputs both `:root` (light) and `[data-bs-theme="dark"]` blocks. The same `var(--name)` references work in both modes.
- **Toggle:** A sun/moon toggle button in the BS5 navigation (`d2u_template_bs5_nav.php`) switches the theme. User preference is stored in `localStorage` under `d2u_theme`.
- **Auto-detection:** On first visit, `prefers-color-scheme: dark` is respected. An inline `<script>` in `<head>` applies the theme before render to prevent flash of wrong colors.
- **Template CSS:** Do NOT add hardcoded `[data-bs-theme="dark"]` overrides for colors — the CSS variable system handles mode switching automatically. See "CSS Color System" section above.

### Templates

Each template (`templates/XX-X/template.php`) follows this pattern:

1. **`<head>`**: Fragment `d2u_template_head.php` for meta/favicon/consent, template CSS, jQuery, Bootstrap CSS
2. **`<body>`**: Template-specific HTML with fragments for navigation, header, footer
3. **Before `</body>`**: Bootstrap JS, optional inline scripts (match-height), CTA box fragment

Templates include jQuery and Bootstrap directly:

```php
// In <head> - jQuery from Redaxo core + Bootstrap CSS from addon assets
$jquery_file = 'jquery.min.js';
echo '<script src="'. rex_url::coreAssets($jquery_file) .'?buster='. filemtime(rex_path::coreAssets($jquery_file)) .'"></script>';
echo '<link rel="stylesheet" type="text/css" href="'. rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap4/bootstrap.min.css') .'?v=4.6.2" />';

// Before </body> - Bootstrap JS bundle
<script src="<?= rex_addon::get('d2u_helper')->getAssetsUrl('bootstrap4/bootstrap.bundle.min.js') ?>?v=4.6.2"></script>
```

#### Template Versioning

Each template has a revision number defined in `lib/TemplateManager.php` inside the `getD2UHelperTemplates()` method. When a template is changed:

1. Add a changelog entry in `pages/help.changelog.php` describing the change.
2. Increment the template's revision number in `TemplateManager::getD2UHelperTemplates()` by one.

**Important:** The revision only needs to be incremented **once per release**, not per commit. To determine whether a release is still in development, check the changelog: if the version number is followed by `-DEV` (e.g. `2.1.0-DEV`), the release is still in development and no additional revision bump is needed for further changes to the same template. Once a version is released (no `-DEV` suffix), the next change requires a new revision increment.

### Modules

30 module variants organized in groups (00-15). Each module has:

- `input.php` — Backend input form
- `output.php` — Frontend output
- `style.css` — Optional module CSS (aggregated via `FrontendHelper::getModulesCSS()`)
- `js.js` — Optional module JS (aggregated via `FrontendHelper::getModulesJS()`)
- `install.php` / `uninstall.php` — Optional setup/teardown

Reserved module ID ranges for other D2U addons: 20-x (Address), 21-x (History), 22-x (Staff), 23-x (Jobs), 24-x (Linkbox), 25-x (Partner), 26-x (Courses).

#### Module Versioning

Each module has a revision number defined in `lib/ModuleManager.php` inside the `getModules()` method. When a module is changed:

1. Add a changelog entry in `pages/help.changelog.php` describing the change.
2. Increment the module's revision number in `ModuleManager::getModules()` by one.

**Important:** The revision only needs to be incremented **once per release**, not per commit. To determine whether a release is still in development, check the changelog: if the version number is followed by `-DEV` (e.g. `2.1.0-DEV`), the release is still in development and no additional revision bump is needed for further changes to the same module. Once a version is released (no `-DEV` suffix), the next change requires a new revision increment.

### Fragments

Reusable template parts in `fragments/`:

| Fragment | Purpose |
| -------- | ------- |
| `d2u_template_head.php` | `<head>` content: meta tags, favicon, consent manager, FontAwesome |
| `d2u_template_nav.php` | BS4 navigation menu (delegates to selected menu type: SmartMenu/MegaMenu/MultiLevel/SlickNav) |
| `d2u_template_bs5_nav.php` | BS5 navigation: logo container, search icon, nav with `include_menu`/`include_menu_show` settings, dark mode toggle. Delegates to `FrontendNavigationBS5` for menu items, language switcher and language modal. When `include_menu` is `megamenu`, uses `FrontendNavigationMegaMenu::getMenuItemsBS5()` for megamenu dropdown structure instead. |
| `d2u_template_header_slider.php` | Header image slider |
| `d2u_template_footer.php` | Footer (delegates to selected footer type) |
| `d2u_template_footer_*.php` | Footer variants (box, box_logo, links_address, etc.) |
| `d2u_template_cta_box.php` | Call-to-action box |
| `d2u_template_language_*.php` | BS4 language selector (icon + modal, uses `data-toggle`/`data-dismiss`) |
| `d2u_template_search_icon.php` | Search icon integration |

## Settings

Managed via `pages/settings.php` and stored in `rex_config`:

- **General:** Default language, WYSIWYG editor, privacy/impress article links
- **Navigation:** Menu type (none/bs5/megamenu/multilevel/slicknav/smartmenu), breakpoint
- **Templates:** Module CSS/JS inclusion, custom CSS, header/footer images and colors
- **Footer:** Footer type, colors, company info, social media links
- **Analytics:** Google Maps API key
- **Languages:** Sprog wildcard installation and language mapping

## Vendor Libraries

| Library | Version | License |
| ------- | ------- | ------- |
| Bootstrap | 4.6.2 | MIT |
| Bootstrap (Ekko) Lightbox | 5.4.0-rc2 | MIT |
| Leaflet | 1.7.1 | BSD |
| PhotoSphereViewer + Three.js | 5.1.4 | MIT |
| Responsive MultiLevelMenu | 1.0.1 | MIT |
| SlickNav | 1.0.10 | MIT |
| SmartMenus | 1.2.1 | MIT |

## Versioning

This addon follows [Semantic Versioning](https://semver.org/):

- **Major** (1st digit): Breaking changes (e.g. removed classes, renamed methods, incompatible DB changes)
- **Minor** (2nd digit): New features, new modules, new database fields (backward compatible)
- **Patch** (3rd digit): Bug fixes, small improvements (backward compatible)

The version number is maintained in `package.yml`. During development, the changelog in `pages/help.changelog.php` uses a `-DEV` suffix (e.g. `2.1.0-DEV`). The `-DEV` suffix is removed when the version is released. The `package.yml` always contains the final version number without `-DEV`.
