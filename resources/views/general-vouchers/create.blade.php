<x-app-layout>
    @section('title', 'Create General Voucher - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        
        ['url' => route('general-vouchers.index'), 'label' => 'General Vouchers'],
        ['url' => '#', 'label' => 'Create']
    ]" />

    <x-dynamic-heading title="Create General Voucher" />

    <div class="bg-white border border-gray-200 shadow-lg sm:rounded-xl p-4">
        <div class="mb-4">
            <h2 class="text-base font-semibold text-gray-900">Voucher Details</h2>
            <p class="text-xs text-gray-600">Enter bank, party, amount and entry type (Credit = deposit to bank, Debit = withdrawal from bank).</p>
        </div>

        @if ($errors->any())
            <div class="rounded-md mb-4 bg-red-50 border border-red-400 p-4 text-red-800">
                <p class="text-sm font-medium">Whoops! Something went wrong.</p>
                <ul class="mt-2 text-sm list-disc list-inside text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <x-error-alert message="{{ session('error') }}" />
        @endif

        <form method="POST" action="{{ route('general-vouchers.store') }}" enctype="multipart/form-data" id="voucherForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="bank_id">Bank <span class="text-red-600">*</span></x-input-label>
                    <select id="bank_id" name="bank_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Select Bank</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->bank_id }}" {{ old('bank_id') == $bank->bank_id ? 'selected' : '' }}>
                                {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    <div id="bank_balance_display" class="mt-1 text-sm hidden">
                        <span class="font-medium">Balance:</span>
                        <span id="bank_balance_amount" class="ml-1"></span>
                    </div>
                    <x-input-error :messages="$errors->get('bank_id')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="party_id">Party <span class="text-red-600">*</span></x-input-label>
                    <select id="party_id" name="party_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Select Party</option>
                        @foreach($parties as $party)
                            <option value="{{ $party->party_id }}" {{ old('party_id') == $party->party_id ? 'selected' : '' }}>
                                {{ $party->party_name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('party_id')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="entry_type">Entry Type <span class="text-red-600">*</span></x-input-label>
                    <select id="entry_type" name="entry_type" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="1" {{ old('entry_type', '1') == '1' ? 'selected' : '' }}>Credit (Deposit to Bank)</option>
                        <option value="2" {{ old('entry_type') == '2' ? 'selected' : '' }}>Debit (Withdrawal from Bank)</option>
                    </select>
                    <x-input-error :messages="$errors->get('entry_type')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="date_added">Date <span class="text-red-600">*</span></x-input-label>
                    <x-text-input id="date_added" name="date_added" type="date" class="mt-1 block w-full"
                        :value="old('date_added', date('Y-m-d'))" required />
                    <x-input-error :messages="$errors->get('date_added')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="amount">Amount <span class="text-red-600">*</span></x-input-label>
                    <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full"
                        :value="old('amount')" required placeholder="0.00" />
                    <x-input-error :messages="$errors->get('amount')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="rate">Rate <span class="text-red-600">*</span></x-input-label>
                    <x-text-input id="rate" name="rate" type="number" step="0.0001" class="mt-1 block w-full"
                        :value="old('rate', '1')" required placeholder="1" />
                    <x-input-error :messages="$errors->get('rate')" class="mt-1" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="details" class="text-xs">Details</x-input-label>
                    <textarea id="details" name="details" rows="2"
                        class="mt-0.5 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5"
                        placeholder="Optional details...">{{ old('details') }}</textarea>
                    <x-input-error :messages="$errors->get('details')" class="mt-0.5" />
                </div>

                <div class="md:col-span-2">
                    <div class="border rounded-lg p-2.5 bg-gray-50">
                        <p class="text-xs font-medium text-gray-700 mb-1.5">Attachments <span class="text-gray-500 font-normal">(PDF, Word, Images - 5MB max)</span></p>
                        <div id="attachments-container">
                            <div class="attachment-group mb-1.5">
                                <div class="flex flex-wrap items-end gap-2">
                                    <div class="flex-1 min-w-[120px]">
                                        <label class="block text-xs font-medium text-gray-700">Title</label>
                                        <input type="text" name="attachment_titles[]" class="mt-0.5 block w-full rounded-md border-gray-300 shadow-sm text-sm py-1 px-2" placeholder="e.g. Receipt" />
                                    </div>
                                    <div class="flex-1 min-w-[140px]">
                                        <label class="block text-xs font-medium text-gray-700">File</label>
                                        <input type="file" name="attachments[]" class="mt-0.5 block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-indigo-50 file:text-indigo-700"
                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" />
                                    </div>
                                    <button type="button" onclick="this.closest('.attachment-group').remove()" class="text-red-600 hover:text-red-800 pb-1 shrink-0" title="Remove">
                                        <svg class="h-4 w-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addAttachmentField()" class="mt-1 inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded text-gray-600 bg-white hover:bg-gray-50">
                            <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Add More
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-start gap-4 mt-6 border-t pt-4">
                <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Create Voucher
                </button>
                <a href="{{ route('general-vouchers.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bankSelect = document.getElementById('bank_id');
            bankSelect.addEventListener('change', function() { fetchBankBalance(this.value); });
            if (bankSelect.value) fetchBankBalance(bankSelect.value);

            document.getElementById('voucherForm').addEventListener('submit', function() {
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').textContent = 'Saving...';
            });
        });
        function fetchBankBalance(bankId) {
            if (!bankId) {
                document.getElementById('bank_balance_display').classList.add('hidden');
                return;
            }
            fetch('/banks/' + bankId + '/balance')
                .then(r => r.json())
                .then(data => {
                    const div = document.getElementById('bank_balance_display');
                    const span = document.getElementById('bank_balance_amount');
                    if (data.balance !== undefined) {
                        span.textContent = parseFloat(data.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        span.className = 'ml-1 font-medium ' + (data.balance >= 0 ? 'text-green-600' : 'text-red-600');
                        span.dataset.balance = data.balance;
                        div.classList.remove('hidden');
                    } else div.classList.add('hidden');
                })
                .catch(() => document.getElementById('bank_balance_display').classList.add('hidden'));
        }
        function addAttachmentField() {
            const container = document.getElementById('attachments-container');
            const div = document.createElement('div');
            div.className = 'attachment-group mb-1.5';
            div.innerHTML = `
                <div class="flex flex-wrap items-end gap-2">
                    <div class="flex-1 min-w-[120px]">
                        <label class="block text-xs font-medium text-gray-700">Title</label>
                        <input type="text" name="attachment_titles[]" class="mt-0.5 block w-full rounded-md border-gray-300 shadow-sm text-sm py-1 px-2" placeholder="e.g. Receipt" />
                    </div>
                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-700">File</label>
                        <input type="file" name="attachments[]" class="mt-0.5 block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-indigo-50 file:text-indigo-700" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" />
                    </div>
                    <button type="button" onclick="this.closest('.attachment-group').remove()" class="text-red-600 hover:text-red-800 pb-1 shrink-0" title="Remove">
                        <svg class="h-4 w-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            `;
            container.appendChild(div);
        }
    </script>
</x-app-layout>
