<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Purchase;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';

    public $showCreateForm = false;

    public $editingProductId = null;

    public $filterStockLevel = 'all'; // all, low, critical

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStockLevel()
    {
        $this->resetPage();
    }

    #[On('product-created')]
    public function productCreated()
    {
        $this->showCreateForm = false;
        $this->resetPage();
    }

    #[On('product-updated')]
    public function productUpdated()
    {
        $this->editingProductId = null;
    }

    #[On('close-create-form')]
    public function closeCreateForm()
    {
        $this->showCreateForm = false;
    }

    public function editProduct($id)
    {
        $this->editingProductId = $id;
    }

    public function deleteProduct($id)
    {
        Product::find($id)->delete();
        $this->dispatch('notification', message: 'Product deleted successfully');
    }

    public function autoReorder($productId)
    {
        $product = Product::find($productId);

        if (! $product || ! $product->auto_reorder_enabled || ! $product->supplier_id) {
            $this->dispatch('notification', message: 'Auto-reorder not enabled or no supplier set');

            return;
        }

        // Create purchase order
        $purchase = Purchase::create([
            'supplier_id' => $product->supplier_id,
            'date' => today(),
            'total_amount' => $product->reorder_quantity * $product->price,
            'status' => 'pending',
        ]);

        $purchase->purchaseItems()->create([
            'product_id' => $product->id,
            'quantity' => $product->reorder_quantity,
            'unit_cost' => $product->price,
        ]);

        $this->dispatch('notification', message: 'Purchase order created successfully!');
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('category', 'like', '%'.$this->search.'%');
        }

        // Filter by stock level
        if ($this->filterStockLevel === 'low') {
            $query->whereRaw('current_stock < low_stock_threshold');
        } elseif ($this->filterStockLevel === 'critical') {
            $query->whereRaw('current_stock < critical_stock_threshold');
        }

        $products = $query->paginate(10);

        return view('livewire.products.product-list', ['products' => $products]);
    }
}
