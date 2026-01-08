<?php

namespace App\Livewire\Reports;

use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InventoryReportView extends Component
{
    public $startDate;
    public $endDate;
    public $activeRange = 'month';

    public function mount()
    {
        $this->setDateRange('month');
    }

    public function setDateRange($range)
    {
        $this->activeRange = $range;
        
        switch ($range) {
            case 'today':
                $this->startDate = now()->startOfDay()->format('Y-m-d');
                $this->endDate = now()->endOfDay()->format('Y-m-d');
                break;
            case 'week':
                $this->startDate = now()->startOfWeek()->format('Y-m-d');
                $this->endDate = now()->endOfWeek()->format('Y-m-d');
                break;
            case 'month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'year':
                $this->startDate = now()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');
                break;
        }
    }

    public function getStockSummaryProperty()
    {
        $products = Product::all();
        
        $totalProducts = $products->count();
        $lowStockCount = $products->filter(fn($p) => $p->isLowStock())->count();
        $criticalStockCount = $products->filter(fn($p) => $p->isCriticalStock())->count();
        $outOfStockCount = $products->where('current_stock', 0)->count();
        
        $totalCostValue = $products->sum(fn($p) => $p->current_stock * $p->cost);
        $totalRetailValue = $products->sum(fn($p) => $p->current_stock * $p->price);
        $potentialProfit = $totalRetailValue - $totalCostValue;

        return [
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'criticalStockCount' => $criticalStockCount,
            'outOfStockCount' => $outOfStockCount,
            'totalCostValue' => $totalCostValue,
            'totalRetailValue' => $totalRetailValue,
            'potentialProfit' => $potentialProfit,
        ];
    }

    public function getLowStockProductsProperty()
    {
        return Product::whereRaw('current_stock < low_stock_threshold')
            ->orderBy('current_stock', 'asc')
            ->limit(15)
            ->get();
    }

    public function getCriticalStockProductsProperty()
    {
        return Product::whereRaw('current_stock < critical_stock_threshold')
            ->orderBy('current_stock', 'asc')
            ->limit(15)
            ->get();
    }

    public function getStockMovementsProperty()
    {
        return StockMovement::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->with('product')
            ->latest()
            ->limit(50)
            ->get();
    }

    public function getMovementsByTypeProperty()
    {
        return StockMovement::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->select('type', DB::raw('SUM(ABS(quantity)) as total_quantity'), DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get();
    }

    public function getCategoryBreakdownProperty()
    {
        return Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('COUNT(products.id) as product_count'),
                DB::raw('SUM(products.current_stock) as total_stock'),
                DB::raw('SUM(products.current_stock * products.cost) as total_value')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_value')
            ->get();
    }

    public function exportCsv()
    {
        $summary = $this->stockSummary;
        $filename = "inventory-report-" . now()->format('Y-m-d') . ".csv";
        $handle = fopen('php://memory', 'w');

        fputcsv($handle, ['Inventory Report']);
        fputcsv($handle, ['Generated', now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, []);

        fputcsv($handle, ['Stock Summary']);
        fputcsv($handle, ['Total Products', $summary['totalProducts']]);
        fputcsv($handle, ['Low Stock Items', $summary['lowStockCount']]);
        fputcsv($handle, ['Critical Stock Items', $summary['criticalStockCount']]);
        fputcsv($handle, ['Out of Stock', $summary['outOfStockCount']]);
        fputcsv($handle, ['Total Cost Value', 'Rp ' . number_format($summary['totalCostValue'], 0, ',', '.')]);
        fputcsv($handle, ['Total Retail Value', 'Rp ' . number_format($summary['totalRetailValue'], 0, ',', '.')]);
        fputcsv($handle, ['Potential Profit', 'Rp ' . number_format($summary['potentialProfit'], 0, ',', '.')]);
        fputcsv($handle, []);

        fputcsv($handle, ['Low Stock Products']);
        fputcsv($handle, ['Product', 'Current Stock', 'Threshold', 'Unit']);
        foreach ($this->lowStockProducts as $product) {
            fputcsv($handle, [
                $product->name,
                $product->current_stock,
                $product->low_stock_threshold,
                $product->unit,
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(
            fn() => print($content),
            $filename,
            ['Content-Type' => 'text/csv; charset=utf-8']
        );
    }

    public function render()
    {
        return view('livewire.reports.inventory-report-view', [
            'summary' => $this->stockSummary,
            'lowStockProducts' => $this->lowStockProducts,
            'criticalStockProducts' => $this->criticalStockProducts,
            'movements' => $this->stockMovements,
            'movementsByType' => $this->movementsByType,
            'categoryBreakdown' => $this->categoryBreakdown,
        ]);
    }
}
