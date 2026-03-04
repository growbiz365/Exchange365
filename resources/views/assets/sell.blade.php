<x-app-layout>
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('assets.index'), 'label' => 'Assets'],
        ['url' => '#', 'label' => 'Sell Asset'],
    ]" />

    <x-dynamic-heading title="Sell Asset #{{ $asset->asset_id }}" />

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-red-800">
            <p class="text-sm font-medium">Please fix the errors below.</p>
            <ul class="mt-2 text-sm list-disc list-inside text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('assets.sell', $asset) }}" method="POST">
        @csrf
        <div class="bg-white shadow-lg sm:rounded-lg border border-gray-200 p-8 mt-4">
            <div class="pb-10 mb-10 border-b border-gray-150 my-8">
                <h2 class="text-lg font-semibold text-gray-900">Asset Sale Information</h2>
                <p class="mt-1 text-sm text-gray-600">Record the sale of this asset.</p>

                <div class="mt-6 mb-6 rounded-lg border border-gray-100 bg-gray-50 px-4 py-3 text-sm text-gray-700">
                    <div class="font-semibold text-gray-900">{{ $asset->asset_name }}</div>
                    <div class="mt-1 flex flex-wrap gap-4 text-xs text-gray-600">
                        <span>Category: <span class="font-medium">{{ $asset->category?->asset_category ?? '—' }}</span></span>
                        <span>Cost Amount: <span class="font-medium">@currency($asset->cost_amount)</span></span>
                        <span>Purchase Date: <span class="font-medium">@businessDate($asset->date_added)</span></span>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <x-input-label for="sale_date">Sale Date <span class="text-red-500">*</span></x-input-label>
                        <div class="mt-2">
                            <input type="date" id="sale_date" name="sale_date"
                                   value="{{ old('sale_date', date('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required />
                        </div>
                        <x-input-error :messages="$errors->get('sale_date')" class="mt-1" />
                    </div>

                    <div class="sm:col-span-1">
                        <x-input-label for="sale_amount">Sale Amount (PKR) <span class="text-red-500">*</span></x-input-label>
                        <div class="mt-2">
                            <input type="number" step="0.01" id="sale_amount" name="sale_amount"
                                   value="{{ old('sale_amount') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required />
                        </div>
                        <x-input-error :messages="$errors->get('sale_amount')" class="mt-1" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-input-label>Sale Transaction Type <span class="text-red-500">*</span></x-input-label>
                        <div class="mt-2 flex flex-wrap gap-4">
                            @php $type = old('sale_transaction_type', '2'); @endphp
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sale_transaction_type" value="2" {{ $type == '2' ? 'checked' : '' }} required
                                       class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Bank (Cash)</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sale_transaction_type" value="3" {{ $type == '3' ? 'checked' : '' }}
                                       class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Party (Credit)</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('sale_transaction_type')" class="mt-1" />
                    </div>

                    <div class="sm:col-span-1" id="sale_bank_wrapper">
                        <x-input-label for="sale_bank_id">Sale Bank (PKR)</x-input-label>
                        <div class="mt-2">
                            <select id="sale_bank_id" name="sale_bank_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select bank</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->bank_id }}" {{ old('sale_bank_id') == $bank->bank_id ? 'selected' : '' }}>
                                        {{ $bank->bank_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="sale_bank_balance_display" class="mt-1 text-sm hidden">
                            <span class="text-gray-600">Balance:</span>
                            <span id="sale_bank_balance_amount" class="ml-1 font-medium"></span>
                        </div>
                        <x-input-error :messages="$errors->get('sale_bank_id')" class="mt-1" />
                    </div>

                    <div class="sm:col-span-1" id="sale_party_wrapper">
                        <x-input-label for="sale_party_id">Sale Party</x-input-label>
                        <div class="mt-2">
                            <select id="sale_party_id" name="sale_party_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select party</option>
                                @foreach($parties as $party)
                                    <option value="{{ $party->party_id }}" {{ old('sale_party_id') == $party->party_id ? 'selected' : '' }}>
                                        {{ $party->party_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="sale_party_balance_display" class="mt-1 text-sm hidden">
                            <span class="text-gray-600">Balance:</span>
                            <span id="sale_party_balance_amount" class="ml-1 font-medium"></span>
                        </div>
                        <x-input-error :messages="$errors->get('sale_party_id')" class="mt-1" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-input-label for="sale_details">Sale Details</x-input-label>
                        <div class="mt-2">
                            <textarea id="sale_details" name="sale_details" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Additional notes about this asset sale...">{{ old('sale_details') }}</textarea>
                        </div>
                        <x-input-error :messages="$errors->get('sale_details')" class="mt-1" />
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('assets.index') }}" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500">Cancel</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 ml-2">Mark as Sold</button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var typeInputs = document.querySelectorAll('input[name="sale_transaction_type"]');
            var bankWrapper = document.getElementById('sale_bank_wrapper');
            var partyWrapper = document.getElementById('sale_party_wrapper');
            var bankSelect = document.getElementById('sale_bank_id');
            var partySelect = document.getElementById('sale_party_id');

            function updateVisibility() {
                var selected = document.querySelector('input[name="sale_transaction_type"]:checked');
                var value = selected ? selected.value : '2';
                bankWrapper.classList.toggle('hidden', value !== '2');
                partyWrapper.classList.toggle('hidden', value !== '3');
                if (value !== '2') {
                    document.getElementById('sale_bank_balance_display').classList.add('hidden');
                }
                if (value !== '3') {
                    document.getElementById('sale_party_balance_display').classList.add('hidden');
                }
            }

            function fetchSaleBankBalance(bankId) {
                var div = document.getElementById('sale_bank_balance_display');
                var span = document.getElementById('sale_bank_balance_amount');
                if (!bankId) { div.classList.add('hidden'); return; }
                fetch('/banks/' + bankId + '/balance')
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (typeof data.balance !== 'undefined') {
                            var bal = parseFloat(data.balance);
                            span.textContent = bal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            span.className = 'ml-1 font-medium ' + (bal >= 0 ? 'text-green-600' : 'text-red-600');
                            div.classList.remove('hidden');
                        } else {
                            div.classList.add('hidden');
                        }
                    })
                    .catch(function () {
                        div.classList.add('hidden');
                    });
            }

            function fetchSalePartyBalance(partyId) {
                var div = document.getElementById('sale_party_balance_display');
                var span = document.getElementById('sale_party_balance_amount');
                if (!partyId) { div.classList.add('hidden'); return; }
                // Use business default currency for party balance.
                fetch('/parties/' + partyId + '/balance?currency_id=' + ({{ $defaultCurrencyId ?? 1 }}))
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (typeof data.balance !== 'undefined') {
                            var bal = parseFloat(data.balance);
                            span.textContent = bal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            span.className = 'ml-1 font-medium ' + (bal >= 0 ? 'text-green-600' : 'text-red-600');
                            div.classList.remove('hidden');
                        } else {
                            div.classList.add('hidden');
                        }
                    })
                    .catch(function () {
                        div.classList.add('hidden');
                    });
            }

            typeInputs.forEach(function (input) {
                input.addEventListener('change', function () {
                    updateVisibility();
                    var selected = document.querySelector('input[name="sale_transaction_type"]:checked');
                    var value = selected ? selected.value : '2';
                    if (value === '2' && bankSelect.value) {
                        fetchSaleBankBalance(bankSelect.value);
                    }
                    if (value === '3' && partySelect.value) {
                        fetchSalePartyBalance(partySelect.value);
                    }
                });
            });

            bankSelect.addEventListener('change', function () {
                var selected = document.querySelector('input[name="sale_transaction_type"]:checked');
                var value = selected ? selected.value : '2';
                if (value === '2') {
                    fetchSaleBankBalance(this.value);
                }
            });

            partySelect.addEventListener('change', function () {
                var selected = document.querySelector('input[name="sale_transaction_type"]:checked');
                var value = selected ? selected.value : '2';
                if (value === '3') {
                    fetchSalePartyBalance(this.value);
                }
            });

            updateVisibility();
        });
    </script>
</x-app-layout>

