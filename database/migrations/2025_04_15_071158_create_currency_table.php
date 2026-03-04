<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->id('currency_id');
            $table->string('currency', 100);
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->tinyInteger('default_currency')->default(0)->comment('1=Default, 0=Not Default');
           
            $table->string('currency_symbol', 10)->nullable();
            $table->timestamps();
        });

        // Note: Currencies are seeded via CurrencySeeder
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency');
    }
};
