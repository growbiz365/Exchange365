<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Business;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\OtherIncome::class => \App\Policies\OtherIncomePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Module gate - simplified to check user permissions only
        // Package/Module functionality has been removed from the system
        Gate::define('module', function ($user, $moduleName) {
            $activeBusinessId = session('active_business');
            
            \Log::info('Gate:module called', [
                'user_id' => $user->id,
                'module' => $moduleName,
                'active_business' => $activeBusinessId
            ]);

            // If there's an active business, verify user has access to it
            if ($activeBusinessId) {
                $business = \App\Models\Business::find($activeBusinessId);
                if (!$business || !$user->businesses()->where('business_id', $activeBusinessId)->exists()) {
                    \Log::warning('Business not found or user not attached', [
                        'business_found' => !!$business,
                        'user_attached' => $user->businesses()->where('business_id', $activeBusinessId)->exists()
                    ]);
                    return false;
                }
            }

            // Check user permission
            $result = $user->can($moduleName);
            \Log::info('User permission check', [
                'result' => $result
            ]);
            return $result;
        });
    }
}
