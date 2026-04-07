<x-app-layout>
    @section('title', 'Sales - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('sales.dashboard'), 'label' => 'Sales Dashboard'],
        ['url' => '#', 'label' => 'Sales'],
    ]" />

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-4 sm:mb-6 mt-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start sm:items-center gap-3 sm:space-x-4 min-w-0">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-emerald-50 border border-emerald-100 rounded-lg">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">Sales</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Manage and track all sales transactions</p>
                </div>
            </div>
            <div class="w-full sm:w-auto shrink-0">
                <x-button href="{{ route('sales.create') }}">Add Sales</x-button>
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
        <form method="GET" action="{{ route('sales.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 lg:items-end">
                <div class="min-w-0 sm:col-span-2 lg:col-span-1 xl:col-span-1">
                    <label for="sales_id" class="sr-only">Sales #</label>
                    <input type="number" id="sales_id" name="sales_id" value="{{ request('sales_id') }}"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Sales # (ID)" min="1" />
                </div>
                <div class="min-w-0">
                    <label for="bank_id" class="sr-only">Bank</label>
                    <select id="bank_id" name="bank_id"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Banks</option>
                        @foreach($banks as $b)
                            <option value="{{ $b->bank_id }}" {{ request('bank_id') == $b->bank_id ? 'selected' : '' }}>{{ $b->bank_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-0">
                    <label for="date_from" class="sr-only">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500" />
                </div>
                <div class="min-w-0">
                    <label for="date_to" class="sr-only">Date To</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                        class="w-full px-2 py-2 sm:py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500" />
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:col-span-2 lg:col-span-3 xl:col-span-1 xl:justify-end">
                    <button type="submit"
                        class="inline-flex flex-1 sm:flex-none justify-center items-center min-h-[2.25rem] px-4 py-2 sm:px-3 sm:py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('sales.index') }}" class="inline-flex items-center justify-center text-xs text-gray-500 hover:text-gray-700 px-3 py-2 min-h-[2.25rem] sm:min-h-0 sm:py-1">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Sales List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-emerald-50 border border-emerald-100 rounded-lg shrink-0">
                        <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Sales Transactions</h2>
                        <p class="text-sm text-gray-500">Total Records: {{ $sales->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($sales->count() > 0)
            <div class="flow-root overflow-x-auto -mx-px">
            <table class="min-w-[920px] w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Sales #</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Date</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Party</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Currency (Bank)</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Party Amount</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Rate</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($sales as $s)
                        <tr onclick="window.location.href='{{ route('sales.show', $s) }}';" class="cursor-pointer hover:bg-indigo-50/40 transition duration-150 ease-in-out" title="Click to view">
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-emerald-700"><a href="{{ route('sales.show', $s) }}" class="hover:text-emerald-800">{{ $s->sales_id }}</a></td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">@businessDate($s->date_added)</td>
                            <td class="px-3 sm:px-6 py-4 text-sm text-gray-500 max-w-[9rem] sm:max-w-none truncate sm:whitespace-normal" title="{{ $s->bank?->bank_name ?? '-' }}">{{ $s->bank?->bank_name ?? '-' }}</td>
                            <td class="px-3 sm:px-6 py-4 text-sm text-gray-500 max-w-[9rem] sm:max-w-none truncate sm:whitespace-normal" title="{{ $s->party?->party_name ?? '-' }}">{{ $s->party?->party_name ?? '-' }}</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">@currency($s->currency_amount)</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $s->partyCurrency?->currency_symbol ?? '' }} {{ number_format($s->party_amount, 2) }}</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($s->rate, 2) }}</td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation();">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('sales.show', $s) }}" class="text-emerald-700 hover:text-emerald-900">View</a>
                                    <a href="{{ route('sales.edit', $s) }}" class="text-emerald-700 hover:text-emerald-900">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 flex justify-center sm:justify-end overflow-x-auto">
                {{ $sales->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No sales found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new sales transaction.</p>
                <div class="mt-6">
                    <a href="{{ route('sales.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Sales
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
