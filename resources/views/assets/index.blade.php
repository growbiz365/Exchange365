<x-app-layout>
    @section('title', 'Assets - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('assets.dashboard'), 'label' => 'Assets Dashboard'],
        ['url' => '#', 'label' => 'Assets'],
    ]" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-slate-50 via-white to-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6 mt-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-slate-100 rounded-lg">
                        <svg class="w-8 h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Assets</h1>
                    <p class="text-sm text-gray-500 mt-1">Manage and track your business assets</p>
                </div>
            </div>
            <div class="flex items-center">
                <x-button href="{{ route('assets.create') }}">Add Asset</x-button>
            </div>
        </div>
    </div>

    @if (Session::has('success'))
        <x-success-alert message="{{ Session::get('success') }}" />
    @endif
    @if (Session::has('error'))
        <x-error-alert message="{{ Session::get('error') }}" />
    @endif

    <!-- Filters Section -->
    <div class="bg-gray-100 rounded-lg shadow-sm border border-gray-200 px-4 py-3 mb-4">
        <form method="GET" action="{{ route('assets.index') }}">
            <div class="flex flex-col lg:flex-row lg:items-end lg:space-x-4 space-y-2 lg:space-y-0">
                <div class="flex-1 min-w-[160px]">
                    <label for="asset_name" class="sr-only">Asset Name</label>
                    <input type="text" id="asset_name" name="asset_name" value="{{ request('asset_name') }}"
                        class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-slate-500 focus:border-slate-500"
                        placeholder="Search by asset name" />
                </div>
                <div class="min-w-[140px]">
                    <label for="date_from" class="sr-only">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                        class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-slate-500 focus:border-slate-500" />
                </div>
                <div class="min-w-[140px]">
                    <label for="date_to" class="sr-only">Date To</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                        class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-slate-500 focus:border-slate-500" />
                </div>
                <div class="flex items-center space-x-2 mt-2 lg:mt-0">
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium rounded-md shadow-sm transition-colors duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('assets.index') }}" class="text-xs text-gray-500 hover:text-gray-700 px-2 py-1">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Assets List -->
    <div class="bg-gray-100 rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-slate-50 via-white to-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-slate-100 rounded-lg">
                        <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Asset List</h2>
                        <p class="text-sm text-gray-500">Total Records: {{ $assets->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($assets->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($assets as $a)
                        <tr
                            onclick="window.location.href='{{ route('assets.show', $a) }}'"
                            class="cursor-pointer hover:bg-gray-50 transition duration-150 ease-in-out"
                            title="Click to view asset"
                        >
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-700">{{ $a->asset_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">@businessDate($a->date_added)</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $a->category?->asset_category ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $a->asset_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">@currency($a->cost_amount)</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full {{ $a->asset_status === \App\Models\Asset::STATUS_SOLD ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $a->asset_status === \App\Models\Asset::STATUS_SOLD ? 'Sold Out' : 'Active' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation();">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('assets.show', $a) }}" class="text-slate-700 hover:text-slate-900">View</a>
                                    <a href="{{ route('assets.edit', $a) }}" class="text-slate-700 hover:text-slate-900">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $assets->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No assets found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new asset record.</p>
                <div class="mt-6">
                    <a href="{{ route('assets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-slate-700 hover:bg-slate-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Asset
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

