<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Ledger - {{ $business->business_name ?? 'ExchangeHub' }}</title>
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

        .report-header-left h1 {
            margin: 4px 0 8px 0;
            font-size: 18px;
            font-weight: 700;
        }

        .report-header-left h4,
        .report-header-right h4 {
            margin: 0;
            font-size: 12px;
            color: #555;
            line-height: 1.7;
        }

        .report-header-right h2 {
            margin: 0 0 8px 0;
            font-size: 18px;
            font-weight: 700;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 4px;
            border: 1px solid #333;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #333; padding: 8px; }
        th { background: #f9fafb; font-weight: 600; text-align: left; }
        .urdu { font-size: 11px; color: #666; display: block; }

        .amount { text-align: right; }
        .credit-amount { color: #059669; font-weight: 600; }
        .debit-amount { color: #dc2626; font-weight: 600; }

        .opening-balance-row { background: #dcfce7 !important; }
        .opening-balance-row td { background: #dcfce7 !important; font-weight: 600; }
        .badge { padding: 2px 8px; border-radius: 4px; font-size: 11px; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #991b1b; }

        .total-row td { font-weight: 700; border-top: 2px solid #333; background: #f9fafb; }
        .pagin { margin-top: 10px; font-size: 13px; color: #555; }
        .print-footer { margin-top: 20px; font-size: 11px; color: #666; }

        /* Chosen select (bank dropdown) */
        .filter-form .chosen-container { width: 100% !important; min-width: 140px; }
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
        .filter-form .chosen-container-single .chosen-single:hover { border-color: #0d6efd; }
        .filter-form .chosen-container-active.chosen-with-drop .chosen-single {
            border-color: #0d6efd;
            border-radius: 4px 4px 0 0;
        }
        .filter-form .chosen-drop { border: 1px solid #dee2e6; border-radius: 0 0 4px 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .filter-form .chosen-results { font-size: 13px; }
        .filter-form .chosen-results li.highlighted { background: #0d6efd; color: white; }

        @media screen and (max-width: 768px) {
            .page-container { padding: 12px 10px; }

            .report-header {
                flex-direction: column;
                align-items: stretch;
                gap: 16px;
            }

            .report-header-right { text-align: left; }

            .filter-form { flex-direction: column; align-items: stretch; }
            .filter-form .form-group { min-width: 100%; width: 100%; }

            .button-group { width: 100%; flex-wrap: wrap; }
            .button-group button, .button-group .btn-link {
                flex: 1 1 auto;
                min-width: min(100%, 120px);
                justify-content: center;
            }

            table { font-size: 10px; }
            th, td { padding: 5px 4px; word-break: break-word; }
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

            .filters, .no-print { display: none !important; }

            .report-header {
                display: flex !important;
                flex-direction: row !important;
                align-items: flex-start !important;
                justify-content: space-between !important;
                gap: 24px !important;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .report-header-left { flex: 1 !important; text-align: left !important; }
            .report-header-right { flex: 1 !important; text-align: right !important; }

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

            .badge-success {
                background: #dcfce7 !important;
                color: #166534 !important;
            }

            .badge-danger {
                background: #fee2e2 !important;
                color: #991b1b !important;
            }

            .total-row td {
                background: #f3f4f6 !important;
                border-top: 2px solid #000 !important;
            }

            .print-footer {
                margin-top: 12px;
                font-size: 9px;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="page-container">
        <!-- Filter form -->
        <div class="filters no-print">
            <form action="{{ route('banks.ledger') }}" method="GET" class="filter-form">
                <div class="form-group">
                    <label for="bank_id">Select Bank</label>
                    <select name="bank_id" id="bank_id" class="chosen-select">
                        <option value="">Select Bank</option>
                        @foreach($banks as $b)
                            <option value="{{ $b->bank_id }}" {{ ($queryArray['bank_id'] ?? '') == $b->bank_id ? 'selected' : '' }}>{{ $b->bank_name }} ({{ $b->currency?->currency_symbol }})</option>
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
                <div class="button-group">
                    <button type="submit" class="btn-primary">Search</button>
                    @if($fields)
                        <button type="button" class="btn-print" onclick="window.print()">Print</button>
                    @endif
                    <a href="{{ route('banks.dashboard') }}" class="btn-secondary btn-link">Back</a>
                </div>
            </form>
        </div>

        @if($fields)
            <div class="report-header">
                <div class="report-header-left">
                    <h1>{{ $business->business_name ?? 'ExchangeHub' }}</h1>
                    <h4>Bank: ( {{ $fields->bank_name }} ) - {{ $fields->currency?->currency ?? '' }} ({{ $fields->currency?->currency_symbol ?? '' }})</h4>
                </div>
                <div class="report-header-right">
                    <h2>{{ auth()->user()->name }}</h2>
                    <h4>Bank Ledger - {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</h4>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="10%">Date <span class="urdu">( تاریخ )</span></th>
                            <th width="18%">Voucher Type <span class="urdu">( واؤچر )</span></th>
                            <th width="32%">Description <span class="urdu">( تفصیل )</span></th>
                            <th width="14%">Deposit <span class="urdu">( جمع )</span></th>
                            <th width="14%">Withdrawal <span class="urdu">( بنام )</span></th>
                            <th width="12%">Balance <span class="urdu">( بقیہ )</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Opening Balance -->
                        <tr class="opening-balance-row">
                            <td>{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</td>
                            <td>Opening</td>
                            <td><span class="badge badge-success">*** Opening Balance ***</span></td>
                            <td class="amount">-</td>
                            <td class="amount">-</td>
                            <td class="amount">{{ number_format($previousBalance, 2) }} {{ $currencySymbol }}</td>
                        </tr>

                        @php
                            $balance = $previousBalance;
                            $total_deposit = 0;
                            $total_withdrawal = 0;
                        @endphp

                        @if($ledgerWithBalance && $ledgerWithBalance->count() > 0)
                            @foreach($ledgerWithBalance as $row)
                                @php
                                    $dep = (float) $row->deposit_amount;
                                    $with = (float) $row->withdrawal_amount;
                                    $balance += ($dep - $with);
                                    $total_deposit += $dep;
                                    $total_withdrawal += $with;
                                @endphp
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($row->date_added)->format('d M Y') }}</td>
                                    <td>{{ $row->voucher_type ?? '-' }}</td>
                                    <td>{{ $row->details ?? '-' }}</td>
                                    <td class="amount">
                                        @if($dep != 0)
                                            <span class="badge badge-success">{{ number_format($dep, 2) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="amount">
                                        @if($with != 0)
                                            <span class="badge badge-danger">{{ number_format($with, 2) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="amount">{{ number_format($balance, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                    @if($ledgerWithBalance && $ledgerWithBalance->count() > 0)
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3" style="text-align: center"><strong>Total</strong></td>
                                <td class="amount"><strong>{{ number_format($total_deposit, 2) }} {{ $currencySymbol }}</strong></td>
                                <td class="amount"><strong>{{ number_format($total_withdrawal, 2) }} {{ $currencySymbol }}</strong></td>
                                <td class="amount"><strong>{{ number_format($balance, 2) }} {{ $currencySymbol }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <div class="pagin">
                                        Total Record Found: <strong><span class="total_row">{{ $ledgerWithBalance->count() }}</span></strong>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    @else
                        <tfoot>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px; color: #666;">No Transactions Found.</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            <div class="print-footer">
                <strong>Print Date/Time:</strong> {{ now()->format('d-m-Y H:i:s') }}
            </div>
        @else
            <p style="text-align: center; color: #666; padding: 30px;">Please select a Bank and date range, then click Search to view the ledger.</p>
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
                placeholder_text_single: 'Select Bank'
            });
        });
    </script>
</body>
</html>
