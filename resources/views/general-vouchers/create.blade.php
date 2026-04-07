<x-app-layout>
    @section('title', 'Create General Voucher - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('general-vouchers.index'), 'label' => 'General Vouchers'],
        ['url' => '#', 'label' => 'Create']
    ]" />

    <x-dynamic-heading title="Create General Voucher" />

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 mt-4">
        <div class="flex items-start sm:items-center gap-3 px-4 sm:px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2 min-w-0 flex-1">
                <div class="bg-gradient-to-br from-indigo-600 to-slate-700 p-1.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900 leading-tight">Create General Voucher</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Record a bank/party voucher</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('general-vouchers.store') }}" enctype="multipart/form-data" id="voucherForm" class="p-4 sm:p-6 space-y-3">
            @csrf

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

            @php
                $dateAddedValue = old('date_added');
                if (is_string($dateAddedValue) && $dateAddedValue !== '' && str_contains($dateAddedValue, '-')) {
                    try { $dateAddedValue = \Carbon\Carbon::parse($dateAddedValue)->format('d/m/Y'); } catch (\Throwable $e) {}
                }
                if (!$dateAddedValue) { $dateAddedValue = date('d/m/Y'); }
            @endphp

            {{-- Row 1: Date & Entry Type --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mb-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                    <label for="date_added" class="w-full sm:w-36 shrink-0 text-sm font-semibold text-red-600">Date <span>*</span></label>
                    <div class="flex-1 min-w-0">
                        <input type="text" id="date_added" name="date_added" value="{{ $dateAddedValue }}" required readonly
                            class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white cursor-pointer shadow-sm"
                            placeholder="DD/MM/YYYY" />
                        <x-input-error :messages="$errors->get('date_added')" class="mt-0.5" />
                    </div>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                    <label class="w-full sm:w-36 shrink-0 text-sm font-semibold text-red-600">Entry Type <span>*</span></label>
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="entry_type" value="1" {{ old('entry_type', '1') == '1' ? 'checked' : '' }}
                                class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-sm font-medium text-gray-700">Credit <span class="text-gray-500">( جمع )</span></span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="entry_type" value="2" {{ old('entry_type') == '2' ? 'checked' : '' }}
                                class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-sm font-medium text-gray-700">Debit <span class="text-gray-500">( بنام )</span></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Row 2: Bank & Amount --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mb-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                    <label for="bank_id" class="w-full sm:w-36 shrink-0 text-sm font-semibold text-red-600">Bank <span>*</span></label>
                    <div class="flex-1">
                        <select id="bank_id" name="bank_id" required class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Bank Account</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->bank_id }}" {{ old('bank_id') == $bank->bank_id ? 'selected' : '' }}>
                                    {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <div id="bank_balance_display" class="mt-2 hidden">
                            <div class="rounded border border-blue-200 bg-blue-50 px-3 py-2 text-sm text-blue-900">
                                <span class="font-medium">Balance:</span>
                                <span id="bank_balance_amount" class="ml-1"></span>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('bank_id')" class="mt-0.5" />
                    </div>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                    <label for="amount" class="w-full sm:w-36 shrink-0 text-sm font-semibold text-red-600">Amount <span>*</span></label>
                    <div class="flex-1">
                        <input type="number" id="amount" name="amount" step="0.01" value="{{ old('amount') }}" required
                            class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="0.00" />
                        <x-input-error :messages="$errors->get('amount')" class="mt-0.5" />
                    </div>
                </div>
            </div>

            {{-- Row 3: Rate & Party --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mb-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                    <label for="rate" class="w-full sm:w-36 shrink-0 text-sm font-semibold text-red-600">Rate <span>*</span></label>
                    <div class="flex-1">
                        <input type="number" id="rate" name="rate" step="0.0001" value="{{ old('rate', '1') }}" required min="0.0001"
                            class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="1" />
                        <x-input-error :messages="$errors->get('rate')" class="mt-0.5" />
                    </div>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                    <label for="party_id" class="w-full sm:w-36 shrink-0 text-sm font-semibold text-red-600">Party <span>*</span></label>
                    <div class="flex-1">
                        <select id="party_id" name="party_id" required class="chosen-select block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Party</option>
                            @foreach($parties as $party)
                                <option value="{{ $party->party_id }}" {{ old('party_id') == $party->party_id ? 'selected' : '' }}>
                                    {{ $party->party_name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('party_id')" class="mt-0.5" />
                    </div>
                </div>
            </div>

            {{-- Row 4: Details --}}
            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:gap-3 mb-6">
                <label for="details" class="w-full sm:w-36 shrink-0 text-sm font-semibold text-gray-700 sm:pt-1">Details</label>
                <div class="flex-1">
                    <input type="text" id="details" name="details" value="{{ old('details') }}"
                        class="block w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="" />
                    <x-input-error :messages="$errors->get('details')" class="mt-0.5" />
                </div>
            </div>

            {{-- Attachments --}}
            <div class="border border-gray-200 rounded-xl p-3 sm:p-4 bg-gray-50 mb-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-1">Attachments</h3>
                <p class="text-xs text-gray-500 mb-3">Upload relevant documents (PDF, DOC, DOCX, JPG, PNG, XLS, XLSX — Max 5MB each)</p>
                <div id="attachments-container" class="space-y-2">
                    <div class="attachment-group mb-1.5">
                        <div class="flex flex-wrap items-end gap-2">
                            <div class="flex-1 min-w-[120px]">
                                <label class="block text-xs font-medium text-gray-700">Title</label>
                                <input type="text" name="attachment_titles[]" class="mt-0.5 block w-full rounded border-gray-300 text-sm py-1 px-2" placeholder="e.g. Receipt" />
                            </div>
                            <div class="flex-1 min-w-[140px]">
                                <label class="block text-xs font-medium text-gray-700">File</label>
                                <input type="file" name="attachments[]" class="mt-0.5 block w-full text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-indigo-50 file:text-indigo-700"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" />
                            </div>
                            <button type="button" onclick="this.closest('.attachment-group').remove()" class="text-red-600 hover:text-red-800 pb-1 shrink-0" title="Remove">
                                <svg class="h-4 w-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addAttachmentField()" class="mt-3 inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Add More
                </button>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center sm:gap-3 pt-2">
                <a href="{{ route('general-vouchers.index') }}" class="inline-flex justify-center items-center px-5 py-2.5 sm:py-1.5 bg-red-500 rounded text-sm font-semibold text-white hover:bg-red-600 w-full sm:w-auto">Cancel</a>
                <button type="submit" id="submitBtn" class="inline-flex justify-center items-center px-5 py-2.5 sm:py-1.5 bg-indigo-600 rounded text-sm font-semibold text-white hover:bg-indigo-700 w-full sm:w-auto">Save</button>
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

        /* Full width + same outer size as Chosen (32px) */
        #voucherForm #date_added.flatpickr-input,
        #voucherForm #date_added {
            width: 100%;
            max-width: none;
            display: block;
            box-sizing: border-box;
        }
        #date_added.flatpickr-input {
            height: 32px;
            line-height: 30px;
            padding: 0 8px;
            font-size: 12px;
        }
        .flatpickr-calendar { font-size: 11px; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(15,23,42,0.15); }
        .flatpickr-day { max-width: 28px; height: 28px; line-height: 28px; border-radius: 9999px; }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange { background: #4f46e5; border-color: #4f46e5; color: #fff; }

        /* More compact form spacing without changing font sizes */
        #voucherForm .mb-4 {
            margin-bottom: 0.75rem;
        }
        #voucherForm .mb-6 {
            margin-bottom: 1rem;
        }
        #voucherForm .grid {
            row-gap: 0.75rem;
            column-gap: 1rem;
        }
        #voucherForm .attachment-group {
            margin-bottom: 0.5rem;
        }
        #voucherForm .flex.items-center.gap-3,
        #voucherForm .flex.items-start.gap-3 {
            gap: 0.5rem;
        }
        #voucherForm .flex.items-center.gap-6 {
            gap: 0.75rem;
        }
        #voucherForm .pt-2 {
            padding-top: 0.5rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
                jQuery('.chosen-select').chosen({ width: '100%', search_contains: true, allow_single_deselect: true, placeholder_text_single: 'Select an option' });
            }

            flatpickr('#date_added', { dateFormat: 'd/m/Y', allowInput: false });

            const bankSelect = document.getElementById('bank_id');
            if (bankSelect) {
                bankSelect.addEventListener('change', function() { fetchBankBalance(this.value); });
                if (bankSelect.value) fetchBankBalance(bankSelect.value);
            }

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
                        <input type="text" name="attachment_titles[]" class="mt-0.5 block w-full rounded border-gray-300 text-sm py-1 px-2" placeholder="e.g. Receipt" />
                    </div>
                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-700">File</label>
                        <input type="file" name="attachments[]" class="mt-0.5 block w-full text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-indigo-50 file:text-indigo-700" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" />
                    </div>
                    <button type="button" onclick="this.closest('.attachment-group').remove()" class="text-red-600 hover:text-red-800 pb-1 shrink-0" title="Remove">
                        <svg class="h-4 w-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            `;
            container.appendChild(div);
        }
    </script>
</x-app-layout>
