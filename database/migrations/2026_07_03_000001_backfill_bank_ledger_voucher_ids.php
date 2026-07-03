<?php

use App\Models\BankLedger;
use App\Support\VoucherLink;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        BankLedger::query()
            ->whereNull('voucher_id')
            ->whereNotNull('voucher_type')
            ->where('voucher_type', '!=', 'Opening Balance')
            ->orderBy('bank_ledger_id')
            ->each(function (BankLedger $row) {
                $voucherId = VoucherLink::resolveBankLedgerVoucherId(
                    $row->voucher_type,
                    (int) $row->bank_id,
                    $row->date_added,
                    (float) $row->deposit_amount,
                    (float) $row->withdrawal_amount,
                    $row->details
                );

                if ($voucherId) {
                    $row->update(['voucher_id' => $voucherId]);
                }
            });
    }

    public function down(): void
    {
        // Legacy rows cannot be reliably restored to null.
    }
};
