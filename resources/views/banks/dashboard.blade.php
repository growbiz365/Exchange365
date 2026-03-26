<x-app-layout>
    @section('title', 'Banks Dashboard - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('banks.dashboard'), 'label' => 'Banks Dashboard']
    ]" />

    {{-- Subtle Background Gradient --}}
    <div class="fixed inset-0 -z-10 bg-gradient-to-br from-blue-50/30 via-white to-cyan-50/20 pointer-events-none"></div>

    {{-- Header Section --}}
    <div class="relative bg-gray-100 rounded-2xl shadow-xl shadow-sky-500/5 border border-gray-200 p-6 mb-6 mt-4 overflow-hidden group">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-sky-400/20 to-cyan-400/20 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
        <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-gradient-to-tr from-indigo-400/15 to-sky-400/15 rounded-full blur-2xl"></div>

        <div class="relative flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative flex-shrink-0">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-500 to-indigo-600 rounded-xl blur-lg opacity-60 group-hover:opacity-80 transition-opacity duration-300"></div>
                    <div class="relative bg-gradient-to-br from-sky-500 to-indigo-600 p-3 rounded-xl shadow-lg transform group-hover:scale-105 transition-all duration-300">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 via-sky-800 to-indigo-900 bg-clip-text text-transparent mb-1">
                        Banks Dashboard
                    </h1>
                    <p class="text-sm text-gray-600">
                        Overview of your bank accounts, balances, and recent transfers
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $business->business_name ?? 'ExchangeHub' }}
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('banks.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-500/25 transition hover:shadow-xl hover:shadow-sky-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Add Bank
                </a>
                <a href="{{ route('bank-transfers.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-gray-50 border border-gray-200/80 px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-gray-300 hover:bg-gray-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Currencies In Use --}}
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c-4.418 0-8 2.239-8 5s3.582 5 8 5 8-2.239 8-5-3.582-5-8-5zM4 13v2c0 2.761 3.582 5 8 5s8-2.239 8-5v-2" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-semibold text-sky-700 uppercase tracking-wide">Currencies In Use</p>
                    <p class="text-2xl font-bold text-sky-900 mt-1">{{ number_format($currenciesInUse) }}</p>
                </div>
            </div>
        </div>

        {{-- Active Banks --}}
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-semibold text-emerald-700 uppercase tracking-wide">Active Banks</p>
                    <p class="text-2xl font-bold text-emerald-900 mt-1">{{ number_format($activeBanks) }}</p>
                </div>
            </div>
        </div>

        {{-- Money Exchange Amount --}}
        <div class="relative bg-gradient-to-br from-indigo-50 via-indigo-50 to-indigo-100 rounded-xl shadow-sm border border-indigo-100 p-6 overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 opacity-10">
                <svg viewBox="0 0 100 100" class="w-full h-full text-indigo-600">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="25" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="10" fill="currentColor"/>
                </svg>
            </div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-indigo-200 rounded-full -ml-8 -mb-8 opacity-20"></div>
            <div class="relative flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h11m0 0L12 3m3 4-3 4m7 6H9m0 0 3-4m-3 4 3 4" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-semibold text-indigo-700 uppercase tracking-wide">Money Exchange Amount</p>
                    <p class="text-2xl font-bold text-indigo-900 mt-1">
                        {{ number_format($moneyExchangeAmount, 2) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Total Bank Balance --}}
        <div class="relative bg-gradient-to-br from-cyan-50 via-cyan-50 to-cyan-100 rounded-xl shadow-sm border border-cyan-100 p-6 overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 opacity-10">
                <svg viewBox="0 0 100 100" class="w-full h-full text-cyan-600">
                    <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="25" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="50" cy="50" r="10" fill="currentColor"/>
                </svg>
            </div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-cyan-200 rounded-full -ml-8 -mb-8 opacity-20"></div>
            <div class="relative flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-semibold text-cyan-700 uppercase tracking-wide">Total Balance</p>
                    <p class="text-2xl font-bold text-cyan-900 mt-1">
                        {{ number_format($totalBalance, 2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="relative bg-gray-100 rounded-xl shadow-lg shadow-indigo-500/5 border border-gray-200 p-6 mb-6 overflow-hidden">
        <div class="absolute -top-20 -left-20 w-40 h-40 bg-gradient-to-br from-indigo-400/10 to-sky-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-gradient-to-br from-sky-400/10 to-cyan-400/10 rounded-full blur-3xl"></div>

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
            <p class="text-sm text-gray-600 mt-1 ml-8">Jump to common bank tasks</p>
        </div>

        <div class="relative grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <a href="{{ route('banks.index') }}"
                class="group relative bg-gray-50 rounded-xl border border-gray-200 p-4 hover:shadow-xl hover:shadow-sky-500/20 hover:-translate-y-1 hover:scale-105 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-500/0 to-indigo-500/0 group-hover:from-sky-500/10 group-hover:to-indigo-500/5 transition-all duration-300 rounded-xl"></div>
                <div class="absolute -top-6 -right-6 w-16 h-16 bg-sky-400/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col items-center text-center space-y-2.5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-sky-500 to-indigo-600 rounded-lg blur-md opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-11 h-11 bg-gradient-to-br from-sky-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all duration-300">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 group-hover:text-sky-700 transition-colors duration-200 block">All Banks</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">View and manage bank accounts</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('banks.ledger') }}"
                class="group relative bg-gray-50 rounded-xl border border-gray-200 p-4 hover:shadow-xl hover:shadow-emerald-500/20 hover:-translate-y-1 hover:scale-105 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-green-500/0 group-hover:from-emerald-500/10 group-hover:to-green-500/5 transition-all duration-300 rounded-xl"></div>
                <div class="absolute -top-6 -right-6 w-16 h-16 bg-emerald-400/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col items-center text-center space-y-2.5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg blur-md opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-11 h-11 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all duration-300">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 group-hover:text-emerald-700 transition-colors duration-200 block">Bank Ledger</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">View bank transaction history</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('banks.balances') }}"
                class="group relative bg-gray-50 rounded-xl border border-gray-200 p-4 hover:shadow-xl hover:shadow-violet-500/20 hover:-translate-y-1 hover:scale-105 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-violet-500/0 to-purple-500/0 group-hover:from-violet-500/10 group-hover:to-purple-500/5 transition-all duration-300 rounded-xl"></div>
                <div class="absolute -top-6 -right-6 w-16 h-16 bg-violet-400/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col items-center text-center space-y-2.5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-violet-500 to-purple-600 rounded-lg blur-md opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-11 h-11 bg-gradient-to-br from-violet-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all duration-300">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4.5 19.5l15-15M9 4.5h10.5V15" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 group-hover:text-violet-700 transition-colors duration-200 block">Bank Balances</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">Balances by bank</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('banks.currency-balances') }}"
                class="group relative bg-gray-50 rounded-xl border border-gray-200 p-4 hover:shadow-xl hover:shadow-cyan-500/20 hover:-translate-y-1 hover:scale-105 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/0 to-teal-500/0 group-hover:from-cyan-500/10 group-hover:to-teal-500/5 transition-all duration-300 rounded-xl"></div>
                <div class="absolute -top-6 -right-6 w-16 h-16 bg-cyan-400/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col items-center text-center space-y-2.5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-lg blur-md opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-11 h-11 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all duration-300">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 3c-4.418 0-8 2.686-8 6s3.582 6 8 6 8-2.686 8-6-3.582-6-8-6Zm0 9.5A3.5 3.5 0 1 1 15.5 9 3.5 3.5 0 0 1 12 12.5Z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 group-hover:text-cyan-700 transition-colors duration-200 block">Currency Balances</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">Balances by currency</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('bank-transfers.index') }}"
                class="group relative bg-gray-50 rounded-xl border border-gray-200 p-4 hover:shadow-xl hover:shadow-indigo-500/20 hover:-translate-y-1 hover:scale-105 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-blue-500/0 group-hover:from-indigo-500/10 group-hover:to-blue-500/5 transition-all duration-300 rounded-xl"></div>
                <div class="absolute -top-6 -right-6 w-16 h-16 bg-indigo-400/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col items-center text-center space-y-2.5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-lg blur-md opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-11 h-11 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all duration-300">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 7h11m0 0L12 3m3 4-3 4m7 6H9m0 0 3-4m-3 4 3 4" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 group-hover:text-indigo-700 transition-colors duration-200 block">All Bank Transfers</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">View and manage transfers</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('money-exchanges.index') }}"
                class="group relative bg-gray-50 rounded-xl border border-gray-200 p-4 hover:shadow-xl hover:shadow-amber-500/20 hover:-translate-y-1 hover:scale-105 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 to-orange-500/0 group-hover:from-amber-500/10 group-hover:to-orange-500/5 transition-all duration-300 rounded-xl"></div>
                <div class="absolute -top-6 -right-6 w-16 h-16 bg-amber-400/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex flex-col items-center text-center space-y-2.5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg blur-md opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-11 h-11 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-6 transition-all duration-300">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h16M4 11h10M4 17h7" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-900 group-hover:text-amber-700 transition-colors duration-200 block">Money Exchanges</span>
                        <span class="text-xs text-gray-500 mt-0.5 block">View and manage exchanges</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
        {{-- Recent Banks --}}
        <div class="relative bg-gray-100 rounded-xl shadow-lg shadow-sky-500/5 border border-gray-200 p-6 overflow-hidden group">
            <div class="absolute -top-16 -left-16 w-40 h-40 bg-gradient-to-br from-sky-400/10 to-indigo-400/5 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>

            <div class="relative flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 flex items-center mb-1">
                        <div class="relative mr-2">
                            <div class="absolute inset-0 bg-sky-500 rounded-lg blur opacity-40"></div>
                            <svg class="relative w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        Recent Banks
                    </h3>
                    <p class="text-sm text-gray-500">Latest bank accounts</p>
                </div>
                <a href="{{ route('banks.index') }}" class="group/link flex items-center space-x-1.5 px-3 py-1.5 backdrop-blur-sm bg-sky-50/80 hover:bg-sky-100/80 rounded-lg border border-sky-200/50 transition-all duration-300 hover:shadow-md">
                    <span class="text-xs font-semibold text-sky-700">View all</span>
                    <svg class="w-3.5 h-3.5 text-sky-600 group-hover/link:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="relative space-y-3">
                @forelse($recentBanks as $bank)
                <div class="relative group/item">
                    @if(!$loop->last)
                    <div class="absolute left-5 top-12 bottom-0 w-0.5 bg-gradient-to-b from-sky-200 to-transparent"></div>
                    @endif
                    <a href="{{ route('banks.edit', $bank) }}" class="relative flex items-start space-x-3 p-3.5 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 block">
                        <div class="relative flex-shrink-0">
                            <div class="absolute inset-0 bg-gradient-to-br from-sky-500 to-indigo-600 rounded-lg blur-md opacity-0 group-hover/item:opacity-60 transition-opacity duration-300"></div>
                            <div class="relative w-10 h-10 bg-gradient-to-br from-sky-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-md group-hover/item:scale-110 group-hover/item:rotate-6 transition-all duration-300">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-bold text-gray-900 group-hover/item:text-sky-700 transition-colors duration-200">
                                    {{ $bank->bank_name }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mb-1">
                                {{ $bank->bankType?->bank_type ?? '—' }}
                                <span class="mx-1">·</span>
                                {{ $bank->currency?->currency ?? '-' }} ({{ $bank->currency?->currency_symbol ?? '-' }})
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    Acct: {{ $bank->account_number ?? 'N/A' }}
                                </span>
                                <span class="px-2 inline-flex text-[10px] leading-5 font-semibold rounded-full {{ $bank->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $bank->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="text-center py-12">
                    <h3 class="text-sm font-bold text-gray-900 mb-1">No banks yet</h3>
                    <p class="text-xs text-gray-500 mb-4">Add your first bank account to get started.</p>
                    <a href="{{ route('banks.create') }}" class="inline-flex items-center text-sm font-semibold text-sky-600 hover:text-sky-500">Add your first bank</a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Bank Transfers --}}
        <div class="relative bg-gray-100 rounded-xl shadow-lg shadow-emerald-500/5 border border-gray-200 p-6 overflow-hidden group">
            <div class="absolute -top-16 -left-16 w-40 h-40 bg-gradient-to-br from-emerald-400/10 to-teal-400/5 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>

            <div class="relative flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 flex items-center mb-1">
                        <div class="relative mr-2">
                            <div class="absolute inset-0 bg-emerald-500 rounded-lg blur opacity-40"></div>
                            <svg class="relative w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        Recent Bank Transfers
                    </h3>
                    <p class="text-sm text-gray-500">Latest transfer activities between banks</p>
                </div>
                <a href="{{ route('bank-transfers.index') }}" class="group/link flex items-center space-x-1.5 px-3 py-1.5 backdrop-blur-sm bg-emerald-50/80 hover:bg-emerald-100/80 rounded-lg border border-emerald-200/50 transition-all duration-300 hover:shadow-md">
                    <span class="text-xs font-semibold text-emerald-700">View all</span>
                    <svg class="w-3.5 h-3.5 text-emerald-600 group-hover/link:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="relative space-y-3">
                @forelse($recentTransfers as $transfer)
                <div class="relative group/item">
                    @if(!$loop->last)
                    <div class="absolute left-5 top-12 bottom-0 w-0.5 bg-gradient-to-b from-emerald-200 to-transparent"></div>
                    @endif
                    <a href="{{ route('bank-transfers.edit', $transfer) }}" class="relative flex items-start space-x-3 p-3.5 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 block">
                        <div class="relative flex-shrink-0">
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg blur-md opacity-0 group-hover/item:opacity-60 transition-opacity duration-300"></div>
                            <div class="relative w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center shadow-md group-hover/item:scale-110 group-hover/item:rotate-6 transition-all duration-300">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-bold text-gray-900 group-hover/item:text-emerald-700 transition-colors duration-200">
                                    {{ $transfer->fromBank?->bank_name ?? '—' }}
                                    <span class="text-gray-400 font-normal">→</span>
                                    {{ $transfer->toBank?->bank_name ?? '—' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mb-2">
                                {{ $transfer->date_added->format('d M Y') }}
                                <span class="mx-1">·</span>
                                {{ $transfer->fromBank?->currency?->currency ?? '-' }} ({{ $transfer->fromBank?->currency?->currency_symbol ?? '-' }})
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-900">{{ number_format($transfer->amount, 2) }}</span>
                                <svg class="h-4 w-4 text-gray-400 group-hover/item:text-emerald-500 group-hover/item:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="text-center py-12">
                    <h3 class="text-sm font-bold text-gray-900 mb-1">No bank transfers yet</h3>
                    <p class="text-xs text-gray-500 mb-4">Record a transfer between banks.</p>
                    <a href="{{ route('bank-transfers.create') }}" class="inline-flex items-center text-sm font-semibold text-emerald-600 hover:text-emerald-500">Create a transfer</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

