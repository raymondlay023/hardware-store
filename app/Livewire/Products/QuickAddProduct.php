<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Supplier;
use Livewire\Attributes\Validate;
use Livewire\Component;

class QuickAddProduct extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:100')]
    public $category = '';

    #[Validate('required|numeric|min:0')]
    public $price = '';

    #[Validate('required|integer|min:0')]
    public $current_stock = 0;

    // Pre-filled smart defaults
    public $unit = 'piece';

    public $supplier_id = '';

    public $low_stock_threshold = 10;

    public $critical_stock_threshold = 5;

    public function save()
    {
        $this->validate();

        Product::create([
            'name' => $this->name,
            'category' => $this->category,
            'unit' => $this->unit,
            'price' => $this->price,
            'current_stock' => $this->current_stock,
            'supplier_id' => $this->supplier_id ?: null,
            'low_stock_threshold' => $this->low_stock_threshold,
            'critical_stock_threshold' => $this->critical_stock_threshold,
            'auto_reorder_enabled' => false,
            'reorder_quantity' => null,
        ]);

        $this->dispatch('notification', message: 'Product added! Add another or close.', type: 'success');
        $this->dispatch('quick-product-created');

        // Reset only input fields, keep modal open for rapid entry
        $this->reset(['name', 'price', 'current_stock']);
        // Keep category and unit for faster consecutive entries
    }

    public function saveAndClose()
    {
        $this->save();
        $this->dispatch('close-quick-add');
    }

    public function cancel()
    {
        $this->reset();
        $this->dispatch('close-quick-add');
    }

    public function render()
    {
        $suppliers = Supplier::all();
        $recentCategories = Product::select('category')
            ->distinct()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->pluck('category');

        return view('livewire.products.quick-add-product', [
            'suppliers' => $suppliers,
            'recentCategories' => $recentCategories,
        ]);
    }
}
