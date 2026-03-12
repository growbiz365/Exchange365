<x-app-layout>
    @section('title', 'Edit Money Exchange - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('banks.dashboard'), 'label' => 'Bank Management'],
        ['url' => route('money-exchanges.index'), 'label' => 'Money Exchanges'],
        ['url' => '#', 'label' => 'Edit'],
    ]" />

    <x-dynamic-heading title="Edit Money Exchange #{{ $moneyExchange->money_exchange_id }}" />

    <div class="bg-white shadow rounded-lg border border-gray-200">
        <div class="flex items-center justify-between px-6 py-3 border-b border-gray-200">
            <h4 class="text-sm font-bold text-gray-900">Edit Money Exchange #{{ $moneyExchange->money_exchange_id }}</h4>
            <div class="flex items-center gap-2">
                <form action="{{ route('money-exchanges.destroy', $moneyExchange) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 rounded text-sm font-semibold text-white hover:bg-red-600">Delete</button>
                </form>
            </div>
        </div>

        <form action="{{ route('money-exchanges.update', $moneyExchange) }}" method="POST" id="exchangeForm" enctype="multipart/form-data" class="p-6 space-y-3">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-3 py-2 rounded mb-4 text-sm" role="alert">
                    <strong class="font-semibold">Errors:</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-3 py-2 rounded mb-4 text-sm" role="alert">
                    <span class="text-xs">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Row 1: Date & Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mb-4">
                <div class="flex items-center gap-3">
                    <label for="date_added" class="w-36 shrink-0 text-sm font-semibold text-red-600">Date <span>*</span></label>
                    <div class="flex-1">
                        @php
                            $dateAddedValue = old('date_added');
                            if (is_string($dateAddedValue) && $dateAddedValue !== '' && str_contains($dateAddedValue, '-')) {
                                try { $dateAddedValue = \Carbon\Carbon::parse($dateAddedValue)->format('d/m/Y'); } catch (\Throwable $e) {}
                            }
                            if (!$dateAddedValue) { $dateAddedValue = $moneyExchange->date_added->format('d/m/Y'); }
                        @endphp
                        <input type="text" id="date_added" name="date_added" value="{{ $dateAddedValue }}" required readonly
                            class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white cursor-pointer" placeholder="DD/MM/YYYY" />
                        <x-input-error :messages="$errors->get('date_added')" class="mt-0.5" />
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <label for="details" class="w-36 shrink-0 text-sm font-semibold text-gray-700">Details</label>
                    <div class="flex-1">
                        <input type="text" id="details" name="details" value="{{ old('details', $moneyExchange->details) }}"
                            class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="" />
                        <x-input-error :messages="$errors->get('details')" class="mt-0.5" />
                    </div>
                </div>
            </div>

            {{-- Row 2: Operation & Rate --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mb-6">
                <div class="flex items-center gap-3">
                    <label class="w-36 shrink-0 text-sm font-semibold text-red-600">Operation <span>*</span></label>
                    <div class="flex items-center gap-5">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="transaction_operation" value="1" {{ old('transaction_operation', $moneyExchange->transaction_operation ?? 2) == '1' ? 'checked' : '' }}
                                class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-sm font-medium text-gray-700">Divide (÷)</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="transaction_operation" value="2" {{ old('transaction_operation', $moneyExchange->transaction_operation ?? 2) == '2' ? 'checked' : '' }}
                                class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-sm font-medium text-gray-700">Multiply (×)</span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <label for="rate" class="w-36 shrink-0 text-sm font-semibold text-red-600">Rate <span>*</span></label>
                    <div class="flex-1">
                        <input type="number" id="rate" name="rate" step="0.0001" value="{{ old('rate', $moneyExchange->rate) }}" required
                            class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="1.0000" />
                        <x-input-error :messages="$errors->get('rate')" class="mt-0.5" />
                    </div>
                </div>
            </div>

            {{-- Row 3: Debit & Credit Tables --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <table class="w-full border border-gray-300 text-sm rounded">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center bg-red-50 text-red-800 font-bold py-2 px-3 border-b border-gray-300 rounded-t">Debit ( بنام )</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-600 bg-gray-50 w-2/5 align-middle">From Account <span>*</span></td>
                            <td class="py-2 px-3">
                                <select id="from_account_id" name="from_account_id" required
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">Select Bank Account</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->bank_id }}" {{ old('from_account_id', $moneyExchange->from_account_id) == $bank->bank_id ? 'selected' : '' }}>
                                            {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="from_account_balance" class="mt-1 text-xs text-gray-700 hidden">
                                    <span class="font-medium">Balance:</span>
                                    <span id="from_balance_amount" class="ml-1"></span>
                                </div>
                                <x-input-error :messages="$errors->get('from_account_id')" class="mt-0.5" />
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-600 bg-gray-50 align-middle">Debit Amount <span>*</span></td>
                            <td class="py-2 px-3">
                                <input type="number" id="debit_amount" name="debit_amount" step="0.01" value="{{ old('debit_amount', $moneyExchange->debit_amount) }}" required
                                    class="block w-full rounded border-gray-300 text-sm font-semibold focus:border-red-500 focus:ring-red-500" placeholder="0.00" />
                                <x-input-error :messages="$errors->get('debit_amount')" class="mt-0.5" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="w-full border border-gray-300 text-sm rounded">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center bg-green-50 text-green-800 font-bold py-2 px-3 border-b border-gray-300 rounded-t">Credit ( جمع )</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-2 px-3 font-semibold text-green-700 bg-gray-50 w-2/5 align-middle">To Account <span>*</span></td>
                            <td class="py-2 px-3">
                                <select id="to_account_id" name="to_account_id" required
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                                    <option value="">Select Bank Account</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->bank_id }}" {{ old('to_account_id', $moneyExchange->to_account_id) == $bank->bank_id ? 'selected' : '' }}>
                                            {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="to_account_balance" class="mt-1 text-xs text-gray-700 hidden">
                                    <span class="font-medium">Balance:</span>
                                    <span id="to_balance_amount" class="ml-1"></span>
                                </div>
                                <x-input-error :messages="$errors->get('to_account_id')" class="mt-0.5" />
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-semibold text-green-700 bg-gray-50 align-middle">Credit Amount <span>*</span></td>
                            <td class="py-2 px-3">
                                <input type="number" id="credit_amount" name="credit_amount" step="0.01" value="{{ old('credit_amount', $moneyExchange->credit_amount) }}" required
                                    class="block w-full rounded border-gray-300 text-sm font-semibold focus:border-green-500 focus:ring-green-500" placeholder="0.00" />
                                <x-input-error :messages="$errors->get('credit_amount')" class="mt-0.5" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Existing Attachments --}}
            @if($moneyExchange->attachments->count() > 0)
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-800 mb-1">Existing Attachments</h3>
                    <p class="text-xs text-gray-500 mb-3">Manage your uploaded documents</p>
                    <div class="space-y-2">
                        @foreach($moneyExchange->attachments as $attachment)
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded border border-blue-200">
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        @if($attachment->file_title)
                                            <p class="text-xs font-semibold text-gray-900 truncate">{{ $attachment->file_title }}</p>
                                        @endif
                                        <a href="{{ $attachment->file_url }}" target="_blank" class="text-xs text-blue-700 hover:text-blue-900 font-medium truncate block">{{ $attachment->file_name }}</a>
                                        @if($attachment->file_size)
                                            <p class="text-xs text-gray-600">{{ $attachment->file_size_formatted }}</p>
                                        @endif
                                    </div>
                                </div>
                                <button type="button" onclick="deleteAttachment({{ $attachment->id }})" class="px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded ml-3 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Add New Attachments --}}
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 mb-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-1">Add New Attachments</h3>
                <p class="text-xs text-gray-500 mb-3">Upload additional documents (PDF, DOC, DOCX, JPG, PNG, XLS, XLSX - Max 5MB each).</p>
                <div id="attachments-container" class="space-y-2">
                    <div class="attachment-group mb-1.5">
                        <div class="flex flex-wrap items-end gap-2">
                            <div class="flex-1 min-w-[120px]">
                                <label class="block text-xs font-medium text-gray-700">Title</label>
                                <input type="text" name="attachment_titles[]" class="mt-0.5 block w-full rounded border-gray-300 text-sm py-1 px-2" placeholder="e.g. Receipt" />
                            </div>
                            <div class="flex-1 min-w-[140px]">
                                <label class="block text-xs font-medium text-gray-700">File</label>
                                <input type="file" name="attachments[]" class="mt-0.5 block w-full text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-indigo-50 file:text-indigo-700" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" />
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addAttachmentField()" class="mt-3 inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Add More
                </button>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" id="submitBtn" class="inline-flex items-center px-5 py-1.5 bg-indigo-600 rounded text-sm font-semibold text-white hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Save
                </button>
                <a href="{{ route('money-exchanges.index') }}" class="inline-flex items-center px-5 py-1.5 bg-red-500 rounded text-sm font-semibold text-white hover:bg-red-600">Cancel</a>
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
        .chosen-container-single .chosen-single { height: 32px; line-height: 30px; padding: 0 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 12px; background: #fff; }
        .chosen-container-single .chosen-single span { margin-right: 0.5rem; }
        .chosen-container-single .chosen-single div { right: 8px; }
        .chosen-container-active.chosen-with-drop .chosen-single { border-radius: 6px 6px 0 0; }
        .chosen-drop { border: 1px solid #d1d5db; border-radius: 0 0 6px 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .chosen-results { font-size: 12px; }
        .chosen-results li.highlighted { background: #2563eb; color: white; }
        .chosen-container .chosen-drop { z-index: 9999 !important; }
        #date_added.flatpickr-input { height: 34px; padding: 4px 8px; font-size: 12px; }
        #date_added {
            max-width: 180px;
            display: inline-block;
        }
        .flatpickr-calendar { font-size: 11px; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(15,23,42,0.15); }
        .flatpickr-day { max-width: 28px; height: 28px; line-height: 28px; border-radius: 9999px; }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange { background: #4f46e5; border-color: #4f46e5; color: #fff; }

        /* More compact form spacing without changing font sizes */
        #exchangeForm .mb-4 {
            margin-bottom: 0.75rem;
        }
        #exchangeForm .mb-6 {
            margin-bottom: 1rem;
        }
        #exchangeForm .grid {
            row-gap: 0.75rem;
            column-gap: 1rem;
        }
        #exchangeForm .attachment-group {
            margin-bottom: 0.5rem;
        }
        #exchangeForm .flex.items-center.gap-3,
        #exchangeForm .flex.items-start.gap-3 {
            gap: 0.5rem;
        }
        #exchangeForm .flex.items-center.gap-5 {
            gap: 0.75rem;
        }
        #exchangeForm .pt-2 {
            padding-top: 0.5rem;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const debitInput = document.getElementById('debit_amount');
            const rateInput = document.getElementById('rate');
            const creditInput = document.getElementById('credit_amount');
            const fromAccountSelect = document.getElementById('from_account_id');
            const toAccountSelect = document.getElementById('to_account_id');

            if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
                jQuery('.chosen-select').chosen({ width: '100%', search_contains: true, allow_single_deselect: true, placeholder_text_single: 'Select an option' });
            }

            flatpickr('#date_added', { dateFormat: 'd/m/Y', allowInput: false });

            function getOperation() { return document.querySelector('input[name="transaction_operation"]:checked')?.value || '2'; }

            function recalcFromDebit() {
                const debit = parseFloat(debitInput.value) || 0;
                const rate = parseFloat(rateInput.value) || 0;
                if (debit > 0 && rate > 0) {
                    let credit = getOperation() === '1' ? debit / rate : debit * rate;
                    creditInput.value = credit.toFixed(2);
                }
            }
            function recalcFromCredit() {
                const credit = parseFloat(creditInput.value) || 0;
                const rate = parseFloat(rateInput.value) || 0;
                if (credit > 0 && rate > 0) {
                    let debit = getOperation() === '1' ? credit * rate : credit / rate;
                    debitInput.value = debit.toFixed(2);
                }
            }
            function onOperationChange() {
                if (debitInput.value) recalcFromDebit();
                else if (creditInput.value) recalcFromCredit();
            }

            debitInput.addEventListener('input', recalcFromDebit);
            rateInput.addEventListener('input', function () {
                if (debitInput.value) recalcFromDebit();
                else if (creditInput.value) recalcFromCredit();
            });
            document.querySelectorAll('input[name="transaction_operation"]').forEach(function (r) { r.addEventListener('change', onOperationChange); });
            creditInput.addEventListener('input', recalcFromCredit);

            function fetchBankBalance(type) {
                const select = type === 'from' ? fromAccountSelect : toAccountSelect;
                const balanceDiv = document.getElementById(type + '_account_balance');
                const balanceAmount = document.getElementById(type + '_balance_amount');
                if (!select || !balanceDiv || !balanceAmount) return;
                const bankId = select.value;
                if (!bankId) { balanceDiv.classList.add('hidden'); balanceAmount.textContent = ''; return; }
                fetch('/banks/' + bankId + '/balance')
                    .then(r => r.json())
                    .then(data => {
                        if (data.balance !== undefined) {
                            balanceAmount.textContent = parseFloat(data.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            balanceAmount.className = 'ml-1 font-semibold ' + (data.balance >= 0 ? 'text-green-600' : 'text-red-600');
                            balanceDiv.classList.remove('hidden');
                        } else { balanceDiv.classList.add('hidden'); balanceAmount.textContent = ''; }
                    })
                    .catch(() => { balanceDiv.classList.add('hidden'); balanceAmount.textContent = ''; });
            }
            function filterToAccountOptions() {
                const selectedFrom = fromAccountSelect.value;
                toAccountSelect.querySelectorAll('option').forEach(option => { option.style.display = 'block'; option.disabled = false; });
                if (selectedFrom) {
                    const fromOption = toAccountSelect.querySelector('option[value="' + selectedFrom + '"]');
                    if (fromOption) { fromOption.style.display = 'none'; fromOption.disabled = true; }
                    if (toAccountSelect.value === selectedFrom) toAccountSelect.value = '';
                }
                if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) jQuery('#to_account_id').trigger('chosen:updated');
            }

            fromAccountSelect.addEventListener('change', function () { filterToAccountOptions(); fetchBankBalance('from'); });
            toAccountSelect.addEventListener('change', function () { fetchBankBalance('to'); });
            filterToAccountOptions();
            fetchBankBalance('from');
            fetchBankBalance('to');

            window.addAttachmentField = function () {
                const container = document.getElementById('attachments-container');
                const div = document.createElement('div');
                div.className = 'attachment-group mb-1.5';
                div.innerHTML = '<div class="flex flex-wrap items-end gap-2"><div class="flex-1 min-w-[120px]"><label class="block text-xs font-medium text-gray-700">Title</label><input type="text" name="attachment_titles[]" class="mt-0.5 block w-full rounded border-gray-300 text-sm py-1 px-2" placeholder="e.g. Receipt" /></div><div class="flex-1 min-w-[140px]"><label class="block text-xs font-medium text-gray-700">File</label><input type="file" name="attachments[]" class="mt-0.5 block w-full text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-indigo-50 file:text-indigo-700" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" /></div><button type="button" onclick="this.closest(\'.attachment-group\').remove()" class="text-red-600 hover:text-red-800 pb-1"><svg class="h-4 w-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></div>';
                container.appendChild(div);
            };

            window.deleteAttachment = function (attachmentId) {
                if (!confirm('Are you sure you want to remove this attachment?')) return;
                fetch('/money-exchanges/attachments/' + attachmentId, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) location.reload();
                        else alert('Failed to remove attachment');
                    })
                    .catch(() => alert('An error occurred'));
            };

            document.getElementById('exchangeForm').addEventListener('submit', function () {
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').innerHTML = '<span class="animate-pulse">Saving...</span>';
            });
        });
    </script>
</x-app-layout>
