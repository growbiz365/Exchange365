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
        Schema::create('party', function (Blueprint $table) {
            $table->id('party_id');
            $table->string('party_name');
            $table->string('contact_no')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->date('opening_date');
            
            $table->tinyInteger('party_type')->default(1)->comment('1=Khata Party, 2=Other Party');
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['business_id', 'status']);
            $table->index(['business_id', 'party_name']);
        });

        Schema::create('party_opening_balances', function (Blueprint $table) {
            $table->id('party_opening_balance_id');
            $table->foreignId('party_id')->constrained('party', 'party_id')->onDelete('cascade');
            $table->unsignedBigInteger('currency_id');
            $table->tinyInteger('entry_type')->comment('1=Credit (We owe them), 2=Debit (They owe us)');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->timestamps();

            $table->index(['party_id', 'currency_id']);
            
            // Note: Foreign key to currency table commented out as currency table 
            // may use different primary key structure from legacy system
            // $table->foreign('currency_id')->references('currency_id')->on('currency')->onDelete('cascade');
        });

        Schema::create('party_ledger', function (Blueprint $table) {
            $table->id('party_ledger_id');
            $table->foreignId('party_id')->constrained('party', 'party_id')->onDelete('cascade');
            $table->decimal('credit_amount', 15, 2)->default(0);
            $table->decimal('debit_amount', 15, 2)->default(0);
            $table->date('date_added');
            $table->text('details')->nullable();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->unsignedBigInteger('voucher_id');
            $table->string('voucher_type', 100);
            $table->unsignedBigInteger('currency_id');

            
            $table->string('transaction_party', 255)->nullable()->comment('Related party in transaction');
            $table->decimal('rate', 15, 4)->default(1)->comment('Exchange rate');
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['party_id', 'currency_id', 'date_added']);
            $table->index(['voucher_id', 'voucher_type']);
            
            // Note: Foreign key to currency table commented out as currency table 
            // may use different primary key structure from legacy system
            // $table->foreign('currency_id')->references('currency_id')->on('currency')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('party_ledger');
        Schema::dropIfExists('party_opening_balances');
        Schema::dropIfExists('party');
    }
};
