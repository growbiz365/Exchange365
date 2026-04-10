<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ensure party_opening_balance_id is AUTO_INCREMENT (matches $table->id() in create_parties_table).
     * Without it, MySQL strict mode raises: Field 'party_opening_balance_id' doesn't have a default value.
     */
    public function up(): void
    {
        if (! in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        if (! Schema::hasTable('party_opening_balances')) {
            return;
        }

        DB::statement('ALTER TABLE `party_opening_balances` MODIFY `party_opening_balance_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        if (! Schema::hasTable('party_opening_balances')) {
            return;
        }

        DB::statement('ALTER TABLE `party_opening_balances` MODIFY `party_opening_balance_id` BIGINT UNSIGNED NOT NULL');
    }
};
