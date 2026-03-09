<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('bank_ledger', 'business_id')) {
            Schema::table('bank_ledger', function (Blueprint $table) {
                $table->unsignedBigInteger('business_id')->nullable()->after('bank_ledger_id');
            });
        }

        if (!Schema::hasColumn('party_ledger', 'business_id')) {
            Schema::table('party_ledger', function (Blueprint $table) {
                $table->unsignedBigInteger('business_id')->nullable()->after('party_ledger_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('bank_ledger', function (Blueprint $table) {
            $table->dropColumn('business_id');
        });

        Schema::table('party_ledger', function (Blueprint $table) {
            $table->dropColumn('business_id');
        });
    }
};
