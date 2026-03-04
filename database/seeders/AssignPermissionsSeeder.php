<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Fetch existing roles
        $adminRole = Role::where('name', 'Super Admin')->first();
        $userRole = Role::where('name', 'user')->first();

        // Define permissions for each role
        $adminPermissions = [
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

        $userPermissions = [
            'view dashboard',
            'view settings',

            'view user management',

            
           
            

            


            'view subusers',
            'create subusers',
            'edit subusers',
            


            'view businesses',
            
            'edit businesses',
            

           
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

        // Assign permissions to the admin role
        if ($adminRole) {
            foreach ($adminPermissions as $permission) {
                $perm = Permission::firstWhere('name', $permission);
                if ($perm && !$adminRole->hasPermissionTo($perm)) {
                    $adminRole->givePermissionTo($perm);
                }
            }
        }

        // Assign permissions to the user role
        if ($userRole) {
            foreach ($userPermissions as $permission) {
                $perm = Permission::firstWhere('name', $permission);
                if ($perm && !$userRole->hasPermissionTo($perm)) {
                    $userRole->givePermissionTo($perm);
                }
            }
        }
    }
}
