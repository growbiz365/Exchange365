<x-app-layout>
    @section('title', 'New Sales - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('sales.index'), 'label' => 'Sales'],
        ['url' => '#', 'label' => 'Create']
    ]" />

    <x-dynamic-heading title="New Sales" />

    @if ($errors->any())
        <div class="mb-3 rounded-lg bg-red-50 border border-red-200 px-3 py-2 text-red-800 text-sm">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-3">
            <x-error-alert message="{{ session('error') }}" />
        </div>
    @endif

    @php
        $rawOldDate = old('date_added');
        $salesCreateDisplayDate = $rawOldDate ?: date('d/m/Y');

        if ($rawOldDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawOldDate)) {
            try {
                $salesCreateDisplayDate = \Carbon\Carbon::createFromFormat('Y-m-d', $rawOldDate)->format('d/m/Y');
            } catch (\Throwable $e) {
                // leave as is
            }
        }
    @endphp

    <form method="POST" action="{{ route('sales.store') }}" id="salesForm" class="space-y-3">
        @csrf

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 mt-4 overflow-hidden">

            {{-- General info rows --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-white">
                {{-- Row 1: Date & Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mb-4">
                    <div class="flex items-center gap-3">
                        <label for="date_added" class="w-32 shrink-0 text-xs font-semibold text-red-600">
                            Date <span>*</span>
                        </label>
                        <div class="flex-1">
                            <input
                                type="text"
                                id="date_added"
                                name="date_added"
                                value="{{ $salesCreateDisplayDate }}"
                                required
                                readonly
                                class="block w-full rounded border-gray-300 text-sm bg-white cursor-pointer focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="dd/mm/yyyy"
                            />
                            <x-input-error :messages="$errors->get('date_added')" class="mt-0.5 text-xs" />
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <label for="details" class="w-32 shrink-0 text-xs font-semibold text-gray-700">
                            Details
                        </label>
                        <div class="flex-1">
                            <input type="text" id="details" name="details" value="{{ old('details') }}"
                                   class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="" />
                            <x-input-error :messages="$errors->get('details')" class="mt-0.5 text-xs" />
                        </div>
                    </div>
                </div>

                {{-- Row 2: Operation & Rate --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                    <div class="flex items-center gap-3">
                        <label class="w-32 shrink-0 text-xs font-semibold text-red-600">
                            Transaction Operation <span>*</span>
                        </label>
                        <div class="flex items-center gap-5">
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="transaction_operation" value="2"
                                       {{ old('transaction_operation', '2') == '2' ? 'checked' : '' }} required
                                       class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Multiply</span>
                            </label>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="transaction_operation" value="1"
                                       {{ old('transaction_operation', '2') == '1' ? 'checked' : '' }}
                                       class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Divide</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('transaction_operation')" class="mt-0.5 text-xs" />
                    </div>

                    <div class="flex items-center gap-3">
                        <label for="rate" class="w-32 shrink-0 text-xs font-semibold text-red-600">
                            Rate <span>*</span>
                        </label>
                        <div class="flex-1">
                            <input type="number" id="rate" name="rate" step="0.0001"
                                   value="{{ old('rate', '1') }}" required placeholder="1"
                                   class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <x-input-error :messages="$errors->get('rate')" class="mt-0.5 text-xs" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Withdrawal & Party tables --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 px-4 py-4">

                {{-- Withdrawal Bank --}}
                <table class="w-full border border-gray-300 text-xs rounded">
                    <thead>
                        <tr>
                            <th colspan="2"
                                class="text-center bg-white text-gray-900 font-bold py-2 px-3 border-b border-gray-300 tracking-wide rounded-t">
                                Withdrawal Bank
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-600 bg-gray-50 w-2/5 align-middle">
                                Bank <span class="text-red-600">*</span>
                            </td>
                            <td class="py-2 px-3">
                                <select id="bank_id" name="bank_id" required
                                        class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-rose-500 focus:ring-rose-500">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->bank_id }}" {{ old('bank_id') == $bank->bank_id ? 'selected' : '' }}>
                                            {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="bank_balance_display" class="mt-1 text-xs text-gray-700 hidden">
                                    <span class="font-medium">Balance:</span>
                                    <span id="bank_balance_amount" class="ml-1"></span>
                                </div>
                                <x-input-error :messages="$errors->get('bank_id')" class="mt-0.5 text-xs" />
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-600 bg-gray-50 align-middle">
                                Currency Amount <span class="text-red-600">*</span>
                            </td>
                            <td class="py-2 px-3">
                                <input type="number" id="currency_amount" name="currency_amount" step="0.01"
                                       value="{{ old('currency_amount') }}" required
                                       class="block w-full rounded border-gray-300 text-sm font-semibold focus:border-rose-500 focus:ring-rose-500"
                                       placeholder="0.00" />
                                <p class="mt-0.5 text-xs text-gray-500">Must not exceed bank balance.</p>
                                <x-input-error :messages="$errors->get('currency_amount')" class="mt-0.5 text-xs" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Party ( بنام ) --}}
                <table class="w-full border border-gray-300 text-xs rounded mt-4 lg:mt-0">
                    <thead>
                        <tr>
                            <th colspan="2"
                                class="text-center bg-white text-gray-900 font-bold py-2 px-3 border-b border-gray-300 tracking-wide rounded-t">
                                Party ( بنام )
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-600 bg-gray-50 w-2/5 align-middle">
                                Party <span class="text-red-600">*</span>
                            </td>
                            <td class="py-2 px-3">
                                <select id="party_id" name="party_id" required
                                        class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">Select Party</option>
                                    @foreach($parties as $party)
                                        <option value="{{ $party->party_id }}" {{ old('party_id') == $party->party_id ? 'selected' : '' }}>
                                            {{ $party->party_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('party_id')" class="mt-0.5 text-xs" />
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-600 bg-gray-50 align-middle">
                                Party Currency <span class="text-red-600">*</span>
                            </td>
                            <td class="py-2 px-3">
                                <select id="party_currency_id" name="party_currency_id" required
                                        class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    @foreach($currencies as $c)
                                        <option value="{{ $c->currency_id }}" {{ old('party_currency_id', '1') == $c->currency_id ? 'selected' : '' }}>
                                            {{ $c->currency }} ({{ $c->currency_symbol ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="party_balance_display" class="mt-1 text-xs text-gray-700 hidden">
                                    <span class="font-medium">Balance:</span>
                                    <span id="party_balance_amount" class="ml-1"></span>
                                </div>
                                <x-input-error :messages="$errors->get('party_currency_id')" class="mt-0.5 text-xs" />
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-600 bg-gray-50 align-middle">
                                Party Amount <span class="text-red-600">*</span>
                            </td>
                            <td class="py-2 px-3">
                                <input type="number" id="party_amount" name="party_amount" step="0.01"
                                       value="{{ old('party_amount') }}" required
                                       class="block w-full rounded border-gray-300 text-sm font-semibold focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="0.00" />
                                <p class="mt-0.5 text-xs text-gray-500">Auto-calculated from Currency Amount &amp; Rate. Editable.</p>
                                <x-input-error :messages="$errors->get('party_amount')" class="mt-0.5 text-xs" />
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-2 px-6 py-3 bg-gray-50 border-t border-gray-100">
                <a href="{{ route('sales.index') }}"
                   class="inline-flex items-center rounded border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                        class="inline-flex items-center rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                    Save Sales
                </button>
            </div>
        </div>
    </form>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        .chosen-container { width: 100% !important; }
        .chosen-container-single .chosen-single {
            height: 42px;
            line-height: 40px;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0 2.25rem 0 0.75rem;
            background: #fff;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            font-size: 0.875rem;
            color: #111827;
        }
        .chosen-container-single .chosen-single span { margin-right: 0.5rem; }
        .chosen-container-single .chosen-single div { right: 0.75rem; }
        .chosen-container-active.chosen-with-drop .chosen-single { border-radius: 0.5rem 0.5rem 0 0; }
        .chosen-drop { border: 1px solid #d1d5db; border-radius: 0 0 0.5rem 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .chosen-results { font-size: 0.875rem; }
        .chosen-results li.highlighted { background: #2563eb; color: white; }

        #date_added.flatpickr-input {
            height: 42px;
            padding-top: 4px;
            padding-bottom: 4px;
            font-size: 12px;
        }
        #date_added {
            max-width: 180px;
            display: inline-block;
        }
        .flatpickr-calendar {
            font-size: 12px;
        }

        /* More compact form spacing without changing font sizes */
        #salesForm .mb-4 {
            margin-bottom: 0.75rem;
        }
        #salesForm .grid {
            row-gap: 0.75rem;
            column-gap: 1rem;
        }
        #salesForm .flex.items-center.gap-3,
        #salesForm .flex.items-start.gap-3 {
            gap: 0.5rem;
        }
        #salesForm .flex.items-center.gap-5 {
            gap: 0.75rem;
        }
        #salesForm .px-6.py-4 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        #salesForm .px-4.py-4 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('salesForm');
            var bankSelect = document.getElementById('bank_id');
            var partySelect = document.getElementById('party_id');
            var currencySelect = document.getElementById('party_currency_id');
            var currencyAmountInput = document.getElementById('currency_amount');
            var rateInput = document.getElementById('rate');
            var partyAmountInput = document.getElementById('party_amount');
            var opMultiply = document.querySelector('input[name="transaction_operation"][value="2"]');
            var opDivide = document.querySelector('input[name="transaction_operation"][value="1"]');

            if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
                jQuery('.chosen-select').chosen({
                    width: '100%',
                    search_contains: true,
                    allow_single_deselect: true,
                    placeholder_text_single: 'Select an option'
                });
            }

            if (typeof flatpickr !== 'undefined') {
                flatpickr('#date_added', {
                    dateFormat: 'd/m/Y',
                    allowInput: false,
                });
            }

            function fetchBankBalance(bankId) {
                var div = document.getElementById('bank_balance_display');
                var span = document.getElementById('bank_balance_amount');
                if (!bankId) { div.classList.add('hidden'); return; }
                fetch('/banks/' + bankId + '/balance')
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data.balance !== undefined) {
                            var bal = parseFloat(data.balance);
                            span.textContent = bal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            span.className = 'font-semibold ' + (bal >= 0 ? 'text-green-600' : 'text-red-600');
                            div.classList.remove('hidden');
                        } else { div.classList.add('hidden'); }
                    })
                    .catch(function() { div.classList.add('hidden'); });
            }

            function fetchPartyBalance(partyId, currencyId) {
                var div = document.getElementById('party_balance_display');
                var span = document.getElementById('party_balance_amount');
                if (!partyId || !currencyId) { div.classList.add('hidden'); return; }
                fetch('/parties/' + partyId + '/balance?currency_id=' + currencyId)
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data.balance !== undefined) {
                            var bal = parseFloat(data.balance);
                            span.textContent = bal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            span.className = 'font-semibold ' + (bal >= 0 ? 'text-green-600' : 'text-red-600');
                            div.classList.remove('hidden');
                        } else { div.classList.add('hidden'); }
                    })
                    .catch(function() { div.classList.add('hidden'); });
            }

            function calculatePartyAmount() {
                var currencyAmt = parseFloat(currencyAmountInput.value) || 0;
                var rate = parseFloat(rateInput.value) || 1;
                if (rate <= 0) rate = 1;
                var op = opMultiply && opMultiply.checked ? 2 : 1;
                var partyAmt = op === 2 ? currencyAmt * rate : (rate !== 0 ? currencyAmt / rate : 0);
                partyAmountInput.value = isNaN(partyAmt) ? '' : Math.round(partyAmt * 100) / 100;
            }

            function calculateCurrencyAmountFromParty() {
                var partyAmt = parseFloat(partyAmountInput.value) || 0;
                var rate = parseFloat(rateInput.value) || 1;
                if (rate <= 0) rate = 1;
                var op = opMultiply && opMultiply.checked ? 2 : 1;
                var currencyAmt = op === 2 ? (rate !== 0 ? partyAmt / rate : 0) : partyAmt * rate;
                currencyAmountInput.value = isNaN(currencyAmt) ? '' : Math.round(currencyAmt * 100) / 100;
            }

            bankSelect.addEventListener('change', function() { fetchBankBalance(this.value); });
            if (bankSelect.value) fetchBankBalance(bankSelect.value);

            function updatePartyBalance() {
                fetchPartyBalance(partySelect.value, currencySelect.value);
            }
            partySelect.addEventListener('change', updatePartyBalance);
            currencySelect.addEventListener('change', updatePartyBalance);
            if (partySelect.value && currencySelect.value) updatePartyBalance();

            currencyAmountInput.addEventListener('input', calculatePartyAmount);
            currencyAmountInput.addEventListener('change', calculatePartyAmount);
            partyAmountInput.addEventListener('input', calculateCurrencyAmountFromParty);
            partyAmountInput.addEventListener('change', calculateCurrencyAmountFromParty);
            rateInput.addEventListener('input', calculatePartyAmount);
            rateInput.addEventListener('change', calculatePartyAmount);
            if (opMultiply) opMultiply.addEventListener('change', calculatePartyAmount);
            if (opDivide) opDivide.addEventListener('change', calculatePartyAmount);

            form.addEventListener('submit', function() {
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').textContent = 'Saving…';
            });
        });
    </script>
</x-app-layout>
