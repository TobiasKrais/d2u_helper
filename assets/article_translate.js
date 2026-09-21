/**
 * D2U Helper - in-page (AJAX) translation for the "Redaxo Artikel Inhalte" tab.
 *
 * Turns the per-article action buttons and the bulk buttons into AJAX calls to
 * the rex-api endpoint "d2u_helper_article_translate", so a translation updates
 * the affected row in place instead of reloading the whole page. The buttons
 * stay real submit buttons, so without JavaScript the form still works.
 *
 * Bulk actions run sequentially (one article after another, so the green checks
 * appear one by one); clicking the running bulk button again cancels the queue
 * after the current article.
 */
(function () {
    'use strict';

    var config = null;
    var form = null;
    var feedback = null;
    var busy = false;
    var bulk = { running: false, cancel: false, button: null, buttonHtml: '' };

    var ACTED_CELL = { all: '.d2u-cell-nocontent', missing: '.d2u-cell-missing', stale: '.d2u-cell-stale' };

    function getConfig() {
        var el = document.getElementById('d2u-article-translate-config');
        if (!el) {
            return null;
        }
        var tokenField = el.querySelector('input[type="hidden"]');
        return {
            url: el.getAttribute('data-url') || 'index.php?rex-api-call=d2u_helper_article_translate',
            targetClang: el.getAttribute('data-target-clang') || '',
            csrfName: tokenField ? tokenField.getAttribute('name') : '_csrf_token',
            csrfValue: tokenField ? tokenField.value : '',
            msgTranslating: el.getAttribute('data-msg-translating') || 'Translating...',
            msgError: el.getAttribute('data-msg-error') || 'Error',
            msgDone: el.getAttribute('data-msg-done') || 'Done',
            msgBulkNone: el.getAttribute('data-msg-bulk-none') || 'No articles selected',
            msgCancel: el.getAttribute('data-msg-cancel') || 'Cancel',
            msgCancelled: el.getAttribute('data-msg-cancelled') || 'Cancelled'
        };
    }

    function setFeedback(html, cssType) {
        if (!feedback) {
            return;
        }
        feedback.className = 'alert alert-' + (cssType || 'info');
        feedback.style.display = '';
        feedback.innerHTML = html;
    }

    function rowById(id) {
        return form.querySelector('tbody tr[data-article-id="' + id + '"]');
    }

    function updateRow(tr, data, mode) {
        if (!tr || !data || !data.cells) {
            return;
        }
        var icon = tr.querySelector('.d2u-status-icon');
        if (icon) {
            icon.innerHTML = data.cells.icon || '';
        }
        var map = { '.d2u-cell-nocontent': data.cells.nocontent, '.d2u-cell-missing': data.cells.missing, '.d2u-cell-stale': data.cells.stale };
        Object.keys(map).forEach(function (sel) {
            var cell = tr.querySelector(sel);
            if (cell) {
                cell.innerHTML = map[sel] || '';
            }
        });
        // The cell whose button was just used shows a green check (the button is
        // gone) instead of a muted dash, so the success is clearly visible.
        var actedSel = ACTED_CELL[mode];
        if (actedSel) {
            var actedCell = tr.querySelector(actedSel);
            if (actedCell) {
                actedCell.innerHTML = '<i class="rex-icon fa-check text-success" title="' + config.msgDone + '"></i>';
            }
        }
    }

    function translate(articleId, mode) {
        var body = new URLSearchParams();
        body.append('article_id', articleId);
        body.append('mode', mode);
        body.append('target_clang', config.targetClang);
        body.append(config.csrfName, config.csrfValue);

        return fetch(config.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: body.toString()
        }).then(function (response) {
            return response.json().catch(function () {
                return { success: false, message: config.msgError };
            });
        }).catch(function () {
            return { success: false, message: config.msgError };
        });
    }

    function handleSingle(button) {
        if (busy) {
            return;
        }
        var value = button.getAttribute('data-d2u-article-action') || '';
        var parts = value.split(':');
        var id = parseInt(parts[0], 10);
        var mode = parts[1] || 'all';
        if (!(id > 0)) {
            return;
        }

        busy = true;
        var original = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="rex-icon fa-spinner fa-spin"></i>';
        setFeedback('<i class="rex-icon fa-spinner fa-spin"></i> ' + config.msgTranslating, 'info');

        translate(id, mode).then(function (data) {
            busy = false;
            if (data && data.success) {
                updateRow(rowById(id), data, mode);
                setFeedback('<i class="rex-icon fa-check text-success"></i> ' + (data.name ? data.name + ': ' : '') + config.msgDone, 'success');
            } else {
                button.disabled = false;
                button.innerHTML = original;
                setFeedback('<i class="rex-icon fa-exclamation-triangle text-danger"></i> ' + ((data && data.message) ? data.message : config.msgError), 'danger');
            }
        });
    }

    function handleBulk(mode, button) {
        // A click on the running bulk button cancels the queue after the current item.
        if (bulk.running) {
            bulk.cancel = true;
            return;
        }
        if (busy) {
            return;
        }
        var ids = Array.prototype.slice.call(form.querySelectorAll('.d2u-row-check'))
            .filter(function (c) { return c.checked && !(c.closest('tr') && c.closest('tr').hidden); })
            .map(function (c) { return parseInt(c.value, 10); })
            .filter(function (v) { return v > 0; });

        if (0 === ids.length) {
            setFeedback('<i class="rex-icon fa-info-circle"></i> ' + config.msgBulkNone, 'warning');
            return;
        }

        busy = true;
        bulk.running = true;
        bulk.cancel = false;
        bulk.button = button || null;
        bulk.buttonHtml = button ? button.innerHTML : '';
        if (button) {
            button.innerHTML = '<i class="rex-icon fa-times"></i> ' + config.msgCancel;
        }

        var index = 0;
        var ok = 0;
        var fail = 0;

        function finish(cancelled) {
            busy = false;
            bulk.running = false;
            if (bulk.button) {
                bulk.button.innerHTML = bulk.buttonHtml;
            }
            bulk.button = null;
            if (cancelled) {
                setFeedback('<i class="rex-icon fa-ban text-warning"></i> ' + config.msgCancelled + ' (' + ok + ' / ' + ids.length + ')', 'warning');
            } else {
                setFeedback('<i class="rex-icon fa-check text-success"></i> ' + ok + ' / ' + ids.length + (fail ? ' (' + fail + ' ' + config.msgError + ')' : ''), fail ? 'warning' : 'success');
            }
        }

        function next() {
            if (bulk.cancel) {
                finish(true);
                return;
            }
            if (index >= ids.length) {
                finish(false);
                return;
            }
            var id = ids[index++];
            setFeedback('<i class="rex-icon fa-spinner fa-spin"></i> ' + config.msgTranslating + ' (' + index + '/' + ids.length + ')', 'info');
            translate(id, mode).then(function (data) {
                if (data && data.success) {
                    ok++;
                    updateRow(rowById(id), data, mode);
                    var tr = rowById(id);
                    var chk = tr ? tr.querySelector('.d2u-row-check') : null;
                    if (chk) {
                        chk.checked = false;
                    }
                } else {
                    fail++;
                }
                next();
            });
        }

        next();
    }

    function init() {
        config = getConfig();
        form = document.getElementById('d2u-article-table');
        if (!config || !form) {
            return;
        }
        feedback = document.getElementById('d2u-article-feedback');

        form.addEventListener('click', function (event) {
            var actionButton = event.target.closest ? event.target.closest('[data-d2u-article-action]') : null;
            if (actionButton) {
                event.preventDefault();
                handleSingle(actionButton);
                return;
            }
            var bulkButton = event.target.closest ? event.target.closest('[data-d2u-bulk]') : null;
            if (bulkButton) {
                event.preventDefault();
                handleBulk(bulkButton.getAttribute('data-d2u-bulk'), bulkButton);
            }
        });
    }

    if (window.jQuery) {
        window.jQuery(document).on('rex:ready', init);
    } else {
        document.addEventListener('DOMContentLoaded', init);
    }
})();
