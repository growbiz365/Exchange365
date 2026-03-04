<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id('asset_id');
            $table->unsignedBigInteger('asset_category_id');
            $table->date('date_added');
            $table->unsignedBigInteger('purchase_bank_id')->nullable();
            $table->unsignedBigInteger('purchase_party_id')->nullable();

            
            $table->tinyInteger('purchase_transaction_type')->default(1)->comment('1=Self, 2=Bank, 3=Party');
            $table->string('asset_name');
            $table->decimal('cost_amount', 15, 2);

           
            $table->text('purchase_details')->nullable();
            $table->tinyInteger('sale_transaction_type')->nullable()->comment('1=None, 2=Bank, 3=Party');
           

           
            $table->date('sale_date')->nullable();
            $table->unsignedBigInteger('sale_bank_id')->nullable();
            $table->unsignedBigInteger('sale_party_id')->nullable();
            $table->decimal('sale_amount', 15, 2)->nullable();
           
            $table->text('sale_details')->nullable();
            $table->tinyInteger('asset_status')->default(1)->comment('1=Active, 2=Sold Out');
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
           

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['business_id', 'asset_category_id']);
            $table->index(['business_id', 'asset_status']);
            $table->index(['business_id', 'date_added']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};

