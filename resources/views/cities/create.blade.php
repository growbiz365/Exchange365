<x-app-layout>
    <x-breadcrumb :breadcrumbs="[['url' => '/', 'label' => 'Home'],     ['url' => '/settings', 'label' => 'Settings'], ['url' => '/cities', 'label' => 'Cities'], ['url' => '#', 'label' => 'Add City']]" />

    <x-dynamic-heading title="Add City" />

    <form action="{{ route('cities.store') }}" method="POST">
        @csrf
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 mt-4">
            <div class="pb-6 mb-6 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="bg-gradient-to-br from-indigo-600 to-slate-700 p-1.5 rounded-lg shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414a4 4 0 10-5.657 5.657l4.243 4.243a8 8 0 115.657-5.657z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 leading-tight">City Information</h2>
                        <p class="mt-0.5 text-xs text-gray-500">Please provide details of the city.</p>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1 mb-4">
                        <x-input-label for="name">City Name <span class="text-red-500">*</span></x-input-label>
                        <div class="mt-2">
                        <x-text-input name="name" value="{{ old('name') }}" required />
                        </div>
                    </div>

                    <div class="sm:col-span-1 mb-4 ml-4">
                        <x-input-label for="country_id">Country <span class="text-red-500">*</span></x-input-label>
                        <x-dynamic-combobox label="Select Country" id="country_id" fetchUrl="{{ route('countries.search') }}" placeholder="Search for a Country..." :defaultValue="old('country_id')" required />
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('cities.index') }}" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500">Cancel</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 ml-2">Save</button>
            </div>
        </div>
    </form>
</x-app-layout>
