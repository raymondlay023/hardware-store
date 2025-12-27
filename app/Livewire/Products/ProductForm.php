<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\ProductAlias;
use App\Models\Supplier;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductForm extends Component
{
    public $productId = null;

    public $isEditing = false;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string|max:100')]
    public $brand = '';

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

    public $aliases = [''];

    public function mount($productId = null)
    {
        if ($productId) {
            $this->isEditing = true;
            $this->productId = $productId;
            $product = Product::find($productId);

            if ($product) {
                $this->name = $product->name;
                $this->brand = $product->brand;
                $this->category = $product->category;
                $this->unit = $product->unit;
                $this->price = $product->price;
                $this->supplier_id = $product->supplier_id;
                $this->low_stock_threshold = $product->low_stock_threshold;
                $this->critical_stock_threshold = $product->critical_stock_threshold;
                $this->auto_reorder_enabled = $product->auto_reorder_enabled;
                $this->reorder_quantity = $product->reorder_quantity;

                if ($product->aliases->count() > 0) {
                    $this->aliases = $product->aliases->pluck('alias')->toArray();
                } else {
                    $this->aliases = [''];
                }
            }
        }
    }

    public function addAlias()
    {
        $this->aliases[] = '';
    }

    public function removeAlias($index)
    {
        unset($this->aliases[$index]);
        $this->aliases = array_values($this->aliases);

        if (empty($this->aliases)) {
            $this->aliases = [''];
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'brand' => $this->brand,
            'category' => $this->category,
            'unit' => $this->unit,
            'price' => $this->price,
            'supplier_id' => $this->supplier_id ?: null,
            'low_stock_threshold' => $this->low_stock_threshold,
            'critical_stock_threshold' => $this->critical_stock_threshold,
            'auto_reorder_enabled' => $this->auto_reorder_enabled,
            'reorder_quantity' => $this->reorder_quantity ?: null,
        ];

        if ($this->isEditing) {
            $product = Product::find($this->productId);
            $product->update($data);

            $product->aliases()->delete();

            $this->dispatch('notification', message: 'Product updated successfully!', type: 'success');
            $this->dispatch('product-updated');
        } else {
            $product = Product::create(array_merge($data, ['current_stock' => 0]));

            $this->dispatch('notification', message: 'Product created successfully!', type: 'success');
            $this->dispatch('product-created');
        }
        
        $filteredAliases = array_filter($this->aliases, fn ($alias) => ! empty(trim($alias)));

        foreach ($filteredAliases as $alias) {
            ProductAlias::create([
                'product_id' => $product->id,
                'alias' => trim($alias),
            ]);
        }

        if ($this->isEditing) {
            // Stay on edit page after update
        } else {
            if (request()->routeIs('products.create')) {
                session()->flash('message', 'Product created successfully!');
                return redirect()->route('products.index');
            }
        }

        $this->reset();
    }

    public function cancel()
    {
        $this->reset();

        if ($this->isEditing) {
            $this->dispatch('product-updated');
        } else {
            // Check if we're on full page or modal
            $this->dispatch('close-create-form');

            // For full page, redirect back
            if (request()->routeIs('products.create')) {
                return redirect()->route('products.index');
            }
        }
    }

    public function render()
    {
        $suppliers = Supplier::all();

        return view('livewire.products.product-form', ['suppliers' => $suppliers]);
    }
}
