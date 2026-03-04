<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id('bank_id');
            $table->string('bank_name');
            $table->unsignedBigInteger('currency_id');
            $table->foreign('currency_id')->references('currency_id')->on('currency')->restrictOnDelete();
            $table->string('account_number', 100)->nullable();
            $table->unsignedBigInteger('bank_type_id');
            $table->foreign('bank_type_id')->references('bank_type_id')->on('bank_type')->restrictOnDelete();
            $table->decimal('opening_balance', 18, 2)->default(0);
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
           
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
