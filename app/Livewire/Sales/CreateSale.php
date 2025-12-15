<?php

namespace App\Livewire\Sales;

use App\Models\Product;
use App\Models\Sale;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateSale extends Component
{
    #[Validate('required|string|max:255')]
    public $customer_name = '';

    #[Validate('required|date')]
    public $date = '';

    public $items = [];

    public $productSearch = '';

    public $selectedProduct = null;

    public $quantity = '';

    public function mount()
    {
        $this->date = today()->format('Y-m-d');
    }

    public function selectProduct($productId, $productName)
    {
        $this->selectedProduct = $productId;
        $this->productSearch = $productName;
    }

    public function clearProduct()
    {
        $this->selectedProduct = null;
        $this->productSearch = '';
    }

    public function addItem()
    {
        if (! $this->selectedProduct || ! $this->quantity) {
            $this->addError('items', 'Please select a product and enter quantity');

            return;
        }

        $product = Product::find($this->selectedProduct);

        if ($product->current_stock < (int) $this->quantity) {
            $this->addError('items', "Not enough stock. Available: {$product->current_stock}");

            return;
        }

        // Check if product already in items
        $existingIndex = collect($this->items)->search(fn ($item) => $item['product_id'] === $product->id);

        if ($existingIndex !== false) {
            $this->items[$existingIndex]['quantity'] += (int) $this->quantity;
        } else {
            $this->items[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => (int) $this->quantity,
            ];
        }

        $this->selectedProduct = null;
        $this->quantity = '';
        $this->productSearch = '';
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updateQuantity($index, $quantity)
    {
        if ($quantity > 0) {
            $this->items[$index]['quantity'] = $quantity;
        }
    }

    public function save()
    {
        $this->validate();

        if (empty($this->items)) {
            $this->addError('items', 'Please add at least one item');

            return;
        }

        $totalAmount = collect($this->items)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        $sale = Sale::create([
            'customer_name' => $this->customer_name,
            'date' => $this->date,
            'total_amount' => $totalAmount,
        ]);

        foreach ($this->items as $item) {
            $sale->saleItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ]);

            // Decrement product stock
            Product::find($item['product_id'])->decrement('current_stock', $item['quantity']);
        }

        $this->reset();
        $this->dispatch('sale-created');
    }

    public function cancel()
    {
        $this->reset();
        $this->dispatch('close-create-form');
    }

    public function getProductSuggestions()
    {
        if (strlen($this->productSearch) < 1) {
            return [];
        }

        return Product::where('name', 'like', '%'.$this->productSearch.'%')
            ->where('current_stock', '>', 0)
            ->limit(5)
            ->get(['id', 'name', 'current_stock'])
            ->toArray();
    }

    public function render()
    {
        $products = $this->getProductSuggestions();

        return view('livewire.sales.create-sale', ['products' => $products]);
    }
}
