<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Supplier;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditProduct extends Component
{
    public $productId = null;
    public $product = null;

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

    #[On('edit-product')]
    public function editProduct($id)
    {
        $this->productId = $id;
        $this->product = Product::find($id);
        
        if ($this->product) {
            $this->name = $this->product->name;
            $this->category = $this->product->category;
            $this->unit = $this->product->unit;
            $this->price = $this->product->price;
            $this->supplier_id = $this->product->supplier_id;
            $this->low_stock_threshold = $this->product->low_stock_threshold;
            $this->critical_stock_threshold = $this->product->critical_stock_threshold;
            $this->auto_reorder_enabled = $this->product->auto_reorder_enabled;
            $this->reorder_quantity = $this->product->reorder_quantity;            
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->product) {
            $this->product->update([
                'name' => $this->name,
                'category' => $this->category,
                'unit' => $this->unit,
                'price' => $this->price,
                'supplier_id' => $this->supplier_id ?: null,
                'low_stock_threshold' => $this->low_stock_threshold,
                'critical_stock_threshold' => $this->critical_stock_threshold,
                'auto_reorder_enabled' => $this->auto_reorder_enabled,
                'reorder_quantity' => $this->reorder_quantity,
            ]);
        }

        $this->reset();
        $this->dispatch('product-updated');
    }

    public function cancel()
    {
        $this->reset();
    }

    public function render()
    {
        $suppliers = Supplier::all();
        return view('livewire.products.edit-product', ['suppliers' => $suppliers]);
    }
}
