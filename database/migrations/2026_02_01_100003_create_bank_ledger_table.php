<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_ledger', function (Blueprint $table) {
            $table->id('bank_ledger_id');
            $table->unsignedBigInteger('bank_id');
            $table->date('date_added');
            $table->foreign('bank_id')->references('bank_id')->on('banks')->cascadeOnDelete();
            
            
            $table->decimal('deposit_amount', 18, 2)->default(0);
            $table->decimal('withdrawal_amount', 18, 2)->default(0);
           
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('voucher_type', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_ledger');
    }
};
