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
