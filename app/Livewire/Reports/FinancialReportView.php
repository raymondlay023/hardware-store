<?php

namespace App\Livewire\Reports;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Purchase;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FinancialReportView extends Component
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

    public function getProfitAndLossProperty()
    {
        // Revenue from sales
        $revenue = Sale::whereBetween('date', [$this->startDate, $this->endDate])
            ->sum('total_amount');

        // Calculate COGS (Cost of Goods Sold)
        $cogs = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.date', [$this->startDate, $this->endDate])
            ->sum(DB::raw('sale_items.quantity * products.cost'));

        // Gross Profit
        $grossProfit = $revenue - $cogs;
        $grossProfitMargin = $revenue > 0 ? ($grossProfit / $revenue) * 100 : 0;

        // Purchase expenses
        $purchaseExpenses = Purchase::whereBetween('date', [$this->startDate, $this->endDate])
            ->sum('total_amount');

        return [
            'revenue' => $revenue,
            'cogs' => $cogs,
            'grossProfit' => $grossProfit,
            'grossProfitMargin' => $grossProfitMargin,
            'purchaseExpenses' => $purchaseExpenses,
            'netIncome' => $grossProfit, // Simplified (no operating expenses tracked yet)
        ];
    }

    public function getProfitByProductProperty()
    {
        return SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.date', [$this->startDate, $this->endDate])
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(sale_items.quantity) as units_sold'),
                DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as revenue'),
                DB::raw('SUM(sale_items.quantity * products.cost) as cost'),
                DB::raw('SUM((sale_items.unit_price - products.cost) * sale_items.quantity) as profit')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('profit')
            ->limit(15)
            ->get();
    }

    public function getProfitByCategoryProperty()
    {
        return SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('sales.date', [$this->startDate, $this->endDate])
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as revenue'),
                DB::raw('SUM(sale_items.quantity * products.cost) as cost'),
                DB::raw('SUM((sale_items.unit_price - products.cost) * sale_items.quantity) as profit')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('profit')
            ->get();
    }

    public function getCashFlowProperty()
    {
        $sales = Sale::whereBetween('date', [$this->startDate, $this->endDate])
            ->select('payment_method', DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        $purchases = Purchase::whereBetween('date', [$this->startDate, $this->endDate])
            ->sum('total_amount');

        $totalCashIn = $sales->sum('total');
        $totalCashOut = $purchases;
        $netCashFlow = $totalCashIn - $totalCashOut;

        return [
            'cashInBySales' => $sales,
            'totalCashIn' => $totalCashIn,
            'totalCashOut' => $totalCashOut,
            'netCashFlow' => $netCashFlow,
        ];
    }

    public function exportCsv()
    {
        $pl = $this->profitAndLoss;
        $filename = "financial-report-{$this->startDate}-to-{$this->endDate}.csv";
        $handle = fopen('php://memory', 'w');

        fputcsv($handle, ['Financial Report']);
        fputcsv($handle, ['Period', "{$this->startDate} to {$this->endDate}"]);
        fputcsv($handle, ['Generated', now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, []);

        fputcsv($handle, ['Profit & Loss Statement']);
        fputcsv($handle, ['Revenue', 'Rp ' . number_format($pl['revenue'], 0, ',', '.')]);
        fputcsv($handle, ['Cost of Goods Sold', 'Rp ' . number_format($pl['cogs'], 0, ',', '.')]);
        fputcsv($handle, ['Gross Profit', 'Rp ' . number_format($pl['grossProfit'], 0, ',', '.')]);
        fputcsv($handle, ['Gross Profit Margin', number_format($pl['grossProfitMargin'], 2) . '%']);
        fputcsv($handle, []);

        fputcsv($handle, ['Top Profitable Products']);
        fputcsv($handle, ['Product', 'Units Sold', 'Revenue', 'Cost', 'Profit']);
        foreach ($this->profitByProduct as $product) {
            fputcsv($handle, [
                $product->name,
                $product->units_sold,
                number_format($product->revenue, 2),
                number_format($product->cost, 2),
                number_format($product->profit, 2),
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
        return view('livewire.reports.financial-report-view', [
            'profitLoss' => $this->profitAndLoss,
            'profitByProduct' => $this->profitByProduct,
            'profitByCategory' => $this->profitByCategory,
            'cashFlow' => $this->cashFlow,
        ]);
    }
}
