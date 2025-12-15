<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class SaleList extends Component
{
    use WithPagination;

    public $search = '';

    public $showCreateForm = false;

    public $dateFrom = '';

    public $dateTo = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    #[On('sale-created')]
    public function saleCreated()
    {
        $this->showCreateForm = false;
        $this->resetPage();
    }

    #[On('close-create-form')]
    public function closeCreateForm()
    {
        $this->showCreateForm = false;
    }

    public function deleteSale($id)
    {
        $sale = Sale::find($id);
        if ($sale) {
            // Restore stock when deleting a sale
            foreach ($sale->saleItems as $item) {
                $item->product->increment('current_stock', $item->quantity);
            }
            $sale->delete();
        }
        $this->dispatch('notification', message: 'Sale deleted and stock restored');
    }

    public function canDeleteSale()
    {
        return Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists();
    }

    public function render()
    {
        $query = Sale::with('saleItems.product');

        if ($this->search) {
            $query->where('customer_name', 'like', '%'.$this->search.'%');
        }

        if ($this->dateFrom) {
            $query->where('date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->where('date', '<=', $this->dateTo);
        }

        $sales = $query->orderBy('date', 'desc')->paginate(10);

        // Calculate statistics
        $totalSales = Sale::sum('total_amount');
        $todaysSales = Sale::whereDate('date', today())->sum('total_amount');
        $totalTransactions = Sale::count();

        return view('livewire.sales.sale-list', [
            'sales' => $sales,
            'totalSales' => $totalSales,
            'todaysSales' => $todaysSales,
            'totalTransactions' => $totalTransactions,
        ]);
    }
}
