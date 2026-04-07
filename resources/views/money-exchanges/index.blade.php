<x-app-layout>
    @section('title', 'Money Exchanges - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('banks.dashboard'), 'label' => 'Bank Management'],
        ['url' => '#', 'label' => 'Money Exchanges'],
    ]" />

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6 mt-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start sm:items-center gap-3 sm:space-x-4 min-w-0">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-indigo-50 border border-indigo-100 rounded-lg">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">Money Exchanges</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">View and manage currency exchanges between bank accounts</p>
                </div>
            </div>
            <div class="w-full sm:w-auto shrink-0">
                <x-button href="{{ route('money-exchanges.create') }}">New Exchange</x-button>
            </div>
        </div>
    </div>

    @if (Session::has('success'))
        <x-success-alert message="{{ Session::get('success') }}" />
    @endif
    @if (Session::has('error'))
        <x-error-alert message="{{ Session::get('error') }}" />
    @endif

    <!-- Filters Section (Compact) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-4 sm:px-5 py-4 mb-4">
        <form method="GET" action="{{ route('money-exchanges.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 lg:items-end">
                <div class="min-w-0 sm:col-span-2 lg:col-span-1 xl:col-span-1">
                    <label for="money_exchange_id" class="sr-only">Exchange #</label>
                    <input type="number" id="money_exchange_id" name="money_exchange_id" value="{{ request('money_exchange_id') }}"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Exchange # (ID)" min="1" />
                </div>
                <div class="min-w-0 sm:col-span-2 lg:col-span-1 xl:col-span-1">
                    <label for="search" class="sr-only">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search bank or details..." />
                </div>
                <div class="min-w-0">
                    <label for="date_from" class="sr-only">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div class="min-w-0">
                    <label for="date_to" class="sr-only">Date To</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:col-span-2 lg:col-span-3 xl:col-span-1 xl:justify-end">
                    <button type="submit"
                        class="inline-flex flex-1 sm:flex-none justify-center items-center min-h-[2.25rem] px-4 py-2 sm:px-3 sm:py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('money-exchanges.index') }}" class="inline-flex items-center justify-center text-xs text-gray-500 hover:text-gray-700 px-3 py-2 min-h-[2.25rem] sm:min-h-0 sm:py-1">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Money Exchanges List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-indigo-50 border border-indigo-100 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Exchange Transactions</h2>
                        <p class="text-sm text-gray-500">Total Records: {{ $exchanges->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($exchanges->count() > 0)
            <div class="flow-root overflow-x-auto -mx-px">
                <table class="min-w-[1020px] w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">#</th>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Date</th>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From Account</th>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Debit Amount</th>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To Account</th>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Credit Amount</th>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Rate</th>
                            <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($exchanges as $exchange)
                            <tr
                                onclick="window.location.href='{{ route('money-exchanges.show', $exchange) }}'"
                                class="cursor-pointer hover:bg-indigo-50/40 transition duration-150 ease-in-out"
                                title="Click to view exchange"
                            >
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">{{ $exchange->money_exchange_id }}</td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">@businessDate($exchange->date_added)</td>
                                <td class="px-3 sm:px-6 py-4 text-sm text-gray-500 max-w-[9rem] sm:max-w-none">
                                    <div class="font-medium text-gray-900 truncate sm:whitespace-normal" title="{{ $exchange->fromBank?->bank_name ?? '-' }}">{{ $exchange->fromBank?->bank_name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $exchange->fromBank?->currency?->currency ?? '-' }} ({{ $exchange->fromBank?->currency?->currency_symbol ?? '-' }})</div>
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full border bg-rose-50 text-rose-700 border-rose-100">
                                        −{{ number_format($exchange->debit_amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-4 text-sm text-gray-500 max-w-[9rem] sm:max-w-none">
                                    <div class="font-medium text-gray-900 truncate sm:whitespace-normal" title="{{ $exchange->toBank?->bank_name ?? '-' }}">{{ $exchange->toBank?->bank_name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $exchange->toBank?->currency?->currency ?? '-' }} ({{ $exchange->toBank?->currency?->currency_symbol ?? '-' }})</div>
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full border bg-emerald-50 text-emerald-700 border-emerald-100">
                                        +{{ number_format($exchange->credit_amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="font-medium">{{ number_format($exchange->rate, 4) }}</span>
                                    <span class="text-xs text-gray-500 ml-1">{{ $exchange->transaction_operation == 1 ? '÷' : '×' }}</span>
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation();">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('money-exchanges.show', $exchange) }}" class="text-indigo-600 hover:text-indigo-800">View</a>
                                        <a href="{{ route('money-exchanges.edit', $exchange) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                        <form action="{{ route('money-exchanges.destroy', $exchange) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this money exchange?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 flex justify-center sm:justify-end overflow-x-auto">
                {{ $exchanges->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No money exchanges found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new money exchange.</p>
                <div class="mt-6">
                    <a href="{{ route('money-exchanges.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        New Exchange
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

