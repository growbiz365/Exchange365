<?php

namespace Database\Seeders;

use App\Models\BankType;
use Illuminate\Database\Seeder;

class BankTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['bank_type' => 'Cash'],
            ['bank_type' => 'Local'],
            ['bank_type' => 'International'],
            ['bank_type' => 'Paypal Accounts'],
        ];

        foreach ($types as $type) {
            BankType::firstOrCreate(
                ['bank_type' => $type['bank_type']],
                $type
            );
        }
    }
}
