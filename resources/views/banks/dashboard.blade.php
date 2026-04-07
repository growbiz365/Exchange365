<x-app-layout>
    @section('title', 'Banks Dashboard - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('banks.dashboard'), 'label' => 'Banks Dashboard']
    ]" />

    {{-- Header Section --}}
    <div class="relative bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 mb-5 mt-4 overflow-hidden group">
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-gradient-to-br from-sky-400/10 to-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between sm:flex-wrap">
            <div class="flex items-start sm:items-center gap-3 sm:space-x-4 min-w-0">
                <div class="relative flex-shrink-0">
                    <div class="bg-gradient-to-br from-sky-500 to-indigo-600 p-2.5 sm:p-3 rounded-xl shadow-lg transform group-hover:scale-105 transition-all duration-300">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-0.5 leading-tight">Banks Dashboard</h1>
                    <p class="text-xs sm:text-sm text-gray-500">Overview of your bank accounts, balances, and recent transfers</p>
                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $business->business_name ?? 'ExchangeHub' }}</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 w-full sm:w-auto">
                <a href="{{ route('banks.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 px-4 sm:px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-500/25 transition hover:shadow-xl hover:shadow-sky-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 w-full sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Add Bank
                </a>
                <a href="{{ route('bank-transfers.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-white border border-gray-200 px-4 sm:px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-gray-300 hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 w-full sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    New Bank Transfer
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

        {{-- Currencies In Use --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-sky-500 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c-4.418 0-8 2.239-8 5s3.582 5 8 5 8-2.239 8-5-3.582-5-8-5zM4 13v2c0 2.761 3.582 5 8 5s8-2.239 8-5v-2" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Currencies In Use</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($currenciesInUse) }}</p>
                </div>
            </div>
        </div>

        {{-- Active Banks --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-emerald-500 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Active Banks</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($activeBanks) }}</p>
                </div>
            </div>
        </div>

        {{-- Money Exchange Amount --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-indigo-500 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h11m0 0L12 3m3 4-3 4m7 6H9m0 0 3-4m-3 4 3 4" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Money Exchange Amount</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($moneyExchangeAmount, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Total Balance --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-cyan-500 p-4 sm:p-5 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Balance</p>
                    <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ number_format($totalBalance, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 mb-5">
        <div class="flex items-center gap-2 mb-4">
            <div class="bg-gradient-to-br from-indigo-600 to-sky-600 p-1.5 rounded-lg shadow-sm">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-gray-900">Quick Actions</h2>
                <p class="text-xs text-gray-500">Jump to common bank tasks</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-3">
            <a href="{{ route('banks.index') }}"
                class="group/card flex flex-col items-center text-center gap-1.5 sm:gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-sky-300 hover:shadow-md p-3 sm:p-4 transition-all duration-200 min-h-[7.5rem] sm:min-h-0">
                <div class="w-11 h-11 bg-gradient-to-br from-sky-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-sky-700 transition-colors">All Banks</span>
                    <span class="block text-xs text-gray-400 mt-0.5">View and manage</span>
                </div>
            </a>

            <a href="{{ route('banks.ledger') }}"
                class="group/card flex flex-col items-center text-center gap-1.5 sm:gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-emerald-300 hover:shadow-md p-3 sm:p-4 transition-all duration-200 min-h-[7.5rem] sm:min-h-0">
                <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-emerald-700 transition-colors">Bank Ledger</span>
                    <span class="block text-xs text-gray-400 mt-0.5">Transaction history</span>
                </div>
            </a>

            <a href="{{ route('banks.balances') }}"
                class="group/card flex flex-col items-center text-center gap-1.5 sm:gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-violet-300 hover:shadow-md p-3 sm:p-4 transition-all duration-200 min-h-[7.5rem] sm:min-h-0">
                <div class="w-11 h-11 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 19.5l15-15M9 4.5h10.5V15" />
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-violet-700 transition-colors">Bank Balances</span>
                    <span class="block text-xs text-gray-400 mt-0.5">By bank</span>
                </div>
            </a>

            <a href="{{ route('banks.currency-balances') }}"
                class="group/card flex flex-col items-center text-center gap-1.5 sm:gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-cyan-300 hover:shadow-md p-3 sm:p-4 transition-all duration-200 min-h-[7.5rem] sm:min-h-0">
                <div class="w-11 h-11 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c-4.418 0-8 2.686-8 6s3.582 6 8 6 8-2.686 8-6-3.582-6-8-6Zm0 9.5A3.5 3.5 0 1 1 15.5 9 3.5 3.5 0 0 1 12 12.5Z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-cyan-700 transition-colors">Currency Balances</span>
                    <span class="block text-xs text-gray-400 mt-0.5">By currency</span>
                </div>
            </a>

            <a href="{{ route('bank-transfers.index') }}"
                class="group/card flex flex-col items-center text-center gap-1.5 sm:gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-indigo-300 hover:shadow-md p-3 sm:p-4 transition-all duration-200 min-h-[7.5rem] sm:min-h-0">
                <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h11m0 0L12 3m3 4-3 4m7 6H9m0 0 3-4m-3 4 3 4" />
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-indigo-700 transition-colors">Bank Transfers</span>
                    <span class="block text-xs text-gray-400 mt-0.5">View and manage</span>
                </div>
            </a>

            <a href="{{ route('money-exchanges.index') }}"
                class="group/card flex flex-col items-center text-center gap-1.5 sm:gap-2 bg-gray-50 hover:bg-white rounded-xl border border-gray-200 hover:border-amber-300 hover:shadow-md p-3 sm:p-4 transition-all duration-200 min-h-[7.5rem] sm:min-h-0">
                <div class="w-11 h-11 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-sm group-hover/card:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h16M4 11h10M4 17h7" />
                    </svg>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-800 group-hover/card:text-amber-700 transition-colors">Money Exchanges</span>
                    <span class="block text-xs text-gray-400 mt-0.5">View and manage</span>
                </div>
            </a>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">

        {{-- Recent Banks --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="bg-gradient-to-br from-sky-500 to-indigo-600 p-1.5 rounded-lg shadow-sm shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Recent Banks</h3>
                        <p class="text-xs text-gray-500">Latest bank accounts</p>
                    </div>
                </div>
                <a href="{{ route('banks.index') }}"
                    class="group/link flex items-center justify-center sm:justify-start gap-1 px-3 py-2 sm:py-1.5 bg-sky-50 hover:bg-sky-100 rounded-lg border border-sky-100 hover:border-sky-200 transition-all duration-200 w-full sm:w-auto shrink-0">
                    <span class="text-xs font-semibold text-sky-700">View all</span>
                    <svg class="w-3.5 h-3.5 text-sky-600 group-hover/link:translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="space-y-2">
                @forelse($recentBanks as $bank)
                <div class="relative group/item">
                    @if(!$loop->last)
                    <div class="absolute left-5 top-12 bottom-0 w-0.5 bg-gradient-to-b from-sky-200 to-transparent"></div>
                    @endif
                    <a href="{{ route('banks.edit', $bank) }}"
                        class="flex items-start gap-3 p-3 bg-gray-50 hover:bg-white rounded-lg border border-gray-200 hover:border-sky-200 hover:shadow-sm transition-all duration-200">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-sky-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm group-hover/item:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-semibold text-gray-900 group-hover/item:text-sky-700 transition-colors">{{ $bank->bank_name }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mb-1.5">
                                {{ $bank->bankType?->bank_type ?? '—' }}
                                <span class="mx-1 text-gray-300">·</span>
                                {{ $bank->currency?->currency ?? '-' }} ({{ $bank->currency?->currency_symbol ?? '-' }})
                            </p>
                            <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:justify-between">
                                <span class="text-xs text-gray-400 break-all">Acct: {{ $bank->account_number ?? 'N/A' }}</span>
                                <span class="px-2 py-0.5 inline-flex text-[10px] leading-5 font-semibold rounded-full w-fit {{ $bank->status == 1 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $bank->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="text-center py-10">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">No banks yet</h3>
                    <p class="text-xs text-gray-500 mb-3">Add your first bank account to get started.</p>
                    <a href="{{ route('banks.create') }}" class="text-sm font-semibold text-sky-600 hover:text-sky-500 transition-colors">Add your first bank</a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Bank Transfers --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-1.5 rounded-lg shadow-sm shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Recent Bank Transfers</h3>
                        <p class="text-xs text-gray-500">Latest transfer activities</p>
                    </div>
                </div>
                <a href="{{ route('bank-transfers.index') }}"
                    class="group/link flex items-center justify-center sm:justify-start gap-1 px-3 py-2 sm:py-1.5 bg-emerald-50 hover:bg-emerald-100 rounded-lg border border-emerald-100 hover:border-emerald-200 transition-all duration-200 w-full sm:w-auto shrink-0">
                    <span class="text-xs font-semibold text-emerald-700">View all</span>
                    <svg class="w-3.5 h-3.5 text-emerald-600 group-hover/link:translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="space-y-2">
                @forelse($recentTransfers as $transfer)
                <div class="relative group/item">
                    @if(!$loop->last)
                    <div class="absolute left-5 top-12 bottom-0 w-0.5 bg-gradient-to-b from-emerald-200 to-transparent"></div>
                    @endif
                    <a href="{{ route('bank-transfers.edit', $transfer) }}"
                        class="flex items-start gap-3 p-3 bg-gray-50 hover:bg-white rounded-lg border border-gray-200 hover:border-emerald-200 hover:shadow-sm transition-all duration-200">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center shadow-sm group-hover/item:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900 group-hover/item:text-emerald-700 transition-colors min-w-0 break-words">
                                    {{ $transfer->fromBank?->bank_name ?? '—' }}
                                    <span class="text-gray-400 font-normal whitespace-nowrap">→</span>
                                    {{ $transfer->toBank?->bank_name ?? '—' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mb-1.5 break-words">
                                {{ $transfer->date_added->format('d M Y') }}
                                <span class="mx-1 text-gray-300">·</span>
                                {{ $transfer->fromBank?->currency?->currency ?? '-' }} ({{ $transfer->fromBank?->currency?->currency_symbol ?? '-' }})
                            </p>
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                <span class="text-sm font-bold text-gray-900">{{ number_format($transfer->amount, 2) }}</span>
                                <svg class="h-4 w-4 text-gray-300 group-hover/item:text-emerald-400 group-hover/item:translate-x-0.5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">No bank transfers yet</h3>
                    <p class="text-xs text-gray-500 mb-3">Record a transfer between banks.</p>
                    <a href="{{ route('bank-transfers.create') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-500 transition-colors">Create a transfer</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
