<x-app-layout>
    @section('title', 'Edit Bank - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('banks.dashboard'), 'label' => 'Banks Dashboard'],
        ['url' => route('banks.index'), 'label' => 'Banks'],
        ['url' => route('banks.edit', $bank), 'label' => 'Edit Bank']
    ]" />

    <x-dynamic-heading title="Edit Bank" />

    <form action="{{ route('banks.update', $bank) }}" method="POST">
        @csrf
        @method('PUT')

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

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 mt-4">
            <div class="flex items-start gap-3 mb-5">
                <div class="bg-gradient-to-br from-sky-500 to-indigo-600 p-2 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Bank Information</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Update bank account details.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-4">
                <div>
                    <x-input-label for="bank_name">Bank Name <span class="text-red-500">*</span></x-input-label>
                    <x-text-input id="bank_name" name="bank_name" value="{{ old('bank_name', $bank->bank_name) }}" required class="uppercase" />
                    <x-input-error :messages="$errors->get('bank_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="currency_id">Currency <span class="text-red-500">*</span></x-input-label>
                    <select id="currency_id" name="currency_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Currency</option>
                        @foreach($currencies as $c)
                            <option value="{{ $c->currency_id }}" {{ old('currency_id', $bank->currency_id) == $c->currency_id ? 'selected' : '' }}>{{ $c->currency }} ({{ $c->currency_symbol }})</option>
                        @endforeach
                    </select>
                    @error('currency_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <x-input-label for="account_number">Account Number</x-input-label>
                    <x-text-input id="account_number" name="account_number" value="{{ old('account_number', $bank->account_number) }}" />
                    @error('account_number') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <x-input-label for="bank_type_id">Bank Type <span class="text-red-500">*</span></x-input-label>
                    <select id="bank_type_id" name="bank_type_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($bankTypes as $bt)
                            <option value="{{ $bt->bank_type_id }}" {{ old('bank_type_id', $bank->bank_type_id) == $bt->bank_type_id ? 'selected' : '' }}>{{ $bt->bank_type }}</option>
                        @endforeach
                    </select>
                    @error('bank_type_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <x-input-label for="opening_balance">Opening Balance <span class="text-red-500">*</span></x-input-label>
                    <x-text-input id="opening_balance" name="opening_balance" type="number" step="0.01" min="0" value="{{ old('opening_balance', $bank->opening_balance) }}" required />
                    @error('opening_balance') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <x-input-label for="status">Status <span class="text-red-500">*</span></x-input-label>
                    <select id="status" name="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="1" {{ old('status', $bank->status) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $bank->status) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-4">
            <a href="{{ route('banks.index') }}" class="rounded-md bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-500">Cancel</a>
            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-500">Update</button>
        </div>
    </form>
</x-app-layout>
