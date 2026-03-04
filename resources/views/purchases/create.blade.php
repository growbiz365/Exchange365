<x-app-layout>
    @section('title', 'New Purchase - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('purchases.index'), 'label' => 'Purchase'],
        ['url' => '#', 'label' => 'Create']
    ]" />

    <x-dynamic-heading title="New Purchase" />

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

    <form method="POST" action="{{ route('purchases.store') }}" id="purchaseForm">
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

            {{-- Deposit & Credit Party side by side --}}
            <div class="grid grid-cols-1 lg:grid-cols-2">
                {{-- Deposit (جمع) --}}
                <div class="bg-gradient-to-br from-emerald-50 to-white border-r border-gray-100 p-4">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-emerald-100">
                        <div class="w-7 h-7 bg-emerald-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-emerald-900">Deposit ( جمع )</h2>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label for="bank_id" class="block text-xs font-semibold text-gray-600 mb-1">Deposit Bank <span class="text-red-500">*</span></label>
                            <select id="bank_id" name="bank_id" required
                                class="block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-emerald-500 focus:ring-emerald-500">
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
                            <label for="credit_amount" class="block text-xs font-semibold text-gray-600 mb-1">Deposit Amount <span class="text-red-500">*</span></label>
                            <input type="number" id="credit_amount" name="credit_amount" step="0.01" value="{{ old('credit_amount') }}" required placeholder="0.00"
                                class="block w-full rounded-lg border-gray-300 text-sm py-2 font-semibold focus:border-emerald-500 focus:ring-emerald-500" />
                            <x-input-error :messages="$errors->get('credit_amount')" class="mt-0.5 text-xs" />
                        </div>
                    </div>
                </div>

                {{-- Credit Party (جمع) --}}
                <div class="bg-gradient-to-br from-indigo-50 to-white p-4">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-indigo-100">
                        <div class="w-7 h-7 bg-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-indigo-900">Credit ( جمع ) Party</h2>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label for="party_id" class="block text-xs font-semibold text-gray-600 mb-1">Credit Party <span class="text-red-500">*</span></label>
                            <select id="party_id" name="party_id" required
                                class="block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-indigo-500 focus:ring-indigo-500">
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
                                class="block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-indigo-500 focus:ring-indigo-500">
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
                            <label for="debit_amount" class="block text-xs font-semibold text-gray-600 mb-1">Amount <span class="text-red-500">*</span></label>
                            <input type="number" id="debit_amount" name="debit_amount" step="0.01" value="{{ old('debit_amount') }}" required placeholder="0.00"
                                class="block w-full rounded-lg border-gray-300 text-sm py-2 font-semibold focus:border-indigo-500 focus:ring-indigo-500" />
                            <p class="mt-0.5 text-xs text-gray-500">Auto-calculated from Deposit Amount & Rate. Editable.</p>
                            <x-input-error :messages="$errors->get('debit_amount')" class="mt-0.5 text-xs" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-2 p-4 bg-gray-50/50 border-t border-gray-100">
                <a href="{{ route('purchases.index') }}"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                    Save Purchase
                </button>
            </div>
        </div>
    </form>

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

            function calculateDebitAmount() {
                var credit = parseFloat(creditAmountInput.value) || 0;
                var rate = parseFloat(rateInput.value) || 1;
                if (rate <= 0) rate = 1;
                var op = opMultiply && opMultiply.checked ? 2 : 1;
                var debit = op === 2 ? credit * rate : (rate !== 0 ? credit / rate : 0);
                debitAmountInput.value = isNaN(debit) ? '' : Math.round(debit * 100) / 100;
            }

            function calculateCreditAmountFromDebit() {
                var debit = parseFloat(debitAmountInput.value) || 0;
                var rate = parseFloat(rateInput.value) || 1;
                if (rate <= 0) rate = 1;
                var op = opMultiply && opMultiply.checked ? 2 : 1;
                var credit = op === 2 ? (rate !== 0 ? debit / rate : 0) : debit * rate;
                creditAmountInput.value = isNaN(credit) ? '' : Math.round(credit * 100) / 100;
            }

            bankSelect.addEventListener('change', function() { fetchBankBalance(this.value); });
            if (bankSelect.value) fetchBankBalance(bankSelect.value);

            function updatePartyBalance() {
                fetchPartyBalance(partySelect.value, currencySelect.value);
            }
            partySelect.addEventListener('change', updatePartyBalance);
            currencySelect.addEventListener('change', updatePartyBalance);
            if (partySelect.value && currencySelect.value) updatePartyBalance();

            creditAmountInput.addEventListener('input', calculateDebitAmount);
            creditAmountInput.addEventListener('change', calculateDebitAmount);
            debitAmountInput.addEventListener('input', calculateCreditAmountFromDebit);
            debitAmountInput.addEventListener('change', calculateCreditAmountFromDebit);
            rateInput.addEventListener('input', calculateDebitAmount);
            rateInput.addEventListener('change', calculateDebitAmount);
            if (opMultiply) opMultiply.addEventListener('change', calculateDebitAmount);
            if (opDivide) opDivide.addEventListener('change', calculateDebitAmount);

            form.addEventListener('submit', function() {
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').textContent = 'Saving…';
            });
        });
    </script>
</x-app-layout>
