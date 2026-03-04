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
        Schema::create('party_transfer', function (Blueprint $table) {
            $table->id('party_transfer_id');
            $table->date('date_added');
            $table->foreignId('debit_party')->constrained('party', 'party_id')->onDelete('restrict');
            $table->decimal('debit_amount', 15, 2);
            $table->unsignedBigInteger('debit_currency_id');
            $table->foreign('debit_currency_id')->references('currency_id')->on('currency')->onDelete('restrict');
           // Credit Party
           $table->foreignId('credit_party')->constrained('party', 'party_id')->onDelete('restrict');
           $table->decimal('credit_amount', 15, 2);
           $table->tinyInteger('transaction_operation')->default(1);
            $table->unsignedBigInteger('credit_currency_id');
            $table->foreign('credit_currency_id')->references('currency_id')->on('currency')->onDelete('restrict');
            $table->decimal('rate', 15, 4)->default(1)->comment('Exchange rate');
           
            
            
            
            // Debit Party
            
            $table->text('details')->nullable();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['business_id', 'date_added']);
            $table->index(['credit_party', 'date_added']);
            $table->index(['debit_party', 'date_added']);
        });

        Schema::create('party_transfer_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('party_transfer_id')->constrained('party_transfer', 'party_transfer_id')->onDelete('cascade');
            $table->string('file_title')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('party_transfer_attachments');
        Schema::dropIfExists('party_transfer');
    }
};
