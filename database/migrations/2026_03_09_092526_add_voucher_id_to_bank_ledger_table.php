<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_ledger', function (Blueprint $table) {
            $table->unsignedBigInteger('voucher_id')->nullable()->after('bank_id');
            $table->string('voucher_type')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bank_ledger', function (Blueprint $table) {
            $table->dropColumn('voucher_id');
        });
    }
};
