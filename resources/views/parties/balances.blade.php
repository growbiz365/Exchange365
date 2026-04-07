<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Party Balances Report - {{ session('active_business') ? \App\Models\Business::find(session('active_business'))->business_name : 'ExchangeHub' }} - Party Management - ExchangeHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            color: #1a1a1a;
            background: #f8fafc;
        }

        .page-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 15mm;
            box-sizing: border-box;
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
            gap: 10px;
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
        }

        button:hover {
            background-color: #0b5ed7;
        }

        button.btn-secondary {
            background-color: #6c757d;
        }

        button.btn-secondary:hover {
            background-color: #5c636a;
        }

        .tables-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
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

        .debit-amount {
            color: #cc0000;
            font-weight: 600;
        }

        .credit-amount {
            color: #009900;
            font-weight: 600;
        }

        .total-row td {
            font-weight: 600;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
        }

        .grand-total {
            margin-top: 20px;
            border: 1px solid #333;
            padding: 15px;
            text-align: right;
            background-color: #f8f9fa;
        }

        .grand-total-label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .grand-total-amount {
            font-size: 18px;
            font-weight: 700;
        }

        .urdu {
            font-size: 11px;
            color: #666;
            display: block;
        }

        @media print {
            body {
                background: white;
            }
            .page-container {
                padding: 0;
            }
            .filters {
                display: none;
            }
            .report-header {
                display: flex !important;
                justify-content: space-between !important;
                gap: 30px !important;
            }
            .report-header-left {
                text-align: left !important;
            }
            .report-header-right {
                text-align: right !important;
            }
            .business-info {
                text-align: left !important;
            }
            .report-title {
                text-align: right !important;
            }
            th {
                background-color: #f8f8f8 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .total-row td {
                background-color: #f8f8f8 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 12px 10px;
                max-width: 100%;
            }

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

            .tables-container {
                grid-template-columns: 1fr;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .form-group {
                min-width: auto;
                flex: 1 1 100%;
            }

            .button-group {
                justify-content: stretch;
                flex-wrap: wrap;
                width: 100%;
            }

            .button-group button,
            .button-group a {
                flex: 1 1 auto;
                min-width: min(100%, 140px);
            }

            table {
                font-size: 11px;
            }

            th, td {
                padding: 6px 4px;
                word-break: break-word;
            }

            .grand-total {
                padding: 12px;
                text-align: center;
            }

            .grand-total-amount {
                font-size: 16px;
                word-break: break-word;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="report-container">
            @if($partyBalances !== null)
                @php
                    $business = \App\Models\Business::find(session('active_business'));
                    $selectedCurrency = $currencies->firstWhere('currency_id', $currencyId);
                @endphp
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
                            <h2>Party Balances Report</h2>
                            <div><strong>Currency:</strong> {{ $selectedCurrency->currency ?? 'N/A' }} ({{ $selectedCurrency->currency_symbol ?? '' }})</div>
                            <div><strong>As of Date:</strong> {{ \Carbon\Carbon::parse($dateSearch)->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="filters">
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
                        <label for="date_search">As of Date</label>
                        <input type="date" name="date_search" id="date_search" value="{{ $dateSearch }}">
                    </div>

                    <div class="button-group">
                        <button type="submit">Search</button>
                        @if($partyBalances !== null)
                            <button type="button" onclick="window.print()">Print</button>
                        @endif
                        <a href="{{ route('parties.dashboard') }}">
                            <button type="button" class="btn-secondary">Back</button>
                        </a>
                    </div>
                </form>
            </div>

            @if($partyBalances !== null)
                @php
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
                @endphp

                <div class="tables-container">
                    <!-- Debit Parties Table -->
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th colspan="3" style="text-align: center; background-color: #f8f9fa;">
                                        Debit Parties
                                        <span class="urdu">(بنام)</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Party Name</th>
                                    <th class="amount">Balance</th>
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
                                        <td colspan="3" style="text-align: center;">No debit parties found</td>
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
                                <tr>
                                    <th colspan="3" style="text-align: center; background-color: #f8f9fa;">
                                        Credit Parties
                                        <span class="urdu">(جمع)</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Party Name</th>
                                    <th class="amount">Balance</th>
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
                                        <td colspan="3" style="text-align: center;">No credit parties found</td>
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

                <!-- Grand Total -->
                <div class="grand-total">
                    <div class="grand-total-label">Net Balance (Credit - Debit)</div>
                    <div class="grand-total-amount" style="color: {{ ($creditTotal - $debitTotal) >= 0 ? '#009900' : '#cc0000' }}">
                        {{ number_format($creditTotal - $debitTotal, 2) }}
                    </div>
                </div>

                <!-- Report Footer -->
                <div style="margin-top: 20px; text-align: right; font-size: 11px; color: #666;">
                    <p>Generated by: {{ auth()->user()->name }} | Print time: {{ now()->format('d M Y h:i A') }}</p>
                    <p style="margin-top: 5px;">Powered By ExchangeHub</p>
                </div>
            @else
                <div style="text-align: center; padding: 30px; color: #666;">
                    <p>Please select a currency to view party balances report.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
