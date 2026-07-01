<x-app-layout>
    @section('title', 'Create Money Exchange - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('banks.dashboard'), 'label' => 'Bank Management'],
        ['url' => route('money-exchanges.index'), 'label' => 'Money Exchanges'],
        ['url' => '#', 'label' => 'Create'],
    ]" />

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 mt-4">

        {{-- Card Header --}}
        <div class="flex items-center justify-between px-4 sm:px-6 py-2.5 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="bg-gradient-to-br from-indigo-600 to-slate-700 p-1.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h11m0 0L12 3m3 4-3 4m7 6H9m0 0 3-4m-3 4 3 4" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900 leading-tight">Create Money Exchange</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Exchange between bank accounts</p>
                </div>
            </div>
        </div>

        <form action="{{ route('money-exchanges.store') }}" method="POST" id="exchangeForm" enctype="multipart/form-data" class="px-4 sm:px-6 py-2 pb-3 space-y-1.5">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-3 py-2 rounded text-sm" role="alert">
                    <strong class="font-semibold">Errors:</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li class="text-xs">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-3 py-2 rounded text-sm" role="alert">
                    <span class="text-xs">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Top Row: Date, Details, Operation, Rate --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-1.5 pb-1.5 border-b border-gray-100">
                <div>
                    <label for="date_added" class="block text-xs font-semibold text-red-600 mb-0.5">Date <span>*</span></label>
                    @php
                        $dateAddedValue = old('date_added');
                        if (is_string($dateAddedValue) && $dateAddedValue !== '' && str_contains($dateAddedValue, '-')) {
                            try { $dateAddedValue = \Carbon\Carbon::parse($dateAddedValue)->format('d/m/Y'); } catch (\Throwable $e) {}
                        }
                        if (!$dateAddedValue) { $dateAddedValue = date('d/m/Y'); }
                    @endphp
                    <input type="text" id="date_added" name="date_added" value="{{ $dateAddedValue }}" required readonly
                        class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white cursor-pointer"
                        placeholder="dd/mm/yyyy" />
                    <x-input-error :messages="$errors->get('date_added')" class="mt-0.5" />
                </div>

                <div>
                    <label for="details" class="block text-xs font-semibold text-gray-700 mb-0.5">Details</label>
                    <input type="text" id="details" name="details" value="{{ old('details') }}"
                        class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="" />
                    <x-input-error :messages="$errors->get('details')" class="mt-0.5" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-red-600 mb-0.5">Operation <span>*</span></label>
                    <div class="flex items-center gap-4 h-9">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="transaction_operation" value="1" {{ old('transaction_operation', '2') == '1' ? 'checked' : '' }}
                                class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                onchange="onOperationChange()" />
                            <span class="text-sm font-medium text-gray-700">Divide (÷)</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="transaction_operation" value="2" {{ old('transaction_operation', '2') == '2' ? 'checked' : '' }}
                                class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                onchange="onOperationChange()" />
                            <span class="text-sm font-medium text-gray-700">Multiply (×)</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="rate" class="block text-xs font-semibold text-red-600 mb-0.5">Rate <span>*</span></label>
                    <input type="number" id="rate" name="rate" step="any" min="0.0001" value="{{ old('rate', '1') }}" required inputmode="decimal"
                        class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="1" oninput="onRateChange()" />
                    <x-input-error :messages="$errors->get('rate')" class="mt-0.5" />
                </div>
            </div>

            {{-- Debit & Credit Tables --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                {{-- Debit Table --}}
                <table class="w-full border border-gray-300 text-xs rounded">
                    <thead>
                        <tr>
                            <th colspan="2"
                                class="text-center bg-white text-black-800 font-bold py-1.5 px-3 border-b border-gray-300 tracking-wide rounded-t">
                                Debit &nbsp;( بنام )
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-1.5 px-3 font-semibold text-red-600 bg-gray-50 w-2/5 align-middle">
                                From Account <span class="text-red-600">*</span>
                            </td>
                            <td class="py-1.5 px-3">
                                <select id="from_account_id" name="from_account_id" required
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">Select Bank Account</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->bank_id }}" {{ old('from_account_id') == $bank->bank_id ? 'selected' : '' }}>
                                            {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="from_account_balance" class="mt-0.5 text-xs text-gray-700 hidden">
                                    <span class="font-medium">Balance:</span>
                                    <span id="from_balance_amount" class="ml-1"></span>
                                </div>
                                <x-input-error :messages="$errors->get('from_account_id')" class="mt-0.5" />
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1.5 px-3 font-semibold text-red-600 bg-gray-50 align-middle">
                                Debit Amount <span class="text-red-600">*</span>
                            </td>
                            <td class="py-1.5 px-3">
                                <input type="number" id="debit_amount" name="debit_amount" step="any" value="{{ old('debit_amount') }}" required
                                    class="format-amount block w-full rounded border-gray-300 text-sm font-semibold focus:border-red-500 focus:ring-red-500"
                                    placeholder="0.00" oninput="recalcFromDebit()" />
                                <x-input-error :messages="$errors->get('debit_amount')" class="mt-0.5" />
                                <x-amount-words for="debit_amount" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- Credit Table --}}
                <table class="w-full border border-gray-300 text-xs rounded">
                    <thead>
                        <tr>
                            <th colspan="2"
                                class="text-center bg-white text-black-800 font-bold py-1.5 px-3 border-b border-gray-300 tracking-wide rounded-t">
                                Credit &nbsp;( جمع )
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="py-1.5 px-3 font-semibold text-red-600 bg-gray-50 w-2/5 align-middle">
                                To Account <span class="text-red-600">*</span>
                            </td>
                            <td class="py-1.5 px-3">
                                <select id="to_account_id" name="to_account_id" required
                                    class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                                    <option value="">Select Bank Account</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->bank_id }}" {{ old('to_account_id') == $bank->bank_id ? 'selected' : '' }}>
                                            {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="to_account_balance" class="mt-0.5 text-xs text-gray-700 hidden">
                                    <span class="font-medium">Balance:</span>
                                    <span id="to_balance_amount" class="ml-1"></span>
                                </div>
                                <x-input-error :messages="$errors->get('to_account_id')" class="mt-0.5" />
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1.5 px-3 font-semibold text-red-600 bg-gray-50 align-middle">
                                Credit Amount <span class="text-red-600">*</span>
                            </td>
                            <td class="py-1.5 px-3">
                                <input type="number" id="credit_amount" name="credit_amount" step="any" value="{{ old('credit_amount') }}" required
                                    class="format-amount block w-full rounded border-gray-300 text-sm font-semibold focus:border-green-500 focus:ring-green-500"
                                    placeholder="0.00" oninput="recalcFromCredit()" />
                                <x-input-error :messages="$errors->get('credit_amount')" class="mt-0.5" />
                                <x-amount-words for="credit_amount" />
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

            {{-- Attachments Section (collapsible) --}}
            <details class="border border-gray-200 rounded-lg bg-gray-50 group">
                <summary class="flex items-center gap-2 px-4 py-2.5 cursor-pointer list-none select-none">
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-xs font-semibold text-gray-700">Attachments</span>
                    <span class="text-xs text-gray-400">(PDF, DOC, DOCX, JPG, PNG, XLS, XLSX — Max 5MB each)</span>
                </summary>
                <div class="px-4 pb-3 pt-1 border-t border-gray-200">
                    <div id="attachments-container" class="space-y-2"></div>
                    <button type="button" onclick="addAttachmentField()"
                        class="mt-2 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Attachment
                    </button>
                </div>
            </details>

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:flex-wrap sm:items-center sm:justify-end gap-2 pt-1.5 border-t border-gray-100">
                <a href="{{ route('money-exchanges.index') }}"
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

        #date_added.flatpickr-input,
        #date_added {
            width: 100%;
            max-width: none;
            display: block;
            box-sizing: border-box;
            height: 36px;
            padding-top: 4px;
            padding-bottom: 4px;
            font-size: 0.875rem;
        }
        .flatpickr-calendar {
            width: 307px !important;
            font-size: 0.8125rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        }
        .flatpickr-calendar .flatpickr-days,
        .flatpickr-calendar .dayContainer {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
        }
        .flatpickr-day {
            flex-basis: 14.2857143% !important;
            max-width: 39px !important;
            height: 39px;
            line-height: 39px;
            border-radius: 9999px;
        }
        .flatpickr-day.today { border-color: #4f46e5; }
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: #4f46e5;
            border-color: #4f46e5;
            color: #fff;
        }
    </style>
