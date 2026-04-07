<x-app-layout>
    @section('title', 'Asset Categories - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        
        ['url' => '#', 'label' => 'Asset Categories'],
    ]" />

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6 mt-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start sm:items-center gap-3 sm:space-x-4 min-w-0">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-sky-50 border border-sky-100 rounded-lg">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M9 7V5a3 3 0 016 0v2" />
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">Asset Categories</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Configure categories for fixed and current assets</p>
                </div>
            </div>
            <div class="w-full sm:w-auto shrink-0">
                <x-button href="{{ route('asset-categories.create') }}">Add Asset Category</x-button>
            </div>
        </div>
    </div>

    @if (Session::has('success'))
        <x-success-alert message="{{ Session::get('success') }}" />
    @endif
    @if (Session::has('error'))
        <x-error-alert message="{{ Session::get('error') }}" />
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-4 sm:px-5 py-4 mb-4">
        <form method="GET" action="{{ route('asset-categories.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 lg:items-end">
                <div class="min-w-0 sm:col-span-2 lg:col-span-1 xl:col-span-1">
                    <label for="asset_category_id" class="sr-only">Category #</label>
                    <input type="number" id="asset_category_id" name="asset_category_id" value="{{ request('asset_category_id') }}"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500"
                        placeholder="Category # (ID)" min="1" />
                </div>
                <div class="min-w-0 sm:col-span-2 lg:col-span-1 xl:col-span-1">
                    <label for="asset_category" class="sr-only">Category Name</label>
                    <input type="text" id="asset_category" name="asset_category" value="{{ request('asset_category') }}"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500"
                        placeholder="Search by category name" />
                </div>
                <div class="min-w-0">
                    <label for="status" class="sr-only">Status</label>
                    <select id="status" name="status"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:col-span-2 lg:col-span-3 xl:col-span-2 xl:justify-end">
                    <button type="submit"
                        class="inline-flex flex-1 sm:flex-none justify-center items-center min-h-[2.25rem] px-4 py-2 sm:px-3 sm:py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('asset-categories.index') }}" class="inline-flex items-center justify-center text-xs text-gray-500 hover:text-gray-700 px-3 py-2 min-h-[2.25rem] sm:min-h-0 sm:py-1">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 bg-white">
            <div class="flex items-center space-x-3 min-w-0">
                <div class="p-2 bg-sky-50 border border-sky-100 rounded-lg">
                    <svg class="w-6 h-6 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M9 7V5a3 3 0 016 0v2" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Asset Category List</h2>
                    <p class="text-sm text-gray-500">Total Records: {{ $categories->total() }}</p>
                </div>
            </div>
        </div>

        @if($categories->count() > 0)
            <div class="overflow-x-auto -mx-px">
                <x-table-wrapper table-class="min-w-[700px] w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <x-table-header>#</x-table-header>
                            <x-table-header>Category #</x-table-header>
                            <x-table-header>Name</x-table-header>
                            <x-table-header>Status</x-table-header>
                            <x-table-header>Actions</x-table-header>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                            <tr
                                onclick="window.location.href='{{ route('asset-categories.edit', $cat) }}'"
                                class="cursor-pointer hover:bg-indigo-50/40 transition duration-150 ease-in-out"
                                title="Click to edit category"
                            >
                                <x-table-cell>{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</x-table-cell>
                                <x-table-cell>{{ $cat->asset_category_id }}</x-table-cell>
                                <x-table-cell class="font-medium">{{ $cat->asset_category }}</x-table-cell>
                                <x-table-cell>
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full border {{ $cat->status == 1 ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                                        {{ $cat->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </x-table-cell>
                                <x-table-cell>
                                    <div class="flex items-center gap-2" onclick="event.stopPropagation();">
                                        <a href="{{ route('asset-categories.edit', $cat) }}" class="text-sky-700 hover:text-sky-900 text-sm font-medium">Edit</a>
                                        <form action="{{ route('asset-categories.destroy', $cat) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this asset category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Delete</button>
                                        </form>
                                    </div>
                                </x-table-cell>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table-wrapper>
            </div>

            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 flex justify-center sm:justify-end overflow-x-auto">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M9 7V5a3 3 0 016 0v2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No asset categories found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new asset category.</p>
                <div class="mt-6">
                    <a href="{{ route('asset-categories.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Asset Category
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

