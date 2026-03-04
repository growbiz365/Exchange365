<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales #{{ $sale->sales_id }} - {{ $business->business_name ?? 'ExchangeHub' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page { size: A4; margin: 12mm; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            color: #0f172a;
            background: #f8fafc;
        }
        .page-container {
            max-width: 820px;
            margin: 24px auto;
            padding: 28px 32px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        .report-header-left { flex: 1.2; }
        .report-header-right { flex: 1; text-align: right; }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            background: linear-gradient(135deg, #059669, #0d9488);
            color: #fff;
        }
        .business-name { font-size: 22px; font-weight: 700; letter-spacing: -0.02em; color: #0f172a; margin-top: 8px; }
        .business-info-details { margin-top: 6px; font-size: 12px; color: #64748b; line-height: 1.5; }
        .report-title-main { font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
        .report-subinfo { font-size: 12px; color: #64748b; }
        .report-subinfo div { margin-bottom: 2px; }
        .label { color: #64748b; font-weight: 500; }
        .value { font-weight: 600; color: #0f172a; margin-left: 4px; }
        .pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 8px;
            background: #fef3c7;
            color: #b45309;
        }
        .pill.multiply { background: #fef3c7; color: #b45309; }
        .pill.divide { background: #dbeafe; color: #1d4ed8; }
        .section {
            margin-top: 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            background: #f8fafc;
        }
        .section-header {
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #334155;
            background: linear-gradient(180deg, #f1f5f9 0%, #e2e8f0 100%);
            border-bottom: 1px solid #e2e8f0;
        }
        .section-content { padding: 16px; }
        .two-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .col-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
        }
        .col-card h3 {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin: 0 0 12px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .row-item { margin-bottom: 10px; }
        .row-item:last-child { margin-bottom: 0; }
        .row-item dt { font-size: 11px; color: #64748b; font-weight: 500; margin-bottom: 2px; }
        .row-item dd { font-size: 14px; font-weight: 600; color: #0f172a; margin: 0; }
        .amount-big {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            margin-top: 12px;
        }
        .summary-label { font-size: 12px; font-weight: 600; color: #64748b; }
        .summary-value { font-size: 14px; font-weight: 700; color: #0f172a; }
        .details-block {
            margin-top: 16px;
            padding: 12px 16px;
            background: #fff;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            font-size: 13px;
            color: #475569;
        }
        .details-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; margin-bottom: 6px; }
        .signatures {
            margin-top: 28px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        .signature-line { border-top: 1px solid #94a3b8; margin-top: 36px; padding-top: 6px; text-align: center; font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em; }
        .footer {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .print-actions { margin-top: 20px; padding-top: 16px; border-top: 1px solid #e2e8f0; display: flex; gap: 12px; }
        .btn { padding: 10px 20px; border-radius: 8px; border: none; font-size: 13px; font-weight: 600; cursor: pointer; }
        .btn-primary { background: linear-gradient(135deg, #059669, #0d9488); color: #fff; }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        @media print {
            body { background: #fff; }
            .page-container { margin: 0; padding: 10mm 12mm; border-radius: 0; box-shadow: none; border: none; max-width: none; }
            .print-actions { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="report-header">
            <div class="report-header-left">
                <span class="badge">Sales</span>
                <div class="business-name">{{ $business->business_name ?? 'ExchangeHub' }}</div>
                <div class="business-info-details">
                    @if($business?->address)<div>{{ $business->address }}</div>@endif
                    @if($business?->contact_no)<div>Contact: {{ $business->contact_no }}</div>@endif
                    @if($business?->email)<div>Email: {{ $business->email }}</div>@endif
                </div>
            </div>
            <div class="report-header-right">
                <div class="report-title-main">Sales Voucher</div>
                <div class="report-subinfo">
                    <div><span class="label">Voucher #</span><span class="value">SAL-{{ str_pad($sale->sales_id, 5, '0', STR_PAD_LEFT) }}</span></div>
                    <div><span class="label">Date</span><span class="value">{{ $sale->date_added->format('d M Y') }}</span></div>
                    <div><span class="label">Prepared by</span><span class="value">{{ $sale->user?->name ?? '—' }}</span></div>
                </div>
                <span class="pill {{ $sale->transaction_operation === \App\Models\Sale::TRANSACTION_MULTIPLY ? 'multiply' : 'divide' }}">{{ $sale->transaction_operation_label }}</span>
            </div>
        </div>

        <div class="section">
            <div class="section-header">Withdrawal from Bank ( جمع ) & Party ( جمع )</div>
            <div class="section-content">
                <div class="two-cols">
                    <div class="col-card">
                        <h3>Withdrawal — Bank</h3>
                        <div class="row-item">
                            <dt>Bank</dt>
                            <dd>{{ $sale->bank?->bank_name ?? '—' }}</dd>
                        </div>
                        <div class="row-item">
                            <dt>Currency</dt>
                            <dd>{{ $sale->bank?->currency?->currency ?? '—' }} ({{ $sale->bank?->currency?->currency_symbol ?? '—' }})</dd>
                        </div>
                        <div class="row-item">
                            <dt>Currency Amount (withdrawal)</dt>
                            <dd class="amount-big">{{ number_format($sale->currency_amount, 2) }} {{ $sale->bank?->currency?->currency_symbol ?? '' }}</dd>
                        </div>
                    </div>
                    <div class="col-card">
                        <h3>Party</h3>
                        <div class="row-item">
                            <dt>Party</dt>
                            <dd>{{ $sale->party?->party_name ?? '—' }}</dd>
                        </div>
                        <div class="row-item">
                            <dt>Party Currency</dt>
                            <dd>{{ $sale->partyCurrency?->currency ?? '—' }} ({{ $sale->partyCurrency?->currency_symbol ?? '—' }})</dd>
                        </div>
                        <div class="row-item">
                            <dt>Party Amount</dt>
                            <dd class="amount-big">{{ number_format($sale->party_amount, 2) }} {{ $sale->partyCurrency?->currency_symbol ?? '' }}</dd>
                        </div>
                    </div>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Rate</span>
                    <span class="summary-value">{{ number_format($sale->rate, 4) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Calculation</span>
                    <span class="summary-value">{{ $sale->transaction_operation_label }} ({{ $sale->transaction_operation === \App\Models\Sale::TRANSACTION_MULTIPLY ? 'Currency Amount × Rate' : 'Currency Amount ÷ Rate' }})</span>
                </div>
                @if($sale->details)
                    <div class="details-block">
                        <div class="details-label">Details</div>
                        <div>{{ $sale->details }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="signatures">
            <div><div class="signature-line">Prepared By</div></div>
            <div><div class="signature-line">Checked By</div></div>
            <div><div class="signature-line">Authorized Signatory</div></div>
        </div>

        <div class="footer">
            <span>Printed: {{ now()->format('d M Y h:i A') }}</span>
            <span>ExchangeHub · Sales Voucher SAL-{{ str_pad($sale->sales_id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>

        <div class="print-actions">
            <button type="button" onclick="window.print()" class="btn btn-primary">Print</button>
            <button type="button" onclick="window.close()" class="btn btn-secondary">Close</button>
        </div>
    </div>
</body>
</html>
