<?php

namespace App\Livewire\Products;

use App\Imports\ProductsImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class BulkImport extends Component
{
    use WithFileUploads;

    public $file;

    public $importing = false;

    public $importComplete = false;

    public $importedCount = 0;

    public $errors = [];

    public $failures = [];

    public $fileError = null; // Custom error property

    public function updatedFile()
    {
        // Clear previous error
        $this->fileError = null;

        // Validate file immediately when selected
        try {
            $this->validate([
                'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            ], [
                'file.required' => 'Please select a file to import',
                'file.mimes' => 'File must be CSV or Excel format (csv, xlsx, xls)',
                'file.max' => 'File size must not exceed 2MB',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->fileError = $e->validator->errors()->first('file');
            $this->file = null;
        }
    }

    public function import()
    {
        // Clear previous errors
        $this->fileError = null;
        $this->errors = [];
        $this->failures = [];

        // Validate file
        if (! $this->file) {
            $this->fileError = 'Please select a file to import';

            return;
        }

        try {
            $this->validate([
                'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->fileError = $e->validator->errors()->first('file');

            return;
        }

        $this->importing = true;
        $this->importComplete = false;

        try {
            $import = new ProductsImport;
            Excel::import($import, $this->file->getRealPath());

            $this->importedCount = $import->importedCount;
            $this->errors = $import->errors;
            $this->failures = $import->failures;

            if (count($this->errors) === 0 && count($this->failures) === 0) {
                $this->dispatch('notification',
                    message: "Successfully imported {$this->importedCount} products!",
                    type: 'success'
                );
                $this->dispatch('products-imported');
                $this->importComplete = true;
            } else {
                $this->dispatch('notification',
                    message: "Import completed with {count($this->failures)} errors. Check details below.",
                    type: 'warning'
                );
            }
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $this->dispatch('notification',
                message: 'Import failed: '.$e->getMessage(),
                type: 'error'
            );
        } finally {
            $this->importing = false;
        }
    }

    public function downloadTemplate()
    {
        $fileName = 'product_import_template.xlsx';

        $data = [
            [
                'name',
                'brand',
                'category',
                'unit',
                'price',
                'stock',
                'supplier',
                'aliases',
                'low_stock_threshold',
                'critical_stock_threshold',
                'auto_reorder',
                'reorder_quantity',
            ],

            // Sample Data Row 1
            [
                'Cat Tembok 5kg',
                'Avian',
                'Paint',
                'bag',
                85000,
                100,
                'PT Cat Indonesia',
                'Wall paint, Cat dinding, Avian white',
                20,
                10,
                1, // true
                50,
            ],

            // Sample Data Row 2
            [
                'Semen 50kg',
                'Tiga Roda',
                'Cement',
                'bag',
                90000,
                200,
                'PT Semen Indonesia',
                'Cement, Portland cement, Semen TR',
                30,
                15,
                0, // false
                '',
            ],

            // Sample Data Row 3
            [
                'Paku 3 inch',
                '',
                'Hardware',
                'piece',
                500,
                5000,
                '',
                'Nail, Paku besi, Steel nail',
                500,
                200,
                0, // false
                '',
            ],
        ];

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ProductTemplateExport($data),
            $fileName
        );
    }

    public function resetImport()
    {
        $this->reset(['file', 'importing', 'importComplete', 'importedCount', 'errors', 'failures', 'fileError']);
    }

    public function cancel()
    {
        $this->reset();
        $this->dispatch('close-bulk-import');
    }

    public function render()
    {
        return view('livewire.products.bulk-import');
    }
}
