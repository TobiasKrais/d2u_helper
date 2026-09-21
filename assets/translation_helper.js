/**
 * D2U Helper - AI translation for the translation helper page.
 *
 * Reads its configuration from #d2u-translation-config (data attributes) and
 * lets the user translate a single listed object or all of them at once via the
 * rex-api endpoint "d2u_helper_translate", which fires the
 * D2U_HELPER_TRANSLATE_OBJECT extension point.
 */
(function () {
    'use strict';

    function getConfig() {
        var el = document.getElementById('d2u-translation-config');
        if (!el) {
            return null;
        }
        var tokenField = el.querySelector('input[type="hidden"]');
        return {
            url: el.getAttribute('data-url') || 'index.php?rex-api-call=d2u_helper_translate',
            sourceClangId: el.getAttribute('data-source-clang-id') || '',
            targetClangId: el.getAttribute('data-target-clang-id') || '',
            csrfName: tokenField ? tokenField.getAttribute('name') : '_csrf_token',
            csrfValue: tokenField ? tokenField.value : '',
            msgTranslating: el.getAttribute('data-msg-translating') || 'Translating...',
            msgError: el.getAttribute('data-msg-error') || 'Error'
        };
    }

    function setStatus(item, html, cssClass) {
        var status = item.querySelector('.d2u-translate-status');
        if (!status) {
            return;
        }
        status.className = 'd2u-translate-status' + (cssClass ? ' ' + cssClass : '');
        status.innerHTML = html;
    }

    function translateItem(item, config) {
        return new Promise(function (resolve) {
            var addon = item.getAttribute('data-addon');
            var type = item.getAttribute('data-type');
            var id = item.getAttribute('data-id');
            if (!addon || !type || !id) {
                resolve(false);
                return;
            }

            setStatus(item, '<i class="rex-icon fa-spinner fa-spin"></i> ' + config.msgTranslating, '');

            var body = new URLSearchParams();
            body.append('addon', addon);
            body.append('type', type);
            body.append('id', id);
            body.append('source_clang_id', config.sourceClangId);
            body.append('target_clang_id', config.targetClangId);
            body.append(config.csrfName, config.csrfValue);

            fetch(config.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: body.toString()
            })
                .then(function (response) {
                    return response.json().catch(function () {
                        return { success: false, message: config.msgError };
                    });
                })
                .then(function (data) {
                    if (data && data.success) {
                        item.classList.add('d2u-translation-done');
                        var link = item.querySelector('a:first-child');
                        if (link && data.name) {
                            link.textContent = data.name;
                        }
                        var trigger = item.querySelector('.d2u-translate-trigger');
                        if (trigger) {
                            trigger.style.display = 'none';
                        }
                        setStatus(item, '<i class="rex-icon fa-check text-success"></i>', 'text-success');
                        resolve(true);
                    } else {
                        var message = (data && data.message) ? data.message : config.msgError;
                        setStatus(item, '<i class="rex-icon fa-exclamation-triangle text-danger"></i> ' + message, 'text-danger');
                        resolve(false);
                    }
                })
                .catch(function () {
                    setStatus(item, '<i class="rex-icon fa-exclamation-triangle text-danger"></i> ' + config.msgError, 'text-danger');
                    resolve(false);
                });
        });
    }

    function translateAllSequential(items, config, button) {
        var index = 0;
        button.disabled = true;

        function next() {
            if (index >= items.length) {
                button.disabled = false;
                return;
            }
            var item = items[index++];
            // Skip already translated items.
            if (item.classList.contains('d2u-translation-done')) {
                next();
                return;
            }
            translateItem(item, config).then(next);
        }

        next();
    }

    function init() {
        var config = getConfig();
        if (!config) {
            return;
        }

        document.addEventListener('click', function (event) {
            var trigger = event.target.closest ? event.target.closest('.d2u-translate-trigger') : null;
            if (!trigger) {
                return;
            }
            event.preventDefault();
            var item = trigger.closest('.d2u-translation-item');
            if (item) {
                translateItem(item, config);
            }
        });

        var allButton = document.getElementById('d2u-translate-all');
        if (allButton) {
            allButton.addEventListener('click', function (event) {
                event.preventDefault();
                var items = Array.prototype.slice.call(document.querySelectorAll('.d2u-translation-item'));
                if (items.length > 0) {
                    translateAllSequential(items, config, allButton);
                }
            });
        }

        // Per-category ("Kategorie") translate button: translates only the items
        // inside the same scope as the clicked button — a table row
        // (.d2u-translate-category-scope) or, in older markup, a <fieldset>. Uses
        // the capture phase + stopPropagation so the click does NOT bubble to a
        // <legend> toggle (which would otherwise just collapse/expand the category).
        document.addEventListener('click', function (event) {
            var catButton = event.target.closest ? event.target.closest('.d2u-translate-category') : null;
            if (!catButton) {
                return;
            }
            event.preventDefault();
            event.stopPropagation();
            var scope = catButton.closest('.d2u-translate-category-scope') || catButton.closest('fieldset');
            if (!scope) {
                return;
            }
            var items = Array.prototype.slice.call(scope.querySelectorAll('.d2u-translation-item'));
            if (items.length > 0) {
                translateAllSequential(items, config, catButton);
            }
        }, true);
    }

    // The script tag is emitted inside the page body, so the DOM may already be
    // parsed (readyState !== 'loading') by the time this runs; guard accordingly.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
