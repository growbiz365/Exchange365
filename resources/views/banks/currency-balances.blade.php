<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Balances - {{ $business->business_name ?? 'ExchangeHub' }} - ExchangeHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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

        .report-container {
            width: 100%;
            position: relative;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            gap: 30px;
        }

        .report-header-left {
            flex: 1;
            text-align: left;
        }

        .report-header-right {
            flex: 1;
            text-align: right;
        }

        .business-info {
            text-align: left;
        }

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

        .report-title {
            text-align: right;
        }

        .report-title h2 {
            margin: 0 0 10px 0;
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .report-title div {
            font-size: 13px;
            color: #555;
            margin-top: 6px;
            line-height: 1.8;
        }

        .filters {
            margin: 15px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .filter-form {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            justify-content: center;
            flex-wrap: wrap;
            width: 100%;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group.date-group {
            flex: 0 0 180px;
            min-width: 180px;
        }

        .button-group {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
            flex-wrap: nowrap;
            align-items: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 12px;
            color: #444;
        }

        select, input {
            width: 100%;
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
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
            justify-content: center;
            white-space: nowrap;
            height: 36px;
            line-height: 1;
            font-family: 'Inter', sans-serif;
        }

        button:hover {
            background-color: #0b5ed7;
        }

        button.btn-print { background-color: #16a34a; }
        button.btn-print:hover { background-color: #15803d; }

        button.btn-secondary {
            background-color: #6c757d;
        }

        button.btn-secondary:hover {
            background-color: #5c636a;
        }

        a button {
            text-decoration: none;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .summary-card {
            border: 1px solid #333;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }

        .summary-card h4 {
            margin: 0 0 6px 0;
            font-size: 12px;
            font-weight: 500;
            color: #444;
        }

        .summary-value {
            font-size: 16px;
            font-weight: 600;
        }

        .currency-meta-count {
            margin: 15px 0 10px 0;
            text-align: right;
            font-size: 13px;
            color: #444;
            font-weight: 600;
            padding: 8px 12px;
            background-color: #f8f9fa;
            border-radius: 4px;
            display: inline-block;
            float: right;
        }

        .clear-float {
            clear: both;
        }

        .table-container {
            margin-top: 20px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border: 1px solid #333;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            background-color: #fff;
            font-weight: 600;
            text-transform: none;
            font-size: 13px;
            color: #000;
            border: 1px solid #333;
            padding: 8px;
        }

        td {
            border: 1px solid #333;
            padding: 8px;
            background-color: #fff;
        }

        .amount {
            text-align: right;
            font-family: 'Inter', monospace;
            font-weight: 500;
        }

        .credit-amount {
            color: #009900;
            font-weight: 600;
        }

        .debit-amount {
            color: #cc0000;
            font-weight: 600;
        }

        .total-row td {
            font-weight: 600;
            border-top: 2px solid #333;
            background-color: #f8f9fa;
        }

        .urdu {
            font-size: 11px;
            color: #666;
            display: block;
        }

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

            .report-container { position: static !important; }

            .filters, .no-print { display: none !important; }

            .report-header {
                display: flex !important;
                justify-content: space-between !important;
                gap: 30px !important;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .report-header-left { text-align: left !important; }
            .report-header-right { text-align: right !important; }
            .business-info { text-align: left !important; }
            .report-title { text-align: right !important; }

            .summary-grid {
                display: table !important;
                width: 100% !important;
                table-layout: fixed;
                border-collapse: separate;
                border-spacing: 8px 0;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .summary-card {
                display: table-cell !important;
                vertical-align: top;
                width: 33.33% !important;
                border: 1px solid #000 !important;
            }

            .table-container {
                overflow: visible !important;
                width: 100% !important;
                border: 1px solid #000 !important;
                page-break-inside: auto;
                break-inside: auto;
            }

            table {
                width: 100% !important;
                font-size: 10.5px;
                page-break-inside: auto;
            }

            thead { display: table-header-group; }

            tbody tr {
                page-break-inside: auto;
                break-inside: auto;
            }

            .total-row {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            th {
                background-color: #f8f8f8 !important;
                border: 1px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            td { border: 1px solid #000 !important; }

            .credit-amount { color: #009900 !important; }
            .debit-amount { color: #cc0000 !important; }
        }

        @media screen and (max-width: 768px) {
            .page-container { padding: 12px 10px; }

            .report-header {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
            }

            .report-header-right,
            .report-title {
                text-align: left;
            }

            .report-title h2 {
                font-size: 16px;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .filters {
                padding: 12px;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .form-group,
            .form-group.date-group {
                flex: 1 1 100%;
                min-width: 100%;
                max-width: 100%;
            }

            .button-group {
                width: 100%;
                flex-wrap: wrap;
            }

            .button-group button,
            .button-group a {
                flex: 1 1 auto;
                min-width: min(100%, 120px);
            }

            table {
                font-size: 11px;
            }

            th, td {
                padding: 6px 4px;
                word-break: break-word;
            }

            .currency-meta-count {
                float: none;
                display: block;
                width: 100%;
                text-align: center;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>

    <div class="page-container">
        <div class="report-container">
            @if($business)
                <div class="report-header">
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
                            <h2>Currency Balances</h2>
                            <div><strong>As of Date:</strong> {{ \Carbon\Carbon::parse($dateSearch)->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>

                @if($currencyBalances->count() > 0)
                <div class="summary-grid">
                    <div class="summary-card">
                        <h4>Total Currencies</h4>
                        <div class="summary-value">{{ $currencyBalances->count() }}</div>
                    </div>
                    <div class="summary-card">
                        <h4>Total Balance</h4>
                        <div class="summary-value">{{ number_format($currencyBalances->sum('currency_balance'), 2) }}</div>
                    </div>
                    <div class="summary-card">
                        <h4>Report Date</h4>
                        <div class="summary-value" style="font-size: 14px;">{{ \Carbon\Carbon::parse($dateSearch)->format('d M Y') }}</div>
                    </div>
                </div>
                @endif
            @endif

            <div class="filters no-print">
                <form action="{{ route('banks.currency-balances') }}" method="GET" class="filter-form">
                    <div class="form-group date-group">
                        <label for="date_search">As of Date</label>
                        <input type="date" name="date_search" id="date_search" value="{{ $dateSearch }}">
                    </div>
                    <div class="button-group">
                        <button type="submit">Apply</button>
                        @if($currencyBalances->count() > 0)
                            <button type="button" class="btn-print" onclick="window.print()">Print</button>
                        @endif
                        <a href="{{ route('banks.dashboard') }}">
                            <button type="button" class="btn-secondary">Back</button>
                        </a>
                    </div>
                </form>
            </div>

            @if($currencyBalances->count() > 0)
                <div class="currency-meta-count">
                    Total Currencies: <strong>{{ $currencyBalances->count() }}</strong>
                </div>
                <div class="clear-float"></div>

                <div class="table-container" style="margin-top: 10px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Sr. <span class="urdu">(نمبر)</span></th>
                                <th>Currency <span class="urdu">(کرنسی)</span></th>
                                <th>Symbol <span class="urdu">(نشان)</span></th>
                                <th class="amount">Balance <span class="urdu">(بقایا)</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currencyBalances as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $row->currency }}</td>
                                    <td style="text-align: center;">{{ $row->currency_symbol ?? '' }}</td>
                                    <td class="amount {{ $row->currency_balance >= 0 ? 'credit-amount' : 'debit-amount' }}">
                                        {{ number_format(abs($row->currency_balance), 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="3" style="text-align: right"><strong>Total</strong></td>
                                <td class="amount"><strong>{{ number_format($currencyBalances->sum('currency_balance'), 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 20px; text-align: right; font-size: 11px; color: #666;">
                    <p>Generated by: {{ auth()->user()->name }} | Print time: {{ now()->format('d M Y h:i A') }}</p>
                    <p style="margin-top: 5px;">Powered By Grow Business 365</p>
                </div>
            @else
                <div style="text-align: center; padding: 30px; color: #666;">
                    <p>No currency balances found for the selected date. Select a date and click Apply to view the report.</p>
                </div>
            @endif
        </div>
    </div>

</body>
</html>
