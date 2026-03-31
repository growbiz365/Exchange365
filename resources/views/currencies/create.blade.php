<x-app-layout>
    <x-breadcrumb :breadcrumbs="[['url' => '/', 'label' => 'Home'],     ['url' => '/settings', 'label' => 'Settings'], ['url' => '/currencies', 'label' => 'Currencies'], ['url' => '#', 'label' => 'Add Currency']]" />

    <x-dynamic-heading title="Add Currency" />

    <form action="{{ route('currencies.store') }}" method="POST">
        @csrf
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 mt-4">
            <div class="pb-6 mb-6 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="bg-gradient-to-br from-indigo-600 to-slate-700 p-1.5 rounded-lg shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V6m0 12v-2" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 leading-tight">Currency Information</h2>
                        <p class="mt-0.5 text-xs text-gray-500">Please provide details of the currency.</p>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1 mb-4">
                        <x-input-label for="currency_code">Currency Code <span class="text-red-500">*</span></x-input-label>
                        <x-text-input name="currency_code" value="{{ old('currency_code') }}" required />
                    </div>

                    <div class="sm:col-span-1 mb-4 ml-4">
                        <x-input-label for="currency_name">Currency Name <span class="text-red-500">*</span></x-input-label>
                        <x-text-input name="currency_name" value="{{ old('currency_name') }}" required />
                    </div>

                    <div class="sm:col-span-1 mb-4">
                        <x-input-label for="symbol">Symbol</x-input-label>
                        <x-text-input name="symbol" value="{{ old('symbol') }}" />
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('currencies.index') }}" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500">Cancel</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 ml-2">Save</button>
            </div>
        </div>
    </form>
</x-app-layout>
