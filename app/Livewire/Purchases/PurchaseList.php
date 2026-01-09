<?php

namespace App\Livewire\Purchases;

use App\Models\Purchase;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all';
    public $showCreateForm = false;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    #[On('purchase-created')]
    public function purchaseCreated()
    {
        $this->showCreateForm = false;
        $this->resetPage();
    }

    #[On('close-create-form')]
    public function closeCreateForm()
    {
        $this->showCreateForm = false;
    }

    public function deletePurchase($id)
    {
        Purchase::find($id)->delete();
        $this->dispatch('notification', message: 'Purchase deleted successfully');
    }

    public function receivePurchase($id)
    {
        $purchase = Purchase::find($id);
        if ($purchase && $purchase->status === 'pending') {
            // Update stock for all items in this purchase
            foreach ($purchase->purchaseItems as $item) {
                $item->product->increment('current_stock', $item->quantity);
            }
            // Mark purchase as received
            $purchase->update(['status' => 'received']);
            $this->dispatch('notification', message: 'Purchase marked as received and stock updated');
        }
    }

    public function render()
    {
        $query = Purchase::with('supplier', 'purchaseItems')->withCount('purchaseItems');

        if ($this->search) {
            $query->whereHas('supplier', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        $purchases = $query->orderBy('date', 'desc')->paginate(10);

        return view('livewire.purchases.purchase-list', ['purchases' => $purchases]);
    }
}
