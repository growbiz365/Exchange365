<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Party Ledger - {{ $business->business_name ?? 'ExchangeHub' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <style>
        @page { margin: 10mm; }

        * { box-sizing: border-box; }

        html, body { width: 100%; }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            color: #1a1a1a;
            background: #fff;
        }

        .page-container {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 16px 20px;
            background: white;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 18px;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            gap: 30px;
        }

        .report-header-left { flex: 1; text-align: left; }
        .report-header-right { flex: 1; text-align: right; }

        .business-info h2 {
            margin: 4px 0 6px 0;
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .business-info-details {
            font-size: 11px;
            color: #555;
            line-height: 1.6;
        }

        .report-title h2 {
            margin: 0 0 8px 0;
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .report-title .meta {
            font-size: 12px;
            color: #555;
            line-height: 1.7;
        }

        .report-title .meta strong { color: #1a1a1a; }

        .meta-pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            background: #eef2ff;
            color: #3730a3;
            font-size: 11px;
            font-weight: 600;
        }

        /* Filters */
        .filters {
            margin: 15px 0;
            padding: 16px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }

        .filter-form .form-group {
            flex: 1;
            min-width: 140px;
        }

        .filter-form .form-group.checks {
            flex: 0 0 auto;
            min-width: auto;
        }

        .filter-form .form-group.checks-stack {
            flex: 0 0 auto;
            min-width: auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            gap: 6px;
            padding-bottom: 2px;
        }

        .filter-form .form-group.checks-stack .checkbox-group {
            padding-top: 0;
        }

        .filter-form label {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            font-weight: 500;
            color: #444;
        }

        .filter-form select,
        .filter-form input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            background: #fff;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #374151;
            cursor: pointer;
            padding-top: 22px;
        }

        .checkbox-group input { width: auto; margin: 0; }

        .button-group {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
            flex-wrap: nowrap;
            align-items: center;
        }

        button, .btn-link {
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-family: 'Inter', sans-serif;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
            height: 36px;
            line-height: 1;
        }

        .btn-primary { background: #0d6efd; color: white; }
        .btn-primary:hover { background: #0b5ed7; }

        .btn-print { background: #1f2937; color: white; }
        .btn-print:hover { background: #111827; }

        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5c636a; }

        /* Table */
        .table-container {
            overflow-x: auto;
            margin-top: 4px;
            border: 1px solid #333;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #333;
            padding: 7px 8px;
            vertical-align: top;
        }

        th {
            background: #f1f5f9;
            font-weight: 600;
            text-align: left;
            white-space: nowrap;
        }

        .urdu {
            font-size: 10px;
            color: #666;
            display: block;
            font-weight: 400;
        }

        .amount { text-align: right; white-space: nowrap; }

        .credit-val {
            color: #15803d;
            font-weight: 600;
        }

        .debit-val {
            color: #b91c1c;
            font-weight: 600;
        }

        .opening-balance-row td {
            background: #dcfce7 !important;
            font-weight: 600;
        }

        .opening-label {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 4px;
            background: #bbf7d0;
            color: #166534;
            font-size: 11px;
            font-weight: 600;
        }

        .total-row td {
            font-weight: 700;
            border-top: 2px solid #333;
            background: #f9fafb;
        }

        .record-count {
            margin-top: 10px;
            font-size: 12px;
            color: #6b7280;
        }

        .report-footer {
            margin-top: 16px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #d1d5db;
            padding-top: 8px;
        }

        .empty-state {
            text-align: center;
            color: #6b7280;
            padding: 40px 20px;
        }

        /* Chosen */
        .filter-form .chosen-container { width: 100% !important; }
        .filter-form .chosen-container-single .chosen-single {
            height: 36px;
            line-height: 34px;
            padding: 0 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            background: #fff;
            box-shadow: none;
        }
        .filter-form .chosen-container-active.chosen-with-drop .chosen-single {
            border-color: #0d6efd;
            border-radius: 4px 4px 0 0;
        }
        .filter-form .chosen-results li.highlighted { background: #0d6efd; }

        @media screen and (max-width: 768px) {
            .page-container { padding: 12px 10px; }
            .filter-form { flex-direction: column; align-items: stretch; }
            .filter-form .form-group { min-width: 100%; width: 100%; }
            .filter-form .form-group.checks-stack { width: 100%; padding-bottom: 0; }
            .button-group { width: 100%; flex-wrap: wrap; }
            .button-group button, .button-group .btn-link { flex: 1; justify-content: center; min-width: min(100%, 120px); }
            table { font-size: 10px; }
            th, td { padding: 5px 4px; }
        }

        /* Print */
        @media print {
            html, body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .page-container {
                padding: 0 !important;
                margin: 0 !important;
                max-width: none !important;
                width: 100% !important;
                background: white !important;
            }

            .no-print { display: none !important; }

            .report-header {
                display: flex !important;
                flex-direction: row !important;
                align-items: flex-start !important;
                justify-content: space-between !important;
                gap: 24px !important;
                margin-bottom: 14px;
                padding-bottom: 10px;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .report-header-left { flex: 1 !important; text-align: left !important; }
            .report-header-right { flex: 1 !important; text-align: right !important; }

            .business-info { text-align: left !important; }
            .report-title { text-align: right !important; }

            .business-info h2,
            .report-title h2 { font-size: 16px; }

            .business-info-details,
            .report-title .meta { font-size: 11px; }

            .meta-pill {
                background: #eef2ff !important;
                color: #3730a3 !important;
            }

            .table-container {
                overflow: visible !important;
                border: 1px solid #000 !important;
                width: 100% !important;
                page-break-inside: auto;
                break-inside: auto;
            }

            table {
                font-size: 10.5px;
                width: 100% !important;
                page-break-inside: auto;
            }

            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }

            tbody tr {
                page-break-inside: auto;
                break-inside: auto;
            }

            .opening-balance-row,
            .total-row {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            th {
                background: #f1f5f9 !important;
                border: 1px solid #000 !important;
                padding: 5px 6px;
            }

            td {
                border: 1px solid #000 !important;
                padding: 4px 6px;
            }

            .opening-balance-row td { background: #dcfce7 !important; }

            .opening-label {
                background: #bbf7d0 !important;
                color: #166534 !important;
            }

            .total-row td {
                background: #f3f4f6 !important;
                border-top: 2px solid #000 !important;
            }

            .credit-val { color: #15803d !important; }
            .debit-val { color: #b91c1c !important; }

            .report-footer {
                margin-top: 12px;
                font-size: 9px;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

@php
    $selectedCurrency = isset($currencyId) ? $currencies->firstWhere('currency_id', (int) $currencyId) : null;
@endphp

<div class="page-container">

    {{-- Filters --}}
    <div class="filters no-print">
        <form action="{{ route('parties.ledger') }}" method="GET" class="filter-form">
            <div class="form-group">
                <label for="party_id">Select Party</label>
                <select name="party_id" id="party_id" class="chosen-select">
                    <option value="">Select Party</option>
                    @foreach($parties as $p)
                        <option value="{{ $p->party_id }}" {{ request('party_id') == $p->party_id ? 'selected' : '' }}>{{ $p->party_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="currency_id">Currency</label>
                <select name="currency_id" id="currency_id">
                    @foreach($currencies as $c)
                        <option value="{{ $c->currency_id }}" {{ isset($currencyId) && (int) $currencyId === (int) $c->currency_id ? 'selected' : '' }}>
                            {{ $c->currency }} ({{ $c->currency_symbol }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="date_from">From Date</label>
                <input type="date" name="date_from" id="date_from" value="{{ $dateFrom }}">
            </div>
            <div class="form-group">
                <label for="date_to">To Date</label>
                <input type="date" name="date_to" id="date_to" value="{{ $dateTo }}">
            </div>
            <div class="form-group checks-stack">
                <label class="checkbox-group">
                    <input type="checkbox" id="check_party_col" value="1" checked>
                    Show Party Column
                </label>
                <label class="checkbox-group">
                    <input type="checkbox" id="check_rate_col" value="1" checked>
                    Show Rate Column
                </label>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-primary">Search</button>
                @if($partyDetails)
                    <button type="button" class="btn-print" onclick="window.print()">Print</button>
                @endif
                <a href="{{ route('parties.dashboard') }}" class="btn-secondary btn-link">Back</a>
            </div>
        </form>
    </div>

    @if($partyDetails)
        <div class="report-header">
            <div class="report-header-left">
                <div class="business-info">
                    <h2>{{ $business->business_name ?? 'ExchangeHub' }}</h2>
                    <div class="business-info-details">
                        @if($business?->address)
                            <div>{{ $business->address }}</div>
                        @endif
                        @if($business?->contact_no)
                            <div>Contact: {{ $business->contact_no }}</div>
                        @endif
                        @if($business?->email)
                            <div>Email: {{ $business->email }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="report-header-right">
                <div class="report-title">
                    <h2>Party Ledger</h2>
                    <div class="meta">
                        @if($business?->owner_name)
                            <div><strong>Owner:</strong> {{ $business->owner_name }}</div>
                        @endif
                        <div><strong>Party:</strong> <span class="meta-pill">{{ $partyDetails->party_name }}</span></div>
                        <div><strong>Currency:</strong> {{ $selectedCurrency->currency ?? '' }} ({{ $currencySymbol }})</div>
                        <div><strong>Period:</strong> {{ $dataDuration }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table id="ledger-table">
                <thead>
                    <tr>
                        <th>Date <span class="urdu">(تاریخ)</span></th>
                        <th>Voucher <span class="urdu">(واؤچر)</span></th>
                        <th>Description <span class="urdu">(تفصیلات)</span></th>
                        <th class="col-party">Party</th>
                        <th class="col-rate">Rate</th>
                        <th class="amount">Credit <span class="urdu">(جمع)</span></th>
                        <th class="amount">Debit <span class="urdu">(بنام)</span></th>
                        <th class="amount">Balance <span class="urdu">(بقیہ)</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="opening-balance-row">
                        <td>{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</td>
                        <td colspan="6" class="opening-head-colspan"><span class="opening-label">Opening Balance</span></td>
                        <td class="amount">{{ number_format($previousBalance, 2) }} {{ $currencySymbol }}</td>
                    </tr>

                    @php
                        $balance = $previousBalance;
                        $totalCredit = 0;
                        $totalDebit = 0;
                    @endphp

                    @forelse($ledgerEntries ?? [] as $row)
                        @php
                            $creditAmount = (float) $row->credit_amount;
                            $debitAmount = (float) $row->debit_amount;
                            $balance += ($creditAmount - $debitAmount);
                            $totalCredit += $creditAmount;
                            $totalDebit += $debitAmount;
                        @endphp
                        <tr>
                            <td>{{ $row->date_added->format('d M Y') }}</td>
                            <td>{{ $row->voucher_type }} #{{ $row->voucher_id }}</td>
                            <td>{{ $row->details ?: '—' }}</td>
                            <td class="col-party">{{ $row->transaction_party ?: '—' }}</td>
                            <td class="col-rate amount">{{ $row->rate != 0 ? number_format($row->rate, 4) : '—' }}</td>
                            <td class="amount">
                                @if($creditAmount != 0)
                                    <span class="credit-val">{{ number_format($creditAmount, 2) }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="amount">
                                @if($debitAmount != 0)
                                    <span class="debit-val">{{ number_format($debitAmount, 2) }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="amount">{{ number_format($balance, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">No transactions found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>

                @if($ledgerEntries && $ledgerEntries->count() > 0)
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="5" class="total-label-colspan" style="text-align: center"><strong>Total</strong></td>
                            <td class="amount"><strong class="credit-val">{{ number_format($totalCredit, 2) }}</strong></td>
                            <td class="amount"><strong class="debit-val">{{ number_format($totalDebit, 2) }}</strong></td>
                            <td class="amount">
                                <strong class="{{ $balance > 0 ? 'credit-val' : ($balance < 0 ? 'debit-val' : '') }}">
                                    {{ number_format($balance, 2) }} {{ $currencySymbol }}
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        @if($ledgerEntries && $ledgerEntries->count() > 0)
            <div class="record-count no-print">
                Total records: <strong>{{ $ledgerEntries->count() }}</strong>
            </div>
        @endif

        <div class="report-footer">
            <span>Generated by: {{ auth()->user()->name ?? 'User' }}</span>
            <span>Printed: {{ now()->format('d M Y, h:i A') }}</span>
        </div>
    @else
        <p class="empty-state">Select a party and currency, then click <strong>Search</strong> to view the ledger.</p>
    @endif

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script>
$(function() {
    $('.chosen-select').chosen({
        width: '100%',
        search_contains: true,
        allow_single_deselect: true,
        placeholder_text_single: 'Select Party'
    });
});

function updateColspans() {
    var showParty = document.getElementById('check_party_col')?.checked ?? true;
    var showRate  = document.getElementById('check_rate_col')?.checked ?? true;
    var hidden    = (showParty ? 0 : 1) + (showRate ? 0 : 1);

    var openingHead = document.querySelector('.opening-head-colspan');
    if (openingHead) {
        openingHead.colSpan = 6 - hidden;
    }

    var totalLabel = document.querySelector('.total-label-colspan');
    if (totalLabel) {
        totalLabel.colSpan = 5 - hidden;
    }
}

function toggleColumns() {
    var showParty = document.getElementById('check_party_col').checked;
    var showRate  = document.getElementById('check_rate_col').checked;

    document.querySelectorAll('.col-party').forEach(function(el) {
        el.style.display = showParty ? '' : 'none';
    });
    document.querySelectorAll('.col-rate').forEach(function(el) {
        el.style.display = showRate ? '' : 'none';
    });

    updateColspans();
}

document.addEventListener('DOMContentLoaded', function() {
    var partyCheck = document.getElementById('check_party_col');
    var rateCheck  = document.getElementById('check_rate_col');

    if (partyCheck) partyCheck.addEventListener('change', toggleColumns);
    if (rateCheck)  rateCheck.addEventListener('change', toggleColumns);

    toggleColumns();
});
</script>
</body>
</html>
