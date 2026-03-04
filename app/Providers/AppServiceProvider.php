<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Observers\BusinessObserver;
use App\Http\View\Composers\BusinessComposer;
use App\Models\Business;
use App\Models\SaleInvoice;
use App\Models\Purchase;
use App\Models\Party;
use App\Models\BankTransfer;
use App\Models\Expense;
use App\Models\OtherIncome;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $this->app->singleton(Gate::class, function ($app) {
        //     return $app->make(Gate::class);
        // });

        // Register date helper functions
        require_once app_path('Helpers/DateHelper.php');

        // Register custom Blade directives for business date formatting
        Blade::directive('businessDate', function ($expression) {
            return "<?php echo formatBusinessDate($expression); ?>";
        });

        Blade::directive('businessDateTime', function ($expression) {
            return "<?php echo formatBusinessDateTime($expression); ?>";
        });

        // Register custom Blade directives for business currency formatting
        Blade::directive('currency', function ($expression) {
            return "<?php echo formatBusinessCurrency($expression); ?>";
        });

        Blade::directive('amount', function ($expression) {
            return "<?php echo formatBusinessAmount($expression); ?>";
        });

        // Share business settings with all views
        View::composer('*', BusinessComposer::class);

        // Register BusinessObserver
        Business::observe(BusinessObserver::class);

        // Define morph map for polymorphic relationships
        Relation::morphMap([
            'Expense' => \App\Models\Expense::class,
            'BankTransfer' => \App\Models\BankTransfer::class,
            'General Voucher' => \App\Models\GeneralVoucher::class,
            'PartyTransfer' => \App\Models\PartyTransfer::class,
            'Party OB' => \App\Models\Party::class,
            'Bank OB' => \App\Models\Bank::class,
            'Bank Transfer' => \App\Models\BankTransfer::class,
        ]);

        // Activity logging is handled by spatie/laravel-activitylog via LogsActivity trait on models.
    }
}
