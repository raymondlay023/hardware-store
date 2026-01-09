<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Attributes\Validate;
use Livewire\Component;

class StockAdjustment extends Component
{
    public $showModal = false;
    public $products = [];
    
    #[Validate('required|exists:products,id')]
    public $selectedProductId = '';
    
    #[Validate('required|in:add,remove')]
    public $adjustmentType = 'add';
    
    #[Validate('required|integer|min:1')]
    public $quantity = 1;
    
    #[Validate('required|in:damage,loss,found,correction,return,expiry,transfer,other')]
    public $reason = 'correction';
    
    #[Validate('nullable|string|max:500')]
    public $notes = '';

    public $selectedProduct = null;

    public function mount()
    {
        $this->products = Product::orderBy('name')->get(['id', 'name', 'current_stock', 'unit']);
    }

    public function openModal()
    {
        $this->reset(['selectedProductId', 'adjustmentType', 'quantity', 'reason', 'notes', 'selectedProduct']);
        $this->quantity = 1;
        $this->adjustmentType = 'add';
        $this->reason = 'correction';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function updatedSelectedProductId($value)
    {
        if ($value) {
            $this->selectedProduct = Product::find($value);
        } else {
            $this->selectedProduct = null;
        }
    }

    public function saveAdjustment()
    {
        $this->validate();

        $product = Product::findOrFail($this->selectedProductId);
        
        // Calculate new stock
        $adjustedQuantity = $this->adjustmentType === 'add' ? $this->quantity : -$this->quantity;
        $newStock = $product->current_stock + $adjustedQuantity;

        // Prevent negative stock
        if ($newStock < 0) {
            $this->addError('quantity', 'Cannot reduce stock below zero. Current stock: ' . $product->current_stock);
            return;
        }

        // Update product stock
        $product->update(['current_stock' => $newStock]);

        // Create stock movement record
        StockMovement::create([
            'product_id' => $product->id,
            'type' => $this->adjustmentType === 'add' ? 'adjustment_in' : 'adjustment_out',
            'quantity' => $this->quantity,
            'reference_type' => 'adjustment',
            'reference_id' => null,
            'notes' => "[{$this->reason}] " . ($this->notes ?: 'Stock adjustment'),
            'user_id' => auth()->id(),
        ]);

        $this->dispatch('notification', 
            message: "Stock adjusted successfully. New stock: {$newStock} {$product->unit}",
            type: 'success'
        );

        $this->closeModal();
        
        // Refresh products list
        $this->products = Product::orderBy('name')->get(['id', 'name', 'current_stock', 'unit']);
    }

    public function render()
    {
        $recentAdjustments = StockMovement::with(['product', 'user'])
            ->whereIn('type', ['adjustment_in', 'adjustment_out'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.inventory.stock-adjustment', [
            'recentAdjustments' => $recentAdjustments,
        ]);
    }
}
