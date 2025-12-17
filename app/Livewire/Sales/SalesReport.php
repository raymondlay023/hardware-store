<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesReport extends Component
{
    use WithPagination;

    #[Url]
    public $start_date = '';

    #[Url]
    public $end_date = '';

    #[Url]
    public $search = '';

    public function mount()
    {
        if (!$this->start_date) {
            $this->start_date = today()->format('Y-m-d');
        }
        if (!$this->end_date) {
            $this->end_date = today()->format('Y-m-d');
        }
    }

    public function resetFilters()
    {
        $this->reset('start_date', 'end_date', 'search');
        $this->start_date = today()->format('Y-m-d');
        $this->end_date = today()->format('Y-m-d');
    }

    public function setDateRange($range)
    {
        match ($range) {
            'today' => [
                $this->start_date = today()->format('Y-m-d'),
                $this->end_date = today()->format('Y-m-d'),
            ],
            'yesterday' => [
                $this->start_date = today()->subDay()->format('Y-m-d'),
                $this->end_date = today()->subDay()->format('Y-m-d'),
            ],
            'last_7_days' => [
                $this->start_date = today()->subDays(7)->format('Y-m-d'),
                $this->end_date = today()->format('Y-m-d'),
            ],
            'last_30_days' => [
                $this->start_date = today()->subDays(30)->format('Y-m-d'),
                $this->end_date = today()->format('Y-m-d'),
            ],
            'this_month' => [
                $this->start_date = today()->startOfMonth()->format('Y-m-d'),
                $this->end_date = today()->endOfMonth()->format('Y-m-d'),
            ],
            'last_month' => [
                $this->start_date = today()->subMonth()->startOfMonth()->format('Y-m-d'),
                $this->end_date = today()->subMonth()->endOfMonth()->format('Y-m-d'),
            ],
            default => null,
        };
    }

    public function getSalesQueryProperty()
    {
        $query = Sale::query()
            ->whereBetween('date', [
                $this->start_date,
                $this->end_date,
            ]);

        if ($this->search) {
            $query->where('customer_name', 'like', '%' . $this->search . '%');
        }

        return $query;
    }

    public function getStatsProperty()
    {
        return [
            'total_sales' => $this->salesQuery->count(),
            'total_revenue' => $this->salesQuery->sum('total_amount'),
            'total_items' => $this->salesQuery
                ->with('saleItems')
                ->get()
                ->sum(fn($sale) => $sale->saleItems->sum('quantity')),
            'avg_transaction' => $this->salesQuery->count() > 0 
                ? $this->salesQuery->sum('total_amount') / $this->salesQuery->count() 
                : 0,
        ];
    }

    #[On('export-sales')]
    public function exportSales($format)
    {
        $sales = $this->salesQuery->with('saleItems.product')->get();
        $stats = $this->stats;
        $dateRange = "{$this->start_date} to {$this->end_date}";

        if ($format === 'pdf') {
            return $this->exportToPdf($sales, $stats, $dateRange);
        } elseif ($format === 'csv') {
            return $this->exportToCsv($sales, $stats, $dateRange);
        }
    }

    private function exportToPdf($sales, $stats, $dateRange)
    {
        $html = view('exports.sales-report-pdf', [
            'sales' => $sales,
            'stats' => $stats,
            'dateRange' => $dateRange,
            'generatedAt' => now()->format('Y-m-d H:i:s'),
        ])->render();

        $pdf = Pdf::loadHTML($html)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            "sales-report-{$this->start_date}-to-{$this->end_date}.pdf"
        );
    }

    private function exportToCsv($sales, $stats, $dateRange)
    {
        $filename = "sales-report-{$this->start_date}-to-{$this->end_date}.csv";
        $handle = fopen('php://memory', 'w');

        // Write headers
        fputcsv($handle, ['Sales Report']);
        fputcsv($handle, ['Date Range', $dateRange]);
        fputcsv($handle, ['Generated', now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, []);

        // Write statistics
        fputcsv($handle, ['Statistics']);
        fputcsv($handle, ['Total Sales', $stats['total_sales']]);
        fputcsv($handle, ['Total Revenue', 'Rp ' . number_format($stats['total_revenue'], 0, ',', '.')]);
        fputcsv($handle, ['Total Items Sold', $stats['total_items']]);
        fputcsv($handle, ['Average Transaction', 'Rp ' . number_format($stats['avg_transaction'], 0, ',', '.')]);
        fputcsv($handle, []);

        // Write sales data
        fputcsv($handle, ['Date', 'Customer Name', 'Items', 'Total Amount']);
        foreach ($sales as $sale) {
            fputcsv($handle, [
                $sale->date,
                $sale->customer_name,
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
        return view('livewire.sales.sales-report', [
            'sales' => $this->salesQuery->with('saleItems.product')->latest('date')->paginate(15),
            'stats' => $this->stats,
        ]);
    }
}
