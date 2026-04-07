<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Ledger - {{ $business->business_name ?? 'ExchangeHub' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <style>
        @page { size: A4; margin: 15mm; }

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

        .display_none { }
        @media print {
            .display_none { display: none !important; }
            body { background: white; }
            .page-container { padding: 0; }
        }

        .form_block {
            background: #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: flex-end;
        }

        .filter-form .form-group {
            min-width: 140px;
        }

        .filter-form label {
            display: block;
            margin-bottom: 4px;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
        }

        .filter-form select,
        .filter-form input[type="date"] {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 13px;
            background: #fff;
        }

        .btn {
            display: inline-block;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }
        .btn-primary:hover { background: #1d4ed8; }

        .btn-secondary {
            background: #6b7280;
            color: white;
            border-color: #6b7280;
        }
        .btn-secondary:hover { background: #4b5563; }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            gap: 20px;
        }

        .report-header-left h1 { margin: 0 0 10px 0; font-size: 20px; }
        .report-header-left h4 { margin: 0; font-size: 14px; color: #444; }
        .report-header-right { text-align: right; }
        .report-header-right h2 { margin: 0 0 10px 0; font-size: 18px; }
        .report-header-right h4 { margin: 0; font-size: 14px; color: #444; }

        .table-container {
            overflow-x: auto;
            margin-top: 15px;
            -webkit-overflow-scrolling: touch;
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
            height: 34px;
            line-height: 32px;
            padding: 0 8px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            background: #fff;
        }
        .filter-form .chosen-container-single .chosen-single:hover { border-color: #2563eb; }
        .filter-form .chosen-container-active.chosen-with-drop .chosen-single { border-color: #2563eb; border-radius: 6px 6px 0 0; }
        .filter-form .chosen-container-single .chosen-single div b { background-position: 0 4px; }
        .filter-form .chosen-container-active .chosen-single div b { background-position: -18px 4px; }
        .filter-form .chosen-drop { border: 1px solid #d1d5db; border-radius: 0 0 6px 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .filter-form .chosen-results { font-size: 13px; }
        .filter-form .chosen-results li.highlighted { background: #2563eb; color: white; }

        @media (max-width: 768px) {
            .page-container {
                padding: 12px 10px;
                max-width: 100%;
            }

            .report-header {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .report-header-right {
                text-align: left;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-form .form-group {
                min-width: 100%;
                width: 100%;
            }

            .filter-form .form-group:last-child {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .filter-form .btn,
            .filter-form .form-group:last-child a {
                width: 100%;
                text-align: center;
                box-sizing: border-box;
            }

            table {
                font-size: 10px;
            }

            th, td {
                padding: 5px 4px;
                word-break: break-word;
            }
        }
    </style>
</head>
<body>

    <div class="page-container">
        <!-- Filter form (hidden when printing) -->
        <div class="form_block display_none">
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
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Search</button>
                    @if($fields)
                        <button type="button" class="btn btn-secondary" onclick="window.print()">Print</button>
                    @endif
                    <a href="{{ route('banks.dashboard') }}" class="btn btn-secondary">Back</a>
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
