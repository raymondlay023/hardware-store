<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDatabaseEncoding extends Command
{
    protected $signature = 'db:fix-encoding';
    protected $description = 'Convert database to UTF-8 encoding';

    public function handle()
    {
        $tables = [
            'sales',
            'sale_items',
            'products',
            'suppliers',
            'purchases',
            'purchase_items',
            'users',
            'roles',
        ];

        foreach ($tables as $table) {
            try {
                DB::statement("ALTER TABLE `$table` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $this->info("✅ Converted $table to utf8mb4");
            } catch (\Exception $e) {
                $this->error("❌ Error converting $table: " . $e->getMessage());
            }
        }

        $this->info("\n✅ Database encoding fixed!");
    }
}
