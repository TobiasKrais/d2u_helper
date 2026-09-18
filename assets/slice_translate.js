/**
 * d2u_helper in-editor slice translation button.
 *
 * On the content edit page it asks the backend which slices are translatable
 * (their module declares a `d2u_translate` marker) and injects a translate
 * button into each slice's option bar. Clicking it translates the slice's
 * declared fields from the base language into the current language.
 */
(function () {
    'use strict';

    function config() {
        return (window.rex && window.rex.d2u_helper_slice_translate) || null;
    }

    // Decide whether the backend runs in a dark theme. REDAXO signals its theme
    // differently across versions (html class, media query, be_style variant), so
    // the rendered background colour is read instead of guessing the mechanism.
    function backendIsDark() {
        var root = document.documentElement;
        if (root.classList.contains('rex-theme-dark')) {
            return true;
        }
        if (root.classList.contains('rex-theme-light')) {
            return false;
        }
        var el = document.querySelector('.rex-page-main') || document.body;
        while (el) {
            var rgb = (window.getComputedStyle(el).backgroundColor || '').match(/\d+(\.\d+)?/g);
            if (rgb && rgb.length >= 3 && !(rgb.length >= 4 && parseFloat(rgb[3]) === 0)) {
                var lum = 0.299 * rgb[0] + 0.587 * rgb[1] + 0.114 * rgb[2];
                return lum < 128;
            }
            el = el.parentElement;
        }
        return !!(window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
    }

    function init() {
        var cfg = config();
        if (!cfg) {
            return;
        }
        if (!document.querySelector('.rex-slice-output')) {
            return;
        }

        var params = new URLSearchParams(window.location.search);
        var articleId = parseInt(params.get('article_id') || '0', 10);
        var clang = parseInt(params.get('clang') || '0', 10);
        if (!articleId || !clang) {
            return;
        }

        fetch(cfg.url + '&action=fields&article_id=' + articleId + '&clang=' + clang, { credentials: 'same-origin' })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (!data || !data.success || !Array.isArray(data.slices)) {
                    return;
                }
                data.slices.forEach(function (id) { injectButton(id, clang, cfg); });
            })
            .catch(function () {});
    }

    function injectButton(sliceId, clang, cfg) {
        var li = document.getElementById('slice' + sliceId);
        if (!li) {
            return;
        }
        var options = li.querySelector('.rex-panel-options');
        if (!options || options.querySelector('.d2u-slice-translate')) {
            return;
        }

        var group = document.createElement('div');
        group.className = 'btn-group btn-group-xs d2u-slice-translate-group';

        var button = document.createElement('a');
        button.href = '#';
        button.className = 'btn btn-default d2u-slice-translate';
        button.title = cfg.label;
        button.innerHTML = '<i class="rex-icon fa-language"></i>';

        button.addEventListener('click', function (event) {
            event.preventDefault();
            if (button.classList.contains('disabled')) {
                return;
            }
            button.classList.add('disabled');
            button.title = cfg.translating;

            // Cover the slice with a loader while the translation runs; the reload
            // afterwards reveals the translated content.
            var li = button.closest('.rex-slice-output');
            var overlay = null;
            if (li) {
                if (!li.style.position) {
                    li.style.position = 'relative';
                }
                overlay = document.createElement('div');
                overlay.className = 'd2u-slice-translate-overlay';
                overlay.style.position = 'absolute';
                overlay.style.left = '0';
                overlay.style.top = '0';
                overlay.style.right = '0';
                overlay.style.bottom = '0';
                overlay.style.zIndex = '1000';
                overlay.style.display = 'flex';
                overlay.style.flexDirection = 'column';
                overlay.style.alignItems = 'center';
                overlay.style.justifyContent = 'center';
                overlay.style.gap = '10px';
                var dark = backendIsDark();
                overlay.style.background = dark ? 'rgba(30, 30, 30, 0.9)' : 'rgba(255, 255, 255, 0.9)';
                overlay.style.color = dark ? '#f0f0f0' : 'inherit';
                overlay.innerHTML = '<i class="rex-icon fa-spinner fa-spin" style="font-size:28px"></i>'
                    + '<span>' + (cfg.running || cfg.translating) + '</span>';
                li.appendChild(overlay);
            }

            var restore = function () {
                if (overlay) {
                    overlay.remove();
                }
                button.classList.remove('disabled');
                button.title = cfg.label;
            };

            var body = new URLSearchParams();
            body.set('action', 'translate');
            body.set('slice_id', String(sliceId));
            body.set('clang', String(clang));
            body.set('_csrf_token', cfg.csrf);

            fetch(cfg.url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body.toString()
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (data && data.success) {
                        window.location.reload();
                        return;
                    }
                    restore();
                    window.alert((data && data.message) || cfg.label);
                })
                .catch(function () {
                    restore();
                });
        });

        group.appendChild(button);
        options.appendChild(group);
    }

    if (window.jQuery) {
        // Re-run after REDAXO's pjax navigation re-renders the content page.
        window.jQuery(document).on('rex:ready', init);
    } else if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
