/**
 * D2U Helper - in-page (AJAX) actions for the "Redaxo Kategorien & SEO" tab.
 *
 * Turns the per-row action buttons (translate / sync / align status / align
 * image) and the bulk buttons (translate / sync / align) into AJAX calls to the
 * rex-api endpoint "d2u_helper_seo_translate". A successful action refreshes the
 * affected row's cells in place instead of reloading the whole page. The buttons
 * stay real submit buttons, so the form still works without JavaScript.
 *
 * Bulk actions run sequentially so the cells update one row after another;
 * clicking the running bulk button again cancels the queue after the current row.
 */
(function () {
    'use strict';

    var form = document.getElementById('d2u-seo-table');
    if (!form || '1' !== form.getAttribute('data-seo-ajax')) {
        return;
    }

    var config = {
        url: 'index.php?rex-api-call=d2u_helper_seo_translate',
        targetClang: form.getAttribute('data-target-clang') || '',
        csrfName: form.getAttribute('data-seo-csrf-name') || 'rex_csrf_token',
        csrfValue: form.getAttribute('data-seo-csrf') || '',
        msgWorking: form.getAttribute('data-msg-working') || 'Processing …',
        msgError: form.getAttribute('data-msg-error') || 'Error',
        msgNone: form.getAttribute('data-msg-none') || 'Nothing selected'
    };

    var feedback = document.getElementById('d2u-seo-feedback');
    var busy = false;
    var bulk = { running: false, cancel: false, button: null, buttonHtml: '' };

    function setFeedback(html, cssType) {
        if (!feedback) {
            return;
        }
        feedback.className = 'alert alert-' + (cssType || 'info');
        feedback.style.display = '';
        feedback.innerHTML = html;
    }

    function rowById(id) {
        return form.querySelector('tbody tr[data-seo-id="' + id + '"]');
    }

    function updateRow(id, data) {
        var tr = rowById(id);
        if (!tr || !data || !data.cells) {
            return;
        }
        Object.keys(data.cells).forEach(function (key) {
            var cell = tr.querySelector('[data-seo-cell="' + key + '"]');
            if (cell) {
                cell.innerHTML = data.cells[key];
            }
        });
    }

    function call(articleId, action) {
        var body = new URLSearchParams();
        body.append('article_id', articleId);
        body.append('seo_action', action);
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
            return response.text().then(function (text) {
                var data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    data = { success: false, message: config.msgError, debug: 'Non-JSON response (HTTP ' + response.status + '): ' + text.slice(0, 2000) };
                }
                data._httpStatus = response.status;
                data._raw = text.slice(0, 4000);
                logDebug(articleId, action, data);
                return data;
            });
        }).catch(function (err) {
            var data = { success: false, message: config.msgError, debug: 'Fetch failed: ' + (err && err.message ? err.message : String(err)) };
            logDebug(articleId, action, data);
            return data;
        });
    }

    // Detailed console output so a failing translation can be traced to its cause
    // (the backend puts the real exception/raw AI response into data.debug).
    function logDebug(articleId, action, data) {
        var failed = !(data && data.success);
        var label = 'd2u_helper SEO ' + action + ' #' + articleId + (failed ? ' \u2717 FAILED' : ' \u2713 ok');
        /* eslint-disable no-console */
        if (console && console.groupCollapsed) {
            (failed ? console.group : console.groupCollapsed).call(console, label);
            console.log('endpoint:', config.url);
            console.log('article_id:', articleId, 'action:', action, 'target_clang:', config.targetClang);
            console.log('http status:', data && data._httpStatus);
            console.log('success:', data && data.success);
            console.log('message:', data && data.message);
            if (data && data.debug) { console.error('debug:', data.debug); }
            if (data && data._raw) { console.log('raw response:', data._raw); }
            console.groupEnd();
        } else if (console && console.log) {
            console.log(label, data);
        }
        /* eslint-enable no-console */
    }

    function handleSingle(button) {
        if (busy) {
            return;
        }
        var id = parseInt(button.getAttribute('data-seo-id'), 10);
        var action = button.getAttribute('data-seo-action') || '';
        if (!(id > 0) || !action) {
            return;
        }

        busy = true;
        var original = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="rex-icon fa-spinner fa-spin"></i>';
        setFeedback('<i class="rex-icon fa-spinner fa-spin"></i> ' + config.msgWorking, 'info');

        call(id, action).then(function (data) {
            busy = false;
            button.disabled = false;
            button.innerHTML = original;
            if (data && data.success) {
                updateRow(id, data);
                setFeedback('<i class="rex-icon fa-check text-success"></i> ' + (data.name ? data.name : ''), 'success');
            } else {
                var errMsg = (data && data.message) ? data.message : config.msgError;
                if (data && data.debug) {
                    errMsg += ' <small class="text-muted d2u-seo-debug">' + String(data.debug).replace(/[<>&]/g, function (c) { return { '<': '&lt;', '>': '&gt;', '&': '&amp;' }[c]; }) + '</small>';
                }
                setFeedback('<i class="rex-icon fa-exclamation-triangle text-danger"></i> ' + errMsg, 'danger');
            }
        });
    }

    function handleBulk(action, button) {
        // A click on the running bulk button cancels the queue after the current row.
        if (bulk.running) {
            bulk.cancel = true;
            return;
        }
        if (busy) {
            return;
        }
        var ids = Array.prototype.slice.call(form.querySelectorAll('.d2u-seo-check'))
            .filter(function (c) { return c.checked && !(c.closest('tr') && c.closest('tr').hidden); })
            .map(function (c) { return parseInt(c.value, 10); })
            .filter(function (v) { return v > 0; });

        if (0 === ids.length) {
            setFeedback('<i class="rex-icon fa-info-circle"></i> ' + config.msgNone, 'warning');
            return;
        }

        busy = true;
        bulk.running = true;
        bulk.cancel = false;
        bulk.button = button || null;
        bulk.buttonHtml = button ? button.innerHTML : '';

        var index = 0;
        var ok = 0;
        var fail = 0;

        function finish() {
            busy = false;
            bulk.running = false;
            if (bulk.button) {
                bulk.button.innerHTML = bulk.buttonHtml;
            }
            bulk.button = null;
            setFeedback('<i class="rex-icon fa-check text-success"></i> ' + ok + ' / ' + ids.length + (fail ? ' (' + fail + ' ' + config.msgError + ')' : ''), fail ? 'warning' : 'success');
        }

        function next() {
            if (bulk.cancel || index >= ids.length) {
                finish();
                return;
            }
            var id = ids[index++];
            setFeedback('<i class="rex-icon fa-spinner fa-spin"></i> ' + config.msgWorking + ' (' + index + '/' + ids.length + ')', 'info');
            call(id, action).then(function (data) {
                if (data && data.success) {
                    ok++;
                    updateRow(id, data);
                } else {
                    fail++;
                }
                next();
            });
        }

        next();
    }

    form.addEventListener('click', function (event) {
        var single = event.target.closest('.d2u-seo-ajax');
        if (single && form.contains(single)) {
            event.preventDefault();
            handleSingle(single);
            return;
        }
        var bulkBtn = event.target.closest('[data-seo-bulk]');
        if (bulkBtn && form.contains(bulkBtn)) {
            event.preventDefault();
            handleBulk(bulkBtn.getAttribute('data-seo-bulk'), bulkBtn);
        }
    });
})();
