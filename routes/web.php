<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\TimezoneController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\SubUserController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\PartyTransferController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BankTransferController;
use App\Http\Controllers\GeneralVoucherController;
use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

// Settings
Route::get('/settings', function () {
    return view('settings');
})->middleware(['auth', 'verified', 'can:view settings'])->name('settings');

Route::middleware('auth')->group(function () {
    // API routes for searchable dropdown
    Route::prefix('api')->group(function () {
        // Reserved for future API endpoints
    });
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Business activation
    Route::get('/businesses/activate/{businessId}', [BusinessController::class, 'setActiveBusiness'])->name('businesses.activate');



   


    // Permissions
    Route::middleware('can:view permissions')->group(function () {
        Route::resource('permissions', PermissionController::class)->except('show');
    });

    // Users
    Route::middleware('can:view users')->group(function () {
        Route::resource('users', UserController::class)->except('show');
        Route::post('users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
        Route::post('users/{user}/unsuspend', [UserController::class, 'unsuspend'])->name('users.unsuspend');
    });

    // Businesses
    Route::middleware('can:view businesses')->group(function () {
        Route::resource('businesses', BusinessController::class);
        Route::post('businesses/{business}/suspend', [BusinessController::class, 'suspend'])->name('businesses.suspend');
        Route::post('businesses/{business}/unsuspend', [BusinessController::class, 'unsuspend'])->name('businesses.unsuspend');
        Route::post('businesses/{business}/clear-all-data', [BusinessController::class, 'clearAllData'])->name('businesses.clear-all-data');
    });

    Route::get('businesses/{business}/edit-store-info', [BusinessController::class, 'editStoreInfo'])->name('businesses.editStoreInfo');
    Route::put('businesses/{business}/update-store-info', [BusinessController::class, 'updateStoreInfo'])->name('businesses.updateStoreInfo');

    // Roles
    Route::middleware('can:view roles')->group(function () {
        Route::resource('roles', RoleController::class)->except('show');
    });

    // Countries
    Route::middleware('can:view countries')->group(function () {
        Route::resource('countries', CountryController::class);
    });

    // Timezones
    Route::middleware('can:view timezones')->group(function () {
        Route::resource('timezones', TimezoneController::class);
    });

    // Currencies
    Route::middleware('can:view currencies')->group(function () {
        Route::resource('currencies', CurrencyController::class);
    });

    // Cities
    Route::middleware('can:view cities')->group(function () {
        Route::resource('cities', CityController::class);
    });

    // Asset Categories & Assets
    Route::middleware('auth')->group(function () {
        // Assets dashboard
        Route::get('assets/dashboard', [AssetController::class, 'dashboard'])->name('assets.dashboard');

        // Asset Categories CRUD
        Route::resource('asset-categories', AssetCategoryController::class);

        // Assets CRUD + extra actions
        Route::get('assets/{asset}/print', [AssetController::class, 'print'])->name('assets.print');
        Route::get('assets/{asset}/sell', [AssetController::class, 'sellForm'])->name('assets.sell.form');
        Route::post('assets/{asset}/sell', [AssetController::class, 'sell'])->name('assets.sell');
        Route::resource('assets', AssetController::class);
    });





    Route::middleware('can:view subusers')->group(function () {
        Route::resource('subusers', SubUserController::class);
        Route::post('subusers/{subuser}/suspend', [SubUserController::class, 'suspend'])->name('subusers.suspend');
        Route::post('subusers/{subuser}/unsuspend', [SubUserController::class, 'unsuspend'])->name('subusers.unsuspend');
    });

    // ========================================
    // PARTY MODULE - ExchangeHub
    // ========================================
    Route::middleware('can:view parties')->group(function () {
        Route::get('parties/dashboard', [PartyController::class, 'dashboard'])->name('parties.dashboard');
        // Party CRUD
        Route::resource('parties', PartyController::class);
        
        // Party Reports
        Route::get('/parties-ledger', [PartyController::class, 'ledger'])->name('parties.ledger');
        Route::get('/parties-balances', [PartyController::class, 'balances'])->name('parties.balances');
        Route::get('/parties-currency', [PartyController::class, 'currencyBreakdown'])->name('parties.currency');
        Route::get('parties/{party}/balance', [PartyController::class, 'balance'])->name('parties.balance');
    });

    // ========================================
    // PARTY TRANSFER MODULE - ExchangeHub
    // ========================================
    Route::middleware('can:view parties')->group(function () {
        Route::resource('party-transfers', PartyTransferController::class);
    });
    Route::delete('/party-transfers/attachments/{attachment}', [PartyTransferController::class, 'deleteAttachment'])
        ->middleware('auth')
        ->name('party-transfers.attachments.delete');

    // ========================================
    // BANKS MODULE - ExchangeHub
    // ========================================
    Route::middleware('auth', 'can:view banks')->group(function () {
        Route::get('banks/dashboard', [BankController::class, 'dashboard'])->name('banks.dashboard');
        Route::get('banks', [BankController::class, 'index'])->name('banks.index');
        Route::get('banks/create', [BankController::class, 'create'])->name('banks.create');
        Route::post('banks', [BankController::class, 'store'])->name('banks.store');
        Route::get('banks/ledger', [BankController::class, 'ledger'])->name('banks.ledger');
        Route::get('banks/balances', [BankController::class, 'bankBalances'])->name('banks.balances');
        Route::get('banks/currency-balances', [BankController::class, 'currencyBalances'])->name('banks.currency-balances');
        Route::get('banks/{bank}/balance', [BankController::class, 'balance'])->name('banks.balance');
        Route::get('banks/{bank}/edit', [BankController::class, 'edit'])->name('banks.edit');
        Route::put('banks/{bank}', [BankController::class, 'update'])->name('banks.update');

        Route::resource('money-exchanges', \App\Http\Controllers\MoneyExchangeController::class)
            ->names('money-exchanges');
        Route::delete('money-exchanges/attachments/{attachment}', [\App\Http\Controllers\MoneyExchangeController::class, 'deleteAttachment'])
            ->name('money-exchanges.attachments.delete');
    });

    // Bank Transfers
    Route::middleware('auth')->group(function () {
        Route::get('bank-transfers/{bankTransfer}/print', [BankTransferController::class, 'print'])->name('bank-transfers.print');
        Route::resource('bank-transfers', BankTransferController::class);
    });
    Route::delete('bank-transfers/attachments/{attachment}', [BankTransferController::class, 'deleteAttachment'])
        ->middleware('auth')
        ->name('bank-transfers.attachments.delete');

    // General Vouchers
    Route::middleware('auth')->group(function () {
        Route::get('general-vouchers/{generalVoucher}/print', [GeneralVoucherController::class, 'print'])->name('general-vouchers.print');
        Route::resource('general-vouchers', GeneralVoucherController::class);
    });

    // Purchase
    Route::middleware('auth')->group(function () {
        Route::get('purchases/dashboard', [PurchaseController::class, 'dashboard'])->name('purchases.dashboard');
        Route::get('purchases/{purchase}/print', [PurchaseController::class, 'print'])->name('purchases.print');
        Route::resource('purchases', PurchaseController::class);
    });

    // Sales
    Route::middleware('auth')->group(function () {
        Route::get('sales/dashboard', [SalesController::class, 'dashboard'])->name('sales.dashboard');
        Route::get('sales/{sale}/print', [SalesController::class, 'print'])->name('sales.print');
        Route::resource('sales', SalesController::class);
    });

    // Reports
    Route::middleware('auth')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/currency-summary', [ReportController::class, 'currencySummary'])->name('reports.currency-summary');
        Route::get('reports/activity-log', [ReportController::class, 'activityLog'])->name('reports.activity-log');
    });

    Route::delete('general-vouchers/attachments/{attachment}', [GeneralVoucherController::class, 'deleteAttachment'])
        ->middleware('auth')
        ->name('general-vouchers.attachments.delete');

    // File Downloads (serve attachments from storage; avoids 404 when storage link missing)
    Route::get('files/bank-transfer-attachments/{attachment}', [FileController::class, 'downloadBankTransferAttachment'])
        ->middleware('auth')
        ->name('files.bank-transfer-attachments.download');
    Route::get('files/general-voucher-attachments/{attachment}', [FileController::class, 'downloadGeneralVoucherAttachment'])
        ->middleware('auth')
        ->name('files.general-voucher-attachments.download');
    Route::get('files/party-transfer-attachments/{attachment}', [FileController::class, 'downloadPartyTransferAttachment'])
        ->middleware('auth')
        ->name('files.party-transfer-attachments.download');
    Route::get('files/money-exchange-attachments/{attachment}', [FileController::class, 'downloadMoneyExchangeAttachment'])
        ->middleware('auth')
        ->name('files.money-exchange-attachments.download');
});

// Search endpoints (outside auth for now)
Route::get('city/search', [CityController::class, 'search'])->name('cities.search');
Route::get('country/search', [CountryController::class, 'search'])->name('countries.search');
Route::get('timezone/search', [TimezoneController::class, 'search'])->name('timezones.search');
Route::get('currency/search', [CurrencyController::class, 'search'])->name('currencies.search');
Route::get('users/search', [UserController::class, 'search'])->name('users.search');
Route::get('businesses/search', [BusinessController::class, 'search'])->name('businesses.search');

require __DIR__ . '/auth.php';
