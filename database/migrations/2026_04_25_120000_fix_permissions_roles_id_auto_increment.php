<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Repair MySQL/MariaDB installs where `id` is not AUTO_INCREMENT on
     * Spatie permission tables, which causes SQLSTATE[HY000] 1364 on insert.
     */
    public function up(): void
    {
        $tableNames = config('permission.table_names', []);
        if (empty($tableNames)) {
            return;
        }

        $connection = Schema::getConnection();
        if (!in_array($connection->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        foreach (['permissions', 'roles'] as $key) {
            if (empty($tableNames[$key])) {
                continue;
            }
            $this->fixAutoIncrementId($connection, (string) $tableNames[$key]);
        }
    }

    public function down(): void
    {
        // Prior column definitions are unknown; cannot safely reverse.
    }

    private function fixAutoIncrementId($connection, string $table): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $database = $connection->getDatabaseName();

        $column = $connection->selectOne(
            'SELECT COLUMN_KEY, EXTRA FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$database, $table, 'id']
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
            [$database, $table]
        );

        $primaryNames = array_map(static fn ($row) => $row->COLUMN_NAME, $primaryColumns);

        if (($column->COLUMN_KEY ?? '') === 'PRI' || $primaryNames === ['id']) {
            DB::statement('ALTER TABLE `'.$table.'` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');

            return;
        }

        if ($primaryNames === []) {
            DB::statement('ALTER TABLE `'.$table.'` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
        }
    }
};
