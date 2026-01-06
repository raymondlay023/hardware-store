<?php

use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Product;

try {
    echo "Starting Import...\n";
    $startCount = Product::count();
    
    Excel::import(new ProductsImport, base_path('tests/fixtures/start_inventory.csv'));
    
    $endCount = Product::count();
    echo "Import Complete.\n";
    echo "Products before: $startCount\n";
    echo "Products after: $endCount\n";
    echo "Imported: " . ($endCount - $startCount) . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
