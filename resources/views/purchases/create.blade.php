<x-app-layout>
    @section('title', 'New Purchase - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('purchases.index'), 'label' => 'Purchase'],
        ['url' => '#', 'label' => 'Create']
    ]" />

    @php
        $rawOldDate = old('date_added');
        $purchaseCreateDisplayDate = $rawOldDate ?: date('d/m/Y');
        if ($rawOldDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawOldDate)) {
            try { $purchaseCreateDisplayDate = \Carbon\Carbon::createFromFormat('Y-m-d', $rawOldDate)->format('d/m/Y'); } catch (\Throwable $e) {}
        }
    @endphp

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 mt-4">

        <div class="flex items-center justify-between px-4 sm:px-6 py-2.5 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="bg-gradient-to-br from-indigo-600 to-slate-700 p-1.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900 leading-tight">New Purchase</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Record a purchase voucher</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('purchases.store') }}" id="purchaseForm" class="px-4 sm:px-6 py-2 pb-3 space-y-1.5">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-3 py-2 rounded text-sm" role="alert">
                    <strong class="font-semibold">Errors:</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-3 py-2 rounded text-sm" role="alert">
                    <span class="text-xs">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Top Row: Date, Details, Operation, Rate --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-1.5 pb-1.5 border-b border-gray-100">
                <div>
                    <label for="date_added" class="block text-xs font-semibold text-red-600 mb-0.5">Date <span>*</span></label>
                    <input type="text" id="date_added" name="date_added" value="{{ $purchaseCreateDisplayDate }}" required readonly
                        class="block w-full rounded border-gray-300 text-sm bg-white cursor-pointer focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="dd/mm/yyyy" />
                    <x-input-error :messages="$errors->get('date_added')" class="mt-0.5" />
                </div>
                <div>
                    <label for="details" class="block text-xs font-semibold text-gray-700 mb-0.5">Details</label>
                    <input type="text" id="details" name="details" value="{{ old('details') }}"
                        class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="" />
                    <x-input-error :messages="$errors->get('details')" class="mt-0.5" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-red-600 mb-0.5">Operation <span>*</span></label>
                    <div class="flex items-center gap-4 h-9">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="transaction_operation" value="1"
                                {{ old('transaction_operation', '2') == '1' ? 'checked' : '' }} required
                                class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-sm font-medium text-gray-700">Divide (÷)</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="transaction_operation" value="2"
                                {{ old('transaction_operation', '2') == '2' ? 'checked' : '' }}
                                class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-sm font-medium text-gray-700">Multiply (×)</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('transaction_operation')" class="mt-0.5" />
                </div>
                <div>
                    <label for="rate" class="block text-xs font-semibold text-red-600 mb-0.5">Rate <span>*</span></label>
                    <input type="number" id="rate" name="rate" step="any" min="0.0001" value="{{ old('rate', '1') }}" required inputmode="decimal"
                        class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="1" />
                    <x-input-error :messages="$errors->get('rate')" class="mt-0.5" />
                </div>
            </div>

            {{-- Deposit & Credit Party Tables --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                <table class="w-full border border-gray-300 text-xs rounded">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center bg-white text-black-800 font-bold py-1.5 px-3 border-b border-gray-300 tracking-wide rounded-t">
                                Deposit ( جمع )
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-1.5 px-3 font-semibold text-red-600 bg-gray-50 w-2/5 align-middle">
                                Deposit Bank <span class="text-red-600">*</span>
                            </td>
                            <td class="py-1.5 px-3">
                                <select id="bank_id" name="bank_id" required
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->bank_id }}" {{ old('bank_id') == $bank->bank_id ? 'selected' : '' }}>
                                            {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="bank_balance_display" class="mt-0.5 text-xs text-gray-700 hidden">
                                    <span class="font-medium">Balance:</span>
                                    <span id="bank_balance_amount" class="ml-1"></span>
                                </div>
                                <x-input-error :messages="$errors->get('bank_id')" class="mt-0.5" />
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1.5 px-3 font-semibold text-red-600 bg-gray-50 align-middle">
                                Deposit Amount <span class="text-red-600">*</span>
                            </td>
                            <td class="py-1.5 px-3">
                                <input type="number" id="credit_amount" name="credit_amount" step="any" value="{{ old('credit_amount') }}" required
                                    class="format-amount block w-full rounded border-gray-300 text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500"
                                    placeholder="0.00" />
                                <x-input-error :messages="$errors->get('credit_amount')" class="mt-0.5" />
                                <x-amount-words for="credit_amount" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="w-full border border-gray-300 text-xs rounded">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center bg-white text-black-800 font-bold py-1.5 px-3 border-b border-gray-300 tracking-wide rounded-t">
                                Credit ( جمع ) Party
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-1.5 px-3 font-semibold text-red-600 bg-gray-50 w-2/5 align-middle">
                                Credit Party <span class="text-red-600">*</span>
                            </td>
                            <td class="py-1.5 px-3">
                                <select id="party_id" name="party_id" required
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Party</option>
                                    @foreach($parties as $party)
                                        <option value="{{ $party->party_id }}" {{ old('party_id') == $party->party_id ? 'selected' : '' }}>
                                            {{ $party->party_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('party_id')" class="mt-0.5" />
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1.5 px-3 font-semibold text-red-600 bg-gray-50 align-middle">
                                Party Currency <span class="text-red-600">*</span>
                            </td>
                            <td class="py-1.5 px-3">
                                <select id="party_currency_id" name="party_currency_id" required
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($currencies as $c)
                                        <option value="{{ $c->currency_id }}" {{ old('party_currency_id', '1') == $c->currency_id ? 'selected' : '' }}>
                                            {{ $c->currency }} ({{ $c->currency_symbol ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="party_balance_display" class="mt-0.5 text-xs text-gray-700 hidden">
                                    <span class="font-medium">Balance:</span>
                                    <span id="party_balance_amount" class="ml-1"></span>
                                </div>
                                <x-input-error :messages="$errors->get('party_currency_id')" class="mt-0.5" />
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1.5 px-3 font-semibold text-red-600 bg-gray-50 align-middle">
                                Amount <span class="text-red-600">*</span>
                            </td>
                            <td class="py-1.5 px-3">
                                <input type="number" id="debit_amount" name="debit_amount" step="any" value="{{ old('debit_amount') }}" required
                                    class="format-amount block w-full rounded border-gray-300 text-sm font-semibold focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="0.00" />
                                <p class="mt-0.5 text-xs text-gray-500">Auto from Deposit &amp; Rate. Editable.</p>
                                <x-input-error :messages="$errors->get('debit_amount')" class="mt-0.5" />
                                <x-amount-words for="debit_amount" />
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:flex-wrap sm:items-center sm:justify-end gap-2 pt-1.5 border-t border-gray-100">
                <a href="{{ route('purchases.index') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 sm:py-1.5 bg-red-500 rounded text-xs font-semibold text-white hover:bg-red-600 w-full sm:w-auto">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                    class="inline-flex items-center justify-center px-5 py-2.5 sm:py-1.5 bg-indigo-600 rounded text-xs font-semibold text-white hover:bg-indigo-700 w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save
                </button>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        .chosen-container { width: 100% !important; }
        .chosen-container-single .chosen-single {
            height: 30px; line-height: 28px; padding: 0 8px;
            border: 1px solid #d1d5db; border-radius: 6px; font-size: 12px;
            background: #fff; font-family: inherit;
        }
        .chosen-container-single .chosen-single span { margin-right: 0.5rem; }
        .chosen-container-single .chosen-single div { right: 8px; }
        .chosen-container-active.chosen-with-drop .chosen-single { border-radius: 6px 6px 0 0; }
        .chosen-drop { border: 1px solid #d1d5db; border-radius: 0 0 6px 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .chosen-results { font-size: 12px; }
        .chosen-results li.highlighted { background: #2563eb; color: white; }
        .chosen-container .chosen-drop { z-index: 9999 !important; }
        #date_added.flatpickr-input, #date_added {
            width: 100%; max-width: none; display: block; box-sizing: border-box;
            height: 36px; padding-top: 4px; padding-bottom: 4px; font-size: 0.875rem;
        }
        .flatpickr-calendar { width: 307px !important; font-size: 0.8125rem; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15); }
        .flatpickr-calendar .flatpickr-days, .flatpickr-calendar .dayContainer { width: 100% !important; min-width: 100% !important; max-width: 100% !important; }
        .flatpickr-day { flex-basis: 14.2857143% !important; max-width: 39px !important; height: 39px; line-height: 39px; border-radius: 9999px; }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange { background: #4f46e5; border-color: #4f46e5; color: #fff; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('purchaseForm');
            var bankSelect = document.getElementById('bank_id');
            var partySelect = document.getElementById('party_id');
            var currencySelect = document.getElementById('party_currency_id');
            var creditAmountInput = document.getElementById('credit_amount');
            var rateInput = document.getElementById('rate');
            var debitAmountInput = document.getElementById('debit_amount');
            var opMultiply = document.querySelector('input[name="transaction_operation"][value="2"]');
            var opDivide = document.querySelector('input[name="transaction_operation"][value="1"]');
            var suppressAmountRecalc = false;

            if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
                jQuery('.chosen-select').chosen({ width: '100%', search_contains: true, allow_single_deselect: true, placeholder_text_single: 'Select an option' });
            }
            flatpickr('#date_added', { dateFormat: 'd/m/Y', allowInput: false, disableMobile: true });

            function fetchBankBalance(bankId) {
                var div = document.getElementById('bank_balance_display');
                var span = document.getElementById('bank_balance_amount');
                if (!bankId) { div.classList.add('hidden'); return; }
                fetch('/banks/' + bankId + '/balance').then(function(r) { return r.json(); }).then(function(data) {
                    if (data.balance !== undefined) {
                        var bal = parseFloat(data.balance);
                        span.textContent = bal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        span.className = 'ml-1 font-semibold ' + (bal >= 0 ? 'text-green-600' : 'text-red-600');
                        div.classList.remove('hidden');
                    } else { div.classList.add('hidden'); }
                }).catch(function() { div.classList.add('hidden'); });
            }

            function fetchPartyBalance(partyId, currencyId) {
                var div = document.getElementById('party_balance_display');
                var span = document.getElementById('party_balance_amount');
                if (!partyId || !currencyId) { div.classList.add('hidden'); return; }
                fetch('/parties/' + partyId + '/balance?currency_id=' + currencyId).then(function(r) { return r.json(); }).then(function(data) {
                    if (data.balance !== undefined) {
                        var bal = parseFloat(data.balance);
                        span.textContent = bal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        span.className = 'ml-1 font-semibold ' + (bal >= 0 ? 'text-green-600' : 'text-red-600');
                        div.classList.remove('hidden');
                    } else { div.classList.add('hidden'); }
                }).catch(function() { div.classList.add('hidden'); });
            }

            function calculateDebitAmount() {
                if (suppressAmountRecalc) return;
                var credit = AmountFormat.read(creditAmountInput);
                var rate = parseFloat(rateInput.value) || 1;
                if (rate <= 0) rate = 1;
                var op = opMultiply && opMultiply.checked ? 2 : 1;
                var debit = op === 2 ? credit * rate : (rate !== 0 ? credit / rate : 0);
                suppressAmountRecalc = true;
                AmountFormat.setValue(debitAmountInput, isNaN(debit) ? '' : Math.round(debit * 100) / 100);
                if (window.AmountInWords) AmountInWords.update('debit_amount');
                suppressAmountRecalc = false;
            }

            function calculateCreditAmountFromDebit() {
                if (suppressAmountRecalc) return;
                var debit = AmountFormat.read(debitAmountInput);
                var rate = parseFloat(rateInput.value) || 1;
                if (rate <= 0) rate = 1;
                var op = opMultiply && opMultiply.checked ? 2 : 1;
                var credit = op === 2 ? (rate !== 0 ? debit / rate : 0) : debit * rate;
                suppressAmountRecalc = true;
                AmountFormat.setValue(creditAmountInput, isNaN(credit) ? '' : Math.round(credit * 100) / 100);
                if (window.AmountInWords) AmountInWords.update('credit_amount');
                suppressAmountRecalc = false;
            }

            bankSelect.addEventListener('change', function() { fetchBankBalance(this.value); });
            if (bankSelect.value) fetchBankBalance(bankSelect.value);
            function updatePartyBalance() { fetchPartyBalance(partySelect.value, currencySelect.value); }
            partySelect.addEventListener('change', updatePartyBalance);
            currencySelect.addEventListener('change', updatePartyBalance);
            if (partySelect.value && currencySelect.value) updatePartyBalance();

            creditAmountInput.addEventListener('input', calculateDebitAmount);
            debitAmountInput.addEventListener('input', calculateCreditAmountFromDebit);
            rateInput.addEventListener('input', calculateDebitAmount);
            if (opMultiply) opMultiply.addEventListener('change', calculateDebitAmount);
            if (opDivide) opDivide.addEventListener('change', calculateDebitAmount);

            form.addEventListener('submit', function() {
                var btn = document.getElementById('submitBtn');
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
            });
        });
    </script>
    <x-amount-words-init :ids="['credit_amount', 'debit_amount']" />
</x-app-layout>
