<x-app-layout>
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('assets.index'), 'label' => 'Assets'],
        ['url' => '#', 'label' => 'Sell Asset'],
    ]" />

    <x-dynamic-heading title="Sell Asset #{{ $asset->asset_id }}" />

    @if ($errors->any())
        <div class="mb-3 rounded-lg bg-red-50 border border-red-200 px-3 py-2 text-red-800 text-sm">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $displaySaleDate = old('sale_date')
            ? (preg_match('/^\d{4}-\d{2}-\d{2}$/', old('sale_date'))
                ? \Carbon\Carbon::parse(old('sale_date'))->format('d/m/Y')
                : old('sale_date'))
            : date('d/m/Y');
    @endphp

    <form action="{{ route('assets.sell', $asset) }}" method="POST" class="space-y-3" id="assetSellForm">
        @csrf

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 mt-4 overflow-hidden">

            {{-- Card Header --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-gray-100 bg-white">
                <div class="flex items-center gap-2">
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-1.5 rounded-lg shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 leading-tight">Sell Asset #{{ $asset->asset_id }}</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Record sale details</p>
                    </div>
                </div>
            </div>

            <div class="px-4 sm:px-6 py-4">

                {{-- Asset summary --}}
                <div class="mb-4 rounded border border-gray-200 bg-gray-50 px-4 py-2.5 text-xs text-gray-700">
                    <span class="font-semibold text-gray-900">{{ $asset->asset_name }}</span>
                    <span class="mx-2 text-gray-400">|</span>
                    <span>Category: <span class="font-medium">{{ $asset->category?->asset_category ?? '—' }}</span></span>
                    <span class="mx-2 text-gray-400">|</span>
                    <span>Cost: <span class="font-medium">@currency($asset->cost_amount)</span></span>
                    <span class="mx-2 text-gray-400">|</span>
                    <span>Purchase Date: <span class="font-medium">@businessDate($asset->date_added)</span></span>
                </div>

                {{-- Row 1: Sale Date & Type --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mb-4">
                    <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-3">
                        <label for="sale_date" class="w-full sm:w-36 shrink-0 text-xs font-semibold text-red-600">
                            Sale Date <span>*</span>
                        </label>
                        <div class="flex-1 min-w-0">
                            <input type="text" id="sale_date" name="sale_date"
                                   value="{{ $displaySaleDate }}" required readonly
                                   placeholder="dd/mm/yyyy"
                                   class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <x-input-error :messages="$errors->get('sale_date')" class="mt-0.5 text-xs" />
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5 sm:flex-row sm:items-start sm:gap-3">
                        <label class="w-full sm:w-36 shrink-0 text-xs font-semibold text-red-600 pt-0.5">
                            Type <span>*</span>
                        </label>
                        <div class="flex-1 min-w-0">
                            @php $type = old('sale_transaction_type', '2'); @endphp
                            <div class="flex flex-wrap gap-3">
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio" name="sale_transaction_type" value="2"
                                           {{ $type == '2' ? 'checked' : '' }} required
                                           class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span class="text-sm text-gray-700">Cash ( نقد )</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio" name="sale_transaction_type" value="3"
                                           {{ $type == '3' ? 'checked' : '' }}
                                           class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span class="text-sm text-gray-700">Party ( پارٹی )</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('sale_transaction_type')" class="mt-0.5 text-xs" />
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200 mb-4">

                {{-- Row 2: Conditional Party / Bank --}}
                <div class="mb-4 space-y-3">
                    <div id="sale_party_wrapper" class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-3">
                        <label for="sale_party_id" class="w-full sm:w-36 shrink-0 text-xs font-semibold text-red-600">
                            Sale On Party <span>*</span>
                        </label>
                        <div class="flex-1 min-w-0">
                            <select id="sale_party_id" name="sale_party_id"
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select party</option>
                                @foreach($parties as $party)
                                    <option value="{{ $party->party_id }}" {{ old('sale_party_id') == $party->party_id ? 'selected' : '' }}>
                                        {{ $party->party_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="sale_party_balance_display" class="mt-1 text-xs text-gray-700 hidden">
                                <span class="font-medium">Balance:</span>
                                <span id="sale_party_balance_amount" class="ml-1"></span>
                            </div>
                            <x-input-error :messages="$errors->get('sale_party_id')" class="mt-0.5 text-xs" />
                        </div>
                    </div>

                    <div id="sale_bank_wrapper" class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-3">
                        <label for="sale_bank_id" class="w-full sm:w-36 shrink-0 text-xs font-semibold text-red-600">
                            Sale Bank <span>*</span>
                        </label>
                        <div class="flex-1 min-w-0">
                            <select id="sale_bank_id" name="sale_bank_id"
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select bank</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->bank_id }}" {{ old('sale_bank_id') == $bank->bank_id ? 'selected' : '' }}>
                                        {{ $bank->bank_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-bank-balance-display display-id="sale_bank_balance_display" amount-id="sale_bank_balance_amount" />
                            <x-input-error :messages="$errors->get('sale_bank_id')" class="mt-0.5 text-xs" />
                        </div>
                    </div>
                </div>

                {{-- Row 3: Sale Amount & Sale Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                    <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-3">
                        <label for="sale_amount" class="w-full sm:w-36 shrink-0 text-xs font-semibold text-red-600">
                            Sale Amount <span>*</span>
                        </label>
                        <div class="flex-1 min-w-0">
                            <input type="number" step="0.01" id="sale_amount" name="sale_amount"
                                   value="{{ old('sale_amount') }}" required
                                   class="format-amount block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="0.00" />
                            <x-input-error :messages="$errors->get('sale_amount')" class="mt-0.5 text-xs" />
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-3">
                        <label for="sale_details" class="w-full sm:w-36 shrink-0 text-xs font-semibold text-gray-700">
                            Sale Details
                        </label>
                        <div class="flex-1 min-w-0">
                            <input type="text" id="sale_details" name="sale_details"
                                   value="{{ old('sale_details') }}"
                                   class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="" />
                            <x-input-error :messages="$errors->get('sale_details')" class="mt-0.5 text-xs" />
                        </div>
                    </div>
                </div>

            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:flex-wrap sm:items-center sm:justify-end gap-2 px-4 sm:px-6 py-3 bg-gray-50 border-t border-gray-100">
                <a href="{{ route('assets.index') }}"
                   class="inline-flex items-center justify-center rounded border border-gray-300 bg-white px-4 py-2.5 sm:py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 w-full sm:w-auto">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center rounded bg-indigo-600 px-4 py-2.5 sm:py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 w-full sm:w-auto">
                    Mark as Sold
                </button>
            </div>
        </div>
    </form>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <x-chosen-styles />
    <x-form-bold-input-styles form-id="assetSellForm" />
    <x-flatpickr-compact-styles />
    <style>
        #assetSellForm .mb-4 {
            margin-bottom: 0.75rem;
        }
        #assetSellForm .grid {
            row-gap: 0.75rem;
            column-gap: 1rem;
        }
        #assetSellForm .flex.flex-col.gap-1\\.5,
        #assetSellForm .flex.items-center.gap-3,
        #assetSellForm .flex.items-start.gap-3 {
            gap: 0.5rem;
        }
        #assetSellForm .flex.flex-wrap.gap-3 {
            gap: 0.6rem;
        }
        #assetSellForm .py-4 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
    </style>

    <script>
        flatpickr('#sale_date', { dateFormat: 'd/m/Y', allowInput: false, disableMobile: true, position: 'below left' });

        document.addEventListener('DOMContentLoaded', function () {
            var typeInputs = document.querySelectorAll('input[name="sale_transaction_type"]');
            var bankWrapper = document.getElementById('sale_bank_wrapper');
            var partyWrapper = document.getElementById('sale_party_wrapper');
            var bankSelect = document.getElementById('sale_bank_id');
            var partySelect = document.getElementById('sale_party_id');

            if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
                jQuery('.chosen-select').chosen({
                    width: '100%',
                    search_contains: true,
                    allow_single_deselect: true,
                    placeholder_text_single: 'Select an option'
                });
            }

            function isBankType() {
                var selected = document.querySelector('input[name="sale_transaction_type"]:checked');
                return selected ? selected.value === '2' : false;
            }

            function updateVisibility() {
                var selected = document.querySelector('input[name="sale_transaction_type"]:checked');
                var value = selected ? selected.value : '2';
                bankWrapper.classList.toggle('hidden', value !== '2');
                partyWrapper.classList.toggle('hidden', value !== '3');
                if (value !== '3') {
                    document.getElementById('sale_party_balance_display').classList.add('hidden');
                }
                if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
                    jQuery('#sale_bank_id').trigger('chosen:updated');
                    jQuery('#sale_party_id').trigger('chosen:updated');
                }
            }

            function refreshBankBalance() {
                if (!window.BankBalance) return;
                var display = document.getElementById('sale_bank_balance_display');
                var amount = document.getElementById('sale_bank_balance_amount');
                if (isBankType() && bankSelect.value) {
                    BankBalance.fetch(bankSelect.value, 'sale_bank_balance_display', 'sale_bank_balance_amount');
                } else if (display && amount) {
                    display.classList.add('hidden');
                    amount.textContent = '';
                    delete amount.dataset.balance;
                }
            }

            if (window.BankBalance) {
                BankBalance.bind('sale_bank_id', 'sale_bank_balance_display', 'sale_bank_balance_amount', {
                    shouldFetch: function () { return isBankType(); },
                });
            }

            function fetchSalePartyBalance(partyId) {
                var div = document.getElementById('sale_party_balance_display');
                var span = document.getElementById('sale_party_balance_amount');
                if (!partyId) { div.classList.add('hidden'); return; }
                fetch('/parties/' + partyId + '/balance?currency_id=' + ({{ $defaultCurrencyId ?? 1 }}))
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (typeof data.balance !== 'undefined') {
                            var bal = parseFloat(data.balance);
                            span.textContent = bal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            span.className = 'ml-1 font-semibold ' + (bal >= 0 ? 'text-green-600' : 'text-red-600');
                            div.classList.remove('hidden');
                        } else {
                            div.classList.add('hidden');
                        }
                    })
                    .catch(function () { div.classList.add('hidden'); });
            }

            typeInputs.forEach(function (input) {
                input.addEventListener('change', function () {
                    updateVisibility();
                    refreshBankBalance();
                    var selected = document.querySelector('input[name="sale_transaction_type"]:checked');
                    var value = selected ? selected.value : '2';
                    if (value === '3' && partySelect.value) {
                        fetchSalePartyBalance(partySelect.value);
                    }
                });
            });

            partySelect.addEventListener('change', function () {
                var selected = document.querySelector('input[name="sale_transaction_type"]:checked');
                var value = selected ? selected.value : '2';
                if (value === '3') {
                    fetchSalePartyBalance(this.value);
                }
            });

            updateVisibility();
            refreshBankBalance();
        });
    </script>
    <x-bank-balance-init />
</x-app-layout>
