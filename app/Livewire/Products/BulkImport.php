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
        if (!$this->file) {
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
            $import = new ProductsImport();
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
                message: 'Import failed: ' . $e->getMessage(), 
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
        // Headers
        ['name', 'category', 'unit', 'price', 'stock', 'supplier', 'low_stock_threshold', 'critical_stock_threshold', 'auto_reorder', 'reorder_quantity'],
        
        // Sample Data Row 1
        ['Cement Bag 50kg', 'Cement', 'bag', 90000, 100, 'PT Semen Indonesia', 20, 10, 'true', 50],
        
        // Sample Data Row 2
        ['Steel Rebar 10mm', 'Steel', 'piece', 45000, 200, 'PT Krakatau Steel', 30, 15, 'false', ''],
        
        // Sample Data Row 3
        ['Red Brick', 'Bricks', 'piece', 1500, 5000, '', 500, 200, 'false', ''],
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
