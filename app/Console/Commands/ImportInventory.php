<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Product;
use App\Models\ProductAlias;
use Illuminate\Support\Facades\DB;

class ImportInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:inventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import initial inventory from Google Sheet (CSV)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Import Process...');

        try {
            // Cleanup
            $this->info('Cleaning old data...');
            
            DB::beginTransaction();
            ProductAlias::query()->delete();
            Product::query()->delete();
            
            // Try reset sequence for sqlite
            try {
                DB::statement("DELETE FROM sqlite_sequence WHERE name='products'");
                DB::statement("DELETE FROM sqlite_sequence WHERE name='product_aliases'");
            } catch (\Exception $e) {
                $this->warn('Could not reset sqlite sequence (ignoring): ' . $e->getMessage());
            }
            DB::commit();

            $this->info('Old data cleared.');
            
            $file = base_path('tests/fixtures/start_inventory.csv');
            if (!file_exists($file)) {
                $this->error("File found not: $file");
                return 1;
            }

            $this->info("Importing from $file...");
            
            $import = new ProductsImport;
            Excel::import($import, $file);
            
            $this->info('Import finished.');
            
            $count = Product::count();
            $aliases = ProductAlias::count();
            
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Products Imported', $count],
                    ['Aliases Generated', $aliases],
                    ['Rows Processed', $import->importedCount],
                ]
            );

            // Validation
            if ($count < 600) {
                 $this->error("Warning: Only $count products imported. Expected ~646.");
                 if (!empty($import->errors)) {
                     foreach($import->errors as $err) $this->error($err);
                 }
                 if (!empty($import->failures)) {
                     $this->error("Validation Failures: " . count($import->failures));
                 }
            } else {
                 $this->info("Success! Inventory populated.");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Fatal Error: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }

        return 0;
    }
}
