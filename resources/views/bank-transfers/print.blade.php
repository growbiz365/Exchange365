<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Transfer #{{ $bankTransfer->bank_transfer_id }} - {{ $business->business_name ?? 'ExchangeHub' }}</title>
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
            background: radial-gradient(circle at top left, #e0f2fe 0, #f9fafb 40%, #f1f5f9 100%);
        }
        .page-container {
            max-width: 800px;
            margin: 24px auto;
            padding: 24px 28px;
            background: #ffffff;
            border-radius: 14px;
            box-shadow:
                0 18px 45px rgba(15, 23, 42, 0.18),
                0 0 0 1px rgba(148, 163, 184, 0.15);
            position: relative;
            overflow: hidden;
        }
        .page-container::before,
        .page-container::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            filter: blur(32px);
            opacity: 0.35;
            z-index: -1;
        }
        .page-container::before {
            width: 220px;
            height: 220px;
            background: radial-gradient(circle at 30% 20%, #38bdf8, rgba(59, 130, 246, 0.15));
            top: -80px;
            right: -60px;
        }
        .page-container::after {
            width: 260px;
            height: 260px;
            background: radial-gradient(circle at 20% 80%, #22c55e, rgba(56, 189, 248, 0.1));
            bottom: -120px;
            left: -80px;
        }
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 32px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.45);
        }
        .report-header-left { flex: 1.3; }
        .report-header-right { flex: 1; text-align: right; }
        .business-name {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #0f172a;
        }
        .business-name span {
            background: linear-gradient(90deg, #0ea5e9, #6366f1);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .business-info-details {
            margin-top: 4px;
            font-size: 11px;
            color: #64748b;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background: rgba(129, 140, 248, 0.12);
            color: #3730a3;
        }
        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            margin-right: 6px;
            background: radial-gradient(circle at 30% 30%, #a5b4fc, #4f46e5);
        }
        .report-title-main {
            font-size: 19px;
            font-weight: 700;
            margin: 4px 0 6px;
            color: #0f172a;
        }
        .report-subinfo {
            font-size: 11px;
            color: #6b7280;
        }
        .label {
            font-size: 11px;
            color: #6b7280;
        }
        .value {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
        }
        .pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 3px 10px;
            font-size: 11px;
            background: rgba(56, 189, 248, 0.12);
            color: #0369a1;
            gap: 6px;
        }
        .pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: radial-gradient(circle at 30% 30%, #22c55e, #16a34a);
        }
        .section {
            margin-top: 18px;
            border-radius: 10px;
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.96), rgba(239, 246, 255, 0.96));
            overflow: hidden;
        }
        .section-header {
            padding: 8px 12px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.9);
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.06), rgba(129, 140, 248, 0.03));
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section-header-title {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #1e293b;
        }
        .section-content {
            padding: 10px 12px 12px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }
        .card {
            padding: 8px 10px;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 1px 0 rgba(15, 23, 42, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.9);
        }
        .card-title {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .card-main {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
        }
        .card-sub {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }
        .amount-chip {
            display: inline-flex;
            align-items: baseline;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            background: radial-gradient(circle at 0 0, rgba(59, 130, 246, 0.22), rgba(15, 23, 42, 0.96));
            color: #e5e7eb;
            font-weight: 600;
            font-size: 13px;
        }
        .amount-chip-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #bae6fd;
        }
        .details-block {
            margin-top: 14px;
            border-radius: 10px;
            border: 1px dashed rgba(148, 163, 184, 0.6);
            padding: 10px 12px;
            background: rgba(248, 250, 252, 0.9);
            font-size: 12px;
            color: #4b5563;
        }
        .details-label {
            font-weight: 600;
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .footer {
            margin-top: 18px;
            font-size: 10px;
            color: #9ca3af;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }
        .signatures {
            margin-top: 24px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 32px;
            font-size: 11px;
            color: #6b7280;
        }
        .signature-line {
            border-top: 1px solid rgba(148, 163, 184, 0.7);
            margin-top: 28px;
            padding-top: 4px;
            text-align: center;
        }
        .signature-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #4b5563;
        }
        .print-actions {
            margin-top: 18px;
            padding-top: 12px;
            border-top: 1px solid rgba(226, 232, 240, 0.9);
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .btn {
            padding: 7px 16px;
            border-radius: 999px;
            border: none;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary {
            background: linear-gradient(90deg, #0ea5e9, #2563eb);
            color: #f9fafb;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #111827;
        }
        @media print {
            body { background: #ffffff; }
            .page-container {
                margin: 0;
                border-radius: 0;
                box-shadow: none;
                max-width: none;
                padding: 8mm 10mm;
            }
            .print-actions { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="report-header">
            <div class="report-header-left">
                <div class="badge">
                    <span class="badge-dot"></span>
                    BANK TRANSFER VOUCHER
                </div>
                <div class="business-info" style="margin-top: 6px;">
                    <div class="business-name">
                        <span>{{ $business->business_name ?? 'ExchangeHub' }}</span>
                    </div>
                    <div class="business-info-details">
                        @if($business?->address)<div>{{ $business->address }}</div>@endif
                        @if($business?->contact_no)<div>Contact: {{ $business->contact_no }}</div>@endif
                        @if($business?->email)<div>Email: {{ $business->email }}</div>@endif
                    </div>
                </div>
            </div>
            <div class="report-header-right">
                <div class="report-title-main">Bank Transfer</div>
                <div class="report-subinfo">
                    <div><span class="label">Voucher #</span> <span class="value">BT-{{ str_pad($bankTransfer->bank_transfer_id, 4, '0', STR_PAD_LEFT) }}</span></div>
                    <div><span class="label">Date</span> <span class="value">{{ $bankTransfer->date_added->format('d M Y') }}</span></div>
                    <div><span class="label">Prepared by</span> <span class="value">{{ $bankTransfer->user?->name ?? '—' }}</span></div>
                </div>
                <div style="margin-top: 8px;">
                    <span class="pill">
                        <span class="pill-dot"></span>
                        Bank-to-bank transfer
                    </span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">
                <div class="section-header-title">Accounts & Amount</div>
                <div class="amount-chip">
                    <span class="amount-chip-label">Total Amount</span>
                    <span>{{ number_format($bankTransfer->amount, 2) }}</span>
                    <span style="font-size: 10px; opacity: 0.8;">
                        {{ $bankTransfer->fromBank?->currency?->currency_symbol ?? '' }}
                    </span>
                </div>
            </div>
            <div class="section-content">
                <div class="grid">
                    <div class="card">
                        <div class="card-title">From Account</div>
                        <div class="card-main">
                            {{ $bankTransfer->fromBank?->bank_name ?? '—' }}
                        </div>
                        <div class="card-sub">
                            {{ $bankTransfer->fromBank?->currency?->currency ?? '-' }}
                            @if($bankTransfer->fromBank?->currency?->currency_symbol)
                                ({{ $bankTransfer->fromBank->currency->currency_symbol }})
                            @endif
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-title">To Account</div>
                        <div class="card-main">
                            {{ $bankTransfer->toBank?->bank_name ?? '—' }}
                        </div>
                        <div class="card-sub">
                            {{ $bankTransfer->toBank?->currency?->currency ?? '-' }}
                            @if($bankTransfer->toBank?->currency?->currency_symbol)
                                ({{ $bankTransfer->toBank->currency->currency_symbol }})
                            @endif
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-title">Summary</div>
                        <div class="card-main">
                            Internal bank transfer
                        </div>
                        <div class="card-sub">
                            This slip confirms movement of funds between the above accounts.
                        </div>
                    </div>
                </div>
                @if($bankTransfer->details)
                    <div class="details-block">
                        <div class="details-label">Transfer Details</div>
                        <div>{{ $bankTransfer->details }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="signatures">
            <div>
                <div class="signature-line"></div>
                <div class="signature-label">Prepared By</div>
            </div>
            <div>
                <div class="signature-line"></div>
                <div class="signature-label">Checked By</div>
            </div>
            <div>
                <div class="signature-line"></div>
                <div class="signature-label">Authorized Signatory</div>
            </div>
        </div>

        <div class="footer">
            <div>
                Printed: {{ now()->format('d M Y h:i A') }}
            </div>
            <div>
                Generated by ExchangeHub · Bank Module
            </div>
        </div>

        <div class="print-actions">
            <button type="button" onclick="window.print()" class="btn btn-primary">
                <span>🖨</span> <span>Print</span>
            </button>
            <button type="button" onclick="window.close()" class="btn btn-secondary">
                Close
            </button>
        </div>
    </div>
</body>
</html>
