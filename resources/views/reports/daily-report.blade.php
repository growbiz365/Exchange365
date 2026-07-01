<x-app-layout>
    @section('title', 'Daily Report - ExchangeHub')
    <x-breadcrumb :breadcrumbs="[
        ['url' => route('dashboard'), 'label' => 'Home'],
        ['url' => route('reports.index'), 'label' => 'Reports'],
        ['url' => '#', 'label' => 'Daily Report'],
    ]" />

    {{-- Header & Filter --}}
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-4 sm:px-6 py-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="bg-gradient-to-br from-indigo-600 to-slate-700 p-1.5 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-900 leading-tight">Daily Report</h4>
                    <p class="text-xs text-gray-500 mt-0.5">All transactions for {{ \Carbon\Carbon::parse($dateSearch)->format('d M Y') }}</p>
                </div>
            </div>
            <form method="GET" action="{{ route('reports.daily-report') }}" class="flex items-center gap-2">
                <input type="date" name="date_search" value="{{ $dateSearch }}"
                    class="block rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 h-9 px-2" />
                <button type="submit"
                    class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded hover:bg-indigo-700">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    Search
                </button>
                <button type="button" onclick="window.print()"
                    class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
            </form>
        </div>

        {{-- Summary Chips --}}
        <div class="px-4 sm:px-6 py-3 border-b border-gray-100 flex flex-wrap gap-2">
            @php
                $sections = [
                    ['label' => 'Party Transfers', 'count' => $partyTransfers->count(), 'color' => 'indigo'],
                    ['label' => 'Bank Transfers',  'count' => $bankTransfers->count(),  'color' => 'sky'],
                    ['label' => 'General Vouchers','count' => $generalVouchers->count(),'color' => 'violet'],
                    ['label' => 'Purchases',       'count' => $purchases->count(),       'color' => 'emerald'],
                    ['label' => 'Sales',           'count' => $sales->count(),           'color' => 'rose'],
                    ['label' => 'Asset Purchases', 'count' => $purchaseAssets->count(), 'color' => 'amber'],
                    ['label' => 'Asset Sales',     'count' => $saleAssets->count(),     'color' => 'orange'],
                ];
                $total = collect($sections)->sum('count');
            @endphp
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-900 text-white">
                Total: {{ $total }}
            </span>
            @foreach($sections as $s)
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $s['color'] }}-50 text-{{ $s['color'] }}-700 border border-{{ $s['color'] }}-100">
                    {{ $s['label'] }}: <strong class="ml-1">{{ $s['count'] }}</strong>
                </span>
            @endforeach
        </div>

        <div class="px-4 sm:px-6 py-4 space-y-6">

            {{-- ── 1. PARTY TRANSFERS ── --}}
            <div>
                <h5 class="text-sm font-bold text-gray-900 mb-2">Party Transfer</h5>
                @if($partyTransfers->count())
                <div class="overflow-x-auto rounded border border-gray-200">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Date</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Debit (بنام) Party</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Debit (بنام) Amount</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Credit (جمع) Party</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Credit (جمع) Amount</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Link</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($partyTransfers as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->date_added->format('d-m-Y') }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->debitParty?->party_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">
                                    {{ number_format($row->debit_amount, 0) }} {{ $row->debitCurrency?->currency }}
                                </td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->creditParty?->party_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">
                                    {{ number_format($row->credit_amount, 0) }} {{ $row->creditCurrency?->currency }}
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('party-transfers.edit', $row->party_transfer_id) }}" class="text-blue-600 font-bold hover:underline">Open Record</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="6" class="px-3 py-2 font-bold text-gray-700">
                                    Total Record Found : <strong>{{ $partyTransfers->count() }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                    <p class="text-xs text-gray-400 italic">No party transfers on this date.</p>
                @endif
            </div>

            {{-- ── 2. BANK TRANSFERS ── --}}
            <div>
                <h5 class="text-sm font-bold text-gray-900 mb-2">Bank Transfer</h5>
                @if($bankTransfers->count())
                <div class="overflow-x-auto rounded border border-gray-200">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Date</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">From Account</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">To Amount</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Transfer Amount</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Link</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($bankTransfers as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->date_added->format('d-m-Y') }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->fromBank?->bank_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->toBank?->bank_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ number_format($row->amount, 0) }}</td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('bank-transfers.edit', $row->bank_transfer_id) }}" class="text-blue-600 font-bold hover:underline">Open Record</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="5" class="px-3 py-2 font-bold text-gray-700">
                                    Total Record Found : <strong>{{ $bankTransfers->count() }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                    <p class="text-xs text-gray-400 italic">No bank transfers on this date.</p>
                @endif
            </div>

            {{-- ── 3. GENERAL VOUCHERS ── --}}
            <div>
                <h5 class="text-sm font-bold text-gray-900 mb-2">General Voucher</h5>
                @if($generalVouchers->count())
                <div class="overflow-x-auto rounded border border-gray-200">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Date</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Type</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Bank</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Party</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Amount</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Link</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($generalVouchers as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->date_added->format('d-m-Y') }}</td>
                                <td class="px-3 py-2 font-bold">
                                    @if($row->entry_type == 1)
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700">Credit</span>
                                    @else
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">Debit</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->bank?->bank_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->party?->party_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">
                                    {{ number_format($row->amount, 0) }} {{ $row->bank?->currency?->currency }}
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('general-vouchers.edit', $row->general_voucher_id) }}" class="text-blue-600 font-bold hover:underline">Open Record</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="6" class="px-3 py-2 font-bold text-gray-700">
                                    Total Record Found : <strong>{{ $generalVouchers->count() }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                    <p class="text-xs text-gray-400 italic">No general vouchers on this date.</p>
                @endif
            </div>

            {{-- ── 4. PURCHASES ── --}}
            <div>
                <h5 class="text-sm font-bold text-gray-900 mb-2">Purchase</h5>
                @if($purchases->count())
                <div class="overflow-x-auto rounded border border-gray-200">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Date</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Bank (Deposit)</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Deposit Amount</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Rate</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Party</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Party Amount</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Link</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($purchases as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->date_added->format('d-m-Y') }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->bank?->bank_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">
                                    {{ number_format($row->credit_amount, 0) }} {{ $row->bank?->currency?->currency }}
                                </td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->rate }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->party?->party_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">
                                    {{ number_format($row->debit_amount, 0) }} {{ $row->partyCurrency?->currency }}
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('purchases.edit', $row->purchase_id) }}" class="text-blue-600 font-bold hover:underline">Open Record</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="7" class="px-3 py-2 font-bold text-gray-700">
                                    Total Record Found : <strong>{{ $purchases->count() }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                    <p class="text-xs text-gray-400 italic">No purchases on this date.</p>
                @endif
            </div>

            {{-- ── 5. SALES ── --}}
            <div>
                <h5 class="text-sm font-bold text-gray-900 mb-2">Sale</h5>
                @if($sales->count())
                <div class="overflow-x-auto rounded border border-gray-200">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Date</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Bank (Withdrawal)</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Currency Amount</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Rate</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Party</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Party Amount</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Link</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($sales as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->date_added->format('d-m-Y') }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->bank?->bank_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">
                                    {{ number_format($row->currency_amount, 0) }} {{ $row->bank?->currency?->currency }}
                                </td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->rate }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->party?->party_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">
                                    {{ number_format($row->party_amount, 0) }} {{ $row->partyCurrency?->currency }}
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('sales.edit', $row->sales_id) }}" class="text-blue-600 font-bold hover:underline">Open Record</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="7" class="px-3 py-2 font-bold text-gray-700">
                                    Total Record Found : <strong>{{ $sales->count() }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                    <p class="text-xs text-gray-400 italic">No sales on this date.</p>
                @endif
            </div>

            {{-- ── 6. ASSET PURCHASES ── --}}
            <div>
                <h5 class="text-sm font-bold text-gray-900 mb-2">Asset Purchase</h5>
                @if($purchaseAssets->count())
                <div class="overflow-x-auto rounded border border-gray-200">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Date</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Asset Name</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Category</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Bank</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Party</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Cost</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Link</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($purchaseAssets as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->date_added->format('d-m-Y') }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->asset_name }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->category?->asset_category ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->purchaseBank?->bank_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->purchaseParty?->party_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ number_format($row->cost_amount, 0) }}</td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('assets.edit', $row->asset_id) }}" class="text-blue-600 font-bold hover:underline">Open Record</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="7" class="px-3 py-2 font-bold text-gray-700">
                                    Total Record Found : <strong>{{ $purchaseAssets->count() }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                    <p class="text-xs text-gray-400 italic">No asset purchases on this date.</p>
                @endif
            </div>

            {{-- ── 7. ASSET SALES ── --}}
            <div>
                <h5 class="text-sm font-bold text-gray-900 mb-2">Asset Sale</h5>
                @if($saleAssets->count())
                <div class="overflow-x-auto rounded border border-gray-200">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Sale Date</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Asset Name</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Category</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Bank</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Party</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Sale Amount</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-800">Link</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($saleAssets as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->sale_date->format('d-m-Y') }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->asset_name }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->category?->asset_category ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->saleBank?->bank_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ $row->saleParty?->party_name ?? '-' }}</td>
                                <td class="px-3 py-2 font-bold text-gray-800">{{ number_format($row->sale_amount, 0) }}</td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('assets.edit', $row->asset_id) }}" class="text-blue-600 font-bold hover:underline">Open Record</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="7" class="px-3 py-2 font-bold text-gray-700">
                                    Total Record Found : <strong>{{ $saleAssets->count() }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                    <p class="text-xs text-gray-400 italic">No asset sales on this date.</p>
                @endif
            </div>

        </div>{{-- end px body --}}
    </div>

    <style>
        @media print {
            nav, aside, header, .breadcrumb, form, button { display: none !important; }
            .bg-white { box-shadow: none !important; border: none !important; }
            body { font-size: 11px; }
        }
    </style>
</x-app-layout>
