<?php

namespace App\Livewire\Purchases;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePurchase extends Component
{
    #[Validate('required|exists:suppliers,id')]
    public $supplier_id = '';

    #[Validate('required|date')]
    public $date = '';

    public $items = [];

    public $productSearch = '';

    public $selectedProduct = null;

    public $quantity = '';

    public $unit_cost = '';

    public function clearProduct()
    {
        $this->selectedProduct = null;
        $this->productSearch = '';
    }

    public function selectProduct($productId, $productName)
    {
        $this->selectedProduct = $productId;
        $this->productSearch = $productName;
    }

    public function mount()
    {
        $this->date = today()->format('Y-m-d');
    }

    public function addItem()
    {
        if (! $this->selectedProduct || ! $this->quantity || ! $this->unit_cost) {
            $this->addError('items', 'Please fill all item fields');

            return;
        }

        $product = Product::find($this->selectedProduct);

        $this->items[] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => (int) $this->quantity,
            'unit_cost' => (float) $this->unit_cost,
        ];

        $this->selectedProduct = null;
        $this->quantity = '';
        $this->unit_cost = '';
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate();

        if (empty($this->items)) {
            $this->addError('items', 'Please add at least one item');

            return;
        }

        $totalAmount = collect($this->items)->sum(function ($item) {
            return $item['quantity'] * $item['unit_cost'];
        });

        $purchase = Purchase::create([
            'supplier_id' => $this->supplier_id,
            'date' => $this->date,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($this->items as $item) {
            $purchase->purchaseItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
            ]);
        }

        $this->reset();
        $this->dispatch('purchase-created');
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
            ->limit(5)
            ->get(['id', 'name'])
            ->toArray();
    }

    public function render()
    {
        $suppliers = Supplier::all();
        $products = $this->getProductSuggestions();

        return view('livewire.purchases.create-purchase', [
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }
}
