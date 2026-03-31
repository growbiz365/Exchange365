<x-app-layout>
    @section('title', 'General Vouchers - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        
        ['url' => '#', 'label' => 'General Vouchers'],
    ]" />

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 mt-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-indigo-50 border border-indigo-100 rounded-lg">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">General Vouchers</h1>
                    <p class="text-sm text-gray-500 mt-1">Manage and track all general voucher transactions</p>
                </div>
            </div>
            <div class="flex items-center">
                <x-button href="{{ route('general-vouchers.create') }}">Add General Voucher</x-button>
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
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-5 py-4 mb-4">
        <form method="GET" action="{{ route('general-vouchers.index') }}">
            <div class="flex flex-col lg:flex-row lg:items-end lg:space-x-4 space-y-2 lg:space-y-0">
                <div class="flex-1 min-w-[150px]">
                    <label for="general_voucher_id" class="sr-only">Voucher #</label>
                    <input type="number" id="general_voucher_id" name="general_voucher_id" value="{{ request('general_voucher_id') }}"
                        class="w-full px-2 py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Voucher # (ID)" min="1" />
                </div>
                <div class="min-w-[160px]">
                    <label for="bank_id" class="sr-only">Bank</label>
                    <select id="bank_id" name="bank_id"
                        class="w-full px-2 py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Banks</option>
                        @foreach($banks as $b)
                            <option value="{{ $b->bank_id }}" {{ request('bank_id') == $b->bank_id ? 'selected' : '' }}>{{ $b->bank_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[160px]">
                    <label for="party_id" class="sr-only">Party</label>
                    <select id="party_id" name="party_id"
                        class="w-full px-2 py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Parties</option>
                        @foreach($parties as $p)
                            <option value="{{ $p->party_id }}" {{ request('party_id') == $p->party_id ? 'selected' : '' }}>{{ $p->party_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[140px]">
                    <label for="date_from" class="sr-only">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                        class="w-full px-2 py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div class="min-w-[140px]">
                    <label for="date_to" class="sr-only">Date To</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                        class="w-full px-2 py-1 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div class="flex items-center space-x-2 mt-2 lg:mt-0">
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md shadow-sm transition-colors duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('general-vouchers.index') }}" class="text-xs text-gray-500 hover:text-gray-700 px-2 py-1">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- General Vouchers List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-indigo-50 border border-indigo-100 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Voucher Transactions</h2>
                        <p class="text-sm text-gray-500">Total Records: {{ $vouchers->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($vouchers->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voucher #</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Party</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($vouchers as $v)
                        <tr
                            onclick="window.location.href='{{ route('general-vouchers.show', $v) }}'"
                            class="cursor-pointer hover:bg-indigo-50/40 transition duration-150 ease-in-out"
                            title="Click to view voucher"
                        >
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">{{ $v->general_voucher_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">@businessDate($v->date_added)</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $v->bank?->bank_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $v->party?->party_name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full border {{ $v->entry_type == 1 ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                                    {{ $v->entry_type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">@currency($v->amount)</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation();">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('general-vouchers.show', $v) }}" class="text-indigo-600 hover:text-indigo-800">View</a>
                                    <a href="{{ route('general-vouchers.edit', $v) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $vouchers->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No vouchers found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new general voucher.</p>
                <div class="mt-6">
                    <a href="{{ route('general-vouchers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add General Voucher
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
