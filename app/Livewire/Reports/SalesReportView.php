<?php

namespace App\Livewire\Reports;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SalesReportView extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $activeRange = 'month';

    public function mount()
    {
        // Default to this month
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

    public function getSalesDataProperty()
    {
        return Sale::whereBetween('date', [$this->startDate, $this->endDate])
            ->with(['saleItems.product', 'customer'])
            ->get();
    }

    public function getRevenueMetricsProperty()
    {
        $sales = $this->salesData;
        
        $totalRevenue = $sales->sum('total_amount');
        $totalTransactions = $sales->count();
        $averageOrderValue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        $totalDiscounts = $sales->sum('discount_value');
        $totalItems = $sales->sum(fn($sale) => $sale->saleItems->sum('quantity'));

        return [
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'averageOrderValue' => $averageOrderValue,
            'totalDiscounts' => $totalDiscounts,
            'totalItems' => $totalItems,
        ];
    }

    public function getTopProductsByRevenueProperty()
    {
        return SaleItem::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->select('product_id', DB::raw('SUM(quantity * unit_price) as total_revenue'), DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->with('product')
            ->get();
    }

    public function getTopProductsByQuantityProperty()
    {
        return SaleItem::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * unit_price) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->with('product')
            ->get();
    }

    public function getSalesByPaymentMethodProperty()
    {
        return Sale::whereBetween('date', [$this->startDate, $this->endDate])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();
    }

    public function getSalesByCustomerTypeProperty()
    {
        return Sale::whereBetween('date', [$this->startDate, $this->endDate])
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->select('customers.type', DB::raw('COUNT(*) as count'), DB::raw('SUM(sales.total_amount) as total'))
            ->groupBy('customers.type')
            ->get();
    }

    public function exportCsv()
    {
        $sales = $this->salesData;
        $metrics = $this->revenueMetrics;

        $filename = "sales-report-{$this->startDate}-to-{$this->endDate}.csv";
        $handle = fopen('php://memory', 'w');

        // Write headers
        fputcsv($handle, ['Sales Report']);
        fputcsv($handle, ['Date Range', "{$this->startDate} to {$this->endDate}"]);
        fputcsv($handle, ['Generated', now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, []);

        // Write metrics
        fputcsv($handle, ['Metrics']);
        fputcsv($handle, ['Total Revenue', 'Rp ' . number_format($metrics['totalRevenue'], 0, ',', '.')]);
        fputcsv($handle, ['Total Transactions', $metrics['totalTransactions']]);
        fputcsv($handle, ['Average Order Value', 'Rp ' . number_format($metrics['averageOrderValue'], 0, ',', '.')]);
        fputcsv($handle, ['Total Items Sold', $metrics['totalItems']]);
        fputcsv($handle, ['Total Discounts', 'Rp ' . number_format($metrics['totalDiscounts'], 0, ',', '.')]);
        fputcsv($handle, []);

        // Write sales data
        fputcsv($handle, ['Date', 'Customer', 'Payment Method', 'Items', 'Total Amount']);
        foreach ($sales as $sale) {
            fputcsv($handle, [
                $sale->date->format('Y-m-d'),
                $sale->customer?->name ?? $sale->customer_name ?? 'Walk-in',
                $sale->payment_method,
                $sale->saleItems->sum('quantity'),
                number_format($sale->total_amount, 2),
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
        return view('livewire.reports.sales-report-view', [
            'recentSales' => $this->salesData->take(20),
            'metrics' => $this->revenueMetrics,
            'topProductsByRevenue' => $this->topProductsByRevenue,
            'topProductsByQuantity' => $this->topProductsByQuantity,
            'paymentMethods' => $this->salesByPaymentMethod,
            'customerTypes' => $this->salesByCustomerType,
        ]);
    }
}
