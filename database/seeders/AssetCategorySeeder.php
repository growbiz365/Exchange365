<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssetCategorySeeder extends Seeder
{
    /**
     * Seed default asset categories.
     */
    public function run(): void
    {
        $business = Business::first();
        $user = User::first();

        if (!$business || !$user) {
            return;
        }

        $names = ['Property', 'Vehicles', 'Gold', 'Goods'];

        foreach ($names as $name) {
            AssetCategory::firstOrCreate(
                [
                    'business_id' => $business->id,
                    'asset_category' => strtoupper($name),
                ],
                [
                    'status' => 1,
                    'user_id' => $user->id,
                ]
            );
        }
    }
}

