<x-app-layout>
    @section('title', 'Assets Dashboard - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('assets.dashboard'), 'label' => 'Assets Dashboard'],
    ]" />

    {{-- Subtle background --}}
    <div class="fixed inset-0 -z-10 bg-gradient-to-br from-slate-50/40 via-white to-amber-50/30 pointer-events-none"></div>

    {{-- Header --}}
    <div class="relative bg-gray-100 rounded-2xl shadow-xl shadow-slate-500/5 border border-gray-200 p-6 mb-6 mt-4 overflow-hidden group">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-amber-400/20 to-slate-500/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
        <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-gradient-to-tr from-slate-400/15 to-amber-400/15 rounded-full blur-2xl"></div>

        <div class="relative flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative flex-shrink-0">
                    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 to-amber-600 rounded-xl blur-lg opacity-60 group-hover:opacity-80 transition-opacity duration-300"></div>
                    <div class="relative bg-gradient-to-br from-slate-900 to-amber-600 p-3 rounded-xl shadow-lg transform group-hover:scale-105 transition-all duration-300">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 via-slate-800 to-amber-800 bg-clip-text text-transparent mb-1">
                        Assets Dashboard
                    </h1>
                    <p class="text-sm text-gray-600">
                        Overview of your business assets, status, and recent activity
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('assets.create') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition hover:shadow-xl hover:shadow-amber-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Add Asset
                </a>
                <a href="{{ route('assets.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-gray-50 border border-gray-200/80 px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-gray-300 hover:bg-gray-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                    </svg>
                    All Assets
                </a>
                <a href="{{ route('asset-categories.index') }}"
                class="inline-flex items-center justify-center rounded-xl bg-gray-50 border border-gray-200/80 px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-gray-300 hover:bg-gray-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Assets --}}
        <div class="relative bg-gradient-to-br from-slate-50 via-slate-50 to-amber-100 rounded-xl shadow-sm border border-slate-100 p-6 overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 opacity-10">
                <svg viewBox="0 0 100 100" class="w-full h-full text-slate-600">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="25" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="10" fill="currentColor"/>
                </svg>
            </div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-slate-200 rounded-full -ml-8 -mb-8 opacity-20"></div>
            <div class="relative flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-slate-700 to-slate-900 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Total Assets</p>
                    </div>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($totalAssets) }}</p>
                </div>
            </div>
        </div>

        {{-- Active Assets --}}
        <div class="relative bg-gradient-to-br from-emerald-50 via-emerald-50 to-emerald-100 rounded-xl shadow-sm border border-emerald-100 p-6 overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 opacity-10">
                <svg viewBox="0 0 100 100" class="w-full h-full text-emerald-600">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="25" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="10" fill="currentColor"/>
                </svg>
            </div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-emerald-200 rounded-full -ml-8 -mb-8 opacity-20"></div>
            <div class="relative flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-emerald-700 uppercase tracking-wide">Active Assets</p>
                    </div>
                    <p class="text-2xl font-bold text-emerald-900 mt-1">{{ number_format($activeAssets) }}</p>
                </div>
            </div>
        </div>

        {{-- Sold Assets --}}
        <div class="relative bg-gradient-to-br from-rose-50 via-rose-50 to-rose-100 rounded-xl shadow-sm border border-rose-100 p-6 overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 opacity-10">
                <svg viewBox="0 0 100 100" class="w-full h-full text-rose-600">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="25" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="10" fill="currentColor"/>
                </svg>
            </div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-rose-200 rounded-full -ml-8 -mb-8 opacity-20"></div>
            <div class="relative flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-6-4h6m2 9H7a2 2 0 01-2-2V7a2 2 0 012-2h8l4 4v7a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-rose-700 uppercase tracking-wide">Sold Assets</p>
                    </div>
                    <p class="text-2xl font-bold text-rose-900 mt-1">{{ number_format($soldAssets) }}</p>
                </div>
            </div>
        </div>

        {{-- Asset Categories --}}
        <div class="relative bg-gradient-to-br from-amber-50 via-amber-50 to-amber-100 rounded-xl shadow-sm border border-amber-100 p-6 overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 opacity-10">
                <svg viewBox="0 0 100 100" class="w-full h-full text-amber-600">
                    <rect x="20" y="20" width="60" height="60" rx="8" fill="none" stroke="currentColor" stroke-width="2"/>
                    <path d="M20 45h60M50 20v60" stroke="currentColor" stroke-width="2"/>
                </svg>
            </div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-amber-200 rounded-full -ml-8 -mb-8 opacity-20"></div>
            <div class="relative flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-amber-700 uppercase tracking-wide">Asset Categories</p>
                    </div>
                    <p class="text-2xl font-bold text-amber-900 mt-1">{{ number_format($categoriesCount) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Totals --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div class="relative bg-gray-100 rounded-xl shadow-lg shadow-slate-500/5 border border-gray-200 p-5 overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-slate-400/10 rounded-full blur-2xl"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Total Purchase Cost</h2>
                    <p class="mt-2 text-2xl font-bold text-slate-900">@currency($totalCost)</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-slate-900 flex items-center justify-center text-white shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="relative bg-gray-100 rounded-xl shadow-lg shadow-emerald-500/5 border border-gray-200 p-5 overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-400/10 rounded-full blur-2xl"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-emerald-700 uppercase tracking-wide">Total Sale Value (Sold Assets)</h2>
                    <p class="mt-2 text-2xl font-bold text-emerald-900">@currency($totalSaleValue)</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-emerald-600 flex items-center justify-center text-white shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent assets --}}
    <div class="relative bg-gray-100 rounded-xl shadow-lg shadow-slate-500/5 border border-gray-200 p-6 mb-8 overflow-hidden">
        <div class="absolute -top-16 -left-16 w-40 h-40 bg-gradient-to-br from-slate-400/10 to-amber-400/10 rounded-full blur-3xl"></div>
        <div class="relative mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7.5l-9-4.5L3 7.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3 7.5l9 4.5M3 7.5v9l9 4.5m0-9v9" />
                    </svg>
                    Recent Assets
                </h2>
                <p class="text-sm text-gray-600">Latest assets added to your business</p>
            </div>
            <a href="{{ route('assets.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">
                View all
            </a>
        </div>

        @if($recentAssets->count() > 0)
            <div class="overflow-x-auto -mx-4 sm:-mx-6 md:mx-0">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Asset #</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Date</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Category</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-600">Name</th>
                            <th class="px-4 py-2 text-right font-semibold text-gray-600">Cost</th>
                            <th class="px-4 py-2 text-center font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-2 text-right font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($recentAssets as $asset)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-2 text-sm text-gray-800 font-medium">{{ $asset->asset_id }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">@businessDate($asset->date_added)</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $asset->category?->asset_category ?? '—' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $asset->asset_name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 text-right">@currency($asset->cost_amount)</td>
                                <td class="px-4 py-2 text-sm text-center">
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full {{ $asset->asset_status === \App\Models\Asset::STATUS_SOLD ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $asset->asset_status === \App\Models\Asset::STATUS_SOLD ? 'Sold' : 'Active' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-sm text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('assets.show', $asset) }}" class="text-slate-700 hover:text-slate-900 font-medium">View</a>
                                        <a href="{{ route('assets.edit', $asset) }}" class="text-slate-700 hover:text-slate-900 font-medium">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500">No assets have been added yet.</p>
        @endif
    </div>
</x-app-layout>

