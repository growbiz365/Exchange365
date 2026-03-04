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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
        
            // Business profile
            $table->string('business_name');
            $table->string('owner_name');
            $table->string('cnic')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('email')->nullable()->unique();
            $table->text('address')->nullable();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('timezone_id')->nullable()->constrained()->nullOnDelete();
            // Reference the currency table's currency_id (not currencies.id)
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('currency_id')->on('currency')->nullOnDelete();
            $table->string('date_format')->nullable()->comment('Allowed: Y-m-d, d/m/Y, m/d/Y');
            
            // Suspension fields
            $table->boolean('is_suspended')->default(false);
            $table->timestamp('suspended_at')->nullable();
            $table->text('suspension_reason')->nullable();
        
            // Store Info (nullable)
            $table->string('store_name')->nullable();
            $table->string('store_license_number')->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->string('issuing_authority')->nullable();
            $table->string('store_type')->nullable();
            $table->string('ntn')->nullable();
            $table->string('strn')->nullable();
            $table->string('store_phone')->nullable();
            $table->string('store_email')->nullable();
            $table->text('store_address')->nullable();
            $table->foreignId('store_city_id')->nullable()->constrained('cities')->nullOnDelete();      // references id on cities
            $table->foreignId('store_country_id')->nullable()->constrained('countries')->nullOnDelete();   // references id on countries
      
            $table->string('store_postal_code')->nullable();
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
