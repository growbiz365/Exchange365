<x-app-layout>
    @section('title', 'Edit Party - Party Management - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[['url' => '/', 'label' => 'Home'],  ['url' => route('parties.dashboard'), 'label' => 'Parties Dashboard'],['url' => route('parties.index'), 'label' => 'Parties'], ['url' => '#', 'label' => 'Edit Party']]" />

    <x-dynamic-heading title="Edit Party" />

    <form action="{{ route('parties.update', $party) }}" method="POST" id="partyForm">
        @csrf
        @method('PUT')

        {{-- Error Display --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Whoops! Something went wrong.</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (Session::has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Error!</strong>
                <p>{{ Session::get('error') }}</p>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
            <div class="flex items-start gap-3 mb-5">
                <div class="bg-gradient-to-br from-indigo-600 to-slate-700 p-2 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 01-8 0 4 4 0 018 0ZM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Party Information</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Update party details.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-4">
                <!-- Name -->
                <div>
                    <x-input-label for="party_name">Name <span class="text-red-500">*</span></x-input-label>
                    <x-text-input id="party_name" name="party_name" value="{{ old('party_name', $party->party_name) }}" required class="uppercase" />
                    <x-input-error :messages="$errors->get('party_name')" class="mt-2" />
                </div>

                <!-- Phone -->
                <div>
                    <x-input-label for="contact_no">Phone Number</x-input-label>
                    <x-text-input id="contact_no" name="contact_no" value="{{ old('contact_no', $party->contact_no) }}" data-mask="0000-0000000" placeholder="03XX-XXXXXXX" />
                    @error('contact_no') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Party Type -->
                <div>
                    <x-input-label for="party_type">Party Type <span class="text-red-500">*</span></x-input-label>
                    <select id="party_type" name="party_type"
                        class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="1" {{ old('party_type', $party->party_type) == '1' ? 'selected' : '' }}>Khata Party</option>
                        <option value="2" {{ old('party_type', $party->party_type) == '2' ? 'selected' : '' }}>Other Party</option>
                    </select>
                    @error('party_type') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Opening Date -->
                <div>
                    <x-input-label for="opening_date">Opening Date <span class="text-red-500">*</span></x-input-label>
                    <x-text-input id="opening_date" type="date" name="opening_date" value="{{ old('opening_date', $party->opening_date->format('Y-m-d')) }}" required />
                    @error('opening_date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div>
                    <x-input-label for="status">Status <span class="text-red-500">*</span></x-input-label>
                    <select id="status" name="status"
                        class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="1" {{ old('status', $party->status) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $party->status) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Opening Balances Section -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 mt-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Opening Balances (Multi-Currency)</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Update opening balances for different currencies</p>
                </div>
                <button type="button" onclick="addBalanceRow()" class="rounded-md bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-500">
                    + Add Currency
                </button>
            </div>

            <div id="balance-rows" class="space-y-3">
                @forelse($party->openingBalances as $index => $balance)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-12 items-center p-4 bg-gray-50 rounded-lg border border-gray-200" id="balance-row-{{ $index }}">
                        <div class="sm:col-span-4 min-w-0">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Currency <span class="text-red-500">*</span></label>
                            <select name="opening_balances[{{ $index }}][currency_id]" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Select Currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->currency_id }}" {{ $balance->currency_id == $currency->currency_id ? 'selected' : '' }}>
                                        {{ $currency->currency }} ({{ $currency->currency_symbol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-3 min-w-0">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Opening Balance <span class="text-red-500">*</span></label>
                            <input type="number" name="opening_balances[{{ $index }}][opening_balance]" step="0.01" min="0" required
                                value="{{ $balance->opening_balance }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="0.00">
                        </div>
                        <div class="sm:col-span-3 min-w-0">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Entry Type <span class="text-red-500">*</span></label>
                            <select name="opening_balances[{{ $index }}][entry_type]" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="1" {{ $balance->entry_type == 1 ? 'selected' : '' }}>Credit (Jama - We Owe Them)</option>
                                <option value="2" {{ $balance->entry_type == 2 ? 'selected' : '' }}>Debit (Banam - They Owe Us)</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2 flex items-center justify-end shrink-0 min-w-[6.5rem]">
                            <button type="button" onclick="removeBalanceRow({{ $index }})"
                                class="inline-flex items-center shrink-0 px-2.5 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5.5A1.5 1.5 0 0 1 10.5 4h3A1.5 1.5 0 0 1 15 5.5V7m-6 0h6m-7 0h8l-.8 11.2A1.5 1.5 0 0 1 13.7 20H10.3a1.5 1.5 0 0 1-1.5-1.3L8 7Z" />
                                </svg>
                                <span class="ml-1">Remove</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div id="empty-state" class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="mx-auto w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <p class="text-sm">No opening balances added.</p>
                        <p class="text-xs text-gray-400 mt-0.5">Click “Add Currency” to add opening balance.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex items-center justify-end gap-x-4">
            <a href="{{ route('parties.index') }}" class="rounded-md bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-500">Cancel</a>
            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-500">Update</button>
        </div>
    </form>
</x-app-layout>

{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(function() {
    $('[name="contact_no"]').mask('0000-0000000');
});

let balanceRowIndex = {{ $party->openingBalances->count() }};
const currencies = @json($currencies);

function addBalanceRow() {
    const container = document.getElementById('balance-rows');
    const emptyState = document.getElementById('empty-state');
    
    if (emptyState) {
        emptyState.remove();
    }

    const row = document.createElement('div');
    row.className = 'grid grid-cols-1 gap-4 sm:grid-cols-12 items-center p-4 bg-gray-50 rounded-lg border border-gray-200';
    row.id = `balance-row-${balanceRowIndex}`;
    
    row.innerHTML = `
        <div class="sm:col-span-4 min-w-0">
            <label class="block text-sm font-medium text-gray-700 mb-1">Currency <span class="text-red-500">*</span></label>
            <select name="opening_balances[${balanceRowIndex}][currency_id]" required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Select Currency</option>
                ${currencies.map(c => `<option value="${c.currency_id}">${c.currency} (${c.currency_symbol})</option>`).join('')}
            </select>
        </div>
        <div class="sm:col-span-3 min-w-0">
            <label class="block text-sm font-medium text-gray-700 mb-1">Opening Balance <span class="text-red-500">*</span></label>
            <input type="number" name="opening_balances[${balanceRowIndex}][opening_balance]" step="0.01" min="0" required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                placeholder="0.00">
        </div>
        <div class="sm:col-span-3 min-w-0">
            <label class="block text-sm font-medium text-gray-700 mb-1">Entry Type <span class="text-red-500">*</span></label>
            <select name="opening_balances[${balanceRowIndex}][entry_type]" required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="1">Credit (Jama - We Owe Them)</option>
                <option value="2">Debit (Banam - They Owe Us)</option>
            </select>
        </div>
        <div class="sm:col-span-2 flex items-center justify-end shrink-0 min-w-[6.5rem]">
            <button type="button" onclick="removeBalanceRow(${balanceRowIndex})"
                class="inline-flex items-center shrink-0 px-2.5 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5.5A1.5 1.5 0 0 1 10.5 4h3A1.5 1.5 0 0 1 15 5.5V7m-6 0h6m-7 0h8l-.8 11.2A1.5 1.5 0 0 1 13.7 20H10.3a1.5 1.5 0 0 1-1.5-1.3L8 7Z" />
                </svg>
                <span class="ml-1">Remove</span>
            </button>
        </div>
    `;
    
    container.appendChild(row);
    balanceRowIndex++;
}

function removeBalanceRow(index) {
    const row = document.getElementById(`balance-row-${index}`);
    if (row) {
        row.remove();
    }
    
    // Show empty state if no rows left
    const container = document.getElementById('balance-rows');
    if (container.children.length === 0) {
        container.innerHTML = `
            <div id="empty-state" class="text-center py-6 text-gray-500 bg-gray-50 rounded-md">
                <p>No opening balances added. Click "Add Currency" to add opening balance.</p>
            </div>
        `;
    }
}
</script>
