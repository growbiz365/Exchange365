<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id('sales_id');
            $table->foreignId('party_id')->constrained('party', 'party_id')->restrictOnDelete();
            $table->date('date_added');
            $table->decimal('currency_amount', 18, 2)->comment('Bank side - withdrawal');
            $table->foreignId('bank_id')->constrained('banks', 'bank_id')->restrictOnDelete();
            $table->decimal('rate', 15, 4)->default(1)->comment('Exchange rate');
           $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
           $table->decimal('party_amount', 18, 2)->comment('Party side - debit to party');
           $table->text('details')->nullable();
          
           $table->unsignedBigInteger('party_currency_id')->comment('Currency for party side amount');
            $table->tinyInteger('transaction_operation')->default(1);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['business_id', 'date_added']);
            $table->index(['bank_id', 'date_added']);
            $table->index(['party_id', 'date_added']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
