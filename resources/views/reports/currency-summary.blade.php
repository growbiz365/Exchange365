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

        html, body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
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
            max-width: 100%;
            margin: 0;
            padding: 16px 20px;
            background: white;
            overflow-x: hidden;
        }

        .report-container {
            width: 100%;
            max-width: 100%;
            position: relative;
            overflow-x: hidden;
        }

        .table-scroll-wrap {
            width: 100%;
            max-width: 100%;
            margin-top: 20px;
            position: relative;
        }

        .table-scroll-hint {
            display: none;
            margin: 0 0 8px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 500;
            color: #854d0e;
            background: #fef9c3;
            border: 1px solid #fde047;
            border-radius: 6px;
            text-align: center;
        }

        .table-scroll-wrap.is-scrollable .table-scroll-hint {
            display: block;
        }

        .table-scroll-wrap::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 28px;
            background: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(0, 0, 0, 0.07));
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 2;
        }

        .table-scroll-wrap.is-scrollable:not(.scrolled-end)::after {
            opacity: 1;
        }

        .table-container {
            overflow-x: auto;
            overflow-y: visible;
            border: 1px solid #333;
            width: 100%;
            max-width: 100%;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-x: contain;
            touch-action: pan-x pan-y;
            scrollbar-width: thin;
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

        table {
            width: 100%;
            min-width: 1120px;
            border-collapse: collapse;
            font-size: 12px;
            table-layout: fixed;
        }

        col.col-no { width: 5%; }
        col.col-currency { width: 15%; }
        col.col-bank { width: 14%; }
        col.col-party { width: 14%; }
        col.col-total { width: 12%; }
        col.col-rate { width: 8%; }
        col.col-operation { width: 12%; }
        col.col-end { width: 20%; }

        th {
            background-color: #fff;
            font-weight: 600;
            font-size: 12px;
            color: #000;
            border: 1px solid #333;
            padding: 8px 6px;
            white-space: nowrap;
            vertical-align: middle;
        }

        td {
            border: 1px solid #333;
            padding: 8px 6px;
            background-color: #fff;
            vertical-align: middle;
        }

        td.col-currency {
            white-space: normal;
            word-break: break-word;
            line-height: 1.35;
        }

        .amount {
            text-align: right;
            font-family: 'Inter', monospace;
            font-weight: 500;
            white-space: nowrap;
        }

        .print-only {
            display: none;
        }

        .cell-input {
            width: 100%;
            min-width: 0;
            padding: 4px 6px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 12px;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
            text-align: right;
        }

        td:not(.amount) .cell-input {
            text-align: left;
        }

        .cell-input[readonly] {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }

        select.cell-input {
            text-align: left;
            cursor: pointer;
        }

        .total-row td {
            font-weight: 600;
            border-top: 2px solid #333;
            background-color: #f8f9fa;
        }

        table input[type="number"],
        table input[type="text"],
        table select {
            width: 100%;
            min-width: 0;
            padding: 4px 6px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 12px;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        table .amount input[type="number"],
        table .amount input[type="text"] {
            text-align: right;
        }

        table input[readonly] {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }

        .report-footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            font-size: 11px;
            color: #666;
        }

        .report-footer-left,
        .report-footer-right {
            flex: 1;
            min-width: 200px;
        }

        .report-footer-right {
            text-align: right;
        }

        .report-footer p {
            margin: 0;
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

            .table-scroll-hint,
            .table-scroll-wrap::after {
                display: none !important;
            }

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
                min-width: 0 !important;
                max-width: none !important;
                font-size: 10px;
                table-layout: fixed !important;
                page-break-inside: auto;
                display: table !important;
            }

            thead {
                display: table-header-group !important;
            }

            tbody {
                display: table-row-group !important;
            }

            tr {
                display: table-row !important;
            }

            th, td {
                display: table-cell !important;
            }

            td::before,
            td.total-summary-label::before {
                display: none !important;
            }

            .cell-value {
                text-align: inherit !important;
                flex: none !important;
            }

            td .cell-input,
            td input,
            td select {
                max-width: none !important;
            }

            .screen-only-input,
            .screen-only-select {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            td .print-only {
                text-align: inherit;
                font-weight: inherit;
                white-space: nowrap;
            }

            .total-row .print-only {
                font-weight: 700;
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
            table input[type="text"],
            table select {
                display: none !important;
            }

            .report-footer {
                display: flex !important;
                justify-content: space-between !important;
                margin-top: 16px;
                page-break-inside: avoid;
            }

            .report-footer-left { text-align: left !important; }
            .report-footer-right { text-align: right !important; }
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

            .table-scroll-wrap {
                margin-top: 16px;
            }

            .table-scroll-wrap::after {
                display: none;
            }

            .table-scroll-hint {
                display: none !important;
            }

            .table-container {
                overflow: visible;
                border: none;
                width: 100%;
                max-width: 100%;
            }

            #currency-summary-table {
                display: block;
                width: 100% !important;
                min-width: 0 !important;
                border: none;
            }

            #currency-summary-table colgroup,
            #currency-summary-table thead {
                display: none;
            }

            #currency-summary-table tbody {
                display: block;
            }

            #currency-summary-table tr.currency-row {
                display: block;
                margin-bottom: 14px;
                border: 1px solid #333;
                border-radius: 8px;
                overflow: hidden;
                background: #fff;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            }

            #currency-summary-table tr.currency-row td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
                padding: 10px 12px;
                border: none;
                border-bottom: 1px solid #e5e7eb;
                white-space: normal;
                text-align: right;
            }

            #currency-summary-table tr.currency-row td:last-child {
                border-bottom: none;
            }

            #currency-summary-table tr.currency-row td::before {
                content: attr(data-label);
                font-weight: 600;
                font-size: 11px;
                color: #444;
                flex: 0 0 42%;
                text-align: left;
                white-space: normal;
                line-height: 1.35;
            }

            #currency-summary-table tr.currency-row td .cell-value {
                flex: 1;
                text-align: right;
                font-family: 'Inter', monospace;
                font-weight: 500;
                font-size: 12px;
                word-break: break-word;
            }

            #currency-summary-table tr.currency-row td .cell-input,
            #currency-summary-table tr.currency-row td input,
            #currency-summary-table tr.currency-row td select {
                flex: 1;
                max-width: 58%;
                min-width: 0;
                font-size: 12px;
            }

            #currency-summary-table tr.total-row {
                display: block;
                margin-top: 4px;
                border: 2px solid #333;
                border-radius: 8px;
                overflow: hidden;
                background: #f8f9fa;
            }

            #currency-summary-table tr.total-row td.total-summary-label {
                display: block;
                text-align: center;
                padding: 10px 12px;
                border-bottom: 1px solid #333;
                font-size: 13px;
            }

            #currency-summary-table tr.total-row td.total-summary-label::before {
                display: none;
            }

            #currency-summary-table tr.total-row td.amount {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
                padding: 10px 12px;
                border: none;
            }

            #currency-summary-table tr.total-row td.amount::before {
                content: attr(data-label);
                font-weight: 700;
                font-size: 12px;
                color: #111;
            }

            #currency-summary-table tr.total-row td.amount .cell-input,
            #currency-summary-table tr.total-row td.amount input {
                flex: 1;
                max-width: 58%;
                font-weight: 700 !important;
                font-size: 13px;
                text-align: right;
            }
        }

        @media screen and (min-width: 769px) {
            .table-scroll-hint {
                display: none !important;
            }
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
                <div class="table-scroll-wrap" id="tableScrollWrap">
                    <div class="table-container" id="tableScrollContainer">
                    <table id="currency-summary-table">
                        <colgroup>
                            <col class="col-no">
                            <col class="col-currency">
                            <col class="col-bank">
                            <col class="col-party">
                            <col class="col-total">
                            <col class="col-rate">
                            <col class="col-operation">
                            <col class="col-end">
                        </colgroup>
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
                                <tr data-row="{{ $index }}" class="currency-row">
                                    <td data-label="No"><span class="cell-value">{{ $index + 1 }}</span></td>
                                    <td class="col-currency" data-label="Currency"><span class="cell-value">{{ $row->currency }}</span></td>
                                    <td class="amount" data-label="Banks Amount"><span class="cell-value">{{ number_format($row->currency_balance, 2) }}</span></td>
                                    <td class="amount" data-label="Party Amount"><span class="cell-value">{{ number_format($row->party_balance, 2) }}</span></td>
                                    <td class="amount" data-label="Total Amount">
                                        <input type="text" readonly id="total_amount_{{ $index }}" class="format-amount screen-only-input cell-input" value="{{ $row->total_amount }}">
                                        <span class="print-only print-value" id="total_amount_print_{{ $index }}">{{ number_format($row->total_amount, 2) }}</span>
                                    </td>
                                    <td class="amount" data-label="Rate">
                                        <input type="text" id="rate_{{ $index }}" class="screen-only-input cell-input" value="1" inputmode="decimal" onchange="calculateEndResult({{ $index }})" oninput="calculateEndResult({{ $index }})">
                                        <span class="print-only print-value" id="rate_print_{{ $index }}">1</span>
                                    </td>
                                    <td data-label="Operation">
                                        <select id="operation_{{ $index }}" class="screen-only-select cell-input" onchange="calculateEndResult({{ $index }})">
                                            <option value="1">Multiply</option>
                                            <option value="2">Divide</option>
                                        </select>
                                        <span class="print-only print-value" id="operation_print_{{ $index }}">Multiply</span>
                                    </td>
                                    <td class="amount" data-label="End Result">
                                        <input type="text" readonly id="end_result_{{ $index }}" class="end-result format-amount screen-only-input cell-input">
                                        <span class="print-only print-value" id="end_result_print_{{ $index }}"></span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="7" data-label="Total Summary" class="total-summary-label"><strong>Total Summary</strong></td>
                                <td class="amount" data-label="Total">
                                    <input type="text" readonly id="total_summary" class="format-amount screen-only-input cell-input" style="font-weight: 700; border: none; background: transparent;">
                                    <span class="print-only print-value" id="total_summary_print" style="font-weight: 700;"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>

                <div class="report-footer">
                    <div class="report-footer-left">
                        <p><strong>Print Date/Time:</strong> {{ now()->format('d-m-Y H:i:s') }}</p>
                        <p>Generated by: {{ auth()->user()->name ?? 'User' }}</p>
                    </div>
                    <div class="report-footer-right">
                        <p><strong>Powered By:</strong> Grow Business 365</p>
                    </div>
                </div>

                <script>
                function readReportAmount(el) {
                    if (window.AmountFormat) {
                        return AmountFormat.read(el);
                    }

                    return parseFloat(String(el.value).replace(/,/g, '')) || 0;
                }

                function formatReportDisplay(value) {
                    var num = typeof value === 'number' ? value : parseFloat(String(value).replace(/,/g, ''));

                    if (isNaN(num)) {
                        return '';
                    }

                    return num.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    });
                }

                function writeReportAmount(el, value) {
                    if (!el) {
                        return;
                    }

                    if (window.AmountFormat) {
                        AmountFormat.setValue(el, value);
                        return;
                    }

                    el.value = formatReportDisplay(value);
                }

                function syncPrintValues(row) {
                    var totalInput = document.getElementById('total_amount_' + row);
                    var rateInput = document.getElementById('rate_' + row);
                    var operationSelect = document.getElementById('operation_' + row);
                    var endInput = document.getElementById('end_result_' + row);
                    var totalPrint = document.getElementById('total_amount_print_' + row);
                    var ratePrint = document.getElementById('rate_print_' + row);
                    var operationPrint = document.getElementById('operation_print_' + row);
                    var endPrint = document.getElementById('end_result_print_' + row);

                    if (totalPrint && totalInput) {
                        totalPrint.textContent = totalInput.value || formatReportDisplay(totalInput.getAttribute('value'));
                    }

                    if (ratePrint && rateInput) {
                        ratePrint.textContent = rateInput.value || '1';
                    }

                    if (operationPrint && operationSelect) {
                        operationPrint.textContent = operationSelect.options[operationSelect.selectedIndex].text;
                    }

                    if (endPrint && endInput) {
                        endPrint.textContent = endInput.value;
                    }
                }

                function syncSummaryPrint() {
                    var summaryInput = document.getElementById('total_summary');
                    var summaryPrint = document.getElementById('total_summary_print');

                    if (summaryInput && summaryPrint) {
                        summaryPrint.textContent = summaryInput.value;
                    }
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

                    syncPrintValues(row);
                    syncSummaryPrint();
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

                window.addEventListener('beforeprint', function() {
                    var totalRecords = {{ $rows->count() }};
                    for (var i = 0; i < totalRecords; i++) {
                        syncPrintValues(i);
                    }
                    syncSummaryPrint();
                });
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
