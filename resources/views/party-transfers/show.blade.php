<x-app-layout>
    @section('title', 'Transfer #' . $partyTransfer->party_transfer_id . ' - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('parties.dashboard'), 'label' => 'Parties Dashboard'],
        ['url' => route('party-transfers.index'), 'label' => 'Party Transfers'],
        ['url' => route('party-transfers.show', $partyTransfer), 'label' => 'Transfer #' . $partyTransfer->party_transfer_id]
    ]" />

    {{-- Page Header --}}
    <div class="relative bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6 mt-4 overflow-hidden group">
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-gradient-to-br from-indigo-400/10 to-slate-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex items-start gap-3 sm:space-x-4 min-w-0">
                <div class="flex-shrink-0">
                    <div class="bg-gradient-to-br from-indigo-600 to-slate-700 p-2.5 sm:p-3 rounded-xl shadow-lg transform group-hover:scale-105 transition-transform duration-300">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">Transfer #{{ $partyTransfer->party_transfer_id }}</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                        {{ $partyTransfer->date_added->format('l, d F Y') }}
                        @if($partyTransfer->user)
                            <span class="text-gray-400"> · Recorded by {{ $partyTransfer->user->name }}</span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 w-full lg:w-auto lg:shrink-0 lg:max-w-xl">
                <a href="{{ route('party-transfers.edit', $partyTransfer) }}"
                    class="inline-flex justify-center items-center rounded-xl bg-gradient-to-br from-indigo-600 to-slate-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-indigo-700 hover:to-slate-800 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 w-full sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('party-transfers.destroy', $partyTransfer) }}" method="POST" class="w-full sm:w-auto"
                    onsubmit="return confirm('Are you sure you want to delete this transfer? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full inline-flex justify-center items-center rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm font-semibold text-rose-700 shadow-sm hover:bg-rose-50 transition focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
                <a href="{{ route('party-transfers.index') }}"
                    class="inline-flex justify-center items-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 w-full sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to list
                </a>
            </div>
        </div>
    </div>

    {{-- Main content: Debit | General | Credit --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
        {{-- Debit (From) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 border border-rose-100">
                        <svg class="h-5 w-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wide text-gray-900">Debit (بنـــام)</h2>
                        <p class="text-xs text-gray-500">From · Sender</p>
                    </div>
                </div>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Party</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $partyTransfer->debitParty?->party_name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Currency</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">
                            {{ $partyTransfer->debitCurrency?->currency ?? '—' }}
                            <span class="text-gray-500">({{ $partyTransfer->debitCurrency?->currency_symbol ?? '—' }})</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Amount</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-lg font-bold bg-rose-50 text-rose-700 ring-1 ring-rose-100">
                                −{{ number_format($partyTransfer->debit_amount, 2) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- General info & details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 border border-indigo-100">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wide text-gray-900">Transfer details</h2>
                        <p class="text-xs text-gray-500">Rate & operation</p>
                    </div>
                </div>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Date</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $partyTransfer->date_added->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Exchange rate</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">
                            {{ number_format($partyTransfer->rate, 4) }}
                            <span class="ml-1 text-sm font-medium text-gray-500">({{ $partyTransfer->transaction_operation == 1 ? '÷' : '×' }})</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Operation</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-700">
                            {{ $partyTransfer->transaction_operation == 1 ? 'Divide' : 'Multiply' }}
                        </dd>
                    </div>
                    @if($partyTransfer->details)
                    <div class="pt-2">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-1">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $partyTransfer->details }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Credit (To) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 border border-emerald-100">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wide text-gray-900">Credit (جمـــع)</h2>
                        <p class="text-xs text-gray-500">To · Receiver</p>
                    </div>
                </div>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Party</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $partyTransfer->creditParty?->party_name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Currency</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">
                            {{ $partyTransfer->creditCurrency?->currency ?? '—' }}
                            <span class="text-gray-500">({{ $partyTransfer->creditCurrency?->currency_symbol ?? '—' }})</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Amount</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-lg font-bold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                +{{ number_format($partyTransfer->credit_amount, 2) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    {{-- Visual flow: Debit → Credit --}}
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 mb-6 py-2 text-center sm:text-left">
        <span class="text-sm font-semibold text-rose-700 max-w-full truncate px-2" title="{{ $partyTransfer->debitParty?->party_name ?? '—' }}">{{ $partyTransfer->debitParty?->party_name ?? '—' }}</span>
        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 text-sky-600">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </span>
        <span class="text-sm font-semibold text-emerald-700 max-w-full truncate px-2" title="{{ $partyTransfer->creditParty?->party_name ?? '—' }}">{{ $partyTransfer->creditParty?->party_name ?? '—' }}</span>
    </div>

    {{-- Attachments --}}
    @if($partyTransfer->attachments->isNotEmpty())
    <div class="relative bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="border-b border-gray-100 bg-white px-4 sm:px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100">
                    <svg class="h-5 w-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L21 13"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">Attachments</h2>
                    <p class="text-xs text-gray-500">{{ $partyTransfer->attachments->count() }} file(s)</p>
                </div>
            </div>
        </div>
        <div class="p-4 sm:p-6">
            <ul class="space-y-3">
                @foreach($partyTransfer->attachments as $attachment)
                <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 rounded-lg border border-gray-200 bg-gray-50 p-4 hover:bg-white hover:shadow-sm transition">
                    <div class="flex min-w-0 flex-1 items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white border border-gray-200">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="truncate font-medium text-gray-900">{{ $attachment->file_title ?: $attachment->file_name }}</p>
                            <p class="text-xs text-gray-500">{{ $attachment->file_name }} @if($attachment->file_size) · {{ $attachment->file_size_formatted }} @endif</p>
                        </div>
                    </div>
                    <a href="{{ $attachment->file_url }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition shrink-0 w-full sm:w-auto">
                        <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Open
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Bottom actions --}}
    <div class="flex flex-col-reverse sm:flex-row sm:flex-wrap sm:items-center sm:justify-between gap-3">
        <a href="{{ route('party-transfers.index') }}" class="inline-flex items-center justify-center sm:justify-start text-sm font-medium text-gray-600 hover:text-gray-900 transition w-full sm:w-auto">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Party Transfers
        </a>
        <div class="flex w-full sm:w-auto">
            <a href="{{ route('party-transfers.edit', $partyTransfer) }}"
                class="inline-flex items-center justify-center w-full sm:w-auto rounded-lg bg-gradient-to-br from-sky-500 to-indigo-600 px-4 py-2.5 sm:py-2 text-sm font-semibold text-white shadow-sm hover:from-sky-600 hover:to-indigo-700 transition">
                Edit transfer
            </a>
        </div>
    </div>
</x-app-layout>
