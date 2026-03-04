<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Currency;
use App\Models\Country;
use App\Models\City;
use App\Models\Timezone;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run()
    {
        // Get necessary IDs - using correct column names
        $pkr = Currency::where('currency', 'Pakistani Rupee')->first();
        $pakistan = Country::where('country_code', 'PK')->first();
        $karachi = City::where('name', 'Karachi')->first();
        $timezone = Timezone::where('timezone_name', 'Asia/Karachi')->first();

        // Check if required data exists
        if (!$pkr || !$pakistan || !$karachi || !$timezone) {
            $this->command->warn('Skipping BusinessSeeder: Required master data not found.');
            return;
        }

        $businesses = [
            [
                'business_name' => 'Grow Business 365',
                'owner_name' => 'Ghufran Javed',
                'cnic' => '35201-1234567-2',
                'contact_no' => '+92-300-1234568',
                'email' => 'ghufran_javed@yahoo.com',
                'address' => '123 Main Street, Gulberg III, Peshawar, Pakistan',
                'country_id' => $pakistan->id,
                'timezone_id' => $timezone->id,
                'currency_id' => $pkr->currency_id,
                'date_format' => 'Y-m-d',
            ],
        ];

        foreach ($businesses as $business) {
            Business::create($business);
        }
    }
}
