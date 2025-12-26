<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows, SkipsOnError, SkipsOnFailure
{
    public $importedCount = 0;
    public $errors = [];
    public $failures = [];

    public function model(array $row)
    {
        // Find supplier by name if provided
        $supplierId = null;
        if (!empty($row['supplier'])) {
            $supplier = Supplier::where('name', 'like', '%' . trim($row['supplier']) . '%')->first();
            $supplierId = $supplier?->id;
        }

        $this->importedCount++;

        return new Product([
            'name' => trim($row['name']),
            'category' => trim($row['category']),
            'unit' => strtolower(trim($row['unit'] ?? 'piece')),
            'price' => floatval($row['price']),
            'current_stock' => intval($row['stock'] ?? 0),
            'supplier_id' => $supplierId,
            'low_stock_threshold' => intval($row['low_stock_threshold'] ?? 10),
            'critical_stock_threshold' => intval($row['critical_stock_threshold'] ?? 5),
            'auto_reorder_enabled' => filter_var($row['auto_reorder'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'reorder_quantity' => !empty($row['reorder_quantity']) ? intval($row['reorder_quantity']) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'unit' => 'required|string|in:piece,bag,box,meter,kg',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'supplier' => 'nullable|string',
            'low_stock_threshold' => 'nullable|integer|min:1',
            'critical_stock_threshold' => 'nullable|integer|min:1',
            'auto_reorder' => 'nullable|boolean',
            'reorder_quantity' => 'nullable|integer|min:1',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Product name is required',
            'category.required' => 'Category is required',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'unit.in' => 'Unit must be one of: piece, bag, box, meter, kg',
        ];
    }

    public function onError(Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->failures[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ];
        }
    }
}
