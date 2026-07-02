<x-app-layout>
    @section('title', 'Edit General Voucher #' . $generalVoucher->general_voucher_id . ' - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('general-vouchers.index'), 'label' => 'General Vouchers'],
        ['url' => route('general-vouchers.show', $generalVoucher), 'label' => 'Voucher #' . $generalVoucher->general_voucher_id],
        ['url' => '#', 'label' => 'Edit']
    ]" />

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 mt-4">

        {{-- Card Header --}}
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between px-4 sm:px-6 py-2.5 border-b border-gray-100">
            <div class="flex items-center gap-2 min-w-0">
                <div class="bg-gradient-to-br from-indigo-600 to-slate-700 p-1.5 rounded-lg shadow-sm shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h4 class="text-sm font-bold text-gray-900 leading-tight">Edit General Voucher #{{ $generalVoucher->general_voucher_id }}</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Update voucher details</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto sm:shrink-0">
                <a href="{{ route('general-vouchers.print', $generalVoucher) }}" target="_blank"
                    class="inline-flex justify-center items-center px-3 py-2 sm:py-1.5 bg-green-600 rounded text-xs font-semibold text-white hover:bg-green-700 w-full sm:w-auto">
                    Print
                </a>
                <form action="{{ route('general-vouchers.destroy', $generalVoucher) }}" method="POST" class="w-full sm:w-auto"
                    onsubmit="return confirm('Are you sure you want to delete? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full inline-flex justify-center items-center px-3 py-2 sm:py-1.5 bg-red-600 rounded text-xs font-semibold text-white hover:bg-red-700">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route('general-vouchers.update', $generalVoucher) }}" enctype="multipart/form-data" id="voucherForm" class="px-4 sm:px-6 py-2 pb-3 space-y-3">
            @csrf
            @method('PUT')
            <input type="hidden" name="general_voucher_id" value="{{ $generalVoucher->general_voucher_id }}" />

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

            @php
                $dateAddedValue = old('date_added');
                if (is_string($dateAddedValue) && $dateAddedValue !== '' && str_contains($dateAddedValue, '-')) {
                    try { $dateAddedValue = \Carbon\Carbon::parse($dateAddedValue)->format('d/m/Y'); } catch (\Throwable $e) {}
                }
                if (!$dateAddedValue) { $dateAddedValue = $generalVoucher->date_added->format('d/m/Y'); }
            @endphp

            {{-- Row 1: Date | Entry Type --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-start sm:gap-3">
                    <label for="date_added" class="w-full sm:w-28 shrink-0 text-xs font-semibold text-red-600 pt-1.5 sm:pt-2">
                        Date <span>*</span>
                    </label>
                    <div class="flex-1 min-w-0">
                        <input type="text" id="date_added" name="date_added" value="{{ $dateAddedValue }}" required readonly
                            class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white cursor-pointer"
                            placeholder="dd/mm/yyyy" />
                        <x-input-error :messages="$errors->get('date_added')" class="mt-0.5" />
                    </div>
                </div>

                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-3">
                    <span class="w-full sm:w-28 shrink-0 text-xs font-semibold text-red-600">
                        Entry Type <span>*</span>
                    </span>
                    <div class="flex-1 min-w-0 flex flex-wrap items-center gap-3">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="entry_type" value="1" {{ old('entry_type', $generalVoucher->entry_type) == '1' ? 'checked' : '' }}
                                class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-sm font-medium text-gray-700">Credit <span class="text-gray-500">( جمع )</span></span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="entry_type" value="2" {{ old('entry_type', $generalVoucher->entry_type) == '2' ? 'checked' : '' }}
                                class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-sm font-medium text-gray-700">Debit <span class="text-gray-500">( بنام )</span></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Row 2: Bank | Amount --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-start sm:gap-3">
                    <label for="bank_id" class="w-full sm:w-28 shrink-0 text-xs font-semibold text-red-600 pt-1.5 sm:pt-2">
                        Bank <span>*</span>
                    </label>
                    <div class="flex-1 min-w-0">
                        <select id="bank_id" name="bank_id" required
                            class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Bank Account</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->bank_id }}" {{ old('bank_id', $generalVoucher->bank_id) == $bank->bank_id ? 'selected' : '' }}>
                                    {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <x-bank-balance-display />
                        <x-input-error :messages="$errors->get('bank_id')" class="mt-0.5" />
                    </div>
                </div>

                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-start sm:gap-3">
                    <label for="amount" class="w-full sm:w-28 shrink-0 text-xs font-semibold text-red-600 pt-1.5 sm:pt-2">
                        Amount <span>*</span>
                    </label>
                    <div class="flex-1 min-w-0">
                        <input type="number" id="amount" name="amount" step="any"
                            value="{{ old('amount', $generalVoucher->amount) }}" required
                            class="format-amount block w-full rounded border-gray-300 text-sm font-semibold focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="0.00" />
                        <x-input-error :messages="$errors->get('amount')" class="mt-0.5" />
                        <x-amount-words for="amount" />
                    </div>
                </div>
            </div>

            {{-- Row 3: Rate --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 pb-3 border-b border-gray-200">
                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-start sm:gap-3">
                    <label for="rate" class="w-full sm:w-28 shrink-0 text-xs font-semibold text-red-600 pt-1.5 sm:pt-2">
                        Rate <span>*</span>
                    </label>
                    <div class="flex-1 min-w-0">
                        <input type="number" id="rate" name="rate" step="any" min="0.0001" value="{{ old('rate', $generalVoucher->rate) }}" required inputmode="decimal"
                            class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="1" />
                        <x-input-error :messages="$errors->get('rate')" class="mt-0.5" />
                        <x-amount-words for="rate" class="mt-0.5 text-xs text-amber-700 not-italic leading-snug" />
                    </div>
                </div>
                <div class="hidden md:block" aria-hidden="true"></div>
            </div>

            {{-- Row 4: Party | Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 pt-1">
                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-start sm:gap-3">
                    <label for="party_id" class="w-full sm:w-28 shrink-0 text-xs font-semibold text-red-600 pt-1.5 sm:pt-2">
                        Party <span>*</span>
                    </label>
                    <div class="flex-1 min-w-0">
                        <select id="party_id" name="party_id" required
                            class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Party</option>
                            @foreach($parties as $party)
                                <option value="{{ $party->party_id }}" {{ old('party_id', $generalVoucher->party_id) == $party->party_id ? 'selected' : '' }}>
                                    {{ $party->party_name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('party_id')" class="mt-0.5" />
                    </div>
                </div>

                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-start sm:gap-3">
                    <label for="details" class="w-full sm:w-28 shrink-0 text-xs font-semibold text-red-600 pt-1.5 sm:pt-2">
                        Details
                    </label>
                    <div class="flex-1 min-w-0">
                        <input type="text" id="details" name="details" value="{{ old('details', $generalVoucher->details) }}"
                            class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="" />
                        <x-input-error :messages="$errors->get('details')" class="mt-0.5" />
                    </div>
                </div>
            </div>

            {{-- Attachments Section (collapsible) --}}
            <details class="border border-gray-200 rounded-lg bg-gray-50 group" {{ $generalVoucher->attachments->count() > 0 ? 'open' : '' }}>
                <summary class="flex items-center gap-2 px-4 py-2.5 cursor-pointer list-none select-none">
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-xs font-semibold text-gray-700">Attachments</span>
                    @if($generalVoucher->attachments->count() > 0)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">{{ $generalVoucher->attachments->count() }}</span>
                    @endif
                    <span class="text-xs text-gray-400">(PDF, DOC, DOCX, JPG, PNG, XLS, XLSX — Max 5MB each)</span>
                </summary>
                <div class="px-4 pb-3 pt-1 border-t border-gray-200">
                    @if($generalVoucher->attachments->count() > 0)
                    <div class="mb-3">
                        <p class="text-xs font-semibold text-gray-700 mb-2">Existing Attachments</p>
                        <div class="space-y-1.5">
                            @foreach($generalVoucher->attachments as $attachment)
                            <div class="flex items-center justify-between p-2 bg-blue-50 rounded border border-blue-200">
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        @if($attachment->file_title)
                                        <p class="text-xs font-semibold text-gray-900 truncate">{{ $attachment->file_title }}</p>
                                        @endif
                                        <a href="{{ $attachment->file_url }}" target="_blank"
                                            class="text-xs text-blue-700 hover:text-blue-900 font-medium truncate block">
                                            {{ $attachment->file_name }}
                                        </a>
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
                <a href="{{ route('general-vouchers.show', $generalVoucher) }}"
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
    <x-chosen-styles />
    <x-form-bold-input-styles form-id="voucherForm" />
    <x-flatpickr-compact-styles />
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

function deleteAttachment(attachmentId) {
    if (!confirm('Are you sure you want to delete this attachment?')) return;
    fetch(`/general-vouchers/attachments/${attachmentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) location.reload();
        else alert('Failed to delete attachment');
    })
    .catch(() => alert('An error occurred while deleting the attachment'));
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
        jQuery('.chosen-select').chosen({
            width: '100%',
            search_contains: true,
            allow_single_deselect: true,
            placeholder_text_single: 'Select an option'
        });
    }

    flatpickr('#date_added', { dateFormat: 'd/m/Y', allowInput: false, disableMobile: true, position: 'below left' });

    if (window.BankBalance) {
        BankBalance.bind('bank_id', 'bank_balance_display', 'bank_balance_amount');
    }

    document.getElementById('voucherForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
    });
});
</script>
<x-bank-balance-init />
<x-amount-words-init :ids="['amount', 'rate']" />
