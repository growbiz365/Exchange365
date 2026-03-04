<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_ledger', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_ledger', 'details')) {
                $table->text('details')->nullable()->after('withdrawal_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bank_ledger', function (Blueprint $table) {
            if (Schema::hasColumn('bank_ledger', 'details')) {
                $table->dropColumn('details');
            }
        });
    }
};

