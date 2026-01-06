<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking for duplicate bar codes in products table...\n\n";

// Get all products with their barcodes
$products = DB::table('products')
    ->select('id', 'name', 'barcode')
    ->get();

echo "Total products: " . $products->count() . "\n";

// Group by barcode
$grouped = $products->groupBy('barcode');

// Find duplicates
$duplicates = $grouped->filter(function($items) {
    return $items->count() > 1;
});

echo "Duplicate barcode groups: " . $duplicates->count() . "\n\n";

// Show details of duplicates
foreach ($duplicates as $barcode => $items) {
    $displayBarcode = $barcode ?: '[NULL]';
    echo "Barcode: {$displayBarcode} - {$items->count()} products\n";
    foreach ($items as $product) {
        echo "  - ID: {$product->id}, Name: {$product->name}\n";
    }
    echo "\n";
}

if ($duplicates->count() == 0) {
    echo "✓ No duplicate barcodes found! Safe to add unique constraint.\n";
} else {
    echo "✗ Found " . $duplicates->count() . " duplicate barcode groups. Need to fix before adding unique constraint.\n";
}
