<x-app-layout>
    @section('title', 'Create Bank Transfer - Bank Management - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('banks.index'), 'label' => 'Bank Management'],
        ['url' => route('bank-transfers.index'), 'label' => 'Bank Transfers'],
        ['url' => '#', 'label' => 'Create']
    ]" />

    <x-dynamic-heading title="Create Bank Transfer" />

    <div class="bg-gray-100 border border-gray-200 shadow-sm sm:rounded-xl p-4">
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

        <form method="POST" action="{{ route('bank-transfers.store') }}" enctype="multipart/form-data" id="transferForm" class="space-y-3">
            @csrf

            {{-- Row 1: Date (same grid width as From Account below) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="flex items-start gap-3">
                    <label for="date_added" class="w-36 shrink-0 text-sm font-semibold text-red-600">
                        Date <span>*</span>
                    </label>
                    <div class="flex-1 min-w-0">
                        @php
                            $dateAddedValue = old('date_added');
                            if (is_string($dateAddedValue) && $dateAddedValue !== '' && str_contains($dateAddedValue, '-')) {
                                try { $dateAddedValue = \Carbon\Carbon::parse($dateAddedValue)->format('d/m/Y'); } catch (\Throwable $e) {}
                            }
                            if (!$dateAddedValue) { $dateAddedValue = date('d/m/Y'); }
                        @endphp
                        <input id="date_added" name="date_added" type="text"
                            class="block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white cursor-pointer shadow-sm"
                            value="{{ $dateAddedValue }}" required placeholder="DD/MM/YYYY" />
                        <x-input-error :messages="$errors->get('date_added')" class="mt-0.5" />
                    </div>
                </div>
                <div class="hidden md:block min-w-0" aria-hidden="true"></div>
            </div>

            {{-- Row 2: From / To Accounts --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="flex items-start gap-3">
                    <label for="from_account_id" class="w-36 shrink-0 text-sm font-semibold text-red-600">
                        From Account <span>*</span>
                    </label>
                    <div class="flex-1 min-w-0">
                        <select id="from_account_id" name="from_account_id" required
                            class="chosen-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Select Source Account</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->bank_id }}" {{ old('from_account_id') == $bank->bank_id ? 'selected' : '' }}>
                                    {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <div id="from_account_balance" class="mt-1 text-xs text-gray-700 hidden">
                            <span class="font-medium">Balance:</span>
                            <span id="from_balance_amount" class="ml-1"></span>
                        </div>
                        <x-input-error :messages="$errors->get('from_account_id')" class="mt-0.5" />
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <label for="to_account_id" class="w-36 shrink-0 text-sm font-semibold text-red-600">
                        To Account <span>*</span>
                    </label>
                    <div class="flex-1 min-w-0">
                        <select id="to_account_id" name="to_account_id" required
                            class="chosen-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Select Destination Account</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->bank_id }}" {{ old('to_account_id') == $bank->bank_id ? 'selected' : '' }}>
                                    {{ $bank->bank_name }} ({{ $bank->currency?->currency ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <div id="to_account_balance" class="mt-1 text-xs text-gray-700 hidden">
                            <span class="font-medium">Balance:</span>
                            <span id="to_balance_amount" class="ml-1"></span>
                        </div>
                        <x-input-error :messages="$errors->get('to_account_id')" class="mt-0.5" />
                    </div>
                </div>
            </div>

            {{-- Row 3: Amount & Details --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 mb-4">
                <div class="flex items-center gap-3">
                    <label for="amount" class="w-36 shrink-0 text-sm font-semibold text-red-600">
                        Transfer Amount <span>*</span>
                    </label>
                    <div class="flex-1">
                        <x-text-input id="amount" name="amount" type="number" step="0.01"
                            class="block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            :value="old('amount')" required placeholder="0.00" />
                        <x-input-error :messages="$errors->get('amount')" class="mt-0.5" />
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <label for="details" class="w-36 shrink-0 text-sm font-semibold text-gray-700">
                        Details
                    </label>
                    <div class="flex-1">
                        <textarea id="details" name="details" rows="2"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5"
                            placeholder="Optional transfer details...">{{ old('details') }}</textarea>
                        <x-input-error :messages="$errors->get('details')" class="mt-0.5" />
                    </div>
                </div>
            </div>

            {{-- Attachments --}}
            <div class="border rounded-lg p-3 bg-gray-50 mb-4">
                <p class="text-xs font-medium text-gray-700 mb-1.5">
                    Attachments
                    <span class="text-gray-500 font-normal">(PDF, Word, Images - 5MB max)</span>
                </p>
                <div id="attachments-container">
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
                    class="mt-1 inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded text-gray-600 bg-white hover:bg-gray-50">
                    <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add More
                </button>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-start gap-4 mt-6 border-t pt-4">
                <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Create Transfer
                </button>
                <a href="{{ route('bank-transfers.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        #transferForm .grid > div { min-width: 0; }
        .chosen-container {
            width: 100% !important;
            max-width: 100%;
        }
        .chosen-container-single .chosen-single {
            height: 36px;
            line-height: 34px;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0 2.25rem 0 0.75rem;
            background: #fff;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            font-size: 0.875rem;
            color: #111827;
            overflow: hidden;
        }
        .chosen-container-single .chosen-single span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-right: 0.5rem;
        }
        .chosen-container-single .chosen-single div { right: 0.5rem; }
        .chosen-container-active.chosen-with-drop .chosen-single { border-radius: 0.375rem 0.375rem 0 0; }
        .chosen-drop { border: 1px solid #d1d5db; border-radius: 0 0 0.375rem 0.375rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 100%; }
        .chosen-results { font-size: 0.875rem; max-width: 100%; }
        .chosen-results li {
            white-space: normal;
            word-break: break-word;
        }
        .chosen-results li.highlighted { background: #2563eb; color: white; }

        /* Match date input to other form controls (full width, same height) */
        #transferForm #date_added.flatpickr-input,
        #transferForm #date_added {
            width: 100%;
            max-width: none;
            display: block;
            box-sizing: border-box;
        }
        #date_added.flatpickr-input {
            height: 36px;
            padding-top: 4px;
            padding-bottom: 4px;
            font-size: 0.875rem;
        }
        .flatpickr-calendar {
            font-size: 0.75rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        }
        .flatpickr-day {
            max-width: 28px;
            height: 28px;
            line-height: 28px;
            border-radius: 9999px;
        }
        .flatpickr-day.today {
            border-color: #4f46e5;
        }
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: #4f46e5;
            border-color: #4f46e5;
            color: #fff;
        }

        /* More compact form spacing without changing font sizes */
        #transferForm .mb-4 {
            margin-bottom: 0.75rem;
        }
        #transferForm .grid {
            row-gap: 0.75rem;
            column-gap: 1rem;
        }
        #transferForm .attachment-group {
            margin-bottom: 0.5rem;
        }
        #transferForm .flex.items-center.gap-3,
        #transferForm .flex.items-start.gap-3 {
            gap: 0.5rem;
        }
        #transferForm .border-t.pt-4 {
            padding-top: 0.75rem;
        }
        #transferForm .mt-6 {
            margin-top: 1rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('transferForm');
            const fromAccountSelect = document.getElementById('from_account_id');
            const toAccountSelect = document.getElementById('to_account_id');

            // Always ensure submit button is enabled on page load (handles back-redirect case)
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Create Transfer';

            if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
                jQuery('.chosen-select').chosen({
                    width: '100%',
                    search_contains: true,
                    allow_single_deselect: true,
                    placeholder_text_single: 'Select an option'
                });
            }

            fromAccountSelect.addEventListener('change', function() {
                fetchBankBalance(this.value, 'from');
                filterToAccountOptions();
            });

            toAccountSelect.addEventListener('change', function() {
                fetchBankBalance(this.value, 'to');
            });

            if (fromAccountSelect.value) fetchBankBalance(fromAccountSelect.value, 'from');
            if (toAccountSelect.value) fetchBankBalance(toAccountSelect.value, 'to');
            filterToAccountOptions();

            // Date picker (day/month/year)
            flatpickr('#date_added', {
                dateFormat: 'd/m/Y',
                allowInput: false,
            });

            form.addEventListener('submit', function(e) {
                if (fromAccountSelect.value === toAccountSelect.value) {
                    e.preventDefault();
                    showInsufficientBalanceError('Source and destination accounts cannot be the same.');
                    return;
                }

                // Block submit if insufficient balance
                const fromBalanceEl = document.getElementById('from_balance_amount');
                const amountInput   = document.getElementById('amount');
                if (fromBalanceEl && fromBalanceEl.dataset.balance !== undefined && fromBalanceEl.dataset.balance !== '' && amountInput.value) {
                    const available = parseFloat(fromBalanceEl.dataset.balance);
                    const requested = parseFloat(amountInput.value);
                    if (requested > available) {
                        e.preventDefault();
                        showInsufficientBalanceError(
                            'Insufficient balance. Available: ' + available.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}) +
                            ', Requested: ' + requested.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2})
                        );
                        amountInput.focus();
                        return;
                    }
                }

                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').innerHTML = 'Saving...';
            });
        });

        function fetchBankBalance(bankId, type) {
            if (!bankId) {
                document.getElementById(type + '_account_balance').classList.add('hidden');
                return;
            }
            fetch(`/banks/${bankId}/balance`)
                .then(response => response.json())
                .then(data => {
                    const balanceDiv = document.getElementById(type + '_account_balance');
                    const balanceAmount = document.getElementById(type + '_balance_amount');
                    if (data.balance !== undefined) {
                        const formattedBalance = parseFloat(data.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        balanceAmount.textContent = formattedBalance;
                        balanceAmount.className = 'ml-1 font-medium ' + (data.balance >= 0 ? 'text-green-600' : 'text-red-600');
                        balanceAmount.dataset.balance = data.balance;
                        balanceDiv.classList.remove('hidden');
                    } else {
                        balanceDiv.classList.add('hidden');
                    }
                })
                .catch(() => document.getElementById(type + '_account_balance').classList.add('hidden'));
        }

        function showInsufficientBalanceError(message) {
            // Remove any existing inline error banner
            let banner = document.getElementById('balance-error-banner');
            if (!banner) {
                banner = document.createElement('div');
                banner.id = 'balance-error-banner';
                banner.className = 'mb-4 flex items-start gap-3 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700';
                const form = document.getElementById('transferForm');
                form.parentNode.insertBefore(banner, form);
            }
            banner.innerHTML = `<svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg><span>${message}</span>`;
            banner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function clearInsufficientBalanceError() {
            const banner = document.getElementById('balance-error-banner');
            if (banner) banner.remove();
        }

        function validateTransferAmount() {
            const fromBalanceAmount = document.getElementById('from_balance_amount');
            const amountInput = document.getElementById('amount');
            let errorDiv = document.getElementById('amount-error');

            if (!fromBalanceAmount || !fromBalanceAmount.dataset.balance || fromBalanceAmount.dataset.balance === '' || !amountInput.value) {
                if (errorDiv) errorDiv.remove();
                amountInput.classList.remove('border-red-500');
                clearInsufficientBalanceError();
                return;
            }

            const availableBalance = parseFloat(fromBalanceAmount.dataset.balance);
            const transferAmount   = parseFloat(amountInput.value);

            if (transferAmount > availableBalance) {
                amountInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                if (!errorDiv) {
                    errorDiv = document.createElement('p');
                    errorDiv.id = 'amount-error';
                    errorDiv.className = 'mt-1 text-xs text-red-600 font-medium';
                    amountInput.closest('div').appendChild(errorDiv);
                }
                errorDiv.textContent = 'Insufficient balance. Available: ' +
                    availableBalance.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
            } else {
                amountInput.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                if (errorDiv) errorDiv.remove();
                clearInsufficientBalanceError();
            }
        }

        function filterToAccountOptions() {
            const fromAccountSelect = document.getElementById('from_account_id');
            const toAccountSelect = document.getElementById('to_account_id');
            const selectedFromAccount = fromAccountSelect.value;
            toAccountSelect.querySelectorAll('option').forEach(option => {
                option.style.display = 'block';
                option.disabled = false;
            });
            if (selectedFromAccount) {
                const fromOption = toAccountSelect.querySelector('option[value="' + selectedFromAccount + '"]');
                if (fromOption) {
                    fromOption.style.display = 'none';
                    fromOption.disabled = true;
                }
                if (toAccountSelect.value === selectedFromAccount) toAccountSelect.value = '';
            }
            if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
                jQuery('#to_account_id').trigger('chosen:updated');
            }
            setTimeout(validateTransferAmount, 300);
        }

        document.getElementById('amount').addEventListener('input', validateTransferAmount);
        document.getElementById('from_account_id').addEventListener('change', function() { setTimeout(validateTransferAmount, 600); });
        // Also hook into Chosen's change event for the from_account select
        if (typeof jQuery !== 'undefined') {
            jQuery('#from_account_id').on('change', function() { setTimeout(validateTransferAmount, 600); });
        }

        let attachmentIndex = 1;
        function addAttachmentField() {
            const container = document.getElementById('attachments-container');
            const newGroup = document.createElement('div');
            newGroup.className = 'attachment-group mb-4';
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
                        <svg class="h-4 w-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            `;
            container.appendChild(newGroup);
            attachmentIndex++;
        }
    </script>
</x-app-layout>
