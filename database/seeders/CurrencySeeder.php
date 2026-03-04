<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run()
    {
        $currencies = [
            [
                'currency' => 'Afghanistan Afghani',
                'currency_symbol' => 'AFN',
                'status' => 1,
                'default_currency' => 0,
            ],
            [
                'currency' => 'US Dollar',
                'currency_symbol' => '$',
                'status' => 1,
                'default_currency' => 1, // Set as default
            ],
            [
                'currency' => 'Pakistani Rupee',
                'currency_symbol' => 'Rs',
                'status' => 1,
                'default_currency' => 0,
            ],
            [
                'currency' => 'Euro',
                'currency_symbol' => '€',
                'status' => 1,
                'default_currency' => 0,
            ],
            [
                'currency' => 'British Pound',
                'currency_symbol' => '£',
                'status' => 1,
                'default_currency' => 0,
            ],
            [
                'currency' => 'UAE Dirham',
                'currency_symbol' => 'AED',
                'status' => 1,
                'default_currency' => 0,
            ],
            [
                'currency' => 'Saudi Riyal',
                'currency_symbol' => 'SAR',
                'status' => 1,
                'default_currency' => 0,
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}