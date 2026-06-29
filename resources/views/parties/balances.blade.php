<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Party Balances Report - {{ session('active_business') ? \App\Models\Business::find(session('active_business'))->business_name : 'ExchangeHub' }} - Party Management - ExchangeHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
        }

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

        .report-container {
            width: 100%;
            position: relative;
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
            margin: 0 0 10px 0;
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .report-title .meta {
            font-size: 12px;
            color: #555;
            margin-top: 4px;
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
            margin-left: 4px;
        }

        /* ===== Filters (screen only) ===== */
        .filters {
            margin: 15px 0;
            padding: 16px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .filter-form {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            justify-content: center;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 150px;
        }

        .button-group {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 12px;
            color: #444;
        }

        input[type="date"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
        }

        button {
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            color: white;
            background-color: #0d6efd;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        button:hover { background-color: #0b5ed7; }

        button.btn-print {
            background-color: #16a34a;
        }
        button.btn-print:hover { background-color: #15803d; }

        button.btn-secondary { background-color: #6c757d; }
        button.btn-secondary:hover { background-color: #5c636a; }

        /* ===== Tables ===== */
        .tables-container {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-top: 20px;
            width: 100%;
        }

        .tables-container.single-table {
            grid-template-columns: 1fr;
        }

        .table-container {
            border: 1px solid #333;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            background-color: #f8f8f8;
            font-weight: 600;
            text-transform: none;
            font-size: 13px;
            color: #000;
            border: 1px solid #333;
            padding: 8px;
        }

        td {
            border: 1px solid #333;
            padding: 7px 8px;
            background-color: #fff;
        }

        .table-title-row th {
            text-align: center;
            background-color: #eef2f7;
            font-size: 14px;
            letter-spacing: 0.2px;
        }

        .amount {
            text-align: right;
            font-family: 'Inter', monospace;
            font-weight: 500;
            white-space: nowrap;
        }

        .debit-amount { color: #cc0000; font-weight: 600; }
        .credit-amount { color: #009900; font-weight: 600; }

        .total-row td {
            font-weight: 600;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            background-color: #fafafa;
        }

        .empty-cell {
            text-align: center;
            color: #6b7280;
            font-style: italic;
        }

        /* ===== Grand Total ===== */
        .summary-grid {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .summary-card {
            border: 1px solid #333;
            padding: 12px 14px;
            background: #fff;
            text-align: center;
        }

        .summary-card .label {
            font-size: 11px;
            font-weight: 600;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .summary-card .value {
            font-size: 16px;
            font-weight: 700;
            margin-top: 6px;
        }

        .summary-card.debit { background: #fff5f5; }
        .summary-card.credit { background: #f0fdf4; }
        .summary-card.net { background: #f8fafc; }

        .urdu {
            font-size: 11px;
            color: #666;
            display: block;
        }

        /* ===== Signature & Footer ===== */
        .signature-row {
            display: none;
            margin-top: 28px;
            justify-content: space-between;
            gap: 40px;
        }

        .signature-row .sig {
            flex: 1;
            text-align: center;
            font-size: 11px;
            color: #1a1a1a;
        }

        .signature-row .sig .line {
            border-top: 1px solid #333;
            margin: 0 auto 6px;
            width: 70%;
            height: 1px;
        }

        .report-footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #d1d5db;
            padding-top: 8px;
        }

        /* ===== Print Styles ===== */
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
                max-width: 100% !important;
                width: 100% !important;
                box-shadow: none !important;
                background: white !important;
            }

            .report-container {
                position: static !important;
            }

            .filters,
            .no-print {
                display: none !important;
            }

            .report-header {
                display: flex !important;
                flex-direction: row !important;
                align-items: flex-start !important;
                justify-content: space-between !important;
                gap: 30px !important;
                margin-bottom: 14px;
                padding-bottom: 10px;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .report-header-left {
                flex: 1 !important;
                text-align: left !important;
            }
            .report-header-right {
                flex: 1 !important;
                text-align: right !important;
            }

            .business-info { text-align: left !important; }
            .report-title { text-align: right !important; }

            .business-info h2,
            .report-title h2 {
                font-size: 16px;
            }

            .business-info-details { font-size: 10.5px; }
            .report-title .meta { font-size: 11.5px; }

            .meta-pill {
                background: #eef2ff !important;
                color: #3730a3 !important;
            }

            .tables-container {
                display: block !important;
                width: 100% !important;
                margin-top: 10px !important;
            }

            .tables-container::after {
                content: "";
                display: table;
                clear: both;
            }

            .table-container {
                float: left;
                width: 49.5% !important;
                min-width: 0 !important;
                margin: 0 !important;
                page-break-inside: auto !important;
                break-inside: auto !important;
                overflow: visible !important;
                border: 1px solid #000 !important;
            }

            .table-container:last-child {
                float: right;
            }

            table {
                font-size: 11px;
                page-break-inside: auto;
                width: 100% !important;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            tbody tr {
                page-break-inside: auto;
                break-inside: auto;
            }

            .table-title-row,
            .total-row {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            th {
                background-color: #f1f5f9 !important;
                color: #000 !important;
                border: 1px solid #000 !important;
                padding: 6px 7px;
            }

            td {
                border: 1px solid #000 !important;
                padding: 5px 7px;
            }

            .table-title-row th {
                background-color: #e2e8f0 !important;
                font-size: 12px;
            }

            .total-row td {
                background-color: #f3f4f6 !important;
                border-top: 2px solid #000 !important;
                border-bottom: 2px solid #000 !important;
            }

            .debit-amount { color: #b91c1c !important; }
            .credit-amount { color: #15803d !important; }

            /* Summary cards in one row for print */
            .summary-grid {
                display: table !important;
                width: 100% !important;
                table-layout: fixed;
                border-collapse: separate;
                border-spacing: 8px 0;
                margin-top: 14px;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .summary-card {
                display: table-cell !important;
                vertical-align: top;
                width: 33.33% !important;
                margin: 0 !important;
                border: 1px solid #000 !important;
                padding: 8px 10px;
                font-size: 11px;
            }
            .summary-card .value { font-size: 14px; }
            .summary-card.debit { background: #fef2f2 !important; }
            .summary-card.credit { background: #f0fdf4 !important; }
            .summary-card.net { background: #f1f5f9 !important; }

            .signature-row {
                display: flex !important;
                page-break-inside: avoid;
            }

            .report-footer {
                margin-top: 14px;
                font-size: 9.5px;
                color: #333;
                border-top: 1px solid #999;
            }

            a, a:link, a:visited {
                color: #000 !important;
                text-decoration: none !important;
            }
        }

        /* ===== Mobile (screen-only so it doesn't hijack print layout) ===== */
        @media screen and (max-width: 768px) {
            .page-container {
                padding: 12px 10px;
            }

            .report-header {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
            }

            .report-header-right,
            .report-title { text-align: left; }

            .report-title h2 { font-size: 16px; }

            .tables-container { grid-template-columns: 1fr; }

            .summary-grid { grid-template-columns: 1fr; }

            .filter-form { flex-direction: column; align-items: stretch; gap: 12px; }

            .form-group { min-width: auto; flex: 1 1 100%; }

            .button-group { justify-content: stretch; flex-wrap: wrap; width: 100%; }

            .button-group button,
            .button-group a {
                flex: 1 1 auto;
                min-width: min(100%, 140px);
            }

            table { font-size: 11px; }

            th, td { padding: 6px 4px; word-break: break-word; }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="report-container">
            @php
                $partyTypeLabels = [
                    'all' => 'All Parties',
                    '1'   => 'Khata Parties',
                    '2'   => 'Other Parties',
                ];
                $partyTypeLabel = $partyTypeLabels[$partyType] ?? 'All Parties';
            @endphp

            <div class="filters no-print">
                <form action="{{ route('parties.balances') }}" method="GET" class="filter-form">
                    <div class="form-group">
                        <label for="currency_id">Select Currency</label>
                        <select name="currency_id" id="currency_id" required>
                            <option value="">Select a currency</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->currency_id }}" {{ $currencyId == $currency->currency_id ? 'selected' : '' }}>
                                    {{ $currency->currency }} ({{ $currency->currency_symbol }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="party_type">Party Type</label>
                        <select name="party_type" id="party_type">
                            <option value="all" {{ $partyType === 'all' ? 'selected' : '' }}>All Parties</option>
                            <option value="1" {{ $partyType === '1' ? 'selected' : '' }}>Khata Party</option>
                            <option value="2" {{ $partyType === '2' ? 'selected' : '' }}>Other Party</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date_search">As of Date</label>
                        <input type="date" name="date_search" id="date_search" value="{{ $dateSearch }}">
                    </div>

                    <div class="button-group">
                        <button type="submit" title="Search">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            Search
                        </button>
                        @if($partyBalances !== null)
                            <button type="button" class="btn-print" onclick="window.print()" title="Print Report">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                Print
                            </button>
                        @endif
                        <a href="{{ route('parties.dashboard') }}">
                            <button type="button" class="btn-secondary" title="Back to Dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                                Back
                            </button>
                        </a>
                    </div>
                </form>
            </div>

            @if($partyBalances !== null)
                @php
                    $business = \App\Models\Business::find(session('active_business'));
                    $selectedCurrency = $currencies->firstWhere('currency_id', $currencyId);
                    $debitParties = collect();
                    $creditParties = collect();
                    $debitTotal = 0;
                    $creditTotal = 0;

                    foreach($partyBalances as $party) {
                        if($party['balance'] < 0) {
                            $debitParties->push($party);
                            $debitTotal += abs($party['balance']);
                        } else {
                            $creditParties->push($party);
                            $creditTotal += $party['balance'];
                        }
                    }

                    $currencySymbol = $selectedCurrency->currency_symbol ?? '';
                    $netBalance = $creditTotal - $debitTotal;
                @endphp
                <div class="report-header print-header">
                    <div class="report-header-left">
                        <div class="business-info">
                            <h2>{{ $business->business_name ?? 'ExchangeHub' }}</h2>
                            <div class="business-info-details">
                                @if($business)
                                    <div>{{ $business->address }}</div>
                                    <div>Contact: {{ $business->contact_no }}</div>
                                    <div>Email: {{ $business->email }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="report-header-right">
                        <div class="report-title">
                            <h2>Party Balances Report</h2>
                            <div class="meta">
                                <div><strong>Currency:</strong> {{ $selectedCurrency->currency ?? 'N/A' }} ({{ $selectedCurrency->currency_symbol ?? '' }})</div>
                                <div><strong>Party Type:</strong> <span class="meta-pill">{{ $partyTypeLabel }}</span></div>
                                <div><strong>As of Date:</strong> {{ \Carbon\Carbon::parse($dateSearch)->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tables-container">
                    <!-- Debit Parties Table -->
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr class="table-title-row">
                                    <th colspan="3">
                                        Debit Parties
                                        <span class="urdu">(بنام)</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="width: 12%;">Sr.</th>
                                    <th>Party Name</th>
                                    <th class="amount" style="width: 32%;">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($debitParties as $index => $party)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $party['party_name'] }}</td>
                                        <td class="amount debit-amount">{{ number_format(abs($party['balance']), 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="empty-cell">No debit parties found</td>
                                    </tr>
                                @endforelse
                                <tr class="total-row">
                                    <td colspan="2" style="text-align: right"><strong>Total Debit</strong></td>
                                    <td class="amount debit-amount">{{ number_format($debitTotal, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Credit Parties Table -->
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr class="table-title-row">
                                    <th colspan="3">
                                        Credit Parties
                                        <span class="urdu">(جمع)</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="width: 12%;">Sr.</th>
                                    <th>Party Name</th>
                                    <th class="amount" style="width: 32%;">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($creditParties as $index => $party)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $party['party_name'] }}</td>
                                        <td class="amount credit-amount">{{ number_format($party['balance'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="empty-cell">No credit parties found</td>
                                    </tr>
                                @endforelse
                                <tr class="total-row">
                                    <td colspan="2" style="text-align: right"><strong>Total Credit</strong></td>
                                    <td class="amount credit-amount">{{ number_format($creditTotal, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="summary-grid">
                    <div class="summary-card debit">
                        <div class="label">Total Debit ({{ $debitParties->count() }})</div>
                        <div class="value debit-amount">{{ $currencySymbol }} {{ number_format($debitTotal, 2) }}</div>
                    </div>
                    <div class="summary-card credit">
                        <div class="label">Total Credit ({{ $creditParties->count() }})</div>
                        <div class="value credit-amount">{{ $currencySymbol }} {{ number_format($creditTotal, 2) }}</div>
                    </div>
                    <div class="summary-card net">
                        <div class="label">Net Balance (Credit − Debit)</div>
                        <div class="value" style="color: {{ $netBalance >= 0 ? '#009900' : '#cc0000' }}">
                            {{ $currencySymbol }} {{ number_format($netBalance, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Signature (print only) -->
                <div class="signature-row">
                    <div class="sig">
                        <div class="line"></div>
                        Prepared By
                    </div>
                    <div class="sig">
                        <div class="line"></div>
                        Checked By
                    </div>
                    <div class="sig">
                        <div class="line"></div>
                        Authorised Signature
                    </div>
                </div>

                <!-- Report Footer -->
                <div class="report-footer">
                    <div>Generated by: <strong>{{ auth()->user()->name }}</strong> &middot; {{ now()->format('d M Y h:i A') }}</div>
                    <div>Powered by Grow Business 365</div>
                </div>
            @else
                <div style="text-align: center; padding: 30px; color: #666;">
                    <p>Please select a currency to view the party balances report.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
