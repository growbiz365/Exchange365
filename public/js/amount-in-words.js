(function (global) {
    'use strict';

    const ones = [
        '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
        'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen',
    ];
    const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

    function convertHundreds(n) {
        let result = '';

        if (n >= 100) {
            result += ones[Math.floor(n / 100)] + ' Hundred';
            n %= 100;
            if (n > 0) {
                result += ' ';
            }
        }

        if (n >= 20) {
            result += tens[Math.floor(n / 10)];
            n %= 10;
            if (n > 0) {
                result += ' ' + ones[n];
            }
        } else if (n > 0) {
            result += ones[n];
        }

        return result.trim();
    }

    function convertInteger(n) {
        if (n === 0) {
            return 'Zero';
        }

        const scales = [
            { value: 1000000000, name: 'Billion' },
            { value: 1000000, name: 'Million' },
            { value: 1000, name: 'Thousand' },
        ];

        let result = '';

        for (const scale of scales) {
            if (n >= scale.value) {
                const count = Math.floor(n / scale.value);
                result += (result ? ' ' : '') + convertHundreds(count) + ' ' + scale.name;
                n %= scale.value;
            }
        }

        if (n > 0) {
            result += (result ? ' ' : '') + convertHundreds(n);
        }

        return result.trim();
    }

    function convert(amount) {
        if (amount === '' || amount === null || amount === undefined) {
            return '';
        }

        const cleaned = String(amount).replace(/,/g, '').trim();
        if (cleaned === '' || cleaned === '-') {
            return '';
        }

        const num = parseFloat(cleaned);
        if (isNaN(num)) {
            return '';
        }

        if (num === 0) {
            return 'Zero Only';
        }

        const negative = num < 0;
        const abs = Math.abs(num);
        const parts = abs.toFixed(2).split('.');
        const integerPart = parseInt(parts[0], 10);
        const decimalPart = parseInt(parts[1], 10);

        let words = convertInteger(integerPart);

        if (decimalPart > 0) {
            words += ' and ' + convertInteger(decimalPart);
        }

        words += ' Only';

        if (negative) {
            words = 'Negative ' + words;
        }

        return words;
    }

    function updateOutput(input, output) {
        const text = convert(input.value);
        output.textContent = text ? text : '';
        output.classList.toggle('hidden', !text);
    }

    function bind(inputId) {
        const input = document.getElementById(inputId);
        if (!input) {
            return;
        }

        const output = document.getElementById(inputId + '_words');
        if (!output) {
            return;
        }

        const update = () => updateOutput(input, output);

        input.addEventListener('input', update);
        input.addEventListener('change', update);
        update();
    }

    function bindAll(inputIds) {
        inputIds.forEach(bind);
    }

    function update(inputId) {
        const input = document.getElementById(inputId);
        const output = document.getElementById(inputId + '_words');
        if (input && output) {
            updateOutput(input, output);
        }
    }

    global.AmountInWords = {
        convert,
        bind,
        bindAll,
        update,
    };
})(window);