</x-app-layout>

<script>
let attachmentCount = 0;
let suppressAmountRecalc = false;

function addAttachmentField() {
    attachmentCount++;
    const container = document.getElementById('attachments-container');
    const newFields = `
        <div id="attachment-${attachmentCount}" class="grid grid-cols-12 gap-2 p-3 border border-gray-200 rounded bg-white">
            <div class="col-span-5">
                <label class="block text-xs font-medium text-gray-700 mb-1">Document Title</label>
                <input name="attachment_titles[]" type="text"
                    class="block w-full rounded border-gray-300 text-xs" placeholder="e.g., Invoice, Receipt" />
            </div>
            <div class="col-span-6">
                <label class="block text-xs font-medium text-gray-700 mb-1">Choose File</label>
                <input type="file" name="attachments[]"
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

function getOperation() {
    return document.querySelector('input[name="transaction_operation"]:checked')?.value || '2';
}

function recalcFromDebit() {
    if (suppressAmountRecalc) return;
    const debitInput = document.getElementById('debit_amount');
    const rateInput = document.getElementById('rate');
    const creditInput = document.getElementById('credit_amount');
    const debit = AmountFormat.read(debitInput);
    const rate = parseFloat(rateInput.value) || 0;
    if (debit > 0 && rate > 0) {
        const credit = getOperation() === '1' ? debit / rate : debit * rate;
        suppressAmountRecalc = true;
        AmountFormat.setValue(creditInput, credit);
        if (window.AmountInWords) AmountInWords.update('credit_amount');
        suppressAmountRecalc = false;
    }
}

function recalcFromCredit() {
    if (suppressAmountRecalc) return;
    const debitInput = document.getElementById('debit_amount');
    const rateInput = document.getElementById('rate');
    const creditInput = document.getElementById('credit_amount');
    const credit = AmountFormat.read(creditInput);
    const rate = parseFloat(rateInput.value) || 0;
    if (credit > 0 && rate > 0) {
        const debit = getOperation() === '1' ? credit * rate : credit / rate;
        suppressAmountRecalc = true;
        AmountFormat.setValue(debitInput, debit);
        if (window.AmountInWords) AmountInWords.update('debit_amount');
        suppressAmountRecalc = false;
    }
}

function onRateChange() {
    const debitInput = document.getElementById('debit_amount');
    const creditInput = document.getElementById('credit_amount');
    if (debitInput.value) recalcFromDebit();
    else if (creditInput.value) recalcFromCredit();
}

function onOperationChange() {
    const debitInput = document.getElementById('debit_amount');
    const creditInput = document.getElementById('credit_amount');
    if (debitInput.value) recalcFromDebit();
    else if (creditInput.value) recalcFromCredit();
}

document.addEventListener('DOMContentLoaded', function () {
    const fromAccountSelect = document.getElementById('from_account_id');
    const toAccountSelect = document.getElementById('to_account_id');
    const submitBtn = document.getElementById('submitBtn');

    if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
        jQuery('.chosen-select').chosen({
            width: '100%',
            search_contains: true,
            allow_single_deselect: true,
            placeholder_text_single: 'Select an option'
        });
    }

    flatpickr('#date_added', { dateFormat: 'd/m/Y', allowInput: false, disableMobile: true });

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

    document.getElementById('exchangeForm').addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
    });
});
</script>
<x-amount-words-init :ids="['debit_amount', 'credit_amount']" />
