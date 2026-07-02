(function (global) {
    'use strict';

    function getElements(displayId, amountId) {
        return {
            display: document.getElementById(displayId),
            amount: document.getElementById(amountId),
        };
    }

    function loadBalance(bankId, displayId, amountId) {
        const { display, amount } = getElements(displayId, amountId);
        if (!display || !amount) {
            return;
        }

        if (!bankId) {
            display.classList.add('hidden');
            amount.textContent = '';
            delete amount.dataset.balance;
            return;
        }

        window.fetch('/banks/' + bankId + '/balance')
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.balance !== undefined) {
                    const balance = parseFloat(data.balance);
                    amount.textContent = balance.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    });
                    amount.className = 'ml-1 font-semibold ' + (balance >= 0 ? 'text-green-600' : 'text-red-600');
                    amount.dataset.balance = data.balance;
                    display.classList.remove('hidden');
                } else {
                    display.classList.add('hidden');
                    amount.textContent = '';
                    delete amount.dataset.balance;
                }
            })
            .catch(function () {
                display.classList.add('hidden');
                amount.textContent = '';
                delete amount.dataset.balance;
            });
    }

    function bind(selectId, displayId, amountId, options) {
        options = options || {};
        const select = document.getElementById(selectId);
        const { display, amount } = getElements(displayId, amountId);

        if (!select || !display || !amount) {
            return;
        }

        function update() {
            if (typeof options.shouldFetch === 'function' && !options.shouldFetch(select.value)) {
                display.classList.add('hidden');
                amount.textContent = '';
                delete amount.dataset.balance;
                return;
            }

            loadBalance(select.value, displayId, amountId);

            if (typeof options.onChange === 'function') {
                options.onChange(select.value);
            }
        }

        select.addEventListener('change', update);

        if (typeof jQuery !== 'undefined') {
            jQuery('#' + selectId).on('change', update);
        }

        update();
    }

    global.BankBalance = {
        fetch: loadBalance,
        bind: bind,
    };
})(window);
