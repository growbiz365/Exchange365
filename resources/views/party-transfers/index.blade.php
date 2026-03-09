<x-app-layout>
    @section('title', 'Party Transfers - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('parties.dashboard'), 'label' => 'Parties Dashboard'],
        ['url' => route('party-transfers.index'), 'label' => 'Party Transfers']
    ]" />

    {{-- Subtle Background Gradient --}}
    <div class="fixed inset-0 -z-10 bg-gradient-to-br from-blue-50/30 via-white to-purple-50/20 pointer-events-none"></div>

    {{-- Page Header - Glass Morphism --}}
    <div class="relative  bg-white/70 rounded-2xl shadow-xl  border border-white/60 p-6 mb-6 mt-4 overflow-hidden group">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-sky-400/20 to-indigo-400/20 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
        <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-gradient-to-tr from-indigo-400/15 to-sky-400/15 rounded-full blur-2xl"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative flex-shrink-0">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-500 to-indigo-600 rounded-xl blur-lg opacity-60 group-hover:opacity-80 transition-opacity duration-300"></div>
                    <div class="relative bg-gradient-to-br from-sky-500 to-indigo-600 p-3 rounded-xl shadow-lg transform group-hover:scale-105 transition-all duration-300">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 via-sky-800 to-indigo-900 bg-clip-text text-transparent">Party Transfers</h1>
                    <p class="text-sm text-gray-600 mt-0.5">View and manage transfers between parties</p>
                </div>
            </div>
            <a href="{{ route('party-transfers.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-500/25 transition hover:shadow-xl hover:shadow-sky-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 shrink-0">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Transfer
            </a>
        </div>
    </div>

    @if (Session::has('success'))
        <x-success-alert message="{{ Session::get('success') }}" />
    @endif
    @if (Session::has('error'))
        <x-error-alert message="{{ Session::get('error') }}" />
    @endif

    {{-- Filters - Glass Card --}}
    <div class="relative backdrop-blur-xl bg-white/70 rounded-xl shadow-lg shadow-sky-500/5 border border-white/60 p-5 mb-6 overflow-hidden">
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-gradient-to-br from-sky-400/10 to-indigo-400/10 rounded-full blur-2xl"></div>
        <div class="relative">
            <div class="flex items-center gap-2 mb-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-100">
                    <svg class="h-4 w-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-900">Filters</span>
            </div>
            <form action="{{ route('party-transfers.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="min-w-[100px] max-w-[140px]">
                    <label for="party_transfer_id" class="block text-xs font-medium text-gray-600 mb-1.5">Transfer ID</label>
                    <input type="number" name="party_transfer_id" id="party_transfer_id" value="{{ request('party_transfer_id') }}"
                        placeholder="ID"
                        class="block w-full rounded-lg border-gray-300 shadow-sm text-sm py-2 focus:border-sky-500 focus:ring-sky-500">
                </div>
                <div class="min-w-[140px] max-w-[160px]">
                    <label for="date_from" class="block text-xs font-medium text-gray-600 mb-1.5">From date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                        class="block w-full rounded-lg border-gray-300 shadow-sm text-sm py-2 focus:border-sky-500 focus:ring-sky-500">
                </div>
                <div class="min-w-[140px] max-w-[160px]">
                    <label for="date_to" class="block text-xs font-medium text-gray-600 mb-1.5">To date</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                        class="block w-full rounded-lg border-gray-300 shadow-sm text-sm py-2 focus:border-sky-500 focus:ring-sky-500">
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="inline-flex items-center rounded-lg bg-gradient-to-br from-sky-500 to-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:from-sky-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </button>
                    <a href="{{ route('party-transfers.index') }}"
                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition"
                        title="Clear filters">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="relative backdrop-blur-xl bg-white/80 rounded-xl shadow-lg shadow-sky-500/5 border border-white/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200/80">
                <thead>
                    <tr class="bg-gradient-to-r from-sky-50/90 to-indigo-50/80">
                        <th scope="col" class="py-4 pl-6 pr-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">#</th>
                        <th scope="col" class="py-4 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Date</th>
                        <th scope="col" class="py-4 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Debit (بنـــام)</th>
                        <th scope="col" class="py-4 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Amount</th>
                        <th scope="col" class="py-4 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Credit (جمـــع)</th>
                        <th scope="col" class="py-4 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Amount</th>
                        <th scope="col" class="py-4 px-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Rate</th>
                        <th scope="col" class="py-4 pl-3 pr-6 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white/50">
                    @forelse($transfers as $transfer)
                    <tr class="hover:bg-sky-50/60 transition-colors duration-150 cursor-pointer"
                        onclick="window.location='{{ route('party-transfers.show', $transfer) }}'">
                        <td class="whitespace-nowrap pl-6 pr-3 py-4 text-sm font-semibold text-gray-900">{{ $transfer->party_transfer_id }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ $transfer->date_added->format('d M Y') }}</td>
                        <td class="whitespace-nowrap px-3 py-4">
                            <div class="font-medium text-gray-900">{{ $transfer->debitParty?->party_name ?? '—' }}</div>
                            <div class="text-xs text-gray-500">{{ $transfer->debitCurrency?->currency ?? '-' }} ({{ $transfer->debitCurrency?->currency_symbol ?? '-' }})</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">
                            <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold bg-rose-100 text-rose-800 ring-1 ring-rose-200/60">
                                −{{ number_format($transfer->debit_amount, 2) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">
                            <div class="font-medium text-gray-900">{{ $transfer->creditParty?->party_name ?? '—' }}</div>
                            <div class="text-xs text-gray-500">{{ $transfer->creditCurrency?->currency ?? '-' }} ({{ $transfer->creditCurrency?->currency_symbol ?? '-' }})</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">
                            <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200/60">
                                +{{ number_format($transfer->credit_amount, 2) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">
                            <span class="font-medium text-gray-900">{{ number_format($transfer->rate, 4) }}</span>
                            <span class="text-xs text-gray-500 ml-1">{{ $transfer->transaction_operation == 1 ? '÷' : '×' }}</span>
                        </td>
                        <td class="whitespace-nowrap pl-3 pr-6 py-4">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('party-transfers.show', $transfer) }}" onclick="event.stopPropagation()"
                                    class="inline-flex items-center justify-center rounded-lg p-2 text-indigo-600 hover:bg-indigo-100 transition"
                                    title="View transfer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('party-transfers.edit', $transfer) }}" onclick="event.stopPropagation()"
                                    class="inline-flex items-center justify-center rounded-lg p-2 text-sky-600 hover:bg-sky-100 transition"
                                    title="Edit transfer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('party-transfers.destroy', $transfer) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this transfer?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="event.stopPropagation()"
                                        class="inline-flex items-center justify-center rounded-lg p-2 text-rose-600 hover:bg-rose-100 transition"
                                        title="Delete transfer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-sky-100/80">
                                    <svg class="h-8 w-8 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-gray-900">No party transfers found</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating your first transfer between parties.</p>
                                <a href="{{ route('party-transfers.create') }}"
                                    class="mt-5 inline-flex items-center rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-sky-600 hover:to-indigo-700 transition">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    New Transfer
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transfers->hasPages())
        <div class="border-t border-gray-200/80 bg-gray-50/50 px-6 py-4">
            {{ $transfers->withQueryString()->links() }}
        </div>
        @endif

        @if($transfers->count() > 0)
        <div class="border-t border-gray-200/80 bg-gradient-to-r from-sky-50/50 to-indigo-50/50 px-6 py-3">
            <p class="text-xs font-medium text-gray-600">
                Showing <span class="font-semibold text-gray-900">{{ $transfers->firstItem() }}</span> to
                <span class="font-semibold text-gray-900">{{ $transfers->lastItem() }}</span> of
                <span class="font-semibold text-gray-900">{{ $transfers->total() }}</span> transfers
            </p>
        </div>
        @endif
    </div>
</x-app-layout>
