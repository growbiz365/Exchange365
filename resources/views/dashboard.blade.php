<x-app-layout>
    @section('title', 'Dashboard - ExchangeHub')

    @php
        $businessTimezone = $businessTimezone ?? 'Asia/Karachi';
        $stats = $stats ?? [
            'total_banks' => 0, 'total_parties' => 0, 'total_general_vouchers' => 0, 'total_assets' => 0,
            'total_party_transfers' => 0, 'total_money_exchanges' => 0, 'total_bank_transfers' => 0,
            'general_vouchers_amount' => 0, 'party_transfers_amount' => 0, 'money_exchanges_amount' => 0, 'bank_transfers_amount' => 0
        ];
    @endphp

    <!-- Header Section -->
    <div class="relative bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-5 mb-4 mt-2 overflow-hidden group">
        <div class="absolute -top-16 -right-16 w-40 h-40 bg-gradient-to-br from-sky-400/10 to-emerald-400/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div class="flex items-start lg:items-center gap-3 flex-1">
                <div class="relative flex-shrink-0">
                    <div class="relative bg-gradient-to-br from-sky-600 via-indigo-700 to-emerald-600 p-2.5 rounded-xl shadow-lg transform group-hover:scale-105 transition-all duration-300">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl lg:text-2xl font-bold text-gray-900 mb-0.5 leading-tight">
                        Welcome back, <span class="text-indigo-600">{{ Auth::user()->name }}</span>!
                    </h1>
                    <p class="text-xs text-gray-500 mb-1">Currency exchange business overview</p>
                    <div class="flex items-center gap-1.5">
                        <div class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </div>
                        <span class="text-xs font-medium text-gray-600">All systems operational</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <div class="text-right hidden sm:block">
                    <div class="text-xs font-bold text-gray-900" id="current-day">{{ now()->setTimezone($businessTimezone)->format('l') }}</div>
                    <div class="text-xs text-gray-500" id="current-date">{{ now()->setTimezone($businessTimezone)->format('M d, Y') }}</div>
                </div>
                <div class="hidden sm:block h-8 w-px bg-gray-200"></div>
                <div class="relative bg-gray-50 rounded-xl px-3 py-2 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                    <div class="relative flex items-center gap-2">
                        <div class="relative flex-shrink-0">
                            <div class="relative w-8 h-8 bg-gradient-to-br from-slate-800 to-slate-900 rounded-full p-0.5 shadow-md ring-1 ring-white/30">
                                <div class="w-full h-full bg-gradient-to-br from-gray-900 to-gray-800 rounded-full p-1 relative overflow-hidden">
                                    <div class="w-full h-full bg-white rounded-full relative">
                                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 100">
                                            <line x1="50" y1="8" x2="50" y2="12" stroke="#1f2937" stroke-width="1.5" stroke-linecap="round"/>
                                            <line x1="92" y1="50" x2="88" y2="50" stroke="#1f2937" stroke-width="1.5" stroke-linecap="round"/>
                                            <line x1="50" y1="92" x2="50" y2="88" stroke="#1f2937" stroke-width="1.5" stroke-linecap="round"/>
                                            <line x1="8" y1="50" x2="12" y2="50" stroke="#1f2937" stroke-width="1.5" stroke-linecap="round"/>
                                            <line x1="73.2" y1="15.4" x2="71.5" y2="17.1" stroke="#4b5563" stroke-width="1" stroke-linecap="round"/>
                                            <line x1="84.6" y1="26.8" x2="82.9" y2="28.5" stroke="#4b5563" stroke-width="1" stroke-linecap="round"/>
                                            <line x1="84.6" y1="73.2" x2="82.9" y2="71.5" stroke="#4b5563" stroke-width="1" stroke-linecap="round"/>
                                            <line x1="73.2" y1="84.6" x2="71.5" y2="82.9" stroke="#4b5563" stroke-width="1" stroke-linecap="round"/>
                                            <line x1="26.8" y1="84.6" x2="28.5" y2="82.9" stroke="#4b5563" stroke-width="1" stroke-linecap="round"/>
                                            <line x1="15.4" y1="73.2" x2="17.1" y2="71.5" stroke="#4b5563" stroke-width="1" stroke-linecap="round"/>
                                            <line x1="15.4" y1="26.8" x2="17.1" y2="28.5" stroke="#4b5563" stroke-width="1" stroke-linecap="round"/>
                                            <line x1="26.8" y1="15.4" x2="28.5" y2="17.1" stroke="#4b5563" stroke-width="1" stroke-linecap="round"/>
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div id="hour-hand" class="absolute" style="left: 50%; top: 50%; transform-origin: 50% 100%; transform: translate(-50%, -100%) rotate(0deg);">
                                                <div class="w-0.5 h-2.5 bg-gray-900 rounded-full shadow-sm"></div>
                                            </div>
                                            <div id="minute-hand" class="absolute" style="left: 50%; top: 50%; transform-origin: 50% 100%; transform: translate(-50%, -100%) rotate(0deg);">
                                                <div class="w-0.5 h-3.5 bg-gray-900 rounded-full shadow-sm"></div>
                                            </div>
                                            <div id="second-hand" class="absolute" style="left: 50%; top: 50%; transform-origin: 50% 100%; transform: translate(-50%, -100%) rotate(0deg);">
                                                <div class="w-px h-3.5 bg-red-500 rounded-full opacity-80"></div>
                                            </div>
                                            <div class="absolute left-1/2 top-1/2 w-1 h-1 bg-gray-900 rounded-full ring-1 ring-white shadow-md -translate-x-1/2 -translate-y-1/2 z-10"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute -right-0.5 top-1/2 transform -translate-y-1/2 w-1 h-4 bg-gradient-to-br from-gray-600 to-gray-800 rounded-full shadow-md"></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-900" id="current-time">{{ now()->setTimezone($businessTimezone)->format('g:i A') }}</span>
                            <span class="text-xs text-gray-500">Live</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-success-alert message="You're Successfully logged in!" />

    <!-- Key Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 mb-4">

        <!-- General Vouchers -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-emerald-500 p-4 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide truncate">General Vouchers</p>
                    <p class="text-xl font-bold text-gray-900 leading-tight mt-0.5">{{ number_format($stats['general_vouchers_amount']) }}</p>
                    <span class="text-xs text-gray-400">{{ $stats['total_general_vouchers'] }} vouchers</span>
                </div>
            </div>
        </div>

        <!-- Party Transfers -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-blue-500 p-4 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide truncate">Party Transfers</p>
                    <p class="text-xl font-bold text-gray-900 leading-tight mt-0.5">{{ number_format($stats['party_transfers_amount']) }}</p>
                    <span class="text-xs text-gray-400">{{ $stats['total_party_transfers'] }} transfers</span>
                </div>
            </div>
        </div>

        <!-- Banks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-purple-500 p-4 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide truncate">Banks</p>
                    <p class="text-xl font-bold text-gray-900 leading-tight mt-0.5">{{ number_format($stats['total_banks']) }}</p>
                    <span class="text-xs text-gray-400">Bank accounts</span>
                </div>
            </div>
        </div>

        <!-- Parties -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-amber-500 p-4 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide truncate">Parties</p>
                    <p class="text-xl font-bold text-gray-900 leading-tight mt-0.5">{{ number_format($stats['total_parties'] ?? 0) }}</p>
                    <span class="text-xs text-gray-400">Customers & suppliers</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Exchange Shortcuts -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
            <div class="flex items-center gap-2">
                <div class="bg-gradient-to-br from-purple-600 to-teal-600 p-1.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Exchange Shortcuts</h2>
                    <p class="text-xs text-gray-500">Frequently used features</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 rounded-lg border border-emerald-100">
                <span class="relative flex h-1.5 w-1.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-medium text-emerald-700">Live</span>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
            <a href="{{ route('general-vouchers.create') }}"
                class="group/card flex flex-col items-center text-center gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-emerald-300 hover:shadow-md p-3 transition-all duration-200">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-emerald-700 transition-colors">General Voucher</span>
                    <span class="block text-xs text-gray-400 mt-0.5">Create voucher</span>
                </div>
            </a>

            @can('view parties')
            <a href="{{ route('party-transfers.create') }}"
                class="group/card flex flex-col items-center text-center gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md p-3 transition-all duration-200">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-blue-700 transition-colors">Party Transfer</span>
                    <span class="block text-xs text-gray-400 mt-0.5">Between parties</span>
                </div>
            </a>
            @endcan

            @can('view banks')
            <a href="{{ route('money-exchanges.create') }}"
                class="group/card flex flex-col items-center text-center gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-teal-300 hover:shadow-md p-3 transition-all duration-200">
                <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" /></svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-teal-700 transition-colors">Money Exchange</span>
                    <span class="block text-xs text-gray-400 mt-0.5">Between banks</span>
                </div>
            </a>
            @endcan

            @can('view parties')
            <a href="{{ route('parties.index') }}"
                class="group/card flex flex-col items-center text-center gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-purple-300 hover:shadow-md p-3 transition-all duration-200">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-purple-700 transition-colors">Parties</span>
                    <span class="block text-xs text-gray-400 mt-0.5">All contacts</span>
                </div>
            </a>
            @endcan

            @can('view banks')
            <a href="{{ route('banks.dashboard') }}"
                class="group/card flex flex-col items-center text-center gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md p-3 transition-all duration-200">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-blue-700 transition-colors">Banks</span>
                    <span class="block text-xs text-gray-400 mt-0.5">Dashboard</span>
                </div>
            </a>
            @endcan

            <a href="{{ route('reports.index') }}"
                class="group/card flex flex-col items-center text-center gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-teal-300 hover:shadow-md p-3 transition-all duration-200">
                <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-teal-700 transition-colors">Reports</span>
                    <span class="block text-xs text-gray-400 mt-0.5">View reports</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Reports & Exchange Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">

        <!-- Quick Reports -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="bg-gradient-to-br from-purple-600 to-indigo-600 p-1.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Quick Reports</h3>
                    <p class="text-xs text-gray-500">Access reports fast</p>
                </div>
            </div>
            <div class="space-y-2">
                @can('view banks')
                <a href="{{ route('banks.balances') }}"
                    class="group/link flex items-center justify-between p-2.5 bg-gray-50 hover:bg-white rounded-lg border border-gray-200 hover:border-blue-200 hover:shadow-sm transition-all duration-200">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-900 group-hover/link:text-blue-700 transition-colors">Bank Balances</span>
                            <span class="block text-xs text-gray-400">Current accounts</span>
                        </div>
                    </div>
                    <svg class="h-4 w-4 text-gray-300 group-hover/link:text-blue-400 group-hover/link:translate-x-0.5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endcan

                @can('view parties')
                <a href="{{ route('parties.balances') }}"
                    class="group/link flex items-center justify-between p-2.5 bg-gray-50 hover:bg-white rounded-lg border border-gray-200 hover:border-purple-200 hover:shadow-sm transition-all duration-200">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-900 group-hover/link:text-purple-700 transition-colors">Party Balances</span>
                            <span class="block text-xs text-gray-400">Customer & supplier</span>
                        </div>
                    </div>
                    <svg class="h-4 w-4 text-gray-300 group-hover/link:text-purple-400 group-hover/link:translate-x-0.5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endcan

                <a href="{{ route('reports.index') }}"
                    class="group/link flex items-center justify-between p-2.5 bg-gray-50 hover:bg-white rounded-lg border border-gray-200 hover:border-teal-200 hover:shadow-sm transition-all duration-200">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-900 group-hover/link:text-teal-700 transition-colors">All Reports</span>
                            <span class="block text-xs text-gray-400">Currency summary & more</span>
                        </div>
                    </div>
                    <svg class="h-4 w-4 text-gray-300 group-hover/link:text-teal-400 group-hover/link:translate-x-0.5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Exchange Overview -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="bg-gradient-to-br from-slate-600 to-gray-700 p-1.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Exchange Overview</h3>
                    <p class="text-xs text-gray-500">Key exchange metrics</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div class="flex items-center justify-between px-3 py-2.5 bg-gray-50 rounded-lg border border-gray-100 hover:border-emerald-200 hover:bg-white transition-all duration-200">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></div>
                        <span class="text-xs font-medium text-gray-600">General Vouchers</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($stats['general_vouchers_amount']) }}</span>
                </div>
                <div class="flex items-center justify-between px-3 py-2.5 bg-gray-50 rounded-lg border border-gray-100 hover:border-blue-200 hover:bg-white transition-all duration-200">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></div>
                        <span class="text-xs font-medium text-gray-600">Party Transfers</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($stats['party_transfers_amount']) }}</span>
                </div>
                <div class="flex items-center justify-between px-3 py-2.5 bg-gray-50 rounded-lg border border-gray-100 hover:border-teal-200 hover:bg-white transition-all duration-200">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-teal-500 flex-shrink-0"></div>
                        <span class="text-xs font-medium text-gray-600">Money Exchanges</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($stats['money_exchanges_amount']) }}</span>
                </div>
                <div class="flex items-center justify-between px-3 py-2.5 bg-gray-50 rounded-lg border border-gray-100 hover:border-slate-200 hover:bg-white transition-all duration-200">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-slate-500 flex-shrink-0"></div>
                        <span class="text-xs font-medium text-gray-600">Bank Transfers</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($stats['bank_transfers_amount']) }}</span>
                </div>
                <div class="flex items-center justify-between px-3 py-2.5 bg-gray-50 rounded-lg border border-gray-100 hover:border-purple-200 hover:bg-white transition-all duration-200 sm:col-span-2">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-purple-500 flex-shrink-0"></div>
                        <span class="text-xs font-medium text-gray-600">Entities — Banks · Parties · Assets</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $stats['total_banks'] }} · {{ $stats['total_parties'] }} · {{ $stats['total_assets'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        const businessTimezone = '{{ $businessTimezone }}';
        function updateBusinessTime() {
            const now = new Date();
            const businessTime = new Date(now.toLocaleString("en-US", { timeZone: businessTimezone }));
            const hours = businessTime.getHours();
            const minutes = businessTime.getMinutes();
            const seconds = businessTime.getSeconds();
            const hourRotation = (hours % 12) * 30 + minutes * 0.5;
            const minuteRotation = minutes * 6 + seconds * 0.1;
            const secondRotation = seconds * 6;
            const hourHand = document.getElementById('hour-hand');
            const minuteHand = document.getElementById('minute-hand');
            const secondHand = document.getElementById('second-hand');
            if (hourHand) hourHand.style.transform = `translate(-50%, -100%) rotate(${hourRotation}deg)`;
            if (minuteHand) minuteHand.style.transform = `translate(-50%, -100%) rotate(${minuteRotation}deg)`;
            if (secondHand) secondHand.style.transform = `translate(-50%, -100%) rotate(${secondRotation}deg)`;
            const dayElement = document.getElementById('current-day');
            if (dayElement) dayElement.textContent = businessTime.toLocaleDateString('en-US', { weekday: 'long' });
            const dateElement = document.getElementById('current-date');
            if (dateElement) dateElement.textContent = businessTime.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const timeElement = document.getElementById('current-time');
            if (timeElement) timeElement.textContent = businessTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        }
        updateBusinessTime();
        setInterval(updateBusinessTime, 1000);
    </script>
</x-app-layout>
