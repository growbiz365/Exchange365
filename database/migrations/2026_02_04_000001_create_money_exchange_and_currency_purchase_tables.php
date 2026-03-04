<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('money_exchange', function (Blueprint $table) {
            $table->id('money_exchange_id');
            $table->date('date_added');

            $table->unsignedBigInteger('from_account_id');
            $table->foreign('from_account_id')->references('bank_id')->on('banks')->restrictOnDelete();

            $table->unsignedBigInteger('to_account_id');
            $table->foreign('to_account_id')->references('bank_id')->on('banks')->restrictOnDelete();
            $table->decimal('debit_amount', 18, 2);
            $table->decimal('credit_amount', 18, 2);
            $table->tinyInteger('transaction_operation')->default(1);
           
            $table->decimal('rate', 15, 4)->default(1);
            $table->text('details')->nullable();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
           
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['business_id', 'date_added']);
            $table->index(['from_account_id', 'date_added']);
            $table->index(['to_account_id', 'date_added']);
        });

        Schema::create('money_exchange_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('money_exchange_id')
                ->constrained('money_exchange', 'money_exchange_id')
                ->cascadeOnDelete();
            $table->string('file_title')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->timestamps();
        });

        Schema::create('currency_purchase', function (Blueprint $table) {
            $table->id('currency_purchase_id');
            $table->unsignedBigInteger('currency_id');
            $table->foreign('currency_id')->references('currency_id')->on('currency')->restrictOnDelete();
            $table->date('date_added');
            $table->decimal('currency_amount', 18, 4);
            $table->decimal('unit_cost', 18, 6);
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->unsignedBigInteger('voucher_id');
            $table->string('voucher_type', 100);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['currency_id', 'date_added']);
            $table->index(['voucher_type', 'voucher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('money_exchange_attachments');
        Schema::dropIfExists('currency_purchase');
        Schema::dropIfExists('money_exchange');
    }
};

