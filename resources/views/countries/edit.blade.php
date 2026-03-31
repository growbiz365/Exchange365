<x-app-layout>
    <x-breadcrumb :breadcrumbs="[['url' => '/', 'label' => 'Home'],     ['url' => '/settings', 'label' => 'Settings'], ['url' => '/countries', 'label' => 'Countries'], ['url' => '#', 'label' => 'Edit Country']]" />

    <x-dynamic-heading title="Edit Country" />

    <form action="{{ route('countries.update', $country->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 mt-4">
            <div class="pb-6 mb-6 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="bg-gradient-to-br from-indigo-600 to-slate-700 p-1.5 rounded-lg shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.6 9h16.8M3.6 15h16.8M10 3.6c-2.2 2.4-3.4 5.6-3.4 8.4S7.8 18 10 20.4m4-16.8c2.2 2.4 3.4 5.6 3.4 8.4s-1.2 6-3.4 8.4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 leading-tight">Country Information</h2>
                        <p class="mt-0.5 text-xs text-gray-500">Please update the details of the country.</p>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1 mb-4">
                        <x-input-label for="country_name">Country Name <span class="text-red-500">*</span></x-input-label>
                        <x-text-input name="country_name" value="{{ old('country_name', $country->country_name) }}" required />
                    </div>

                    <div class="sm:col-span-1 mb-4 ml-4">
                        <x-input-label for="country_code">Country Code <span class="text-red-500">*</span></x-input-label>
                        <x-text-input name="country_code" value="{{ old('country_code', $country->country_code) }}" required />
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('countries.index') }}" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500">Cancel</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 ml-2">Update</button>
            </div>
        </div>
    </form>
</x-app-layout>
