(function (global) {
    'use strict';

    function parse(value) {
        if (value === null || value === undefined) {
            return NaN;
        }

        const cleaned = String(value).replace(/,/g, '').trim();
        if (cleaned === '' || cleaned === '-') {
            return NaN;
        }

        return parseFloat(cleaned);
    }

    function decimalsFor(input) {
        if (input && input.dataset.decimals !== undefined) {
            const parsed = parseInt(input.dataset.decimals, 10);
            return isNaN(parsed) ? 2 : parsed;
        }

        return 2;
    }

    function format(value, decimals) {
        const num = typeof value === 'number' ? value : parse(value);
        if (isNaN(num)) {
            return '';
        }

        const places = decimals === undefined ? 2 : decimals;

        return num.toLocaleString('en-US', {
            minimumFractionDigits: places,
            maximumFractionDigits: places,
        });
    }

    function resolveInput(input) {
        if (typeof input === 'string') {
            return document.getElementById(input);
        }

        return input || null;
    }

    function setValue(input, value) {
        const el = resolveInput(input);
        if (!el) {
            return;
        }

        const num = typeof value === 'number' ? value : parse(value);
        if (isNaN(num)) {
            el.value = '';
            return;
        }

        el.value = format(num, decimalsFor(el));
    }

    function bind(input) {
        const el = resolveInput(input);
        if (!el || el.dataset.amountFormatBound === '1') {
            return el;
        }

        el.dataset.amountFormatBound = '1';

        if (el.type === 'number') {
            el.type = 'text';
        }

        if (!el.inputMode) {
            el.inputMode = 'decimal';
        }

        el.classList.add('format-amount');

        if (el.value !== '') {
            const num = parse(el.value);
            if (!isNaN(num)) {
                el.value = format(num, decimalsFor(el));
            }
        }

        el.addEventListener('focus', function () {
            const num = parse(el.value);
            if (!isNaN(num)) {
                el.value = String(num);
            }
        });

        el.addEventListener('blur', function () {
            const num = parse(el.value);
            if (!isNaN(num)) {
                el.value = format(num, decimalsFor(el));
            }
        });

        return el;
    }

    function bindAll(root) {
        const scope = root && root.querySelectorAll ? root : document;
        scope.querySelectorAll('.format-amount, [data-format-amount]').forEach(bind);
    }

    function prepareForms(root) {
        const scope = root && root.querySelectorAll ? root : document;
        scope.querySelectorAll('form').forEach(function (form) {
            if (form.dataset.amountFormatSubmit === '1') {
                return;
            }

            form.dataset.amountFormatSubmit = '1';
            form.addEventListener('submit', function () {
                form.querySelectorAll('.format-amount, [data-format-amount]').forEach(function (input) {
                    const num = parse(input.value);
                    input.value = isNaN(num) ? '' : String(num);
                });
            }, true);
        });
    }

    function init(root) {
        bindAll(root);
        prepareForms(root);
    }

    function read(input, fallback) {
        const el = resolveInput(input);
        if (!el) {
            return fallback === undefined ? 0 : fallback;
        }

        const num = parse(el.value);
        if (isNaN(num)) {
            return fallback === undefined ? 0 : fallback;
        }

        return num;
    }

    global.AmountFormat = {
        parse: parse,
        format: format,
        read: read,
        bind: bind,
        bindAll: bindAll,
        setValue: setValue,
        init: init,
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            init();
        });
    } else {
        init();
    }
})(window);
