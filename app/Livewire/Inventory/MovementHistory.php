<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MovementHistory extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';
    
    #[Url]
    public $filterProduct = '';
    
    #[Url]
    public $filterType = '';
    
    public $dateFrom = '';
    public $dateTo = '';

    public function mount()
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterProduct()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterProduct = '';
        $this->filterType = '';
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function render()
    {
        $query = StockMovement::with(['product', 'user']);

        if ($this->search) {
            $query->whereHas('product', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterProduct) {
            $query->where('product_id', $this->filterProduct);
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(20);

        // Only load products for filter when needed (lazy - only 20 most active)
        $products = Product::orderBy('name')->limit(50)->get(['id', 'name']);

        $movementTypes = [
            'sale' => 'Sale',
            'purchase' => 'Purchase',
            'adjustment_in' => 'Adjustment (In)',
            'adjustment_out' => 'Adjustment (Out)',
            'return' => 'Return',
        ];

        // Combined stats query (single query instead of 3)
        $statsRaw = StockMovement::query()
            ->whereDate('created_at', '>=', $this->dateFrom)
            ->whereDate('created_at', '<=', $this->dateTo)
            ->selectRaw("
                SUM(CASE WHEN type IN ('purchase', 'adjustment_in', 'return') THEN quantity ELSE 0 END) as total_in,
                SUM(CASE WHEN type IN ('sale', 'adjustment_out') THEN quantity ELSE 0 END) as total_out,
                COUNT(*) as movement_count
            ")
            ->first();

        $stats = [
            'total_in' => $statsRaw->total_in ?? 0,
            'total_out' => $statsRaw->total_out ?? 0,
            'movement_count' => $statsRaw->movement_count ?? 0,
        ];

        return view('livewire.inventory.movement-history', [
            'movements' => $movements,
            'products' => $products,
            'movementTypes' => $movementTypes,
            'stats' => $stats,
        ]);
    }
}
