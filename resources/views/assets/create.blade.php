<x-app-layout>
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('assets.index'), 'label' => 'Assets'],
        ['url' => '#', 'label' => 'Add Asset'],
    ]" />

    <x-dynamic-heading title="Add Asset" />

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

    <form action="{{ route('assets.store') }}" method="POST" id="assetForm">
        @csrf
        <div class="relative backdrop-blur-xl bg-white/80 rounded-xl shadow-lg border border-white/60 overflow-hidden">
            {{-- Asset info row --}}
            <div class="p-4 border-b border-gray-100">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label for="asset_category_id" class="block text-xs font-semibold text-gray-600 mb-1">Asset Category <span class="text-red-500">*</span></label>
                        <select id="asset_category_id" name="asset_category_id" required
                            class="block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->asset_category_id }}" {{ old('asset_category_id') == $cat->asset_category_id ? 'selected' : '' }}>
                                    {{ $cat->asset_category }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('asset_category_id')" class="mt-0.5 text-xs" />
                    </div>
                    <div>
                        <label for="date_added" class="block text-xs font-semibold text-gray-600 mb-1">Purchase Date <span class="text-red-500">*</span></label>
                        <input type="date" id="date_added" name="date_added" value="{{ old('date_added', date('Y-m-d')) }}" required
                            class="block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('date_added')" class="mt-0.5 text-xs" />
                    </div>
                    <div>
                        <label for="asset_name" class="block text-xs font-semibold text-gray-600 mb-1">Asset Name <span class="text-red-500">*</span></label>
                        <input type="text" id="asset_name" name="asset_name" value="{{ old('asset_name') }}" required placeholder="Asset name"
                            class="block w-full rounded-lg border-gray-300 text-sm py-2 uppercase focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('asset_name')" class="mt-0.5 text-xs" />
                    </div>
                    <div>
                        <label for="cost_amount" class="block text-xs font-semibold text-gray-600 mb-1">Cost Amount <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" id="cost_amount" name="cost_amount" value="{{ old('cost_amount') }}" required placeholder="0.00"
                            class="block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('cost_amount')" class="mt-0.5 text-xs" />
                    </div>
                </div>
            </div>

            {{-- Type & source --}}
            <div class="p-4 border-b border-gray-100">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Type <span class="text-red-500">*</span></label>
                        <div class="flex flex-wrap gap-4 mt-0.5">
                            @php $type = old('purchase_transaction_type', '1'); @endphp
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="purchase_transaction_type" value="1" {{ $type == '1' ? 'checked' : '' }} required class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Opening Asset ( اوپننگ اثاثہ )</span>
                            </label>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="purchase_transaction_type" value="2" {{ $type == '2' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Cash ( نقد )</span>
                            </label>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="purchase_transaction_type" value="3" {{ $type == '3' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <span class="text-sm text-gray-700">Party ( پارٹی )</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('purchase_transaction_type')" class="mt-0.5 text-xs" />
                    </div>
                    <div id="purchase_bank_wrapper" class="space-y-0">
                        <label for="purchase_bank_id" class="block text-xs font-semibold text-gray-600 mb-1">Purchase Bank</label>
                        <select id="purchase_bank_id" name="purchase_bank_id"
                            class="block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select bank</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->bank_id }}" {{ old('purchase_bank_id') == $bank->bank_id ? 'selected' : '' }}>{{ $bank->bank_name }}</option>
                            @endforeach
                        </select>
                        <div id="purchase_bank_balance_display" class="mt-1 text-xs text-gray-600 hidden">Balance: <span id="purchase_bank_balance_amount" class="font-semibold"></span></div>
                        <x-input-error :messages="$errors->get('purchase_bank_id')" class="mt-0.5 text-xs" />
                    </div>
                    <div id="purchase_party_wrapper" class="space-y-0 lg:col-span-1">
                        <label for="purchase_party_id" class="block text-xs font-semibold text-gray-600 mb-1">Purchase Party</label>
                        <select id="purchase_party_id" name="purchase_party_id"
                            class="block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select party</option>
                            @foreach($parties as $party)
                                <option value="{{ $party->party_id }}" {{ old('purchase_party_id') == $party->party_id ? 'selected' : '' }}>{{ $party->party_name }}</option>
                            @endforeach
                        </select>
                        <div id="purchase_party_balance_display" class="mt-1 text-xs text-gray-600 hidden">Balance: <span id="purchase_party_balance_amount" class="font-semibold"></span></div>
                        <x-input-error :messages="$errors->get('purchase_party_id')" class="mt-0.5 text-xs" />
                    </div>
                    <div class="lg:col-span-2">
                        <label for="purchase_details" class="block text-xs font-semibold text-gray-600 mb-1">Purchase Details</label>
                        <textarea id="purchase_details" name="purchase_details" rows="2" placeholder="Optional notes..."
                            class="block w-full rounded-lg border-gray-300 text-sm py-2 focus:border-indigo-500 focus:ring-indigo-500">{{ old('purchase_details') }}</textarea>
                        <x-input-error :messages="$errors->get('purchase_details')" class="mt-0.5 text-xs" />
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-2 p-4 bg-gray-50/50 border-t border-gray-100">
                <a href="{{ route('assets.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" id="submitBtn" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">Save</button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var typeInputs = document.querySelectorAll('input[name="purchase_transaction_type"]');
            var bankWrapper = document.getElementById('purchase_bank_wrapper');
            var partyWrapper = document.getElementById('purchase_party_wrapper');
            var bankSelect = document.getElementById('purchase_bank_id');
            var partySelect = document.getElementById('purchase_party_id');

            function updateVisibility() {
                var selected = document.querySelector('input[name="purchase_transaction_type"]:checked');
                var value = selected ? selected.value : '1';
                bankWrapper.classList.toggle('hidden', value !== '2');
                partyWrapper.classList.toggle('hidden', value !== '3');
                document.getElementById('purchase_bank_balance_display').classList.toggle('hidden', value !== '2' || !bankSelect.value);
                document.getElementById('purchase_party_balance_display').classList.toggle('hidden', value !== '3' || !partySelect.value);
            }

            function fetchBankBalance(bankId) {
                var div = document.getElementById('purchase_bank_balance_display');
                var span = document.getElementById('purchase_bank_balance_amount');
                if (!bankId) { div.classList.add('hidden'); return; }
                fetch('/banks/' + bankId + '/balance').then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (typeof data.balance !== 'undefined') {
                            var bal = parseFloat(data.balance);
                            span.textContent = bal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            span.className = 'font-semibold ' + (bal >= 0 ? 'text-green-600' : 'text-red-600');
                            div.classList.remove('hidden');
                        } else { div.classList.add('hidden'); }
                    }).catch(function () { div.classList.add('hidden'); });
            }

            function fetchPartyBalance(partyId) {
                var div = document.getElementById('purchase_party_balance_display');
                var span = document.getElementById('purchase_party_balance_amount');
                if (!partyId) { div.classList.add('hidden'); return; }
                fetch('/parties/' + partyId + '/balance?currency_id=' + ({{ $defaultCurrencyId ?? 1 }}))
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (typeof data.balance !== 'undefined') {
                            var bal = parseFloat(data.balance);
                            span.textContent = bal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            span.className = 'font-semibold ' + (bal >= 0 ? 'text-green-600' : 'text-red-600');
                            div.classList.remove('hidden');
                        } else { div.classList.add('hidden'); }
                    }).catch(function () { div.classList.add('hidden'); });
            }

            function updateAll() {
                updateVisibility();
                var selected = document.querySelector('input[name="purchase_transaction_type"]:checked');
                var value = selected ? selected.value : '1';
                if (value === '2' && bankSelect.value) fetchBankBalance(bankSelect.value);
                if (value === '3' && partySelect.value) fetchPartyBalance(partySelect.value);
            }

            typeInputs.forEach(function (input) { input.addEventListener('change', updateAll); });
            bankSelect.addEventListener('change', function () {
                if (document.querySelector('input[name="purchase_transaction_type"]:checked')?.value === '2') fetchBankBalance(this.value);
            });
            partySelect.addEventListener('change', function () {
                if (document.querySelector('input[name="purchase_transaction_type"]:checked')?.value === '3') fetchPartyBalance(this.value);
            });

            updateAll();
            document.getElementById('assetForm').addEventListener('submit', function () {
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').textContent = 'Saving…';
            });
        });
    </script>
</x-app-layout>
