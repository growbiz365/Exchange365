<x-app-layout>
    @section('title', 'Edit Party Transfer - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[['url' => '/', 'label' => 'Home'], ['url' => '/parties/dashboard', 'label' => 'Party Management'], ['url' => route('party-transfers.index'), 'label' => 'Party Transfers'], ['url' => '#', 'label' => 'Edit Transfer']]" />

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 mt-4">

        {{-- Card Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-4 sm:px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2 min-w-0">
                <div class="bg-gradient-to-br from-indigo-600 to-slate-700 p-1.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900 leading-tight">
                        Edit Party Transfer #{{ $partyTransfer->party_transfer_id }}
                    </h4>
                    <p class="text-xs text-gray-500 mt-0.5">Update voucher details</p>
                </div>
            </div>
            <div class="flex w-full sm:w-auto sm:shrink-0">
                <form action="{{ route('party-transfers.destroy', $partyTransfer) }}" method="POST" class="w-full sm:w-auto"
                    onsubmit="return confirm('Are you sure you want to delete? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex justify-center items-center w-full sm:w-auto px-3 py-2 sm:py-1.5 bg-red-600 rounded text-xs font-semibold text-white hover:bg-red-700">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <form action="{{ route('party-transfers.update', $partyTransfer) }}" method="POST" id="transferForm" enctype="multipart/form-data" class="px-4 sm:px-6 py-4 pb-6 space-y-3">
            @csrf
            @method('PUT')

            {{-- Error Display --}}
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
                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-3">
                    <label for="date_added" class="w-full sm:w-36 shrink-0 text-xs font-semibold text-red-600">
                        Date <span>*</span>
                    </label>
                    <div class="flex-1 min-w-0">
                        <input type="text" id="date_added" name="date_added"
                            value="{{ old('date_added', $partyTransfer->date_added->format('d/m/Y')) }}" required readonly
                            class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white cursor-pointer"
                            placeholder="dd/mm/yyyy" />
                        @error('date_added') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-3">
                    <label for="details" class="w-full sm:w-36 shrink-0 text-xs font-semibold text-gray-700">Details</label>
                    <div class="flex-1 min-w-0">
                        <input type="text" id="details" name="details"
                            value="{{ old('details', $partyTransfer->details) }}"
                            class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="" />
                        @error('details') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Row 2: Operation & Rate --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mb-6">
                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-start sm:gap-3">
                    <label class="w-full sm:w-36 shrink-0 text-xs font-semibold text-red-600 pt-0.5 sm:pt-0">
                        Operation <span>*</span>
                    </label>
                    <div class="flex flex-wrap items-center gap-4 sm:gap-5 flex-1 min-w-0">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="transaction_operation" value="1"
                                {{ old('transaction_operation', $partyTransfer->transaction_operation) == '1' ? 'checked' : '' }}
                                class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                onchange="calculateCreditAmount()" />
                            <span class="text-sm font-medium text-gray-700">Divide (÷)</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="transaction_operation" value="2"
                                {{ old('transaction_operation', $partyTransfer->transaction_operation) == '2' ? 'checked' : '' }}
                                class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                onchange="calculateCreditAmount()" />
                            <span class="text-sm font-medium text-gray-700">Multiply (×)</span>
                        </label>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-3">
                    <label for="rate" class="w-full sm:w-36 shrink-0 text-xs font-semibold text-red-600">
                        Rate <span>*</span>
                    </label>
                    <div class="flex-1 min-w-0">
                        <input type="number" id="rate" name="rate" step="any" min="0.0001"
                            value="{{ old('rate', $partyTransfer->rate) }}" required inputmode="decimal"
                            class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="1" oninput="calculateCreditAmount()" />
                        @error('rate') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Row 3: Debit & Credit Tables --}}
            <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0 pb-1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 md:min-w-[640px]">

                @php
                    $defaultCurrencyId = $currencies->firstWhere('currency', 'PKR')->currency_id
                        ?? $currencies->firstWhere('currency_symbol', '₨')->currency_id
                        ?? $currencies->firstWhere('currency_symbol', 'Rs')->currency_id
                        ?? $currencies->firstWhere('currency_symbol', 'PKR')->currency_id
                        ?? optional($currencies->first())->currency_id;
                    $selectedDebitCurrencyId = old('debit_currency_id', $partyTransfer->debit_currency_id ?? $defaultCurrencyId);
                    $selectedCreditCurrencyId = old('credit_currency_id', $partyTransfer->credit_currency_id ?? $defaultCurrencyId);
                @endphp

                {{-- Debit Table --}}
                <table class="w-full border border-gray-300 text-xs rounded">
                    <thead>
                        <tr>
                            <th colspan="2"
                                class="text-center bg-white text-black-800 font-bold py-2 px-3 border-b border-gray-300 tracking-wide rounded-t">
                                Debit &nbsp;( بنام )
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-600 bg-gray-50 w-2/5 align-middle">
                                Party <span class="text-red-600">*</span>
                            </td>
                            <td class="py-2 px-3">
                                <select id="debit_party" name="debit_party" required
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">Select Party</option>
                                    @foreach($parties as $party)
                                        <option value="{{ $party->party_id }}"
                                            {{ old('debit_party', $partyTransfer->debit_party) == $party->party_id ? 'selected' : '' }}>
                                            {{ $party->party_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('debit_party') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-600 bg-gray-50 align-middle">
                                Currency <span class="text-red-600">*</span>
                            </td>
                            <td class="py-2 px-3">
                                <select id="debit_currency_id" name="debit_currency_id" required
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">Select Currency</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->currency_id }}"
                                            {{ (string) $selectedDebitCurrencyId === (string) $currency->currency_id ? 'selected' : '' }}>
                                            {{ $currency->currency }} ({{ $currency->currency_symbol }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('debit_currency_id') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                                <div id="debit_balance" class="mt-1 text-xs text-gray-700 hidden">
                                    <span class="font-medium">Balance:</span>
                                    <span id="debit_balance_amount" class="ml-1"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-600 bg-gray-50 align-middle">
                                Amount <span class="text-red-600">*</span>
                            </td>
                            <td class="py-2 px-3">
                                <input type="number" id="debit_amount" name="debit_amount" step="any"
                                    value="{{ old('debit_amount', $partyTransfer->debit_amount) }}" required
                                    class="block w-full rounded border-gray-300 text-sm font-semibold focus:border-red-500 focus:ring-red-500"
                                    placeholder="0.00" oninput="calculateCreditAmount()" />
                                @error('debit_amount') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Credit Table --}}
                <table class="w-full border border-gray-300 text-xs rounded">
                    <thead>
                        <tr>
                            <th colspan="2"
                                class="text-center bg-white text-black-800 font-bold py-2 px-3 border-b border-gray-300 tracking-wide rounded-t">
                                Credit &nbsp;( جمع )
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-700 bg-gray-50 w-2/5 align-middle">
                                Party <span class="text-red-600">*</span>
                            </td>
                            <td class="py-2 px-3">
                                <select id="credit_party" name="credit_party" required
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                                    <option value="">Select Party</option>
                                    @foreach($parties as $party)
                                        <option value="{{ $party->party_id }}"
                                            {{ old('credit_party', $partyTransfer->credit_party) == $party->party_id ? 'selected' : '' }}>
                                            {{ $party->party_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('credit_party') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-700 bg-gray-50 align-middle">
                                Currency <span class="text-red-600">*</span>
                            </td>
                            <td class="py-2 px-3">
                                <select id="credit_currency_id" name="credit_currency_id" required
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                                    <option value="">Select Currency</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->currency_id }}"
                                            {{ (string) $selectedCreditCurrencyId === (string) $currency->currency_id ? 'selected' : '' }}>
                                            {{ $currency->currency }} ({{ $currency->currency_symbol }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('credit_currency_id') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                                <div id="credit_balance" class="mt-1 text-xs text-gray-700 hidden">
                                    <span class="font-medium">Balance:</span>
                                    <span id="credit_balance_amount" class="ml-1"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-semibold text-red-700 bg-gray-50 align-middle">
                                Amount <span class="text-red-600">*</span>
                            </td>
                            <td class="py-2 px-3">
                                <input type="number" id="credit_amount" name="credit_amount" step="any"
                                    value="{{ old('credit_amount', $partyTransfer->credit_amount) }}" required
                                    class="block w-full rounded border-gray-300 text-sm font-semibold focus:border-green-500 focus:ring-green-500"
                                    placeholder="0.00" oninput="calculateDebitAmount()" />
                                @error('credit_amount') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
            </div>

            {{-- Attachments Section --}}
            <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 mb-4">

                {{-- Existing Attachments --}}
                @if($partyTransfer->attachments->count() > 0)
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-800 mb-1">Existing Attachments</h3>
                    <p class="text-xs text-gray-500 mb-3">Manage your uploaded documents</p>
                    <div class="space-y-2">
                        @foreach($partyTransfer->attachments as $attachment)
                        <div class="flex items-center justify-between p-2.5 bg-blue-50 rounded border border-blue-200">
                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    @if($attachment->file_title)
                                    <p class="text-xs font-semibold text-gray-900 truncate">{{ $attachment->file_title }}</p>
                                    @endif
                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                                        class="text-xs text-blue-700 hover:text-blue-900 font-medium truncate block">
                                        {{ $attachment->file_name }}
                                    </a>
                                    <p class="text-xs text-gray-500">{{ $attachment->file_size_formatted }}</p>
                                </div>
                            </div>
                            <button type="button" onclick="deleteAttachment({{ $attachment->id }})"
                                class="px-2 py-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded ml-3 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Add New Attachments --}}
                <h3 class="text-xs font-semibold text-gray-800 mb-1">Add New Attachments</h3>
                <p class="text-xs text-gray-500 mb-3">Upload additional documents (PDF, DOC, DOCX, JPG, PNG, XLS, XLSX — Max 5MB each)</p>

                <div id="attachments-container" class="space-y-2"></div>

                <button type="button" onclick="addAttachmentField()"
                    class="mt-3 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Attachment
                </button>
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:flex-wrap sm:items-center sm:justify-end gap-2 pt-4 border-t border-gray-100">
                <a href="{{ route('party-transfers.index') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 sm:py-1.5 bg-red-500 rounded text-xs font-semibold text-white hover:bg-red-600 w-full sm:w-auto">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                    class="inline-flex items-center justify-center px-5 py-2.5 sm:py-1.5 bg-indigo-600 rounded text-xs font-semibold text-white hover:bg-indigo-700 w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save
                </button>
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
        .chosen-container-single .chosen-single {
            height: 30px; line-height: 28px; padding: 0 8px;
            border: 1px solid #d1d5db; border-radius: 6px; font-size: 12px;
            background: #fff; font-family: inherit;
        }
        .chosen-container-single .chosen-single span { margin-right: 0.5rem; }
        .chosen-container-single .chosen-single div { right: 8px; }
        .chosen-container-active.chosen-with-drop .chosen-single { border-radius: 6px 6px 0 0; }
        .chosen-drop { border: 1px solid #d1d5db; border-radius: 0 0 6px 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .chosen-results { font-size: 12px; }
        .chosen-results li.highlighted { background: #2563eb; color: white; }
        .chosen-container .chosen-drop { z-index: 9999 !important; }

        /* Compact flatpickr date input & calendar */
        #date_added.flatpickr-input {
            height: 30px;
            padding-top: 4px;
            padding-bottom: 4px;
            font-size: 11px;
        }
        .flatpickr-calendar {
            font-size: 11px;
        }
        .flatpickr-day {
            max-width: 26px;
            height: 26px;
            line-height: 26px;
        }
    </style>
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
    if (element) element.remove();
}

function deleteAttachment(attachmentId) {
    if (!confirm('Are you sure you want to delete this attachment?')) return;

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
            creditAmount = debitAmount / rate;
        } else {
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
            debitAmount = creditAmount * rate;
        } else {
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

    if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
        jQuery('.chosen-select').chosen({
            width: '100%',
            search_contains: true,
            allow_single_deselect: true,
            placeholder_text_single: 'Select an option'
        });
    }

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
        debitCurrencySelect.addEventListener('change', function() { fetchPartyBalance('debit'); });
    }
    creditPartySelect.addEventListener('change', function() { fetchPartyBalance('credit'); });
    if (creditCurrencySelect) {
        creditCurrencySelect.addEventListener('change', function() { fetchPartyBalance('credit'); });
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
        if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
            jQuery('#credit_party').trigger('chosen:updated');
        }
    }

    flatpickr('#date_added', {
        dateFormat: 'd/m/Y',
        allowInput: false,
    });

    filterCreditPartyOptions();
    fetchPartyBalance('debit');
    fetchPartyBalance('credit');

    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
    });
});
</script>
