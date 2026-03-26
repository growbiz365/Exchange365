<x-app-layout>
    @section('title', 'Parties Dashboard - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('parties.dashboard'), 'label' => 'Parties Dashboard']
    ]" />

    {{-- Subtle Background Gradient --}}
    <div class="fixed inset-0 -z-10 bg-gradient-to-br from-blue-50/30 via-white to-purple-50/20 pointer-events-none"></div>

    {{-- Header Section - Glass Morphism --}}
    <div class="relative bg-gray-100 rounded-2xl shadow-xl shadow-blue-500/5 border border-gray-200 p-6 mb-6 mt-4 overflow-hidden group">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
        <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-gradient-to-tr from-indigo-400/15 to-blue-400/15 rounded-full blur-2xl"></div>

        <div class="relative flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative flex-shrink-0">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl blur-lg opacity-60 group-hover:opacity-80 transition-opacity duration-300"></div>
                    <div class="relative bg-gradient-to-br from-amber-500 to-orange-600 p-3 rounded-xl shadow-lg transform group-hover:scale-105 transition-all duration-300">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.528A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 via-amber-800 to-orange-900 bg-clip-text text-transparent mb-1">Parties Dashboard</h1>
                    <p class="text-sm text-gray-600">Overview of your parties, balances, and recent activity</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('parties.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/25 transition hover:shadow-xl hover:shadow-amber-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Add Party
                </a>
                <a href="{{ route('party-transfers.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-gray-50 border border-gray-200/80 px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-gray-300 hover:bg-gray-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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

    {{-- Stats grid - Enhanced Design --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Parties --}}
        <div class="relative bg-gradient-to-br from-amber-50 via-amber-50 to-orange-100 rounded-xl shadow-sm border border-amber-100 p-6 overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 opacity-10">
                <svg viewBox="0 0 100 100" class="w-full h-full text-amber-600">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="25" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="10" fill="currentColor"/>
                </svg>
            </div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-amber-200 rounded-full -ml-8 -mb-8 opacity-20"></div>
            <div class="relative flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.528A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-amber-700 uppercase tracking-wide">Total Parties</p>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-amber-400 rounded-full"></div>
                            <div class="w-1 h-1 bg-amber-300 rounded-full"></div>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-amber-900 mt-1">{{ number_format($totalParties) }}</p>
                </div>
            </div>
        </div>

        {{-- Total Transfers --}}
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-rose-700 uppercase tracking-wide">Total Transfers</p>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-rose-400 rounded-full"></div>
                            <div class="w-1 h-1 bg-rose-300 rounded-full"></div>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-rose-900 mt-1">{{ number_format($totalTransfers) }}</p>
                </div>
            </div>
        </div>

        {{-- Parties with Balance --}}
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-emerald-700 uppercase tracking-wide">Parties with Balance</p>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full"></div>
                            <div class="w-1 h-1 bg-emerald-300 rounded-full"></div>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-emerald-900 mt-1">{{ number_format($partiesWithBalance) }}</p>
                </div>
            </div>
        </div>

        {{-- Currencies in Use --}}
        <div class="relative bg-gradient-to-br from-sky-50 via-sky-50 to-sky-100 rounded-xl shadow-sm border border-sky-100 p-6 overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 opacity-10">
                <svg viewBox="0 0 100 100" class="w-full h-full text-sky-600">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="25" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="10" fill="currentColor"/>
                </svg>
            </div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-sky-200 rounded-full -ml-8 -mb-8 opacity-20"></div>
            <div class="relative flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818.879.659 1.685 1.682a8.159 8.159 0 0 0 11.13.874c.94-.704 1.636-1.705 1.636-2.871 0-1.567-1.268-2.837-2.872-2.837-1.604 0-2.872 1.27-2.872 2.837 0 .857.324 1.587.677 2.197M12 6V4.5A2.5 2.5 0 1 0 9.5 7H12Z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-sky-700 uppercase tracking-wide">Currencies in Use</p>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-sky-400 rounded-full"></div>
                            <div class="w-1 h-1 bg-sky-300 rounded-full"></div>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-sky-900 mt-1">{{ number_format($currenciesInUse) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions - Command Palette Style --}}
    <div class="relative bg-gray-100 rounded-xl shadow-lg shadow-indigo-500/5 border border-gray-200 p-6 mb-6 overflow-hidden">
        <div class="absolute -top-20 -left-20 w-40 h-40 bg-gradient-to-br from-indigo-400/10 to-purple-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-gradient-to-br from-blue-400/10 to-indigo-400/10 rounded-full blur-3xl"></div>

        <div class="relative mb-6">
            <h2 class="text-xl font-bold bg-gradient-to-r from-gray-900 to-indigo-900 bg-clip-text text-transparent flex items-center">
                <div class="relative mr-2.5">
                    <div class="absolute inset-0 bg-indigo-500 rounded-lg blur opacity-40"></div>
                    <svg class="relative w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                Quick Actions
            </h2>
            <p class="text-sm text-gray-600 mt-1 ml-8">Jump to common tasks</p>
        </div>

        <div class="relative grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <a href="{{ route('parties.index') }}"
                class="group relative bg-gray-50 rounded-xl border border-gray-200 p-4 hover:shadow-xl hover:shadow-amber-500/20 hover:-translate-y-1 hover:scale-105 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 to-orange-500/0 group-hover:from-amber-500/10 group-hover:to-orange-500/5 transition-all duration-300 rounded-xl"></div>
                <div class="absolute -top-6 -right-6 w-16 h-16 bg-amber-400/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col items-center text-center space-y-2.5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg blur-md opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-11 h-11 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all duration-300">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.528A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 group-hover:text-amber-700 transition-colors duration-200 block">All Parties</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">View and manage all parties</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('party-transfers.index') }}"
                class="group relative bg-gray-50 rounded-xl border border-gray-200 p-4 hover:shadow-xl hover:shadow-sky-500/20 hover:-translate-y-1 hover:scale-105 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-500/0 to-blue-500/0 group-hover:from-sky-500/10 group-hover:to-blue-500/5 transition-all duration-300 rounded-xl"></div>
                <div class="absolute -top-6 -right-6 w-16 h-16 bg-sky-400/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col items-center text-center space-y-2.5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-sky-500 to-blue-600 rounded-lg blur-md opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-11 h-11 bg-gradient-to-br from-sky-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all duration-300">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 group-hover:text-sky-700 transition-colors duration-200 block">All Transfers</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">View and manage all transfers</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('parties.ledger') }}"
                class="group relative bg-gray-50 rounded-xl border border-gray-200 p-4 hover:shadow-xl hover:shadow-emerald-500/20 hover:-translate-y-1 hover:scale-105 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-green-500/0 group-hover:from-emerald-500/10 group-hover:to-green-500/5 transition-all duration-300 rounded-xl"></div>
                <div class="absolute -top-6 -right-6 w-16 h-16 bg-emerald-400/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col items-center text-center space-y-2.5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg blur-md opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-11 h-11 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all duration-300">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 group-hover:text-emerald-700 transition-colors duration-200 block">Party Ledger</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">View ledger by party</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('parties.balances') }}"
                class="group relative bg-gray-50 rounded-xl border border-gray-200 p-4 hover:shadow-xl hover:shadow-violet-500/20 hover:-translate-y-1 hover:scale-105 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-violet-500/0 to-purple-500/0 group-hover:from-violet-500/10 group-hover:to-purple-500/5 transition-all duration-300 rounded-xl"></div>
                <div class="absolute -top-6 -right-6 w-16 h-16 bg-violet-400/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col items-center text-center space-y-2.5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-violet-500 to-purple-600 rounded-lg blur-md opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-11 h-11 bg-gradient-to-br from-violet-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all duration-300">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 group-hover:text-violet-700 transition-colors duration-200 block">Balances</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">Party balances by currency</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('parties.currency') }}"
                class="group relative bg-gray-50 rounded-xl border border-gray-200 p-4 hover:shadow-xl hover:shadow-rose-500/20 hover:-translate-y-1 hover:scale-105 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-rose-500/0 to-pink-500/0 group-hover:from-rose-500/10 group-hover:to-pink-500/5 transition-all duration-300 rounded-xl"></div>
                <div class="absolute -top-6 -right-6 w-16 h-16 bg-rose-400/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col items-center text-center space-y-2.5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-rose-500 to-rose-600 rounded-lg blur-md opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-11 h-11 bg-gradient-to-br from-rose-500 to-rose-600 rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all duration-300">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818.879.659 1.685 1.682a8.159 8.159 0 0 0 11.13.874c.94-.704 1.636-1.705 1.636-2.871 0-1.567-1.268-2.837-2.872-2.837-1.604 0-2.872 1.27-2.872 2.837 0 .857.324 1.587.677 2.197M12 6V4.5A2.5 2.5 0 1 0 9.5 7H12Z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 group-hover:text-rose-700 transition-colors duration-200 block">Currency Breakdown</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">Balances by party & currency</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Recent activity - Timeline Style --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
        {{-- Recent Parties --}}
        <div class="relative bg-gray-100 rounded-xl shadow-lg shadow-amber-500/5 border border-gray-200 p-6 overflow-hidden group">
            <div class="absolute -top-16 -left-16 w-40 h-40 bg-gradient-to-br from-amber-400/10 to-orange-400/5 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>

            <div class="relative flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 flex items-center mb-1">
                        <div class="relative mr-2">
                            <div class="absolute inset-0 bg-amber-500 rounded-lg blur opacity-40"></div>
                            <svg class="relative w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.528A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </div>
                        Recent Parties
                    </h3>
                    <p class="text-sm text-gray-500">Latest party entries</p>
                </div>
                <a href="{{ route('parties.index') }}" class="group/link flex items-center space-x-1.5 px-3 py-1.5 backdrop-blur-sm bg-amber-50/80 hover:bg-amber-100/80 rounded-lg border border-amber-200/50 transition-all duration-300 hover:shadow-md">
                    <span class="text-xs font-semibold text-amber-700">View all</span>
                    <svg class="w-3.5 h-3.5 text-amber-600 group-hover/link:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="relative space-y-3">
                @forelse($recentParties as $party)
                <div class="relative group/item">
                    @if(!$loop->last)
                    <div class="absolute left-5 top-12 bottom-0 w-0.5 bg-gradient-to-b from-amber-200 to-transparent"></div>
                    @endif
                    <a href="{{ route('parties.show', $party) }}" class="relative flex items-start space-x-3 p-3.5 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 block">
                        <div class="relative flex-shrink-0">
                            <div class="absolute inset-0 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg blur-md opacity-0 group-hover/item:opacity-60 transition-opacity duration-300"></div>
                            <div class="relative w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center shadow-md group-hover/item:scale-110 group-hover/item:rotate-6 transition-all duration-300">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.528A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-bold text-gray-900 group-hover/item:text-amber-700 transition-colors duration-200">{{ $party->party_name }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mb-2">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $party->party_type == 1 ? 'bg-blue-50 text-blue-700' : 'bg-violet-50 text-violet-700' }}">
                                    {{ $party->party_type_label }}
                                </span>
                                <span class="ml-2">Since {{ $party->opening_date->format('d M Y') }}</span>
                            </p>
                            <div class="flex items-center justify-between">
                                @if($party->openingBalances->count() > 0)
                                    <span class="text-xs text-gray-500">
                                        @foreach($party->openingBalances->take(2) as $ob)
                                            {{ $ob->currency?->currency_symbol ?? '-' }} {{ number_format($ob->opening_balance, 0) }}{{ !$loop->last ? ' · ' : '' }}
                                        @endforeach
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">No balance</span>
                                @endif
                                <svg class="h-4 w-4 text-gray-400 group-hover/item:text-amber-500 group-hover/item:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="relative mx-auto mb-4 w-16 h-16">
                        <div class="absolute inset-0 bg-gray-200 rounded-full blur-xl opacity-50"></div>
                        <div class="relative w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.528A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 mb-1">No parties yet</h3>
                    <p class="text-xs text-gray-500 mb-4">Add your first party to get started</p>
                    <a href="{{ route('parties.create') }}" class="inline-flex items-center text-sm font-semibold text-amber-600 hover:text-amber-500">Add your first party</a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Transfers --}}
        <div class="relative bg-gray-100 rounded-xl shadow-lg shadow-sky-500/5 border border-gray-200 p-6 overflow-hidden group">
            <div class="absolute -top-16 -left-16 w-40 h-40 bg-gradient-to-br from-sky-400/10 to-blue-400/5 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>

            <div class="relative flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 flex items-center mb-1">
                        <div class="relative mr-2">
                            <div class="absolute inset-0 bg-sky-500 rounded-lg blur opacity-40"></div>
                            <svg class="relative w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                            </svg>
                        </div>
                        Recent Transfers
                    </h3>
                    <p class="text-sm text-gray-500">Latest transfer activities</p>
                </div>
                <a href="{{ route('party-transfers.index') }}" class="group/link flex items-center space-x-1.5 px-3 py-1.5 backdrop-blur-sm bg-sky-50/80 hover:bg-sky-100/80 rounded-lg border border-sky-200/50 transition-all duration-300 hover:shadow-md">
                    <span class="text-xs font-semibold text-sky-700">View all</span>
                    <svg class="w-3.5 h-3.5 text-sky-600 group-hover/link:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="relative space-y-3">
                @forelse($recentTransfers as $transfer)
                <div class="relative group/item">
                    @if(!$loop->last)
                    <div class="absolute left-5 top-12 bottom-0 w-0.5 bg-gradient-to-b from-sky-200 to-transparent"></div>
                    @endif
                    <a href="{{ route('party-transfers.edit', $transfer) }}" class="relative flex items-start space-x-3 p-3.5 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 block">
                        <div class="relative flex-shrink-0">
                            <div class="absolute inset-0 bg-gradient-to-br from-sky-500 to-blue-600 rounded-lg blur-md opacity-0 group-hover/item:opacity-60 transition-opacity duration-300"></div>
                            <div class="relative w-10 h-10 bg-gradient-to-br from-sky-500 to-blue-600 rounded-lg flex items-center justify-center shadow-md group-hover/item:scale-110 group-hover/item:rotate-6 transition-all duration-300">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-bold text-gray-900 group-hover/item:text-sky-700 transition-colors duration-200">
                                    {{ $transfer->debitParty?->party_name ?? '—' }} <span class="text-gray-400 font-normal">→</span> {{ $transfer->creditParty?->party_name ?? '—' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mb-2">
                                {{ $transfer->date_added->format('d M Y') }}
                                <span class="mx-1">·</span>
                                {{ $transfer->debitCurrency?->currency ?? '-' }} / {{ $transfer->creditCurrency?->currency ?? '-' }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-900">{{ number_format($transfer->debit_amount, 2) }}</span>
                                <svg class="h-4 w-4 text-gray-400 group-hover/item:text-sky-500 group-hover/item:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="relative mx-auto mb-4 w-16 h-16">
                        <div class="absolute inset-0 bg-gray-200 rounded-full blur-xl opacity-50"></div>
                        <div class="relative w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 mb-1">No transfers yet</h3>
                    <p class="text-xs text-gray-500 mb-4">Record a transfer between parties</p>
                    <a href="{{ route('party-transfers.create') }}" class="inline-flex items-center text-sm font-semibold text-amber-600 hover:text-amber-500">Create a transfer</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
