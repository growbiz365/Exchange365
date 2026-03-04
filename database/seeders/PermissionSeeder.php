<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a set of default permissions
        $permissions = [
            'view dashboard',
            'view settings',

            'view user management',

            'view permissions',
            'edit permissions',
            'create permissions',
            'delete permissions',

            'view users',
            'create users',
            'edit users',
            

            'view roles',
            'create roles',
            'edit roles',
            

            'view countries',
            'edit countries',
            'create countries',
            'delete countries',

            
            'view timezones',
            'create timezones',
            'edit timezones',
            'delete timezones',

            'view currencies',
            'create currencies',
            'edit currencies',
            'delete currencies',

            'view cities',
            'create cities',
            'edit cities',
            'delete cities',

            'view subusers',
            'create subusers',
            'edit subusers',
            'delete subusers',


            'view businesses',
            'create businesses',
            'edit businesses',
            'delete businesses',

            'view finance',

            


            
            'view parties',
            'create parties',
            'edit parties',
            'delete parties',
            'view parties ledger',
            'view parties balances',
            'view parties transfers',
            'create parties transfers',
            'edit parties transfers',
            'delete parties transfers',



            'view banks',
            'create banks',
            'edit banks',
            'delete banks',
            'view bank-transfers',
            'create bank-transfers',
            'edit bank-transfers',
            'delete bank-transfers',
            'view bank ledger',
            'view bank balances',


            
            'view general vouchers',
            'create general vouchers',
            'edit general vouchers',
            'delete general vouchers',

            'view purchases',
            'create purchases',
            'edit purchases',
            'cancel purchases',

           
            
            'view sales',
            'create sales',
            'edit sales',
            'cancel sales',

            

            
            
            
            



























        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web' // Add this line
            ]);
        }

        // Optionally, use the factory to generate additional random permissions
        //Permission::factory(1)->create();
    }
}
