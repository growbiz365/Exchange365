<x-app-layout>
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('asset-categories.index'), 'label' => 'Asset Categories'],
        ['url' => '#', 'label' => 'Add Asset Category'],
    ]" />

    <x-dynamic-heading title="Add Asset Category" />

    <form action="{{ route('asset-categories.store') }}" method="POST">
        @csrf
        <div class="bg-gray-100 shadow-sm sm:rounded-lg border border-gray-200 p-8 mt-4">
            <div class="pb-10 mb-10 border-b border-gray-150 my-8">
                <h2 class="text-lg font-semibold text-gray-900">Asset Category Information</h2>
                <p class="mt-1 text-sm text-gray-600">Please provide details of the asset category.</p>

                <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1 mb-4">
                        <x-input-label for="asset_category">Asset Category <span class="text-red-500">*</span></x-input-label>
                        <div class="mt-2">
                            <x-text-input name="asset_category" id="asset_category" value="{{ old('asset_category') }}" class="uppercase w-full" required />
                        </div>
                        <x-input-error :messages="$errors->get('asset_category')" class="mt-1" />
                    </div>
                    <div class="sm:col-span-1 mb-4 ml-0 sm:ml-4">
                        <x-input-label for="status">Status <span class="text-red-500">*</span></x-input-label>
                        <div class="mt-2">
                            <select id="status" name="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('asset-categories.index') }}" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500">Cancel</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 ml-2">Save</button>
            </div>
        </div>
    </form>
</x-app-layout>

