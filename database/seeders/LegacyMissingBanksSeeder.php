<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegacyMissingBanksSeeder extends Seeder
{
    /**
     * Add banks 71-74 missing from banks.sql (referenced in bank_ledger).
     * All belong to business_id 46 per ledger entries.
     */
    public function run(): void
    {
        $ids = [71, 72, 73, 74];
        $now = now();

        foreach ($ids as $id) {
            if (DB::table('banks')->where('bank_id', $id)->exists()) {
                continue;
            }
            DB::table('banks')->insert([
                'bank_id' => $id,
                'bank_name' => "Legacy Bank {$id}",
                'currency_id' => 1,
                'account_number' => null,
                'bank_type_id' => 1,
                'opening_balance' => 0,
                'status' => 1,
                'business_id' => 46,
                'user_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('Created missing banks: ' . implode(', ', $ids));
    }
}
