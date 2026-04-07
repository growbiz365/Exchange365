<x-app-layout>
    @section('title', 'Party Transfers - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('parties.dashboard'), 'label' => 'Parties Dashboard'],
        ['url' => route('party-transfers.index'), 'label' => 'Party Transfers']
    ]" />

    @if (Session::has('success'))
        <x-success-alert message="{{ Session::get('success') }}" />
    @endif
    @if (Session::has('error'))
        <x-error-alert message="{{ Session::get('error') }}" />
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-4">
        <div class="border-b border-gray-100 bg-white px-4 sm:px-5 py-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <h4 class="text-lg sm:text-base font-semibold text-gray-900 leading-tight">Party Transfers</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Debit and credit party vouchers</p>
                </div>
                <div class="w-full sm:w-auto shrink-0">
                    <a href="{{ route('party-transfers.create') }}" class="inline-flex w-full sm:w-auto justify-center items-center min-h-[2.25rem] rounded-lg bg-indigo-600 px-4 py-2.5 sm:py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New transfer
                    </a>
                </div>
            </div>

            <form action="{{ route('party-transfers.index') }}" method="GET" class="mt-4 pt-4 border-t border-gray-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3 lg:items-end">
                    <div class="min-w-0 sm:col-span-2 lg:col-span-1 xl:col-span-1">
                        <label for="party_transfer_id" class="sr-only">Voucher number</label>
                        <input type="number" id="party_transfer_id" name="party_transfer_id" value="{{ request('party_transfer_id') }}"
                            placeholder="Voucher No"
                            class="w-full rounded border border-gray-300 bg-white px-2 py-2 sm:py-1.5 text-sm focus:border-indigo-600 focus:ring-indigo-600" />
                    </div>
                    <div class="min-w-0">
                        <label for="date_from" class="sr-only">From date</label>
                        <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                            class="w-full rounded border border-gray-300 px-2 py-2 sm:py-1.5 text-sm focus:border-indigo-600 focus:ring-indigo-600" />
                    </div>
                    <div class="min-w-0">
                        <label for="date_to" class="sr-only">To date</label>
                        <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                            class="w-full rounded border border-gray-300 px-2 py-2 sm:py-1.5 text-sm focus:border-indigo-600 focus:ring-indigo-600" />
                    </div>
                    <div class="flex flex-wrap items-center gap-2 sm:col-span-2 lg:col-span-1 xl:col-span-2 xl:justify-end">
                        <button type="submit" class="inline-flex flex-1 sm:flex-none justify-center items-center min-h-[2.25rem] rounded-lg bg-indigo-600 px-4 py-2 sm:px-3 sm:py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                            <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </button>
                        <a href="{{ route('party-transfers.index') }}" class="inline-flex items-center justify-center text-sm text-gray-500 hover:text-gray-700 px-3 py-2 min-h-[2.25rem] sm:min-h-0 sm:py-1">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="flow-root overflow-x-auto -mx-px">
            <table class="min-w-[920px] w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">No</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Date</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Debit ( بنام ) Party</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Debit Amount</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Credit ( جمع ) Party</th>
                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Credit Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transfers as $transfer)
                        <tr class="hover:bg-indigo-50/40 transition duration-150 ease-in-out cursor-pointer"
                            onclick="window.location='{{ route('party-transfers.show', $transfer) }}'">
                            <td class="px-3 sm:px-4 py-3 whitespace-nowrap font-semibold text-indigo-700">{{ $transfer->party_transfer_id }}</td>
                            <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-gray-600">{{ $transfer->date_added->format('d/m/Y') }}</td>
                            <td class="px-3 sm:px-4 py-3 text-gray-800 max-w-[10rem] sm:max-w-none truncate sm:whitespace-normal" title="{{ $transfer->debitParty?->party_name ?? '—' }}">{{ $transfer->debitParty?->party_name ?? '—' }}</td>
                            <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-gray-800">
                                {{ number_format($transfer->debit_amount, 2) }}
                                <span class="text-gray-500">{{ $transfer->debitCurrency?->currency ?? '-' }}</span>
                            </td>
                            <td class="px-3 sm:px-4 py-3 text-gray-800 max-w-[10rem] sm:max-w-none truncate sm:whitespace-normal" title="{{ $transfer->creditParty?->party_name ?? '—' }}">{{ $transfer->creditParty?->party_name ?? '—' }}</td>
                            <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-gray-800">
                                {{ number_format($transfer->credit_amount, 2) }}
                                <span class="text-gray-500">{{ $transfer->creditCurrency?->currency ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 sm:px-6 py-10 text-center text-sm text-gray-600">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 bg-gray-50 px-4 sm:px-5 py-3">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div class="text-sm text-gray-700 text-center sm:text-left">
                    Total Record Found: <strong>{{ $transfers->total() }}</strong>
                </div>
                <div class="flex justify-center sm:justify-end overflow-x-auto">
                    {{ $transfers->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
