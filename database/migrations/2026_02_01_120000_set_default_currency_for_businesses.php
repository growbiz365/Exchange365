<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Set default currency_id for businesses that have null currency_id.
     */
    public function up(): void
    {
        $defaultCurrencyId = DB::table('currency')->where('status', 1)->value('currency_id');
        if ($defaultCurrencyId !== null) {
            DB::table('businesses')->whereNull('currency_id')->update(['currency_id' => $defaultCurrencyId]);
        }
    }

    /**
     * Reverse: we cannot reliably restore nulls, so no-op.
     */
    public function down(): void
    {
        //
    }
};
