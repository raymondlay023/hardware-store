<?php

namespace App\Livewire\Purchases;

use App\Models\Product;
use App\Models\Supplier;
use App\Services\PurchaseService;
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

    public $unit_price = '';

    protected PurchaseService $purchaseService;

    public function boot(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function clearProduct()
    {
        $this->selectedProduct = null;
        $this->productSearch = '';
    }

    public function selectProduct($productId, $productName, $cost = null)
    {
        $this->selectedProduct = $productId;
        $this->productSearch = $productName;
        // Pre-fill with product cost if available
        if ($cost) {
            $this->unit_price = $cost;
        }
    }

    public function mount()
    {
        $this->date = today()->format('Y-m-d');
    }

    public function addItem()
    {
        if (! $this->selectedProduct || ! $this->quantity || ! $this->unit_price) {
            $this->addError('items', 'Please fill all item fields');

            return;
        }

        $product = Product::find($this->selectedProduct);

        $this->items[] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => (int) $this->quantity,
            'unit_price' => (float) $this->unit_price,
        ];

        $this->selectedProduct = null;
        $this->productSearch = '';
        $this->quantity = '';
        $this->unit_price = '';
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

        try {
            $this->purchaseService->createPurchase(
                [
                    'supplier_id' => $this->supplier_id,
                    'date' => $this->date,
                    'status' => 'pending',
                ],
                $this->items
            );

            $this->reset();
            $this->dispatch('purchase-created');
            $this->dispatch('notification', message: 'Purchase order created successfully');
        } catch (\Exception $e) {
            $this->addError('items', $e->getMessage());
        }
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
            ->get(['id', 'name', 'cost'])
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

