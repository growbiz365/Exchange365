<?php

use App\Models\PartyLedger;
use App\Support\VoucherLink;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        PartyLedger::query()
            ->whereNull('voucher_id')
            ->whereNotNull('voucher_type')
            ->where('voucher_type', '!=', 'Opening Balance')
            ->orderBy('party_ledger_id')
            ->each(function (PartyLedger $row) {
                $voucherId = VoucherLink::resolvePartyLedgerVoucherId(
                    $row->voucher_type,
                    (int) $row->party_id,
                    (int) $row->currency_id,
                    $row->date_added,
                    (float) $row->credit_amount,
                    (float) $row->debit_amount,
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
