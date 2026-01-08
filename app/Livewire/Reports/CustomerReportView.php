<?php

namespace App\Livewire\Reports;

use App\Models\Customer;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CustomerReportView extends Component
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

    public function getCustomerOverviewProperty()
    {
        $allCustomers = Customer::all();
        $activeCustomers = Customer::whereHas('sales', function($q) {
            $q->where('date', '>=', now()->subDays(30));
        })->count();
        
        $newCustomersThisMonth = Customer::where('created_at', '>=', now()->startOfMonth())->count();
        
        $customersByType = Customer::select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get();

        return [
            'totalCustomers' => $allCustomers->count(),
            'activeCustomers' => $activeCustomers,
            'newCustomersThisMonth' => $newCustomersThisMonth,
            'retailCount' => $customersByType->where('type', 'Retail')->first()->count ?? 0,
            'wholesaleCount' => $customersByType->where('type', 'Wholesale')->first()->count ?? 0,
            'contractorCount' => $customersByType->where('type', 'Contractor')->first()->count ?? 0,
        ];
    }

    public function getTopCustomersByRevenueProperty()
    {
        return Customer::orderByDesc('total_purchases')
            ->limit(20)
            ->get();
    }

    public function getTopCustomersByOrdersProperty()
    {
        return Customer::orderByDesc('total_orders')
            ->limit(20)
            ->get();
    }

    public function getCreditUtilizationProperty()
    {
        $customers = Customer::whereNotNull('credit_limit')
            ->where('credit_limit', '>', 0)
            ->get();

        $customersNearLimit = $customers->filter(function($customer) {
            $utilization = ($customer->total_purchases / $customer->credit_limit) * 100;
            return $utilization >= 80;
        });

        return [
            'totalWithCredit' => $customers->count(),
            'averageUtilization' => $customers->count() > 0 
                ? $customers->avg(fn($c) => ($c->total_purchases / $c->credit_limit) * 100)
                : 0,
            'customersNearLimit' => $customersNearLimit,
        ];
    }

    public function getCustomerTypeDistributionProperty()
    {
        return Customer::select('type', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_purchases) as total_revenue'))
            ->groupBy('type')
            ->get();
    }

    public function exportCsv()
    {
        $overview = $this->customerOverview;
        $filename = "customer-report-" . now()->format('Y-m-d') . ".csv";
        $handle = fopen('php://memory', 'w');

        fputcsv($handle, ['Customer Report']);
        fputcsv($handle, ['Generated', now()->format('Y-m-d H:i:s')]);
        fputcsv($handle, []);

        fputcsv($handle, ['Overview']);
        fputcsv($handle, ['Total Customers', $overview['totalCustomers']]);
        fputcsv($handle, ['Active Customers (Last 30 Days)', $overview['activeCustomers']]);
        fputcsv($handle, ['New Customers This Month', $overview['newCustomersThisMonth']]);
        fputcsv($handle, []);

        fputcsv($handle, ['Top Customers by Revenue']);
        fputcsv($handle, ['Name', 'Type', 'Total Purchases', 'Total Orders']);
        foreach ($this->topCustomersByRevenue as $customer) {
            fputcsv($handle, [
                $customer->name,
                $customer->type,
                number_format($customer->total_purchases, 2),
                $customer->total_orders,
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
        return view('livewire.reports.customer-report-view', [
            'overview' => $this->customerOverview,
            'topByRevenue' => $this->topCustomersByRevenue,
            'topByOrders' => $this->topCustomersByOrders,
            'creditData' => $this->creditUtilization,
            'typeDistribution' => $this->customerTypeDistribution,
        ]);
    }
}
