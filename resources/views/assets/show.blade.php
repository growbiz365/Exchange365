<x-app-layout>
    @section('title', 'Asset #' . $asset->asset_id . ' - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('assets.index'), 'label' => 'Assets'],
        ['url' => '#', 'label' => 'Asset #' . $asset->asset_id],
    ]" />

    <div class="relative bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6 mt-4 overflow-hidden group">
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-gradient-to-br from-slate-400/10 to-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex items-start gap-3 sm:space-x-4 min-w-0">
                <div class="flex-shrink-0">
                    <div class="bg-gradient-to-br from-slate-600 to-emerald-600 p-2.5 sm:p-3 rounded-xl shadow-lg transform group-hover:scale-105 transition-transform duration-300">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M9 7V5a3 3 0 016 0v2" />
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight break-words">Asset #{{ $asset->asset_id }} — {{ $asset->asset_name }}</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                        Purchased on @businessDate($asset->date_added)
                        @if($asset->user)
                            <span class="text-gray-400"> · Recorded by {{ $asset->user->name }}</span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 w-full xl:max-w-4xl xl:shrink-0">
                <a href="{{ route('assets.print', $asset) }}" target="_blank"
                    class="inline-flex justify-center items-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition w-full sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print
                </a>
                @if($asset->asset_status === \App\Models\Asset::STATUS_ACTIVE)
                    <a href="{{ route('assets.sell.form', $asset) }}"
                        class="inline-flex justify-center items-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-emerald-600 hover:to-teal-700 transition w-full sm:w-auto">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        Sell Asset
                    </a>
                @endif
                <a href="{{ route('assets.edit', $asset) }}"
                    class="inline-flex justify-center items-center rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-slate-700 hover:to-slate-900 transition w-full sm:w-auto">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="w-full sm:w-auto"
                    onsubmit="return confirm('Are you sure you want to delete this asset? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center items-center rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm font-semibold text-rose-700 shadow-sm hover:bg-rose-50 transition">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete
                    </button>
                </form>
                <a href="{{ route('assets.index') }}"
                    class="inline-flex justify-center items-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition w-full sm:w-auto sm:col-span-2 lg:col-span-1">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to list
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
        {{-- Purchase Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-50 border border-slate-100">
                        <svg class="h-5 w-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M9 7V5a3 3 0 016 0v2" /></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wide text-gray-900">Purchase</h2>
                    </div>
                </div>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Category</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900">{{ $asset->category?->asset_category ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Date</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">@businessDate($asset->date_added)</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Cost Amount (PKR)</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-lg font-bold bg-slate-50 text-slate-700 ring-1 ring-slate-200">
                                @currency($asset->cost_amount)
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Purchase Source</dt>
                        <dd class="mt-1 text-sm text-gray-800">
                            @if($asset->purchase_transaction_type === 2)
                                Bank — {{ $asset->purchaseBank?->bank_name ?? '—' }}
                            @elseif($asset->purchase_transaction_type === 3)
                                Party — {{ $asset->purchaseParty?->party_name ?? '—' }}
                            @else
                                Self / Company Funds
                            @endif
                        </dd>
                    </div>
                    @if($asset->purchase_details)
                        <div class="pt-2">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-1">Purchase Details</dt>
                            <dd class="mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $asset->purchase_details }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Sale Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 border border-emerald-100">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wide text-gray-900">Sale</h2>
                    </div>
                </div>
                <dl class="space-y-4">
                    @if($asset->asset_status === \App\Models\Asset::STATUS_SOLD)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full border bg-emerald-50 text-emerald-700 border-emerald-100">
                                    Sold Out
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Sale Date</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">@businessDate($asset->sale_date)</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Sale Amount (PKR)</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-lg font-bold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                    @currency($asset->sale_amount)
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Sale Target</dt>
                            <dd class="mt-1 text-sm text-gray-800">
                                @if($asset->sale_transaction_type === 2)
                                    Bank — {{ $asset->saleBank?->bank_name ?? '—' }}
                                @elseif($asset->sale_transaction_type === 3)
                                    Party — {{ $asset->saleParty?->party_name ?? '—' }}
                                @else
                                    —
                                @endif
                            </dd>
                        </div>
                        @if($asset->sale_details)
                            <div class="pt-2">
                                <dt class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-1">Sale Details</dt>
                                <dd class="mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $asset->sale_details }}</dd>
                            </div>
                        @endif
                    @else
                        <div class="text-sm text-gray-600">
                            This asset has not been sold yet. Use the “Sell Asset” button above to record a sale.
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Summary --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 border border-indigo-100">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10m4 0h10"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wide text-gray-900">Summary</h2>
                    </div>
                </div>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Current Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full border {{ $asset->asset_status === \App\Models\Asset::STATUS_SOLD ? 'bg-rose-50 text-rose-700 border-rose-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100' }}">
                                {{ $asset->asset_status === \App\Models\Asset::STATUS_SOLD ? 'Sold Out' : 'Active' }}
                            </span>
                        </dd>
                    </div>
                    @if($asset->asset_status === \App\Models\Asset::STATUS_SOLD && $asset->sale_amount)
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Gain / Loss (PKR)</dt>
                            @php $gain = (float)$asset->sale_amount - (float)$asset->cost_amount; @endphp
                            <dd class="mt-1 text-sm font-semibold {{ $gain >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $gain >= 0 ? '+' : '' }}{{ number_format($gain, 2) }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>

