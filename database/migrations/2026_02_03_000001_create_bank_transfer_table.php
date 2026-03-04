<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_transfer', function (Blueprint $table) {
            $table->id('bank_transfer_id');
           
           

            $table->unsignedBigInteger('from_account_id');
            $table->foreign('from_account_id')->references('bank_id')->on('banks')->restrictOnDelete();

            $table->unsignedBigInteger('to_account_id');
            $table->foreign('to_account_id')->references('bank_id')->on('banks')->restrictOnDelete();

            $table->decimal('amount', 18, 2);
            $table->text('details')->nullable();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date_added');
            $table->timestamps();

            $table->index(['business_id', 'date_added']);
            $table->index(['from_account_id', 'date_added']);
            $table->index(['to_account_id', 'date_added']);
        });

        Schema::create('bank_transfer_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_transfer_id')
                ->constrained('bank_transfer', 'bank_transfer_id')
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
        Schema::dropIfExists('bank_transfer_attachments');
        Schema::dropIfExists('bank_transfer');
    }
};

