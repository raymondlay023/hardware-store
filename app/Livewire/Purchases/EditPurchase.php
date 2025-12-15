<?php

namespace App\Livewire\Purchases;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditPurchase extends Component
{
    public $purchaseId = null;
    public $purchase = null;

    #[Validate('required|exists:suppliers,id')]
    public $supplier_id = '';

    #[Validate('required|date')]
    public $date = '';

    public $items = [];
    public $productSearch = '';
    public $selectedProduct = null;
    public $quantity = '';
    public $unit_cost = '';

    public function selectProduct($productId, $productName)
    {
        $this->selectedProduct = $productId;
        $this->productSearch = $productName;
    }

    #[On('edit-purchase')]
    public function editPurchase($id)
    {
        $this->purchaseId = $id;
        $this->purchase = Purchase::with('purchaseItems')->find($id);

        if ($this->purchase && $this->purchase->status === 'pending') {
            $this->supplier_id = $this->purchase->supplier_id;
            $this->date = $this->purchase->date->format('Y-m-d');

            $this->items = $this->purchase->purchaseItems
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'unit_cost' => $item->unit_cost,
                    ];
                })
                ->toArray();
        }
    }

    public function addItem()
    {
        if (!$this->selectedProduct || !$this->quantity || !$this->unit_cost) {
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

        if ($this->purchase && $this->purchase->status === 'pending') {
            $totalAmount = collect($this->items)->sum(function ($item) {
                return $item['quantity'] * $item['unit_cost'];
            });

            $this->purchase->update([
                'supplier_id' => $this->supplier_id,
                'date' => $this->date,
                'total_amount' => $totalAmount,
            ]);

            // Delete old items and create new ones
            $this->purchase->purchaseItems()->delete();
            foreach ($this->items as $item) {
                $this->purchase->purchaseItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                ]);
            }
        }

        $this->reset();
        $this->dispatch('purchase-updated');
    }

    public function cancel()
    {
        $this->reset();
    }

    public function getProductSuggestions()
    {
        if (strlen($this->productSearch) < 1) {
            return [];
        }

        return Product::where('name', 'like', '%' . $this->productSearch . '%')
            ->limit(5)
            ->get(['id', 'name'])
            ->toArray();
    }

    public function render()
    {
        $suppliers = Supplier::all();
        $products = $this->getProductSuggestions();

        return view('livewire.purchases.edit-purchase', [
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }
}
