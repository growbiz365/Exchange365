<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_voucher', function (Blueprint $table) {
            $table->id('general_voucher_id');
            $table->date('date_added');
            $table->tinyInteger('entry_type')->comment('1=Credit (deposit to bank), 2=Debit (withdrawal from bank)');
            $table->foreignId('party_id')->constrained('party', 'party_id')->restrictOnDelete();
            
            $table->foreignId('bank_id')->constrained('banks', 'bank_id')->restrictOnDelete();
            $table->decimal('amount', 18, 2);
            $table->text('details')->nullable();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            
            $table->decimal('rate', 15, 4)->default(1)->comment('Exchange rate');
            
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['business_id', 'date_added']);
            $table->index(['bank_id', 'date_added']);
            $table->index(['party_id', 'date_added']);
        });

        Schema::create('general_voucher_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('general_voucher_id')
                ->constrained('general_voucher', 'general_voucher_id')
                ->cascadeOnDelete();
            $table->string('file_title')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_voucher_attachments');
        Schema::dropIfExists('general_voucher');
    }
};
