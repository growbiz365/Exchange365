<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Party Ledger - {{ $business->business_name ?? 'ExchangeHub' }}</title>
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

        .filter-form .checkbox-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-form .checkbox-group input {
            width: auto;
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

        /* Chosen select (party dropdown) */
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
                            <option value="{{ $c->currency_id }}" {{ (isset($currencyId) && (int)$currencyId === (int)$c->currency_id) ? 'selected' : '' }}>{{ $c->currency }}</option>
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
                    <label class="checkbox-group">
                        <input type="checkbox" id="check_party_col" value="1" checked> Show Party Column
                    </label>
                </div>
                <div class="form-group">
                    <label class="checkbox-group">
                        <input type="checkbox" id="check_rate_col" value="1" checked> Show Rate Column
                    </label>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Search</button>
                    @if($partyDetails)
                        <button type="button" class="btn btn-secondary" onclick="window.print()">Print</button>
                    @endif
                    <a href="{{ route('parties.dashboard') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>

        @if($partyDetails)
            <div class="report-header">
                <div class="report-header-left">
                    <h1>{{ $business->business_name ?? 'ExchangeHub' }}</h1>
                    @if($partyDetails)
                        <h4>Party: ( {{ $partyDetails->party_name }} )</h4>
                    @endif
                </div>
                <div class="report-header-right">
                    <h2>{{ auth()->user()->name }}</h2>
                    <h4>Party Ledger - {{ $dataDuration }}</h4>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="10%">Date <span class="urdu">( تاریخ )</span></th>
                            <th width="15%">Voucher <span class="urdu">( واؤچر )</span></th>
                            <th width="20%">Description <span class="urdu">( تفصیلات )</span></th>
                            <th class="party_column" width="15%">Party</th>
                            <th class="rate_column" width="10%">Rate</th>
                            <th width="12%">Credit/Received <span class="urdu">( جمع )</span></th>
                            <th width="12%">Debit/Paid <span class="urdu">( بنام )</span></th>
                            <th width="12%">Balance <span class="urdu">( بقیہ )</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Opening Balance -->
                        <tr class="opening-balance-row">
                            <td>{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</td>
                            <td class="total_head_colspan" colspan="6"><span class="badge badge-success">*** Opening Balance ***</span></td>
                            <td class="amount">{{ number_format($previousBalance, 2) }} {{ $currencySymbol }}</td>
                        </tr>

                        @php
                            $balance = $previousBalance;
                            $total_credit_amount = 0;
                            $total_debit_amount = 0;
                        @endphp

                        @if($ledgerEntries && $ledgerEntries->count() > 0)
                            @foreach($ledgerEntries as $row)
                                @php
                                    $credit_amount = $row->credit_amount;
                                    $debit_amount = $row->debit_amount;
                                    $balance += ($credit_amount - $debit_amount);
                                    $total_credit_amount += $credit_amount;
                                    $total_debit_amount += $debit_amount;
                                @endphp
                                <tr>
                                    <td>{{ $row->date_added->format('d M Y') }}</td>
                                    <td>{{ $row->voucher_type }} # {{ $row->voucher_id }}</td>
                                    <td>{{ $row->details ?? '-' }}</td>
                                    <td class="party_column">{{ $row->transaction_party ?? '-' }}</td>
                                    <td class="rate_column">{{ $row->rate != 0 ? number_format($row->rate, 4) : '' }}</td>
                                    <td class="amount">
                                        @if($credit_amount != 0)
                                            <span class="badge badge-success">{{ number_format($credit_amount, 2) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="amount">
                                        @if($debit_amount != 0)
                                            <span class="badge badge-danger">{{ number_format($debit_amount, 2) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="amount">{{ number_format($balance, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                    @if($ledgerEntries && $ledgerEntries->count() > 0)
                        <tfoot>
                            <tr class="total-row">
                                <td class="total_colspan" colspan="5" style="text-align: center"><strong>Total</strong></td>
                                <td class="amount"><strong>{{ number_format($total_credit_amount, 2) }} {{ $currencySymbol }}</strong></td>
                                <td class="amount"><strong>{{ number_format($total_debit_amount, 2) }} {{ $currencySymbol }}</strong></td>
                                <td class="amount"><strong>{{ number_format($balance, 2) }} {{ $currencySymbol }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <div class="pagin">
                                        Total Record Found: <strong><span class="total_row">{{ $ledgerEntries->count() }}</span></strong>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    @else
                        <tfoot>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 20px; color: #666;">No Transactions Found.</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            <div class="print-footer">
                <strong>Print Date/Time:</strong> {{ now()->format('d-m-Y H:i:s') }}
            </div>
        @else
            <p style="text-align: center; color: #666; padding: 30px;">Please select a Party and Currency, then click Search to view the ledger.</p>
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
            var partyCol = document.getElementById('check_party_col');
            var rateCol = document.getElementById('check_rate_col');
            if (!partyCol || !rateCol) return;
            var showParty = partyCol.checked;
            var showRate = rateCol.checked;
            var totalHead = document.querySelector('.total_head_colspan');
            var totalCol = document.querySelector('.total_colspan');
            if (!totalHead || !totalCol) return;
            var headSpan = 4 + (showParty ? 1 : 0) + (showRate ? 1 : 0);
            var totalSpan = 3 + (showParty ? 1 : 0) + (showRate ? 1 : 0);
            totalHead.setAttribute('colspan', headSpan);
            totalCol.setAttribute('colspan', totalSpan);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var partyCol = document.getElementById('check_party_col');
            var rateCol = document.getElementById('check_rate_col');
            var partyCols = document.querySelectorAll('.party_column');
            var rateCols = document.querySelectorAll('.rate_column');

            function toggleParty() {
                var show = partyCol.checked;
                partyCols.forEach(function(el) { el.style.display = show ? '' : 'none'; });
                updateColspans();
            }
            function toggleRate() {
                var show = rateCol.checked;
                rateCols.forEach(function(el) { el.style.display = show ? '' : 'none'; });
                updateColspans();
            }

            if (partyCol) partyCol.addEventListener('click', toggleParty);
            if (rateCol) rateCol.addEventListener('click', toggleRate);
            updateColspans();
        });
    </script>
</body>
</html>
