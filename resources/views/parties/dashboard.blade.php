<x-app-layout>
    @section('title', 'Parties Dashboard - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('parties.dashboard'), 'label' => 'Parties Dashboard']
    ]" />

    {{-- Header Section --}}
    <div class="relative bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 mb-5 mt-4 overflow-hidden group">
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-gradient-to-br from-amber-400/10 to-orange-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sm:flex-wrap">
            <div class="flex items-start sm:items-center gap-3 sm:space-x-4 min-w-0">
                <div class="relative flex-shrink-0">
                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-2.5 sm:p-3 rounded-xl shadow-lg transform group-hover:scale-105 transition-all duration-300">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.528A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-0.5 leading-tight">Parties Dashboard</h1>
                    <p class="text-xs sm:text-sm text-gray-500">Overview of your parties, balances, and recent activity</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 w-full sm:w-auto">
                <a href="{{ route('parties.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 px-4 sm:px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition hover:shadow-xl hover:shadow-amber-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 w-full sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Add Party
                </a>
                <a href="{{ route('party-transfers.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-white border border-gray-200 px-4 sm:px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-gray-300 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 w-full sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                    </svg>
                    New Transfer
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

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-5">

        {{-- Total Parties --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-amber-500 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.528A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Parties</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($totalParties) }}</p>
                </div>
            </div>
        </div>

        {{-- Total Transfers --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-rose-500 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Transfers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($totalTransfers) }}</p>
                </div>
            </div>
        </div>

        {{-- Parties with Balance --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-emerald-500 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Parties with Balance</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($partiesWithBalance) }}</p>
                </div>
            </div>
        </div>

        {{-- Currencies in Use --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-sky-500 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818.879.659 1.685 1.682a8.159 8.159 0 0 0 11.13.874c.94-.704 1.636-1.705 1.636-2.871 0-1.567-1.268-2.837-2.872-2.837-1.604 0-2.872 1.27-2.872 2.837 0 .857.324 1.587.677 2.197M12 6V4.5A2.5 2.5 0 1 0 9.5 7H12Z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Currencies in Use</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($currenciesInUse) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 mb-5">
        <div class="flex items-center gap-2 mb-4">
            <div class="bg-gradient-to-br from-indigo-600 to-purple-600 p-1.5 rounded-lg shadow-sm">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-gray-900">Quick Actions</h2>
                <p class="text-xs text-gray-500">Jump to common tasks</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 sm:gap-3">
            <a href="{{ route('parties.index') }}"
                class="group/card flex flex-col items-center text-center gap-1.5 sm:gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-amber-300 hover:shadow-md p-3 sm:p-4 transition-all duration-200 min-h-[7.5rem] sm:min-h-0">
                <div class="w-11 h-11 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.528A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-amber-700 transition-colors">All Parties</span>
                    <span class="block text-xs text-gray-400 mt-0.5">View and manage</span>
                </div>
            </a>

            <a href="{{ route('party-transfers.index') }}"
                class="group/card flex flex-col items-center text-center gap-1.5 sm:gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-sky-300 hover:shadow-md p-3 sm:p-4 transition-all duration-200 min-h-[7.5rem] sm:min-h-0">
                <div class="w-11 h-11 bg-gradient-to-br from-sky-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-sky-700 transition-colors">All Transfers</span>
                    <span class="block text-xs text-gray-400 mt-0.5">View and manage</span>
                </div>
            </a>

            <a href="{{ route('parties.ledger') }}"
                class="group/card flex flex-col items-center text-center gap-1.5 sm:gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-emerald-300 hover:shadow-md p-3 sm:p-4 transition-all duration-200 min-h-[7.5rem] sm:min-h-0">
                <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-emerald-700 transition-colors">Party Ledger</span>
                    <span class="block text-xs text-gray-400 mt-0.5">View by party</span>
                </div>
            </a>

            <a href="{{ route('parties.balances') }}"
                class="group/card flex flex-col items-center text-center gap-1.5 sm:gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-violet-300 hover:shadow-md p-3 sm:p-4 transition-all duration-200 min-h-[7.5rem] sm:min-h-0">
                <div class="w-11 h-11 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-violet-700 transition-colors">Balances</span>
                    <span class="block text-xs text-gray-400 mt-0.5">By currency</span>
                </div>
            </a>

            <a href="{{ route('parties.currency') }}"
                class="group/card flex flex-col items-center text-center gap-1.5 sm:gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-rose-300 hover:shadow-md p-3 sm:p-4 transition-all duration-200 min-h-[7.5rem] sm:min-h-0">
                <div class="w-11 h-11 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818.879.659 1.685 1.682a8.159 8.159 0 0 0 11.13.874c.94-.704 1.636-1.705 1.636-2.871 0-1.567-1.268-2.837-2.872-2.837-1.604 0-2.872 1.27-2.872 2.837 0 .857.324 1.587.677 2.197M12 6V4.5A2.5 2.5 0 1 0 9.5 7H12Z"/>
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-rose-700 transition-colors">Currency Breakdown</span>
                    <span class="block text-xs text-gray-400 mt-0.5">By party & currency</span>
                </div>
            </a>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">

        {{-- Recent Parties --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-1.5 rounded-lg shadow-sm shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.528A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Recent Parties</h3>
                        <p class="text-xs text-gray-500">Latest party entries</p>
                    </div>
                </div>
                <a href="{{ route('parties.index') }}"
                    class="group/link flex items-center justify-center sm:justify-start gap-1 px-3 py-2 sm:py-1.5 bg-amber-50 hover:bg-amber-100 rounded-lg border border-amber-100 hover:border-amber-200 transition-all duration-200 w-full sm:w-auto shrink-0">
                    <span class="text-xs font-semibold text-amber-700">View all</span>
                    <svg class="w-3.5 h-3.5 text-amber-600 group-hover/link:translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="space-y-2">
                @forelse($recentParties as $party)
                <div class="relative group/item">
                    @if(!$loop->last)
                    <div class="absolute left-5 top-12 bottom-0 w-0.5 bg-gradient-to-b from-amber-200 to-transparent"></div>
                    @endif
                    <a href="{{ route('parties.edit', $party) }}"
                        class="flex items-start gap-3 p-3 bg-gray-50 hover:bg-white rounded-lg border border-gray-200 hover:border-amber-200 hover:shadow-sm transition-all duration-200">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center shadow-sm group-hover/item:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.528A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-semibold text-gray-900 group-hover/item:text-amber-700 transition-colors">{{ $party->party_name }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mb-1.5 flex flex-wrap items-center gap-1.5">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $party->party_type == 1 ? 'bg-blue-50 text-blue-700' : 'bg-violet-50 text-violet-700' }}">
                                    {{ $party->party_type_label }}
                                </span>
                                <span class="text-gray-400">Since {{ $party->opening_date->format('d M Y') }}</span>
                            </p>
                            <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:justify-between">
                                @if($party->openingBalances->count() > 0)
                                    <span class="text-xs text-gray-500">
                                        @foreach($party->openingBalances->take(2) as $ob)
                                            {{ $ob->currency?->currency_symbol ?? '-' }} {{ number_format($ob->opening_balance, 0) }}{{ !$loop->last ? ' · ' : '' }}
                                        @endforeach
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">No balance</span>
                                @endif
                                <svg class="h-4 w-4 text-gray-300 group-hover/item:text-amber-400 group-hover/item:translate-x-0.5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="text-center py-10">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.528A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">No parties yet</h3>
                    <p class="text-xs text-gray-500 mb-3">Add your first party to get started</p>
                    <a href="{{ route('parties.create') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-500 transition-colors">Add your first party</a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Transfers --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="bg-gradient-to-br from-sky-500 to-blue-600 p-1.5 rounded-lg shadow-sm shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Recent Transfers</h3>
                        <p class="text-xs text-gray-500">Latest transfer activities</p>
                    </div>
                </div>
                <a href="{{ route('party-transfers.index') }}"
                    class="group/link flex items-center justify-center sm:justify-start gap-1 px-3 py-2 sm:py-1.5 bg-sky-50 hover:bg-sky-100 rounded-lg border border-sky-100 hover:border-sky-200 transition-all duration-200 w-full sm:w-auto shrink-0">
                    <span class="text-xs font-semibold text-sky-700">View all</span>
                    <svg class="w-3.5 h-3.5 text-sky-600 group-hover/link:translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="space-y-2">
                @forelse($recentTransfers as $transfer)
                <div class="relative group/item">
                    @if(!$loop->last)
                    <div class="absolute left-5 top-12 bottom-0 w-0.5 bg-gradient-to-b from-sky-200 to-transparent"></div>
                    @endif
                    <a href="{{ route('party-transfers.edit', $transfer) }}"
                        class="flex items-start gap-3 p-3 bg-gray-50 hover:bg-white rounded-lg border border-gray-200 hover:border-sky-200 hover:shadow-sm transition-all duration-200">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-sky-500 to-blue-600 rounded-lg flex items-center justify-center shadow-sm group-hover/item:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900 group-hover/item:text-sky-700 transition-colors min-w-0 break-words">
                                    {{ $transfer->debitParty?->party_name ?? '—' }} <span class="text-gray-400 font-normal whitespace-nowrap">→</span> {{ $transfer->creditParty?->party_name ?? '—' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mb-1.5 break-words">
                                {{ $transfer->date_added->format('d M Y') }}
                                <span class="mx-1 text-gray-300">·</span>
                                {{ $transfer->debitCurrency?->currency ?? '-' }} / {{ $transfer->creditCurrency?->currency ?? '-' }}
                            </p>
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                <span class="text-sm font-bold text-gray-900">{{ number_format($transfer->debit_amount, 2) }}</span>
                                <svg class="h-4 w-4 text-gray-300 group-hover/item:text-sky-400 group-hover/item:translate-x-0.5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="text-center py-10">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">No transfers yet</h3>
                    <p class="text-xs text-gray-500 mb-3">Record a transfer between parties</p>
                    <a href="{{ route('party-transfers.create') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-500 transition-colors">Create a transfer</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
