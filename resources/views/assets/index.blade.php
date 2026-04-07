<x-app-layout>
    @section('title', 'Assets - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('assets.dashboard'), 'label' => 'Assets Dashboard'],
        ['url' => '#', 'label' => 'Assets'],
    ]" />

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6 mt-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start sm:items-center gap-3 sm:space-x-4 min-w-0">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-slate-50 border border-slate-100 rounded-lg">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">Assets</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Manage and track your business assets</p>
                </div>
            </div>
            <div class="w-full sm:w-auto shrink-0">
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
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-4 sm:px-5 py-4 mb-4">
        <form method="GET" action="{{ route('assets.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3 lg:items-end">
                <div class="min-w-0 sm:col-span-2 lg:col-span-2 xl:col-span-2">
                    <label for="asset_name" class="sr-only">Asset Name</label>
                    <input type="text" id="asset_name" name="asset_name" value="{{ request('asset_name') }}"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-slate-500 focus:border-slate-500"
                        placeholder="Search by asset name" />
                </div>
                <div class="min-w-0">
                    <label for="date_from" class="sr-only">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-slate-500 focus:border-slate-500" />
                </div>
                <div class="min-w-0">
                    <label for="date_to" class="sr-only">Date To</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-slate-500 focus:border-slate-500" />
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:col-span-2 lg:col-span-4 xl:col-span-1 xl:justify-end">
                    <button type="submit"
                        class="inline-flex flex-1 sm:flex-none justify-center items-center min-h-[2.25rem] px-4 py-2 sm:px-3 sm:py-1.5 bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium rounded-md shadow-sm transition-colors duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('assets.index') }}" class="inline-flex items-center justify-center text-xs text-gray-500 hover:text-gray-700 px-3 py-2 min-h-[2.25rem] sm:min-h-0 sm:py-1">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Assets List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-slate-50 border border-slate-100 rounded-lg">
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
            <div class="flow-root overflow-x-auto -mx-px">
            <table class="min-w-[900px] w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Asset ID</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Date</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Cost Amount</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($assets as $a)
                        <tr
                            onclick="window.location.href='{{ route('assets.show', $a) }}'"
                            class="cursor-pointer hover:bg-indigo-50/40 transition duration-150 ease-in-out"
                            title="Click to view asset"
                        >
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-700">{{ $a->asset_id }}</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">@businessDate($a->date_added)</td>
                            <td class="px-3 sm:px-6 py-4 text-sm text-gray-500 max-w-[8rem] sm:max-w-none truncate sm:whitespace-normal" title="{{ $a->category?->asset_category ?? '—' }}">{{ $a->category?->asset_category ?? '—' }}</td>
                            <td class="px-3 sm:px-6 py-4 text-sm text-gray-900 max-w-[10rem] sm:max-w-none truncate sm:whitespace-normal" title="{{ $a->asset_name }}">{{ $a->asset_name }}</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">@currency($a->cost_amount)</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full border {{ $a->asset_status === \App\Models\Asset::STATUS_SOLD ? 'bg-rose-50 text-rose-700 border-rose-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100' }}">
                                    {{ $a->asset_status === \App\Models\Asset::STATUS_SOLD ? 'Sold Out' : 'Active' }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation();">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('assets.show', $a) }}" class="text-slate-700 hover:text-slate-900">View</a>
                                    <a href="{{ route('assets.edit', $a) }}" class="text-slate-700 hover:text-slate-900">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 flex justify-center sm:justify-end overflow-x-auto">
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

