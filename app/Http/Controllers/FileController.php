<?php

namespace App\Http\Controllers;

use App\Models\BankTransferAttachment;
use App\Models\GeneralVoucherAttachment;
use App\Models\MoneyExchangeAttachment;
use App\Models\PartyTransferAttachment;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function downloadBankTransferAttachment(BankTransferAttachment $attachment)
    {
        $transfer = $attachment->bankTransfer;
        $businessId = session('active_business');
        if (! $businessId || ! $transfer || (int) $transfer->business_id !== (int) $businessId) {
            abort(403, 'Unauthorized access to this file.');
        }
        return $this->streamFile($attachment->file_path, $attachment->file_name, $attachment->file_type);
    }

    public function downloadGeneralVoucherAttachment(GeneralVoucherAttachment $attachment)
    {
        $voucher = $attachment->generalVoucher;
        $businessId = session('active_business');
        if (! $businessId || ! $voucher || (int) $voucher->business_id !== (int) $businessId) {
            abort(403, 'Unauthorized access to this file.');
        }
        return $this->streamFile($attachment->file_path, $attachment->file_name, $attachment->file_type);
    }

    public function downloadPartyTransferAttachment(PartyTransferAttachment $attachment)
    {
        $transfer = $attachment->partyTransfer;
        $businessId = session('active_business');
        if (! $businessId || ! $transfer || (int) $transfer->business_id !== (int) $businessId) {
            abort(403, 'Unauthorized access to this file.');
        }
        return $this->streamFile($attachment->file_path, $attachment->file_name, $attachment->file_type);
    }

    public function downloadMoneyExchangeAttachment(MoneyExchangeAttachment $attachment)
    {
        $exchange = $attachment->moneyExchange;
        $businessId = session('active_business');
        if (! $businessId || ! $exchange || (int) $exchange->business_id !== (int) $businessId) {
            abort(403, 'Unauthorized access to this file.');
        }
        return $this->streamFile($attachment->file_path, $attachment->file_name, $attachment->file_type);
    }

    protected function streamFile(string $filePath, string $displayName, ?string $mimeType)
    {
        if (! Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }
        return Storage::disk('public')->response($filePath, $displayName, [
            'Content-Type' => $mimeType ?: 'application/octet-stream',
        ]);
    }
}
