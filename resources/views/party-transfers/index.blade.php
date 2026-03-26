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

    {{-- Simple Table Layout (like reference) --}}
    <div class="bg-gray-100 rounded-lg shadow-sm border border-gray-200 overflow-hidden mt-4">
        <div class="border-b border-gray-200 bg-white px-4 py-3">
            <form action="{{ route('party-transfers.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                    <div class="md:col-span-3">
                        <h4 class="text-base font-semibold text-gray-900">Party Transfers</h4>
                    </div>

                    <div class="md:col-span-2">
                        <input type="number" id="party_transfer_id" name="party_transfer_id" value="{{ request('party_transfer_id') }}"
                            placeholder="Voucher No"
                            class="w-full rounded border border-indigo-500/70 px-2 py-1.5 text-sm focus:border-indigo-600 focus:ring-indigo-600" />
                    </div>

                    <div class="md:col-span-2">
                        <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                            placeholder="From Date"
                            class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-indigo-600 focus:ring-indigo-600" />
                    </div>

                    <div class="md:col-span-2">
                        <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                            placeholder="To Date"
                            class="w-full rounded border border-gray-300 px-2 py-1.5 text-sm focus:border-indigo-600 focus:ring-indigo-600" />
                    </div>

                    <div class="md:col-span-3 flex items-center gap-2 md:justify-end">
                        <button type="submit" class="inline-flex items-center rounded bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </button>
                        <a href="{{ route('party-transfers.create') }}" class="inline-flex items-center rounded bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            New
                        </a>
                        <a href="{{ route('party-transfers.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-2 py-2">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Debit ( بنام ) Party</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Debit ( بنام ) Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Credit ( جمع ) Party</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Credit ( جمع ) Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transfers as $transfer)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out cursor-pointer"
                            onclick="window.location='{{ route('party-transfers.show', $transfer) }}'">
                            <td class="px-4 py-3 whitespace-nowrap font-semibold text-indigo-700">{{ $transfer->party_transfer_id }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-600">{{ $transfer->date_added->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $transfer->debitParty?->party_name ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-800">
                                {{ number_format($transfer->debit_amount, 2) }}
                                <span class="text-gray-500">{{ $transfer->debitCurrency?->currency ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-800">{{ $transfer->creditParty?->party_name ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-800">
                                {{ number_format($transfer->credit_amount, 2) }}
                                <span class="text-gray-500">{{ $transfer->creditCurrency?->currency ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-600">No Records Found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div class="text-sm text-gray-700">
                    Total Record Found: <strong>{{ $transfers->total() }}</strong>
                </div>
                <div>
                    {{ $transfers->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
