<x-app-layout>
    @section('title', 'Reports Dashboard - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('reports.index'), 'label' => 'Reports Dashboard'],
    ]" />

    <!-- Header Section -->
    <div class="relative bg-gradient-to-br from-indigo-700 via-indigo-800 to-slate-900 rounded-2xl shadow-xl border border-indigo-600/20 p-8 mb-8 mt-4 overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>
        </div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex items-start space-x-5">
                <div class="flex-shrink-0 relative">
                    <div class="absolute inset-0 bg-white/20 rounded-2xl blur-xl"></div>
                    <div class="relative bg-white/20 backdrop-blur-md p-4 rounded-2xl shadow-lg border border-white/30">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2 tracking-tight">Reports Dashboard</h1>
                    <p class="text-base text-white/80 font-medium">Currency summary, bank &amp; party ledgers, and balances</p>
                    <div class="flex items-center space-x-3 mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white border border-white/30 backdrop-blur-sm">
                            Activity &amp; Analytics
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-100 border border-emerald-400/30 backdrop-blur-sm">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full mr-1.5 animate-pulse"></span>
                            8 Reports
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <div class="bg-white/10 backdrop-blur-md rounded-xl px-4 py-3 border border-white/20 shadow-lg">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-white/70 font-medium">{{ now()->format('l') }}</div>
                            <div class="text-sm font-bold text-white">{{ now()->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl px-4 py-3 border border-white/20 shadow-lg">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-white/70 font-medium">Time</div>
                            <div class="text-sm font-bold text-white">{{ now()->format('g:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Reports -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Summary Reports
                </h2>
                <p class="text-sm text-gray-600 mt-1 ml-9">Currency and balance overview by date</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">2 Reports</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <a href="{{ route('reports.currency-summary') }}" class="group relative bg-white rounded-2xl shadow-sm border-2 border-gray-100 overflow-hidden hover:shadow-xl hover:border-indigo-500 transition-all duration-300 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-blue-600/0 group-hover:from-indigo-500/5 group-hover:to-blue-600/5 transition-all duration-300"></div>
                <div class="relative p-6">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-indigo-500/20 rounded-2xl blur-xl group-hover:blur-2xl transition-all duration-300"></div>
                            <div class="relative w-20 h-20 flex items-center justify-center bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-2xl group-hover:from-indigo-500 group-hover:to-blue-600 group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-10 h-10 text-indigo-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300 mb-1">Currency Summary</h3>
                            <p class="text-xs text-gray-500">Bank &amp; party balances by currency</p>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 to-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
            </a>
            <a href="{{ route('reports.activity-log') }}" class="group relative bg-white rounded-2xl shadow-sm border-2 border-gray-100 overflow-hidden hover:shadow-xl hover:border-slate-500 transition-all duration-300 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-slate-500/0 to-slate-600/0 group-hover:from-slate-500/5 group-hover:to-slate-600/5 transition-all duration-300"></div>
                <div class="relative p-6">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-slate-500/20 rounded-2xl blur-xl group-hover:blur-2xl transition-all duration-300"></div>
                            <div class="relative w-20 h-20 flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-50 rounded-2xl group-hover:from-slate-500 group-hover:to-slate-600 group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-3">
                                <!-- Activity Log Icon: clock with circular border -->
                                <svg class="w-10 h-10 text-slate-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-slate-600 transition-colors duration-300 mb-1">Activity Log</h3>
                            <p class="text-xs text-gray-500">Audit trail of who did what and when</p>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-slate-500 to-slate-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
            </a>
        </div>
    </div>

    <!-- Bank Reports -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Bank Reports
                </h2>
                <p class="text-sm text-gray-600 mt-1 ml-9">Ledger, balances, and currency breakdown for banks</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-sky-100 text-sky-800 border border-sky-200">3 Reports</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <a href="{{ route('banks.ledger') }}" class="group relative bg-white rounded-2xl shadow-sm border-2 border-gray-100 overflow-hidden hover:shadow-xl hover:border-sky-500 transition-all duration-300 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-500/0 to-blue-600/0 group-hover:from-sky-500/5 group-hover:to-blue-600/5 transition-all duration-300"></div>
                <div class="relative p-6">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-sky-500/20 rounded-2xl blur-xl group-hover:blur-2xl transition-all duration-300"></div>
                            <div class="relative w-20 h-20 flex items-center justify-center bg-gradient-to-br from-sky-100 to-sky-50 rounded-2xl group-hover:from-sky-500 group-hover:to-blue-600 group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-10 h-10 text-sky-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-sky-600 transition-colors duration-300 mb-1">Bank Ledger</h3>
                            <p class="text-xs text-gray-500">All bank transactions by date</p>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-sky-500 to-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
            </a>
            <a href="{{ route('banks.balances') }}" class="group relative bg-white rounded-2xl shadow-sm border-2 border-gray-100 overflow-hidden hover:shadow-xl hover:border-sky-500 transition-all duration-300 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-500/0 to-blue-600/0 group-hover:from-sky-500/5 group-hover:to-blue-600/5 transition-all duration-300"></div>
                <div class="relative p-6">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-sky-500/20 rounded-2xl blur-xl group-hover:blur-2xl transition-all duration-300"></div>
                            <div class="relative w-20 h-20 flex items-center justify-center bg-gradient-to-br from-sky-100 to-sky-50 rounded-2xl group-hover:from-sky-500 group-hover:to-blue-600 group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-10 h-10 text-sky-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-sky-600 transition-colors duration-300 mb-1">Bank Balances</h3>
                            <p class="text-xs text-gray-500">Current balance per bank</p>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-sky-500 to-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
            </a>
            <a href="{{ route('banks.currency-balances') }}" class="group relative bg-white rounded-2xl shadow-sm border-2 border-gray-100 overflow-hidden hover:shadow-xl hover:border-sky-500 transition-all duration-300 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-500/0 to-blue-600/0 group-hover:from-sky-500/5 group-hover:to-blue-600/5 transition-all duration-300"></div>
                <div class="relative p-6">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-sky-500/20 rounded-2xl blur-xl group-hover:blur-2xl transition-all duration-300"></div>
                            <div class="relative w-20 h-20 flex items-center justify-center bg-gradient-to-br from-sky-100 to-sky-50 rounded-2xl group-hover:from-sky-500 group-hover:to-blue-600 group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-10 h-10 text-sky-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-sky-600 transition-colors duration-300 mb-1">Currency Balances</h3>
                            <p class="text-xs text-gray-500">Balances grouped by currency</p>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-sky-500 to-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
            </a>
        </div>
    </div>

    <!-- Party Reports -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Party Reports
                </h2>
                <p class="text-sm text-gray-600 mt-1 ml-9">Ledger, balances, and currency breakdown for parties</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">3 Reports</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <a href="{{ route('parties.ledger') }}" class="group relative bg-white rounded-2xl shadow-sm border-2 border-gray-100 overflow-hidden hover:shadow-xl hover:border-emerald-500 transition-all duration-300 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-teal-600/0 group-hover:from-emerald-500/5 group-hover:to-teal-600/5 transition-all duration-300"></div>
                <div class="relative p-6">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-emerald-500/20 rounded-2xl blur-xl group-hover:blur-2xl transition-all duration-300"></div>
                            <div class="relative w-20 h-20 flex items-center justify-center bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-2xl group-hover:from-emerald-500 group-hover:to-teal-600 group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-10 h-10 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-emerald-600 transition-colors duration-300 mb-1">Parties Ledger</h3>
                            <p class="text-xs text-gray-500">All party transactions by date</p>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
            </a>
            <a href="{{ route('parties.balances') }}" class="group relative bg-white rounded-2xl shadow-sm border-2 border-gray-100 overflow-hidden hover:shadow-xl hover:border-emerald-500 transition-all duration-300 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-teal-600/0 group-hover:from-emerald-500/5 group-hover:to-teal-600/5 transition-all duration-300"></div>
                <div class="relative p-6">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-emerald-500/20 rounded-2xl blur-xl group-hover:blur-2xl transition-all duration-300"></div>
                            <div class="relative w-20 h-20 flex items-center justify-center bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-2xl group-hover:from-emerald-500 group-hover:to-teal-600 group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-10 h-10 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-emerald-600 transition-colors duration-300 mb-1">Parties Balances</h3>
                            <p class="text-xs text-gray-500">Current balance per party</p>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
            </a>
            <a href="{{ route('parties.currency') }}" class="group relative bg-white rounded-2xl shadow-sm border-2 border-gray-100 overflow-hidden hover:shadow-xl hover:border-emerald-500 transition-all duration-300 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-teal-600/0 group-hover:from-emerald-500/5 group-hover:to-teal-600/5 transition-all duration-300"></div>
                <div class="relative p-6">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-emerald-500/20 rounded-2xl blur-xl group-hover:blur-2xl transition-all duration-300"></div>
                            <div class="relative w-20 h-20 flex items-center justify-center bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-2xl group-hover:from-emerald-500 group-hover:to-teal-600 group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-10 h-10 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-emerald-600 transition-colors duration-300 mb-1">Parties Currency</h3>
                            <p class="text-xs text-gray-500">Party balances grouped by currency</p>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
            </a>
        </div>
    </div>
</x-app-layout>
