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

    <form method="POST" action="{{ route('sales.store') }}" id="salesForm">
        @csrf

        {{-- Compact single-card layout --}}
        <div class="relative backdrop-blur-xl bg-white/80 rounded-xl shadow-lg border border-white/60 overflow-hidden">
            {{-- General info row --}}
            <div class="p-4 border-b border-gray-100">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label for="date_added" class="block text-xs font-semibold text-gray-600 mb-1">Date <span class="text-red-500">*</span></label>
                        <input type="date" id="date_added" name="date_added" value="{{ old('date_added', date('Y-m-d')) }}" required
                            class="block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('date_added')" class="mt-0.5 text-xs" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Operation <span class="text-red-500">*</span></label>
                        <div class="flex gap-4 mt-0.5">
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="transaction_operation" value="2" {{ old('transaction_operation', '1') == '2' ? 'checked' : '' }} required
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Multiply</span>
                            </label>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="transaction_operation" value="1" {{ old('transaction_operation', '1') == '1' ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Divide</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('transaction_operation')" class="mt-0.5 text-xs" />
                    </div>
                    <div>
                        <label for="rate" class="block text-xs font-semibold text-gray-600 mb-1">Rate <span class="text-red-500">*</span></label>
                        <input type="number" id="rate" name="rate" step="0.0001" value="{{ old('rate', '1') }}" required placeholder="1"
                            class="block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('rate')" class="mt-0.5 text-xs" />
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label for="details" class="block text-xs font-semibold text-gray-600 mb-1">Details</label>
                        <input type="text" id="details" name="details" value="{{ old('details') }}" placeholder="Optional notes..."
                            class="block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('details')" class="mt-0.5 text-xs" />
                    </div>
                </div>
            </div>

            {{-- Withdrawal from Bank & Party side by side --}}
            <div class="grid grid-cols-1 lg:grid-cols-2">
                {{-- Withdrawal from Bank --}}
                <div class="bg-gradient-to-br from-rose-50 to-white border-r border-gray-100 p-4">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-rose-100">
                        <div class="w-7 h-7 bg-rose-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-rose-900">Withdrawal from Bank</h2>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label for="bank_id" class="block text-xs font-semibold text-gray-600 mb-1">Bank <span class="text-red-500">*</span></label>
                            <select id="bank_id" name="bank_id" required
                                class="chosen-select block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-rose-500 focus:ring-rose-500">
                                <option value="">Select Bank</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->bank_id }}" {{ old('bank_id') == $bank->bank_id ? 'selected' : '' }}>
                                        {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            <div id="bank_balance_display" class="mt-1 text-xs text-gray-600 hidden">
                                Balance: <span id="bank_balance_amount" class="font-semibold"></span>
                            </div>
                            <x-input-error :messages="$errors->get('bank_id')" class="mt-0.5 text-xs" />
                        </div>
                        <div>
                            <label for="currency_amount" class="block text-xs font-semibold text-gray-600 mb-1">Currency Amount (withdrawal) <span class="text-red-500">*</span></label>
                            <input type="number" id="currency_amount" name="currency_amount" step="0.01" value="{{ old('currency_amount') }}" required placeholder="0.00"
                                class="block w-full rounded-lg border-gray-300 text-sm py-2 font-semibold focus:border-rose-500 focus:ring-rose-500" />
                            <p class="mt-0.5 text-xs text-gray-500">Must not exceed bank balance.</p>
                            <x-input-error :messages="$errors->get('currency_amount')" class="mt-0.5 text-xs" />
                        </div>
                    </div>
                </div>

                {{-- Party ( بنام ) --}}
                <div class="bg-gradient-to-br from-emerald-50 to-white p-4">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-emerald-100">
                        <div class="w-7 h-7 bg-emerald-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-emerald-900">Party ( بنام )</h2>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label for="party_id" class="block text-xs font-semibold text-gray-600 mb-1">Party <span class="text-red-500">*</span></label>
                            <select id="party_id" name="party_id" required
                                class="chosen-select block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Select Party</option>
                                @foreach($parties as $party)
                                    <option value="{{ $party->party_id }}" {{ old('party_id') == $party->party_id ? 'selected' : '' }}>
                                        {{ $party->party_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('party_id')" class="mt-0.5 text-xs" />
                        </div>
                        <div>
                            <label for="party_currency_id" class="block text-xs font-semibold text-gray-600 mb-1">Party Currency <span class="text-red-500">*</span></label>
                            <select id="party_currency_id" name="party_currency_id" required
                                class="chosen-select block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-emerald-500 focus:ring-emerald-500">
                                @foreach($currencies as $c)
                                    <option value="{{ $c->currency_id }}" {{ old('party_currency_id', '1') == $c->currency_id ? 'selected' : '' }}>
                                        {{ $c->currency }} ({{ $c->currency_symbol ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                            <div id="party_balance_display" class="mt-1 text-xs text-gray-600 hidden">
                                Balance: <span id="party_balance_amount" class="font-semibold"></span>
                            </div>
                            <x-input-error :messages="$errors->get('party_currency_id')" class="mt-0.5 text-xs" />
                        </div>
                        <div>
                            <label for="party_amount" class="block text-xs font-semibold text-gray-600 mb-1">Party Amount <span class="text-red-500">*</span></label>
                            <input type="number" id="party_amount" name="party_amount" step="0.01" value="{{ old('party_amount') }}" required placeholder="0.00"
                                class="block w-full rounded-lg border-gray-300 text-sm py-2 font-semibold focus:border-emerald-500 focus:ring-emerald-500" />
                            <p class="mt-0.5 text-xs text-gray-500">Auto-calculated from Currency Amount & Rate. Editable.</p>
                            <x-input-error :messages="$errors->get('party_amount')" class="mt-0.5 text-xs" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-2 p-4 bg-gray-50/50 border-t border-gray-100">
                <a href="{{ route('sales.index') }}"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                    Save Sales
                </button>
            </div>
        </div>
    </form>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
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
