<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Country;
use App\Models\Timezone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegacyBusinessIdsSeeder extends Seeder
{
    /**
     * Create businesses with specific IDs required for legacy party import.
     * Old user_id values become business_id in the new party table.
     */
    public function run(): void
    {
        $ids = [16, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 52, 53, 54, 56, 57];

        $pkr = Currency::where('currency', 'Pakistani Rupee')->first();
        $pakistan = Country::where('country_code', 'PK')->first();
        $timezone = Timezone::where('timezone_name', 'Asia/Karachi')->first();

        $countryId = $pakistan?->id;
        $timezoneId = $timezone?->id;
        $currencyId = $pkr?->currency_id;

        $now = now();
        $rows = [];

        foreach ($ids as $id) {
            if (DB::table('businesses')->where('id', $id)->exists()) {
                continue;
            }
            $rows[] = [
                'id' => $id,
                'business_name' => "Legacy Business {$id}",
                'owner_name' => "Owner {$id}",
                'country_id' => $countryId,
                'timezone_id' => $timezoneId,
                'currency_id' => $currencyId,
                'date_format' => 'Y-m-d',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($rows)) {
            DB::table('businesses')->insert($rows);
            $this->command->info('Created ' . count($rows) . ' legacy businesses with IDs: ' . implode(', ', array_column($rows, 'id')));
        }
    }
}
