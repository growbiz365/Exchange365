<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            if (! Schema::hasColumn('activity_log', 'business_id')) {
                $table->unsignedBigInteger('business_id')->nullable()->after('causer_id');
                $table->index('business_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            if (Schema::hasColumn('activity_log', 'business_id')) {
                $table->dropIndex(['business_id']);
                $table->dropColumn('business_id');
            }
        });
    }
};
