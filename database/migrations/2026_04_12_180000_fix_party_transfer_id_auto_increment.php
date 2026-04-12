<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Repair installs where `party_transfer_id` exists but is not AUTO_INCREMENT,
     * which causes SQLSTATE[HY000] 1364 on insert.
     */
    public function up(): void
    {
        if (!Schema::hasTable('party_transfer')) {
            return;
        }

        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        $database = $connection->getDatabaseName();

        $column = $connection->selectOne(
            'SELECT COLUMN_KEY, EXTRA FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$database, 'party_transfer', 'party_transfer_id']
        );

        if (!$column) {
            return;
        }

        if (stripos((string) ($column->EXTRA ?? ''), 'auto_increment') !== false) {
            return;
        }

        $primaryColumns = $connection->select(
            'SELECT COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = \'PRIMARY\'
             ORDER BY ORDINAL_POSITION',
            [$database, 'party_transfer']
        );

        $primaryNames = array_map(fn ($row) => $row->COLUMN_NAME, $primaryColumns);

        if (($column->COLUMN_KEY ?? '') === 'PRI' || $primaryNames === ['party_transfer_id']) {
            DB::statement(
                'ALTER TABLE `party_transfer` MODIFY `party_transfer_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT'
            );

            return;
        }

        if ($primaryNames === []) {
            DB::statement(
                'ALTER TABLE `party_transfer` MODIFY `party_transfer_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY'
            );
        }
    }

    public function down(): void
    {
        // Prior column definitions are unknown; cannot safely reverse.
    }
};
