<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Supplier;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateProduct extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:100')]
    public $category = '';

    #[Validate('required|string|max:50')]
    public $unit = '';

    #[Validate('required|numeric|min:0')]
    public $price = '';

    #[Validate('nullable|exists:suppliers,id')]
    public $supplier_id = '';

    #[Validate('nullable|integer|min:1')]
    public $low_stock_threshold = 10;

    #[Validate('nullable|integer|min:1')]
    public $critical_stock_threshold = 5;

    public $auto_reorder_enabled = false;

    #[Validate('nullable|integer|min:1')]
    public $reorder_quantity = '';

    public function save()
    {
        $this->validate();

        Product::create([
            'name' => $this->name,
            'category' => $this->category,
            'unit' => $this->unit,
            'price' => $this->price,
            'supplier_id' => $this->supplier_id ?: null,
            'current_stock' => 0,
            'low_stock_threshold' => $this->low_stock_threshold,
            'critical_stock_threshold' => $this->critical_stock_threshold,
            'auto_reorder_enabled' => $this->auto_reorder_enabled,
            'reorder_quantity' => $this->reorder_quantity,
        ]);

        $this->reset();
        $this->dispatch('product-created');
    }

     public function cancel()
    {
        $this->reset();
        $this->dispatch('close-create-form');
    }

    public function render()
    {
        $suppliers = Supplier::all();
        return view('livewire.products.create-product', ['suppliers' => $suppliers]);
    }
}
