<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Party Currency Breakdown - {{ session('active_business') ? \App\Models\Business::find(session('active_business'))->business_name : 'ExchangeHub' }} - Party Management - ExchangeHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chosen CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
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

        /* Chosen Select Styling */
        .chosen-container {
            width: 100% !important;
        }

        .chosen-container-single .chosen-single {
            height: 36px;
            line-height: 34px;
            padding: 0 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            background: white;
        }

        .chosen-container-single .chosen-single:hover {
            border-color: #0d6efd;
        }

        .chosen-container-single .chosen-single div b {
            background-position: 0px 2px;
        }

        .chosen-container-active .chosen-single div b {
            background-position: -18px 2px;
        }

        .chosen-drop {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .chosen-results {
            font-size: 13px;
            font-family: 'Inter', sans-serif;
        }

        .chosen-results li {
            padding: 8px 12px;
        }

        .chosen-results li.highlighted {
            background-color: #0d6efd;
            color: white;
        }

        input[type="date"] {
            padding-right: 30px;
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

            .filter-form button,
            .filter-form a {
                width: 100%;
                box-sizing: border-box;
                min-height: 44px;
            }

            .filter-form a {
                display: block;
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
            @if($partyDetails)
                @php
                    $business = \App\Models\Business::find(session('active_business'));
                    $totalCurrencies = $currencyBalances->count();
                    $totalBalance = 0;
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
                            <h2>Party Currency Breakdown</h2>
                            <div><strong>Party Name:</strong> {{ $partyDetails->party_name }}</div>
                            <div><strong>Contact:</strong> {{ $partyDetails->contact_no ?? 'N/A' }}</div>
                            <div><strong>As of Date:</strong> {{ \Carbon\Carbon::parse($dateSearch)->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="summary-grid">
                    <div class="summary-card">
                        <h4>Total Currencies</h4>
                        <div class="summary-value">{{ $totalCurrencies }}</div>
                    </div>
                    <div class="summary-card">
                        <h4>Party Type</h4>
                        <div class="summary-value" style="font-size: 14px;">{{ $partyDetails->party_type == 1 ? 'Regular' : 'Expense' }}</div>
                    </div>
                    <div class="summary-card">
                        <h4>Status</h4>
                        <div class="summary-value" style="font-size: 14px; color: {{ $partyDetails->status == 1 ? '#009900' : '#cc0000' }}">
                            {{ $partyDetails->status == 1 ? 'Active' : 'Inactive' }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="filters">
                <form action="{{ route('parties.currency') }}" method="GET" class="filter-form">
                    <div class="form-group">
                        <label for="party_id">Select Party</label>
                        <select name="party_id" id="party_id" required class="chosen-select">
                            <option value="">Select a party</option>
                            @foreach($parties as $party)
                                <option value="{{ $party->party_id }}" {{ $partyId == $party->party_id ? 'selected' : '' }}>
                                    {{ $party->party_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group date-group">
                        <label for="date_search">As of Date</label>
                        <input type="date" name="date_search" id="date_search" value="{{ $dateSearch }}">
                    </div>

                    <button type="submit">Apply</button>
                    @if($partyDetails)
                        <button type="button" onclick="window.print()">Print</button>
                    @endif
                    <a href="{{ route('parties.dashboard') }}">
                        <button type="button" class="btn-secondary">Back</button>
                    </a>
                </form>
            </div>

            @if($partyDetails)
                <!-- Total Records Info -->
                <div class="currency-meta-count">
                    Total Currencies: <strong>{{ $currencyBalances->count() }}</strong>
                </div>
                <div class="clear-float"></div>

                <div class="table-container" style="margin-top: 10px;">
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    Sr.
                                    <span class="urdu">(نمبر)</span>
                                </th>
                                <th>
                                    Currency
                                    <span class="urdu">(کرنسی)</span>
                                </th>
                                <th>
                                    Symbol
                                    <span class="urdu">(نشان)</span>
                                </th>
                                <th>
                                    Balance
                                    <span class="urdu">(بقایا)</span>
                                </th>
                                <th>
                                    Type
                                    <span class="urdu">(قسم)</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($currencyBalances as $index => $balance)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $balance->currency->currency }}</td>
                                    <td style="text-align: center;">{{ $balance->currency->currency_symbol }}</td>
                                    <td class="amount {{ $balance->balance >= 0 ? 'credit-amount' : 'debit-amount' }}">
                                        {{ number_format(abs($balance->balance), 2) }}
                                    </td>
                                    <td style="text-align: center;">
                                        @if($balance->balance >= 0)
                                            <span style="color: #009900; font-weight: 600;">Credit (جمع)</span>
                                        @else
                                            <span style="color: #cc0000; font-weight: 600;">Debit (نام)</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 30px; color: #666;">
                                        No currency balances found for this party.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Report Footer -->
                <div style="margin-top: 20px; text-align: right; font-size: 11px; color: #666;">
                    <p>Generated by: {{ auth()->user()->name }} | Print time: {{ now()->format('d M Y h:i A') }}</p>
                    <p style="margin-top: 5px;">Powered By ExchangeHub</p>
                </div>
            @else
                <div style="text-align: center; padding: 30px; color: #666;">
                    <p>Please select a party to view currency breakdown.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chosen JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Chosen select
            $('.chosen-select').chosen({
                width: '100%',
                search_contains: true,
                allow_single_deselect: true,
                placeholder_text_single: 'Select a party'
            });
        });
    </script>
</body>
</html>
