<x-app-layout>
    @section('title', 'Purchase #' . $purchase->purchase_id . ' - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
       
        ['url' => route('purchases.index'), 'label' => 'Purchase'],
        ['url' => route('purchases.show', $purchase), 'label' => 'Purchase #' . $purchase->purchase_id]
    ]" />

    <div class="fixed inset-0 -z-10 bg-gradient-to-br from-amber-50/30 via-white to-orange-50/20 pointer-events-none"></div>

    <div class="relative bg-gray-100 rounded-2xl shadow-xl shadow-amber-500/5 border border-gray-200 p-6 mb-6 mt-4 overflow-hidden group">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-amber-400/20 to-orange-400/20 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative flex-shrink-0">
                    <div class="relative bg-gradient-to-br from-amber-500 to-orange-600 p-3 rounded-xl shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 via-amber-800 to-orange-900 bg-clip-text text-transparent">
                        Purchase #{{ $purchase->purchase_id }}
                    </h1>
                    <p class="text-sm text-gray-600 mt-0.5">
                        {{ $purchase->date_added->format('l, d F Y') }}
                        @if($purchase->user)
                            <span class="text-gray-400"> · Recorded by {{ $purchase->user->name }}</span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('purchases.print', $purchase) }}" target="_blank"
                    class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print
                </a>
                <a href="{{ route('purchases.edit', $purchase) }}"
                    class="inline-flex items-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-amber-600 hover:to-orange-700 transition">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="inline"
                    onsubmit="return confirm('Are you sure you want to delete this purchase? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm font-semibold text-rose-700 shadow-sm hover:bg-rose-50 transition">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete
                    </button>
                </form>
                <a href="{{ route('purchases.index') }}"
                    class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to list
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Deposit (Bank) --}}
        <div class="relative overflow-hidden rounded-xl shadow-lg border border-rose-100 bg-gradient-to-br from-rose-50/90 via-white to-rose-50/50">
            <div class="relative p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-rose-200/60">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 shadow-md">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wide text-rose-800">Deposit ( جمع )</h2>
                    </div>
                </div>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Bank</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $purchase->bank?->bank_name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Currency</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">
                            {{ $purchase->bank?->currency?->currency ?? '—' }}
                            <span class="text-gray-500">({{ $purchase->bank?->currency?->currency_symbol ?? '—' }})</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Deposit Amount</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-lg font-bold bg-rose-100 text-rose-800 ring-1 ring-rose-200/60">
                                {{ number_format($purchase->credit_amount, 2) }} {{ $purchase->bank?->currency?->currency_symbol ?? '' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Transaction Details --}}
        <div class="relative overflow-hidden rounded-xl shadow-lg border border-gray-200/80 bg-gray-50">
            <div class="relative p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-200">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-md">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wide text-gray-800">Transaction</h2>
                    </div>
                </div>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Date</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $purchase->date_added->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Operation</dt>
                        <dd class="mt-1">
                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded {{ $purchase->transaction_operation === \App\Models\Purchase::TRANSACTION_MULTIPLY ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-800' }}">
                                {{ $purchase->transaction_operation_label }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Rate</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ number_format($purchase->rate, 4) }}</dd>
                    </div>
                    @if($purchase->details)
                    <div class="pt-2">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-1">Details</dt>
                        <dd class="mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg p-3 border border-gray-100">{{ $purchase->details }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Credit Party --}}
        <div class="relative overflow-hidden rounded-xl shadow-lg border border-emerald-100 bg-gradient-to-br from-emerald-50/90 via-white to-emerald-50/50">
            <div class="relative p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-emerald-200/60">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-md">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wide text-emerald-800">Credit ( جمع ) Party</h2>
                    </div>
                </div>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Party</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $purchase->party?->party_name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Party Currency</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">
                            {{ $purchase->partyCurrency?->currency ?? '—' }}
                            <span class="text-gray-500">({{ $purchase->partyCurrency?->currency_symbol ?? '—' }})</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Amount</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-lg font-bold bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200/60">
                                {{ number_format($purchase->debit_amount, 2) }} {{ $purchase->partyCurrency?->currency_symbol ?? '' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('purchases.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Purchase list
        </a>
        <a href="{{ route('purchases.edit', $purchase) }}" class="inline-flex items-center rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:from-amber-600 hover:to-orange-700 transition">Edit purchase</a>
    </div>
</x-app-layout>
