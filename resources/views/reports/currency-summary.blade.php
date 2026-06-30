<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Summary - {{ $business->business_name ?? 'ExchangeHub' }} - ExchangeHub</title>
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

        .filters label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 12px;
            color: #444;
        }

        .filters input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        .filters button {
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

        .filters button:hover {
            background-color: #0b5ed7;
        }

        .filters button.btn-print { background-color: #16a34a; }
        .filters button.btn-print:hover { background-color: #15803d; }

        .filters button.btn-secondary {
            background-color: #6c757d;
        }

        .filters button.btn-secondary:hover {
            background-color: #5c636a;
        }

        .filters a {
            text-decoration: none;
        }

        .table-container {
            margin-top: 20px;
            overflow-x: auto;
            border: 1px solid #333;
            width: 100%;
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

        .total-row td {
            font-weight: 600;
            border-top: 2px solid #333;
            background-color: #f8f9fa;
        }

        table input[type="number"],
        table select {
            width: 100%;
            min-width: 70px;
            padding: 4px 6px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 12px;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        table input[readonly] {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }

        .report-footer {
            margin-top: 20px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }

        .report-footer p {
            margin: 0;
        }

        .report-footer p + p {
            margin-top: 5px;
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

            td {
                border: 1px solid #000 !important;
            }

            .total-row td {
                background-color: #f8f8f8 !important;
                border-top: 2px solid #000 !important;
            }

            table input[type="number"],
            table select {
                border: none !important;
                background: transparent !important;
                padding: 0 !important;
                font-size: inherit;
                text-align: inherit;
                -webkit-appearance: none;
                appearance: none;
            }

            table input[readonly] {
                font-weight: inherit;
            }

            #total_summary {
                font-weight: 700 !important;
            }
        }

        @media screen and (max-width: 768px) {
            .page-container { padding: 12px 10px; }

            .report-header {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
            }

            .report-header-right { text-align: left; }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .form-group.date-group {
                flex: 1 1 100%;
                min-width: 100%;
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

            table { font-size: 10px; }
            th, td { padding: 5px 4px; word-break: break-word; }
        }
    </style>
    <script src="{{ asset('js/amount-format.js') }}"></script>
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
                                @if($business->address)
                                    <div>{{ $business->address }}</div>
                                @endif
                                @if($business->contact_no)
                                    <div>Contact: {{ $business->contact_no }}</div>
                                @endif
                                @if($business->email)
                                    <div>Email: {{ $business->email }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="report-header-right">
                        <div class="report-title">
                            <h2>Currency Summary</h2>
                            <div><strong>As On:</strong> {{ \Carbon\Carbon::parse($dateSearch)->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="filters no-print">
                <form action="{{ route('reports.currency-summary') }}" method="GET" class="filter-form">
                    <div class="form-group date-group">
                        <label for="date_search">As On Date</label>
                        <input type="date" name="date_search" id="date_search" value="{{ $dateSearch }}">
                    </div>
                    <div class="button-group">
                        <button type="submit">Search</button>
                        @if($rows->isNotEmpty())
                            <button type="button" class="btn-print" onclick="window.print()">Print</button>
                        @endif
                        <a href="{{ route('reports.index') }}">
                            <button type="button" class="btn-secondary">Back</button>
                        </a>
                    </div>
                </form>
            </div>

            @if($rows->isNotEmpty())
                <div class="table-container">
                    <table id="currency-summary-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Currency</th>
                                <th class="amount">Banks Amount</th>
                                <th class="amount">Party Amount</th>
                                <th class="amount">Total Amount</th>
                                <th class="amount">Rate</th>
                                <th>Operation</th>
                                <th class="amount">End Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $index => $row)
                                <tr data-row="{{ $index }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $row->currency }}</td>
                                    <td class="amount">{{ number_format($row->currency_balance, 2) }}</td>
                                    <td class="amount">{{ number_format($row->party_balance, 2) }}</td>
                                    <td class="amount">
                                        <input type="number" step="any" readonly id="total_amount_{{ $index }}" class="format-amount" value="{{ $row->total_amount }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" id="rate_{{ $index }}" value="1" min="0" onchange="calculateEndResult({{ $index }})" oninput="calculateEndResult({{ $index }})">
                                    </td>
                                    <td>
                                        <select id="operation_{{ $index }}" onchange="calculateEndResult({{ $index }})">
                                            <option value="1">Multiply</option>
                                            <option value="2">Divide</option>
                                        </select>
                                    </td>
                                    <td class="amount">
                                        <input type="number" readonly id="end_result_{{ $index }}" class="end-result format-amount">
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="7" style="text-align: right;"><strong>Total Summary</strong></td>
                                <td class="amount">
                                    <input type="number" readonly id="total_summary" class="format-amount" style="font-weight: 700; border: none; background: transparent;">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="report-footer">
                    <p>Generated by: {{ auth()->user()->name ?? 'User' }} | Print time: {{ now()->format('d M Y h:i A') }}</p>
                    <p>Powered By Grow Business 365</p>
                </div>

                <script>
                function readReportAmount(el) {
                    if (window.AmountFormat) {
                        return AmountFormat.read(el);
                    }

                    return parseFloat(String(el.value).replace(/,/g, '')) || 0;
                }

                function writeReportAmount(el, value) {
                    if (!el) {
                        return;
                    }

                    if (window.AmountFormat) {
                        AmountFormat.setValue(el, value);
                        return;
                    }

                    var num = typeof value === 'number' ? value : parseFloat(String(value).replace(/,/g, ''));
                    el.value = isNaN(num) ? '' : num.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    });
                }

                function calculateEndResult(row) {
                    var totalAmount = readReportAmount(document.getElementById('total_amount_' + row));
                    var rate = parseFloat(document.getElementById('rate_' + row).value) || 1;
                    var operation = document.getElementById('operation_' + row).value;
                    var endResult;

                    if (operation === '1') {
                        endResult = totalAmount * rate;
                    } else {
                        endResult = rate !== 0 ? totalAmount / rate : 0;
                    }

                    writeReportAmount(document.getElementById('end_result_' + row), Math.round(endResult * 100) / 100);

                    var totalSummary = 0;
                    document.querySelectorAll('.end-result').forEach(function(input) {
                        totalSummary += readReportAmount(input);
                    });
                    writeReportAmount(document.getElementById('total_summary'), Math.round(totalSummary * 100) / 100);
                }

                function initCurrencySummaryCalcs() {
                    var totalRecords = {{ $rows->count() }};
                    for (var i = 0; i < totalRecords; i++) {
                        calculateEndResult(i);
                    }
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initCurrencySummaryCalcs);
                } else {
                    initCurrencySummaryCalcs();
                }
                </script>
            @else
                <div style="padding: 30px; text-align: center; color: #666;">
                    <p>No currency data found for this date.</p>
                    <p style="margin-top: 15px;"><a href="{{ route('reports.index') }}" style="color: #0d6efd;">Back to Reports</a></p>
                </div>
            @endif
        </div>
    </div>

</body>
</html>
