<x-app-layout>
    @section('title', 'Assets Dashboard - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('assets.dashboard'), 'label' => 'Assets Dashboard'],
    ]" />

    {{-- Header --}}
    <div class="relative bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 mb-5 mt-4 overflow-hidden group">
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-gradient-to-br from-slate-400/10 to-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sm:flex-wrap">
            <div class="flex items-start sm:items-center gap-3 sm:space-x-4 min-w-0">
                <div class="flex-shrink-0">
                    <div class="bg-gradient-to-br from-slate-800 to-amber-600 p-2.5 sm:p-3 rounded-xl shadow-lg transform group-hover:scale-105 transition-all duration-300">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-0.5 leading-tight">Assets Dashboard</h1>
                    <p class="text-xs sm:text-sm text-gray-500">Overview of your business assets, status, and recent activity</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 w-full sm:w-auto">
                <a href="{{ route('assets.create') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 px-4 sm:px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition hover:shadow-xl hover:shadow-amber-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 w-full sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Add Asset
                </a>
                <a href="{{ route('assets.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white border border-gray-200 px-4 sm:px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-gray-300 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 w-full sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                    </svg>
                    All Assets
                </a>
                <a href="{{ route('asset-categories.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-white border border-gray-200 px-4 sm:px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-gray-300 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 w-full sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                    </svg>
                    Asset Categories
                </a>
            </div>
        </div>
    </div>

    @if (Session::has('success'))
        <x-success-alert message="{{ Session::get('success') }}" />
    @endif
    @if (Session::has('error'))
        <x-error-alert message="{{ Session::get('error') }}" />
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        {{-- Total Assets --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-slate-700 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-slate-700 to-slate-900 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Assets</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($totalAssets) }}</p>
                </div>
            </div>
        </div>

        {{-- Active Assets --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-emerald-500 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Active Assets</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($activeAssets) }}</p>
                </div>
            </div>
        </div>

        {{-- Sold Assets --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-rose-500 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-6-4h6m2 9H7a2 2 0 01-2-2V7a2 2 0 012-2h8l4 4v7a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sold Assets</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($soldAssets) }}</p>
                </div>
            </div>
        </div>

        {{-- Asset Categories --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-amber-500 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Asset Categories</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($categoriesCount) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Totals --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-slate-700 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Purchase Cost</p>
                    <p class="mt-1.5 text-2xl font-bold text-gray-900">@currency($totalCost)</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-slate-700 to-slate-900 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-emerald-500 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Sale Value (Sold Assets)</p>
                    <p class="mt-1.5 text-2xl font-bold text-gray-900">@currency($totalSaleValue)</p>
                </div>
                <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Assets Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 mb-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div class="flex items-center gap-2 min-w-0">
                <div class="bg-gradient-to-br from-slate-700 to-amber-600 p-1.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Recent Assets</h2>
                    <p class="text-xs text-gray-500">Latest assets added to your business</p>
                </div>
            </div>
            <a href="{{ route('assets.index') }}"
                class="group/link flex items-center justify-center sm:justify-start gap-1 px-3 py-2 sm:py-1.5 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 w-full sm:w-auto shrink-0">
                <span class="text-xs font-semibold text-gray-600">View all</span>
                <svg class="w-3.5 h-3.5 text-gray-500 group-hover/link:translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if($recentAssets->count() > 0)
            <div class="flow-root overflow-x-auto -mx-4 sm:-mx-5 sm:mx-0">
                <table class="min-w-[720px] w-full divide-y divide-gray-100 text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Asset #</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Date</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Category</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Name</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Cost</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($recentAssets as $asset)
                            <tr class="hover:bg-amber-50/30 transition-colors duration-150">
                                <td class="px-4 py-2.5 text-sm font-semibold text-gray-700">{{ $asset->asset_id }}</td>
                                <td class="px-4 py-2.5 text-sm text-gray-600">@businessDate($asset->date_added)</td>
                                <td class="px-4 py-2.5 text-sm text-gray-600 max-w-[7rem] sm:max-w-none truncate sm:whitespace-normal" title="{{ $asset->category?->asset_category ?? '—' }}">{{ $asset->category?->asset_category ?? '—' }}</td>
                                <td class="px-4 py-2.5 text-sm font-medium text-gray-900 max-w-[8rem] sm:max-w-none truncate sm:whitespace-normal" title="{{ $asset->asset_name }}">{{ $asset->asset_name }}</td>
                                <td class="px-4 py-2.5 text-sm font-medium text-gray-900 text-right">@currency($asset->cost_amount)</td>
                                <td class="px-4 py-2.5 text-sm text-center">
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full {{ $asset->asset_status === \App\Models\Asset::STATUS_SOLD ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}">
                                        {{ $asset->asset_status === \App\Models\Asset::STATUS_SOLD ? 'Sold' : 'Active' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-sm text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <a href="{{ route('assets.show', $asset) }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900 transition-colors">View</a>
                                        <a href="{{ route('assets.edit', $asset) }}" class="text-xs font-semibold text-gray-500 hover:text-gray-700 transition-colors">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-10">
                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-900 mb-1">No assets yet</p>
                <p class="text-xs text-gray-500">Add your first asset to get started.</p>
            </div>
        @endif
    </div>
</x-app-layout>
