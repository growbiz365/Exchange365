<x-app-layout>
    @section('title', 'Edit Party Transfer - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[['url' => '/', 'label' => 'Home'], ['url' => '/parties/dashboard', 'label' => 'Party Management'], ['url' => route('party-transfers.index'), 'label' => 'Party Transfers'], ['url' => '#', 'label' => 'Edit Transfer']]" />
    
    <x-dynamic-heading title="Edit Party Transfer #{{ $partyTransfer->party_transfer_id }}" />

    <form action="{{ route('party-transfers.update', $partyTransfer) }}" method="POST" id="transferForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Error Display --}}
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
            <!-- Left Column - Debit Party -->
            <div class="bg-gradient-to-br from-red-50 to-white shadow rounded-lg border-l-4 border-red-500 p-4">
                <div class="flex items-center mb-3 pb-2 border-b border-red-200">
                    <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-red-900">Debit (بنـــام)</h3>
                        <p class="text-xs text-red-600">From - Sender</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <!-- Debit Party -->
                    <div>
                        <label for="debit_party" class="block text-xs font-semibold text-gray-700 mb-1">
                            Party <span class="text-red-600">*</span>
                        </label>
                        <select id="debit_party" name="debit_party" required
                            class="block w-full rounded border-gray-300 text-xs focus:border-red-500 focus:ring-red-500">
                            <option value="">Select Party</option>
                            @foreach($parties as $party)
                                <option value="{{ $party->party_id }}" {{ old('debit_party', $partyTransfer->debit_party) == $party->party_id ? 'selected' : '' }}>
                                    {{ $party->party_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('debit_party') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    <!-- Debit Currency -->
                    <div>
                        <label for="debit_currency_id" class="block text-xs font-semibold text-gray-700 mb-1">
                            Currency <span class="text-red-600">*</span>
                        </label>
                        <select id="debit_currency_id" name="debit_currency_id" required
                            class="block w-full rounded border-gray-300 text-xs focus:border-red-500 focus:ring-red-500">
                            <option value="">Select Currency</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->currency_id }}" {{ old('debit_currency_id', $partyTransfer->debit_currency_id) == $currency->currency_id ? 'selected' : '' }}>
                                    {{ $currency->currency }} ({{ $currency->currency_symbol }})
                                </option>
                            @endforeach
                        </select>
                        @error('debit_currency_id') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        <div id="debit_balance" class="mt-1 text-xs text-gray-700 hidden">
                            <span class="font-medium">Balance:</span>
                            <span id="debit_balance_amount" class="ml-1"></span>
                        </div>
                    </div>

                    <!-- Debit Amount -->
                    <div>
                        <label for="debit_amount" class="block text-xs font-semibold text-gray-700 mb-1">
                            Amount <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="debit_amount" name="debit_amount" step="0.01" value="{{ old('debit_amount', $partyTransfer->debit_amount) }}" required
                                class="block w-full rounded border-gray-300 text-xs pl-6 font-semibold focus:border-red-500 focus:ring-red-500" placeholder="0.00" oninput="calculateCreditAmount()" />
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <span class="text-red-500 text-xs font-bold">-</span>
                            </div>
                        </div>
                        @error('debit_amount') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Center Column - General Info -->
            <div class="bg-white shadow rounded-lg border border-gray-200 p-4">
                <div class="mb-3 pb-2 border-b border-gray-200">
                    <h3 class="text-sm font-bold text-gray-900">General Information</h3>
                </div>

                <div class="space-y-3">
                    <!-- Date -->
                    <div>
                        <label for="date_added" class="block text-xs font-semibold text-gray-700 mb-1">
                            Date <span class="text-red-600">*</span>
                        </label>
                        <input type="date" id="date_added" name="date_added" value="{{ old('date_added', $partyTransfer->date_added->format('Y-m-d')) }}" required
                            class="block w-full rounded border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500" />
                        @error('date_added') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    <!-- Exchange Rate -->
                    <div>
                        <label for="rate" class="block text-xs font-semibold text-gray-700 mb-1">
                            Rate <span class="text-red-600">*</span>
                        </label>
                        <input type="number" id="rate" name="rate" step="0.0001" value="{{ old('rate', $partyTransfer->rate) }}" required
                            class="block w-full rounded border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500" placeholder="1.0000" oninput="calculateCreditAmount()" />
                        @error('rate') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    <!-- Transaction Operation -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">
                            Operation
                        </label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="transaction_operation" value="1" {{ old('transaction_operation', $partyTransfer->transaction_operation) == '1' ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" onchange="calculateCreditAmount()" />
                                <span class="ml-2 text-xs font-medium text-gray-700">Divide (÷)</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="transaction_operation" value="2" {{ old('transaction_operation', $partyTransfer->transaction_operation) == '2' ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" onchange="calculateCreditAmount()" />
                                <span class="ml-2 text-xs font-medium text-gray-700">Multiply (×)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Credit Party -->
            <div class="bg-gradient-to-br from-green-50 to-white shadow rounded-lg border-l-4 border-green-500 p-4">
                <div class="flex items-center mb-3 pb-2 border-b border-green-200">
                    <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-green-900">Credit (جمـــع)</h3>
                        <p class="text-xs text-green-600">To - Receiver</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <!-- Credit Party -->
                    <div>
                        <label for="credit_party" class="block text-xs font-semibold text-gray-700 mb-1">
                            Party <span class="text-red-600">*</span>
                        </label>
                        <select id="credit_party" name="credit_party" required
                            class="block w-full rounded border-gray-300 text-xs focus:border-green-500 focus:ring-green-500">
                            <option value="">Select Party</option>
                            @foreach($parties as $party)
                                <option value="{{ $party->party_id }}" {{ old('credit_party', $partyTransfer->credit_party) == $party->party_id ? 'selected' : '' }}>
                                    {{ $party->party_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('credit_party') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    <!-- Credit Currency -->
                    <div>
                        <label for="credit_currency_id" class="block text-xs font-semibold text-gray-700 mb-1">
                            Currency <span class="text-red-600">*</span>
                        </label>
                        <select id="credit_currency_id" name="credit_currency_id" required
                            class="block w-full rounded border-gray-300 text-xs focus:border-green-500 focus:ring-green-500">
                            <option value="">Select Currency</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->currency_id }}" {{ old('credit_currency_id', $partyTransfer->credit_currency_id) == $currency->currency_id ? 'selected' : '' }}>
                                    {{ $currency->currency }} ({{ $currency->currency_symbol }})
                                </option>
                            @endforeach
                        </select>
                        @error('credit_currency_id') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        <div id="credit_balance" class="mt-1 text-xs text-gray-700 hidden">
                            <span class="font-medium">Balance:</span>
                            <span id="credit_balance_amount" class="ml-1"></span>
                        </div>
                    </div>

                    <!-- Credit Amount -->
                    <div>
                        <label for="credit_amount" class="block text-xs font-semibold text-gray-700 mb-1">
                            Amount <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="credit_amount" name="credit_amount" step="0.01" value="{{ old('credit_amount', $partyTransfer->credit_amount) }}" required
                                class="block w-full rounded border-gray-300 text-xs pl-6 font-semibold focus:border-green-500 focus:ring-green-500" placeholder="0.00" oninput="calculateDebitAmount()" />
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <span class="text-green-500 text-xs font-bold">+</span>
                            </div>
                        </div>
                        @error('credit_amount') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Attachments Section -->
        <div class="bg-white shadow rounded-lg border border-gray-200 p-4 mt-4">
            <!-- Existing Attachments -->
            @if($partyTransfer->attachments->count() > 0)
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900 mb-1">Existing Attachments</h3>
                <p class="text-xs text-gray-500 mb-3">Manage your uploaded documents</p>
                <div class="space-y-2">
                    @foreach($partyTransfer->attachments as $attachment)
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded border border-blue-200">
                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                @if($attachment->file_title)
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $attachment->file_title }}</p>
                                @endif
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="text-sm text-blue-700 hover:text-blue-900 font-medium truncate block">
                                    {{ $attachment->file_name }}
                                </a>
                                <p class="text-xs text-gray-600">{{ $attachment->file_size_formatted }}</p>
                            </div>
                        </div>
                        <button type="button" onclick="deleteAttachment({{ $attachment->id }})" 
                            class="px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded ml-3 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Add New Attachments -->
            <div class="border rounded-lg p-4 bg-gray-50">
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-900">Add New Attachments</h3>
                    <p class="text-xs text-gray-500">Upload additional documents (PDF, DOC, DOCX, JPG, PNG, XLS, XLSX - Max 5MB each)</p>
                </div>
                
                <div id="attachments-container" class="space-y-3"></div>
                
                <button type="button" onclick="addAttachmentField()" 
                    class="mt-3 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add More
                </button>
            </div>

            <div class="mt-6 pb-4 border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900 mb-1">Additional Details</h3>
                <p class="text-xs text-gray-500">Add notes and supporting information</p>
            </div>

            <div class="mt-4">
                <label for="details" class="block text-xs font-semibold text-gray-700 mb-1">
                    Details / Notes
                </label>
                <textarea id="details" name="details" rows="3"
                    class="block w-full rounded border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Enter any additional details or notes...">{{ old('details', $partyTransfer->details) }}</textarea>
                @error('details') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 bg-white shadow rounded-lg border border-gray-200 p-3 mt-4">
            <a href="{{ route('party-transfers.index') }}" 
                class="inline-flex items-center px-4 py-1.5 bg-gray-200 rounded text-xs font-semibold text-gray-700 hover:bg-gray-300">
                Cancel
            </a>
            <button type="submit" id="submitBtn" 
                class="inline-flex items-center px-4 py-1.5 bg-indigo-600 rounded text-xs font-semibold text-white hover:bg-indigo-700">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Update Transfer
            </button>
        </div>
    </form>
</x-app-layout>

<script>
let attachmentCount = 0;

function addAttachmentField() {
    attachmentCount++;
    const container = document.getElementById('attachments-container');

    const newFields = `
        <div id="attachment-${attachmentCount}" class="grid grid-cols-12 gap-2 p-3 border border-gray-200 rounded bg-white">
            <div class="col-span-5">
                <label class="block text-xs font-medium text-gray-700 mb-1">Document Title</label>
                <input id="attachment_title_${attachmentCount}" name="attachment_titles[]" type="text" 
                    class="block w-full rounded border-gray-300 text-xs" placeholder="e.g., Invoice, Receipt" />
            </div>
            <div class="col-span-6">
                <label class="block text-xs font-medium text-gray-700 mb-1">Choose File</label>
                <input type="file" id="attachment_file_${attachmentCount}" name="attachments[]" 
                    class="block w-full text-xs file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" 
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" />
            </div>
            <div class="col-span-1 flex items-end">
                <button type="button" onclick="removeAttachmentField(${attachmentCount})" 
                    class="w-full px-2 py-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded flex items-center justify-center" title="Remove">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', newFields);
}

function removeAttachmentField(id) {
    const element = document.getElementById(`attachment-${id}`);
    if (element) {
        element.remove();
    }
}

function deleteAttachment(attachmentId) {
    if (!confirm('Are you sure you want to delete this attachment?')) {
        return;
    }

    fetch(`/party-transfers/attachments/${attachmentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to delete attachment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the attachment');
    });
}

function calculateCreditAmount() {
    const debitAmount = parseFloat(document.getElementById('debit_amount').value) || 0;
    const rate = parseFloat(document.getElementById('rate').value) || 1;
    const operation = document.querySelector('input[name="transaction_operation"]:checked')?.value || '1';
    const creditAmountField = document.getElementById('credit_amount');
    
    if (debitAmount > 0 && rate > 0) {
        let creditAmount;
        if (operation === '1') {
            // Divide operation
            creditAmount = debitAmount / rate;
        } else {
            // Multiply operation
            creditAmount = debitAmount * rate;
        }
        creditAmountField.value = creditAmount.toFixed(2);
    }
}

function calculateDebitAmount() {
    const creditAmount = parseFloat(document.getElementById('credit_amount').value) || 0;
    const rate = parseFloat(document.getElementById('rate').value) || 1;
    const operation = document.querySelector('input[name="transaction_operation"]:checked')?.value || '1';
    const debitAmountField = document.getElementById('debit_amount');
    
    if (creditAmount > 0 && rate > 0) {
        let debitAmount;
        if (operation === '1') {
            // Original: credit = debit / rate  ➜  debit = credit * rate
            debitAmount = creditAmount * rate;
        } else {
            // Original: credit = debit * rate  ➜  debit = credit / rate
            debitAmount = creditAmount / rate;
        }
        debitAmountField.value = debitAmount.toFixed(2);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('transferForm');
    const debitPartySelect = document.getElementById('debit_party');
    const creditPartySelect = document.getElementById('credit_party');
    const debitCurrencySelect = document.getElementById('debit_currency_id');
    const creditCurrencySelect = document.getElementById('credit_currency_id');
    const submitBtn = document.getElementById('submitBtn');

    function fetchPartyBalance(side) {
        const partySelect = side === 'debit' ? debitPartySelect : creditPartySelect;
        const currencySelect = side === 'debit' ? debitCurrencySelect : creditCurrencySelect;
        const balanceDiv = document.getElementById(side + '_balance');
        const balanceAmount = document.getElementById(side + '_balance_amount');

        if (!partySelect || !currencySelect || !balanceDiv || !balanceAmount) return;

        const partyId = partySelect.value;
        const currencyId = currencySelect.value;

        if (!partyId || !currencyId) {
            balanceDiv.classList.add('hidden');
            balanceAmount.textContent = '';
            return;
        }

        fetch(`/parties/${partyId}/balance?currency_id=${currencyId}`)
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

    debitPartySelect.addEventListener('change', function() {
        filterCreditPartyOptions();
        fetchPartyBalance('debit');
    });
    if (debitCurrencySelect) {
        debitCurrencySelect.addEventListener('change', function() {
            fetchPartyBalance('debit');
        });
    }
    creditPartySelect.addEventListener('change', function() {
        fetchPartyBalance('credit');
    });
    if (creditCurrencySelect) {
        creditCurrencySelect.addEventListener('change', function() {
            fetchPartyBalance('credit');
        });
    }

    function filterCreditPartyOptions() {
        const selectedDebitParty = debitPartySelect.value;
        const creditOptions = creditPartySelect.querySelectorAll('option');
        
        creditOptions.forEach(option => {
            option.style.display = 'block';
            option.disabled = false;
        });
        
        if (selectedDebitParty) {
            const debitOption = creditPartySelect.querySelector(`option[value="${selectedDebitParty}"]`);
            if (debitOption) {
                debitOption.style.display = 'none';
                debitOption.disabled = true;
            }
            
            if (creditPartySelect.value === selectedDebitParty) {
                creditPartySelect.value = '';
            }
        }
    }

    filterCreditPartyOptions();
    fetchPartyBalance('debit');
    fetchPartyBalance('credit');

    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...';
    });
});
</script>
