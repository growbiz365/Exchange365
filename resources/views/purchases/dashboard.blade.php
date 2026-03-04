<x-app-layout>
    @section('title', 'Purchase Dashboard - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('purchases.dashboard'), 'label' => 'Purchase Dashboard'],
    ]" />

    <div class="fixed inset-0 -z-10 bg-gradient-to-br from-amber-50/40 via-white to-orange-50/30 pointer-events-none"></div>

    <div class="relative backdrop-blur-xl bg-white/80 rounded-2xl shadow-xl shadow-amber-500/5 border border-white/60 p-6 mb-6 mt-4 overflow-hidden group">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-amber-400/20 to-orange-500/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
        <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-gradient-to-tr from-amber-400/15 to-orange-400/15 rounded-full blur-2xl"></div>

        <div class="relative flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative flex-shrink-0">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl blur-lg opacity-60 group-hover:opacity-80 transition-opacity duration-300"></div>
                    <div class="relative bg-gradient-to-br from-amber-500 to-orange-600 p-3 rounded-xl shadow-lg transform group-hover:scale-105 transition-all duration-300">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 via-amber-800 to-orange-800 bg-clip-text text-transparent mb-1">
                        Purchase Dashboard
                    </h1>
                    <p class="text-sm text-gray-600">Overview of purchase transactions and totals</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('purchases.create') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition hover:shadow-xl hover:shadow-amber-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Add Purchase
                </a>
                <a href="{{ route('purchases.index') }}"
                   class="inline-flex items-center justify-center rounded-xl backdrop-blur-sm bg-white/90 border border-gray-200/80 px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-gray-300 hover:bg-white hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    All Purchases
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="relative bg-gradient-to-br from-amber-50 via-amber-50 to-orange-100 rounded-xl shadow-sm border border-amber-100 p-6 overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="relative flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-semibold text-amber-700 uppercase tracking-wide">Total Purchases</p>
                    <p class="text-2xl font-bold text-amber-900 mt-1">{{ number_format($totalPurchases) }}</p>
                </div>
            </div>
        </div>

        <div class="relative bg-gradient-to-br from-slate-50 via-slate-50 to-slate-100 rounded-xl shadow-sm border border-slate-100 p-6 overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="relative flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-slate-600 to-slate-800 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Total Credit (Bank)</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">@currency($totalCredit)</p>
                </div>
            </div>
        </div>

        <div class="relative bg-gradient-to-br from-rose-50 via-rose-50 to-rose-100 rounded-xl shadow-sm border border-rose-100 p-6 overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="relative flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-semibold text-rose-700 uppercase tracking-wide">Total Debit (Party)</p>
                    <p class="text-2xl font-bold text-rose-900 mt-1">@currency($totalDebit)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="relative backdrop-blur-xl bg-white/80 rounded-xl shadow-lg shadow-slate-500/5 border border-white/60 p-6 mb-8 overflow-hidden">
        <div class="relative mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Recent Purchases
                </h2>
                <p class="text-sm text-gray-600">Latest purchase transactions</p>
            </div>
            <a href="{{ route('purchases.index') }}" class="text-xs font-semibold text-amber-700 hover:text-amber-900">View all</a>
        </div>

        @if($recentPurchases->count() > 0)
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Purchase #</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Date</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Bank</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Party</th>
                        <th class="px-4 py-2 text-right font-semibold text-gray-600">Credit</th>
                        <th class="px-4 py-2 text-right font-semibold text-gray-600">Debit</th>
                        <th class="px-4 py-2 text-right font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($recentPurchases as $p)
                        <tr class="hover:bg-amber-50/50 transition">
                            <td class="px-4 py-2 text-sm font-medium text-amber-700">{{ $p->purchase_id }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">@businessDate($p->date_added)</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $p->bank?->bank_name ?? '—' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $p->party?->party_name ?? '—' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 text-right">@currency($p->credit_amount)</td>
                            <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ $p->partyCurrency?->currency_symbol ?? '' }} {{ number_format($p->debit_amount ?? 0, 2) }}</td>
                            <td class="px-4 py-2 text-sm text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('purchases.show', $p) }}" class="text-amber-700 hover:text-amber-900 font-medium">View</a>
                                    <a href="{{ route('purchases.edit', $p) }}" class="text-amber-700 hover:text-amber-900 font-medium">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-sm text-gray-500">No purchases yet.</p>
        @endif
    </div>
</x-app-layout>
