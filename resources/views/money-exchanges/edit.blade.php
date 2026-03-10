<x-app-layout>
    @section('title', 'Edit Money Exchange - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('banks.dashboard'), 'label' => 'Bank Management'],
        ['url' => route('money-exchanges.index'), 'label' => 'Money Exchanges'],
        ['url' => '#', 'label' => 'Edit'],
    ]" />

    <x-dynamic-heading title="Edit Money Exchange #{{ $moneyExchange->money_exchange_id }}" />

    <form action="{{ route('money-exchanges.update', $moneyExchange) }}" method="POST" id="exchangeForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-3 py-2 rounded mb-3 text-sm" role="alert">
                <strong class="font-semibold">Errors:</strong>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-xs">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-3 py-2 rounded mb-3 text-sm" role="alert">
                <span class="text-xs">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- From Account (Debit) --}}
            <div class="bg-gradient-to-br from-red-50 to-white shadow rounded-lg border-l-4 border-red-500 p-4">
                <div class="flex items-center mb-3 pb-2 border-b border-red-200">
                    <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-red-900">From Account (Debit)</h3>
                        <p class="text-xs text-red-600">Source bank account</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <label for="from_account_id" class="block text-xs font-semibold text-gray-700 mb-1">
                            From Account <span class="text-red-600">*</span>
                        </label>
                        <select id="from_account_id" name="from_account_id" required
                            class="chosen-select block w-full rounded border-gray-300 text-xs focus:border-red-500 focus:ring-red-500">
                            <option value="">Select Source Account</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->bank_id }}" {{ old('from_account_id', $moneyExchange->from_account_id) == $bank->bank_id ? 'selected' : '' }}>
                                    {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('from_account_id')" class="mt-1" />
                        <div id="from_account_balance" class="mt-1 text-xs text-gray-700 hidden">
                            <span class="font-medium">Balance:</span>
                            <span id="from_balance_amount" class="ml-1"></span>
                        </div>
                    </div>

                    <div>
                        <label for="debit_amount" class="block text-xs font-semibold text-gray-700 mb-1">
                            Debit Amount <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="debit_amount" name="debit_amount" step="0.01"
                                value="{{ old('debit_amount', $moneyExchange->debit_amount) }}" required
                                class="block w-full rounded border-gray-300 text-xs pl-6 font-semibold focus:border-red-500 focus:ring-red-500"
                                placeholder="0.00" />
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <span class="text-red-500 text-xs font-bold">-</span>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('debit_amount')" class="mt-1" />
                    </div>
                </div>
            </div>

            {{-- General Information --}}
            <div class="bg-white shadow rounded-lg border border-gray-200 p-4">
                <div class="mb-3 pb-2 border-b border-gray-200">
                    <h3 class="text-sm font-bold text-gray-900">General Information</h3>
                </div>

                <div class="space-y-3">
                    <div>
                        <label for="date_added" class="block text-xs font-semibold text-gray-700 mb-1">
                            Date <span class="text-red-600">*</span>
                        </label>
                        <input type="date" id="date_added" name="date_added"
                            value="{{ old('date_added', $moneyExchange->date_added->format('Y-m-d')) }}" required
                            class="block w-full rounded border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500" />
                        <x-input-error :messages="$errors->get('date_added')" class="mt-1" />
                    </div>

                    <div>
                        <label for="rate" class="block text-xs font-semibold text-gray-700 mb-1">
                            Rate <span class="text-red-600">*</span>
                        </label>
                        <input type="number" id="rate" name="rate" step="0.0001"
                            value="{{ old('rate', $moneyExchange->rate) }}" required
                            class="block w-full rounded border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="1.0000" />
                        <x-input-error :messages="$errors->get('rate')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">
                            Operation
                        </label>
                        <div class="flex flex-col gap-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="transaction_operation" value="1" {{ old('transaction_operation', $moneyExchange->transaction_operation) == '1' ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <span class="ml-2 text-xs font-medium text-gray-700">Divide (Debit / Rate = Credit)</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="transaction_operation" value="2" {{ old('transaction_operation', $moneyExchange->transaction_operation) == '2' ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                <span class="ml-2 text-xs font-medium text-gray-700">Multiply (Debit × Rate = Credit)</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="details" class="block text-xs font-semibold text-gray-700 mb-1">
                            Details / Notes
                        </label>
                        <textarea id="details" name="details" rows="3"
                            class="block w-full rounded border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Optional exchange details...">{{ old('details', $moneyExchange->details) }}</textarea>
                        <x-input-error :messages="$errors->get('details')" class="mt-1" />
                    </div>
                </div>
            </div>

            {{-- To Account (Credit) --}}
            <div class="bg-gradient-to-br from-green-50 to-white shadow rounded-lg border-l-4 border-green-500 p-4">
                <div class="flex items-center mb-3 pb-2 border-b border-green-200">
                    <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-green-900">To Account (Credit)</h3>
                        <p class="text-xs text-green-600">Destination bank account</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <label for="to_account_id" class="block text-xs font-semibold text-gray-700 mb-1">
                            To Account <span class="text-red-600">*</span>
                        </label>
                        <select id="to_account_id" name="to_account_id" required
                            class="chosen-select block w-full rounded border-gray-300 text-xs focus:border-green-500 focus:ring-green-500">
                            <option value="">Select Destination Account</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->bank_id }}" {{ old('to_account_id', $moneyExchange->to_account_id) == $bank->bank_id ? 'selected' : '' }}>
                                    {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('to_account_id')" class="mt-1" />
                        <div id="to_account_balance" class="mt-1 text-xs text-gray-700 hidden">
                            <span class="font-medium">Balance:</span>
                            <span id="to_balance_amount" class="ml-1"></span>
                        </div>
                    </div>

                    <div>
                        <label for="credit_amount" class="block text-xs font-semibold text-gray-700 mb-1">
                            Credit Amount <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="credit_amount" name="credit_amount" step="0.01"
                                value="{{ old('credit_amount', $moneyExchange->credit_amount) }}" required
                                class="block w-full rounded border-gray-300 text-xs pl-6 font-semibold focus:border-green-500 focus:ring-green-500"
                                placeholder="0.00" />
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <span class="text-green-500 text-xs font-bold">+</span>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('credit_amount')" class="mt-1" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Attachments --}}
        <div class="bg-white shadow rounded-lg border border-gray-200 p-4 mt-4">
            {{-- Existing Attachments --}}
            @if($moneyExchange->attachments->count() > 0)
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900 mb-1">Existing Attachments</h3>
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
                                        <a href="{{ $attachment->file_url }}" target="_blank"
                                           class="text-xs text-blue-700 hover:text-blue-900 font-medium truncate block">
                                            {{ $attachment->file_name }}
                                        </a>
                                        @if($attachment->file_size)
                                            <p class="text-xs text-gray-600">{{ $attachment->file_size_formatted }}</p>
                                        @endif
                                    </div>
                                </div>
                                <button type="button" onclick="deleteAttachment({{ $attachment->id }})"
                                        class="px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded ml-3 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Add New Attachments --}}
            <div class="border rounded-lg p-4 bg-gray-50">
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-900">Add New Attachments</h3>
                    <p class="text-xs text-gray-500">Upload additional documents (PDF, DOC, DOCX, JPG, PNG, XLS, XLSX - Max 5MB each).</p>
                </div>

                <div id="attachments-container" class="space-y-3">
                    <div class="attachment-group mb-1.5">
                        <div class="flex flex-wrap items-end gap-2">
                            <div class="flex-1 min-w-[120px]">
                                <label class="block text-xs font-medium text-gray-700">Title</label>
                                <input type="text" name="attachment_titles[]"
                                    class="mt-0.5 block w-full rounded-md border-gray-300 shadow-sm text-sm py-1 px-2"
                                    placeholder="e.g. Receipt" />
                            </div>
                            <div class="flex-1 min-w-[140px]">
                                <label class="block text-xs font-medium text-gray-700">File</label>
                                <input type="file" name="attachments[]"
                                    class="mt-0.5 block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-indigo-50 file:text-indigo-700"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" />
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addAttachmentField()"
                    class="mt-3 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add More
                </button>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 bg-white shadow rounded-lg border border-gray-200 p-3 mt-4">
            <a href="{{ route('money-exchanges.index') }}"
               class="inline-flex items-center px-4 py-1.5 bg-gray-200 rounded text-xs font-semibold text-gray-700 hover:bg-gray-300">
                Cancel
            </a>
            <button type="submit" id="submitBtn"
               class="inline-flex items-center px-4 py-1.5 bg-indigo-600 rounded text-xs font-semibold text-white hover:bg-indigo-700">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Update Exchange
            </button>
        </div>
    </form>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <style>
        .chosen-container { width: 100% !important; }
        .chosen-container-single .chosen-single {
            height: 34px; line-height: 32px; padding: 0 8px;
            border: 1px solid #d1d5db; border-radius: 6px; font-size: 12px;
            background: #fff; font-family: inherit;
        }
        .chosen-container-single .chosen-single span { margin-right: 0.5rem; }
        .chosen-container-single .chosen-single div { right: 8px; }
        .chosen-container-active.chosen-with-drop .chosen-single { border-radius: 6px 6px 0 0; }
        .chosen-drop { border: 1px solid #d1d5db; border-radius: 0 0 6px 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .chosen-results { font-size: 12px; }
        .chosen-results li.highlighted { background: #2563eb; color: white; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const debitInput = document.getElementById('debit_amount');
            const rateInput = document.getElementById('rate');
            const creditInput = document.getElementById('credit_amount');
            const fromAccountSelect = document.getElementById('from_account_id');
            const toAccountSelect = document.getElementById('to_account_id');

            if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
                jQuery('.chosen-select').chosen({
                    width: '100%',
                    search_contains: true,
                    allow_single_deselect: true,
                    placeholder_text_single: 'Select an option'
                });
            }

            function getOperation() {
                return document.querySelector('input[name="transaction_operation"]:checked')?.value || '1';
            }

            function recalcFromDebit() {
                const debit = parseFloat(debitInput.value) || 0;
                const rate = parseFloat(rateInput.value) || 0;
                if (debit > 0 && rate > 0) {
                    let credit;
                    if (getOperation() === '1') {
                        credit = debit / rate;
                    } else {
                        credit = debit * rate;
                    }
                    creditInput.value = credit.toFixed(2);
                }
            }

            function recalcFromCredit() {
                const credit = parseFloat(creditInput.value) || 0;
                const rate = parseFloat(rateInput.value) || 0;
                if (credit > 0 && rate > 0) {
                    let debit;
                    if (getOperation() === '1') {
                        // Debit / Rate = Credit  =>  Debit = Credit * Rate
                        debit = credit * rate;
                    } else {
                        // Debit × Rate = Credit  =>  Debit = Credit / Rate
                        debit = credit / rate;
                    }
                    debitInput.value = debit.toFixed(2);
                }
            }

            function onOperationChange() {
                if (debitInput.value) {
                    recalcFromDebit();
                } else if (creditInput.value) {
                    recalcFromCredit();
                }
            }

            debitInput.addEventListener('input', recalcFromDebit);
            rateInput.addEventListener('input', function () {
                if (debitInput.value) {
                    recalcFromDebit();
                } else if (creditInput.value) {
                    recalcFromCredit();
                }
            });
            document.querySelectorAll('input[name="transaction_operation"]').forEach(function (radio) {
                radio.addEventListener('change', onOperationChange);
            });
            creditInput.addEventListener('input', recalcFromCredit);

            function fetchBankBalance(type) {
                const select = type === 'from' ? fromAccountSelect : toAccountSelect;
                const balanceDiv = document.getElementById(type + '_account_balance');
                const balanceAmount = document.getElementById(type + '_balance_amount');

                if (!select || !balanceDiv || !balanceAmount) return;

                const bankId = select.value;
                if (!bankId) {
                    balanceDiv.classList.add('hidden');
                    balanceAmount.textContent = '';
                    return;
                }

                fetch(`/banks/${bankId}/balance`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.balance !== undefined) {
                            const formatted = parseFloat(data.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            balanceAmount.textContent = formatted;
                            balanceAmount.className = 'ml-1 font-semibold ' + (data.balance >= 0 ? 'text-green-600' : 'text-red-600');
                            balanceDiv.classList.remove('hidden');
                        } else {
                            balanceDiv.classList.add('hidden');
                            balanceAmount.textContent = '';
                        }
                    })
                    .catch(() => {
                        balanceDiv.classList.add('hidden');
                        balanceAmount.textContent = '';
                    });
            }

            function filterToAccountOptions() {
                const selectedFrom = fromAccountSelect.value;
                const toOptions = toAccountSelect.querySelectorAll('option');
                toOptions.forEach(option => {
                    option.style.display = 'block';
                    option.disabled = false;
                });

                if (selectedFrom) {
                    const fromOption = toAccountSelect.querySelector(`option[value="${selectedFrom}"]`);
                    if (fromOption) {
                        fromOption.style.display = 'none';
                        fromOption.disabled = true;
                    }
                    if (toAccountSelect.value === selectedFrom) {
                        toAccountSelect.value = '';
                    }
                }
                if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
                    jQuery('#to_account_id').trigger('chosen:updated');
                }
            }

            fromAccountSelect.addEventListener('change', function () {
                filterToAccountOptions();
                fetchBankBalance('from');
            });

            toAccountSelect.addEventListener('change', function () {
                fetchBankBalance('to');
            });

            filterToAccountOptions();
            fetchBankBalance('from');
            fetchBankBalance('to');

            window.addAttachmentField = function () {
                const container = document.getElementById('attachments-container');
                const newGroup = document.createElement('div');
                newGroup.className = 'attachment-group mb-1.5';
                newGroup.innerHTML = `
                    <div class="flex flex-wrap items-end gap-2">
                        <div class="flex-1 min-w-[120px]">
                            <label class="block text-xs font-medium text-gray-700">Title</label>
                            <input type="text" name="attachment_titles[]" class="mt-0.5 block w-full rounded-md border-gray-300 shadow-sm text-sm py-1 px-2" placeholder="e.g. Receipt" />
                        </div>
                        <div class="flex-1 min-w-[140px]">
                            <label class="block text-xs font-medium text-gray-700">File</label>
                            <input type="file" name="attachments[]" class="mt-0.5 block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-indigo-50 file:text-indigo-700" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" />
                        </div>
                        <button type="button" onclick="this.closest('.attachment-group').remove()" class="text-red-600 hover:text-red-800 pb-1">
                            <svg class="h-4 w-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                `;
                container.appendChild(newGroup);
            };

            window.deleteAttachment = function (attachmentId) {
                if (!confirm('Are you sure you want to remove this attachment?')) return;
                fetch(`/money-exchanges/attachments/${attachmentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Failed to remove attachment');
                        }
                    })
                    .catch(() => alert('An error occurred'));
            };
        });
    </script>
</x-app-layout>

