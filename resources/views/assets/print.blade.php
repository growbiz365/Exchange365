<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset #{{ $asset->asset_id }} - {{ $business->business_name ?? 'ExchangeHub' }}</title>
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
            background: linear-gradient(135deg, #64748b, #0f172a);
            color: #fff;
        }
        .business-name { font-size: 22px; font-weight: 700; letter-spacing: -0.02em; color: #0f172a; margin-top: 8px; }
        .business-info-details { margin-top: 6px; font-size: 12px; color: #64748b; line-height: 1.5; }
        .report-title-main { font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
        .report-subinfo { font-size: 12px; color: #64748b; }
        .report-subinfo div { margin-bottom: 2px; }
        .label { color: #64748b; font-weight: 500; }
        .value { font-weight: 600; color: #0f172a; margin-left: 4px; }
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
        .btn-primary { background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff; }
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
                <span class="badge">Asset</span>
                <div class="business-name">{{ $business->business_name ?? 'ExchangeHub' }}</div>
                <div class="business-info-details">
                    @if($business?->address)<div>{{ $business->address }}</div>@endif
                    @if($business?->contact_no)<div>Contact: {{ $business->contact_no }}</div>@endif
                    @if($business?->email)<div>Email: {{ $business->email }}</div>@endif
                </div>
            </div>
            <div class="report-header-right">
                <div class="report-title-main">Asset Voucher</div>
                <div class="report-subinfo">
                    <div><span class="label">Asset #</span><span class="value">AST-{{ str_pad($asset->asset_id, 5, '0', STR_PAD_LEFT) }}</span></div>
                    <div><span class="label">Purchase Date</span><span class="value">{{ $asset->date_added->format('d M Y') }}</span></div>
                    <div><span class="label">Prepared by</span><span class="value">{{ $asset->user?->name ?? '—' }}</span></div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">Purchase & Sale Summary</div>
            <div class="section-content">
                <div class="two-cols">
                    <div class="col-card">
                        <h3>Purchase</h3>
                        <div class="row-item">
                            <dt>Asset Name</dt>
                            <dd>{{ $asset->asset_name }}</dd>
                        </div>
                        <div class="row-item">
                            <dt>Category</dt>
                            <dd>{{ $asset->category?->asset_category ?? '—' }}</dd>
                        </div>
                        <div class="row-item">
                            <dt>Cost Amount (PKR)</dt>
                            <dd class="amount-big">{{ number_format($asset->cost_amount, 2) }}</dd>
                        </div>
                        <div class="row-item">
                            <dt>Source</dt>
                            <dd>
                                @if($asset->purchase_transaction_type === 2)
                                    Bank — {{ $asset->purchaseBank?->bank_name ?? '—' }}
                                @elseif($asset->purchase_transaction_type === 3)
                                    Party — {{ $asset->purchaseParty?->party_name ?? '—' }}
                                @else
                                    Self / Company Funds
                                @endif
                            </dd>
                        </div>
                    </div>
                    <div class="col-card">
                        <h3>Sale</h3>
                        @if($asset->asset_status === \App\Models\Asset::STATUS_SOLD)
                            <div class="row-item">
                                <dt>Status</dt>
                                <dd>Sold Out</dd>
                            </div>
                            <div class="row-item">
                                <dt>Sale Date</dt>
                                <dd>{{ optional($asset->sale_date)->format('d M Y') }}</dd>
                            </div>
                            <div class="row-item">
                                <dt>Sale Amount (PKR)</dt>
                                <dd class="amount-big">{{ number_format($asset->sale_amount, 2) }}</dd>
                            </div>
                            <div class="row-item">
                                <dt>Target</dt>
                                <dd>
                                    @if($asset->sale_transaction_type === 2)
                                        Bank — {{ $asset->saleBank?->bank_name ?? '—' }}
                                    @elseif($asset->sale_transaction_type === 3)
                                        Party — {{ $asset->saleParty?->party_name ?? '—' }}
                                    @else
                                        —
                                    @endif
                                </dd>
                            </div>
                        @else
                            <div class="row-item">
                                <dt>Status</dt>
                                <dd>Active (Not Sold)</dd>
                            </div>
                        @endif
                    </div>
                </div>
                @if($asset->purchase_details)
                    <div class="summary-row">
                        <span class="summary-label">Purchase Details</span>
                        <span class="summary-value">{{ $asset->purchase_details }}</span>
                    </div>
                @endif
                @if($asset->asset_status === \App\Models\Asset::STATUS_SOLD && $asset->sale_details)
                    <div class="summary-row">
                        <span class="summary-label">Sale Details</span>
                        <span class="summary-value">{{ $asset->sale_details }}</span>
                    </div>
                @endif
                @if($asset->asset_status === \App\Models\Asset::STATUS_SOLD && $asset->sale_amount)
                    @php $gain = (float)$asset->sale_amount - (float)$asset->cost_amount; @endphp
                    <div class="summary-row">
                        <span class="summary-label">Gain / Loss (PKR)</span>
                        <span class="summary-value" style="color: {{ $gain >= 0 ? '#15803d' : '#b91c1c' }};">
                            {{ $gain >= 0 ? '+' : '' }}{{ number_format($gain, 2) }}
                        </span>
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
            <span>ExchangeHub · Asset AST-{{ str_pad($asset->asset_id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>

        <div class="print-actions">
            <button type="button" onclick="window.print()" class="btn btn-primary">Print</button>
            <button type="button" onclick="window.close()" class="btn btn-secondary">Close</button>
        </div>
    </div>
</body>
</html>

