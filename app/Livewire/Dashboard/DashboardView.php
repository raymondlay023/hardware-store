<?php

namespace App\Livewire\Dashboard;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Carbon\Carbon;

class DashboardView extends Component
{
    // Date range properties
    public $dateRange = 'today';
    public $customDateFrom = null;
    public $customDateTo = null;
    public $showCustomDatePicker = false;

    public function mount()
    {
        // Set default dates for custom range
        $this->customDateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->customDateTo = now()->format('Y-m-d');
    }

    public function updatedDateRange()
    {
        if ($this->dateRange !== 'custom') {
            $this->showCustomDatePicker = false;
        } else {
            $this->showCustomDatePicker = true;
        }
    }

    public function applyCustomDateRange()
    {
        $this->dateRange = 'custom';
    }

    /**
     * Get the date range query for filtering sales
     */
    private function getDateRangeQuery()
    {
        $query = Sale::query();

        switch($this->dateRange) {
            case 'today':
                $query->whereDate('date', today());
                break;
            
            case 'yesterday':
                $query->whereDate('date', today()->subDay());
                break;
            
            case 'week':
                $query->whereBetween('date', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
                break;
            
            case 'last_week':
                $query->whereBetween('date', [
                    now()->subWeek()->startOfWeek(),
                    now()->subWeek()->endOfWeek()
                ]);
                break;
            
            case 'month':
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
                break;
            
            case 'last_month':
                $query->whereMonth('date', now()->subMonth()->month)
                      ->whereYear('date', now()->subMonth()->year);
                break;
            
            case 'year':
                $query->whereYear('date', now()->year);
                break;
            
            case 'custom':
                if ($this->customDateFrom && $this->customDateTo) {
                    $query->whereBetween('date', [
                        $this->customDateFrom,
                        $this->customDateTo
                    ]);
                }
                break;
            
            case 'all':
            default:
                // No date filter
                break;
        }

        return $query;
    }

    /**
     * Get the date range label for display
     */
    private function getDateRangeLabel()
    {
        return match($this->dateRange) {
            'today' => 'Today (' . now()->format('M d, Y') . ')',
            'yesterday' => 'Yesterday (' . now()->subDay()->format('M d, Y') . ')',
            'week' => 'This Week (' . now()->startOfWeek()->format('M d') . ' - ' . now()->endOfWeek()->format('M d, Y') . ')',
            'last_week' => 'Last Week',
            'month' => now()->format('F Y'),
            'last_month' => now()->subMonth()->format('F Y'),
            'year' => 'Year ' . now()->year,
            'custom' => $this->customDateFrom && $this->customDateTo 
                ? Carbon::parse($this->customDateFrom)->format('M d') . ' - ' . Carbon::parse($this->customDateTo)->format('M d, Y')
                : 'Custom Range',
            'all' => 'All Time',
            default => 'Today',
        };
    }

    /**
     * Get cached static metrics (don't change frequently)
     */
    private function getCachedStaticMetrics()
    {
        return Cache::remember('dashboard_static_metrics', 300, function() {
            return [
                'totalProducts' => Product::count(),
                'totalSuppliers' => Supplier::count(),
                'lowStockCount' => Product::where('current_stock', '<', 10)->count(),
                'criticalStockCount' => Product::where('current_stock', '<', 5)->count(),
                'inventoryValue' => Product::get()->sum(function ($product) {
                    return $product->current_stock * $product->price;
                }),
                'pendingPurchases' => Purchase::where('status', 'pending')->count(),
                'lowStockProducts' => Product::where('current_stock', '<', 10)
                    ->orderBy('current_stock', 'asc')
                    ->limit(5)
                    ->get(),
            ];
        });
    }

    /**
     * Get comparison metrics (cached separately for 5 minutes)
     */
    private function getComparisonMetrics()
    {
        return Cache::remember('dashboard_comparison_metrics', 300, function() {
            $todayRevenue = Sale::whereDate('date', today())->sum('total_amount');
            $yesterdayRevenue = Sale::whereDate('date', today()->subDay())->sum('total_amount');
            
            $thisMonthRevenue = Sale::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('total_amount');
            
            $lastMonthRevenue = Sale::whereMonth('date', now()->subMonth()->month)
                ->whereYear('date', now()->subMonth()->year)
                ->sum('total_amount');

            return [
                'todayRevenue' => $todayRevenue,
                'yesterdayRevenue' => $yesterdayRevenue,
                'revenueChange' => $yesterdayRevenue > 0 
                    ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100 
                    : 0,
                'thisMonthRevenue' => $thisMonthRevenue,
                'lastMonthRevenue' => $lastMonthRevenue,
                'monthlyChange' => $lastMonthRevenue > 0 
                    ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
                    : 0,
            ];
        });
    }

    /**
     * Calculate profit metrics (cached for 10 minutes)
     */
    private function getProfitMetrics()
    {
        return Cache::remember('dashboard_profit_metrics', 600, function() {
            $totalRevenue = Sale::sum('total_amount');
            
            $totalCostOfGoods = Sale::with('saleItems.product')->get()->sum(function ($sale) {
                return $sale->saleItems->sum(function ($item) {
                    return $item->quantity * ($item->product->cost ?? 0);
                });
            });

            $grossProfit = $totalRevenue - $totalCostOfGoods;
            $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

            return [
                'grossProfit' => $grossProfit,
                'profitMargin' => $profitMargin,
            ];
        });
    }

    /**
     * Calculate stock turnover (cached for 30 minutes)
     */
    private function getStockTurnoverMetrics()
    {
        return Cache::remember('dashboard_turnover_metrics', 1800, function() {
            $soldLast30Days = Sale::where('date', '>=', now()->subDays(30))
                ->with('saleItems')
                ->get()
                ->sum(function ($sale) {
                    return $sale->saleItems->sum('quantity');
                });

            $averageStock = Product::avg('current_stock');
            $turnoverRate = $averageStock > 0 ? ($soldLast30Days / $averageStock) : 0;

            return [
                'soldLast30Days' => $soldLast30Days,
                'averageStock' => $averageStock,
                'turnoverRate' => $turnoverRate,
            ];
        });
    }

    public function render()
    {
        $user = Auth::user();

        // If cashier/staff, show cashier dashboard (unchanged, not cached for real-time data)
        if ($user->roles()->whereIn('name', ['cashier', 'staff'])->exists()) {
            $todaysSales = Sale::whereDate('date', today())
                ->latest('created_at')
                ->get();

            return view('livewire.dashboard.cashier-dashboard', [
                'stats' => [
                    'sales_count' => $todaysSales->count(),
                    'total_revenue' => $todaysSales->sum('total_amount'),
                    'total_items' => $todaysSales->sum(fn ($sale) => $sale->saleItems->sum('quantity')),
                    'avg_transaction' => $todaysSales->count() > 0
                        ? $todaysSales->sum('total_amount') / $todaysSales->count()
                        : 0,
                ],
                'todaysSales' => $todaysSales,
                'userName' => $user->name,
            ]);
        }

        // Get cached static metrics (refreshes every 5 minutes)
        $staticMetrics = $this->getCachedStaticMetrics();
        
        // Get cached comparison metrics (refreshes every 5 minutes)
        $comparisonMetrics = $this->getComparisonMetrics();
        
        // Get cached profit metrics (refreshes every 10 minutes)
        $profitMetrics = $this->getProfitMetrics();
        
        // Get cached turnover metrics (refreshes every 30 minutes)
        $turnoverMetrics = $this->getStockTurnoverMetrics();

        // Get filtered sales based on date range (NOT CACHED - dynamic based on filter)
        $filteredSales = $this->getDateRangeQuery();
        
        // Revenue metrics for selected period
        $totalRevenue = (clone $filteredSales)->sum('total_amount');
        $totalTransactions = (clone $filteredSales)->count();
        $avgTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // Recent sales (filtered by date range)
        $recentSales = (clone $filteredSales)
            ->with('saleItems.product')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Top selling products (filtered by date range) - optimized query
        $salesInRange = (clone $filteredSales)
            ->with(['saleItems' => function($query) {
                $query->select('id', 'sale_id', 'product_id', 'quantity', 'unit_price')
                      ->with('product:id,name');
            }])
            ->get();
        
        $topProducts = $salesInRange->flatMap(function ($sale) {
                return $sale->saleItems;
            })
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = $items->first()->product;
                return [
                    'name' => $product->name,
                    'quantity_sold' => $items->sum('quantity'),
                    'revenue' => $items->sum(function ($item) {
                        return $item->quantity * $item->unit_price;
                    }),
                ];
            })
            ->sortByDesc('quantity_sold')
            ->take(5)
            ->values();

        // Daily revenue for chart
        $dailyRevenue = [];
        
        if ($this->dateRange === 'custom' && $this->customDateFrom && $this->customDateTo) {
            $startDate = Carbon::parse($this->customDateFrom);
            $endDate = Carbon::parse($this->customDateTo);
            $daysDiff = $startDate->diffInDays($endDate);
            
            if ($daysDiff <= 30) {
                for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                    $revenue = Sale::whereDate('date', $date)->sum('total_amount');
                    $dailyRevenue[$date->format('Y-m-d')] = $revenue;
                }
            }
        } else {
            // Default: last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $revenue = Sale::whereDate('date', $date)->sum('total_amount');
                $dailyRevenue[$date] = $revenue;
            }
        }

        // Payment method breakdown (filtered by date range)
        $paymentMethodStats = (clone $filteredSales)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Recent Activity Feed (from activity_logs)
        $recentActivity = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get()
            ->map(function ($log) {
                return [
                    'action' => $log->action,
                    'model' => class_basename($log->model_type),
                    'model_id' => $log->model_id,
                    'user' => $log->user ? $log->user->name : 'System',
                    'time' => $log->created_at->diffForHumans(),
                    'icon' => match($log->action) {
                        'created' => 'plus-circle',
                        'updated' => 'edit',
                        'deleted' => 'trash',
                        default => 'clock',
                    },
                    'color' => match($log->action) {
                        'created' => 'green',
                        'updated' => 'blue',
                        'deleted' => 'red',
                        default => 'gray',
                    },
                ];
            });

        // Top Customers by revenue
        $topCustomers = Customer::withCount('sales')
            ->withSum('sales', 'total_amount')
            ->orderByDesc('sales_sum_total_amount')
            ->limit(5)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'type' => $customer->type,
                    'orders' => $customer->sales_count,
                    'revenue' => $customer->sales_sum_total_amount ?? 0,
                ];
            });

        return view('livewire.dashboard.dashboard-view', [
            // Static metrics (cached)
            'totalProducts' => $staticMetrics['totalProducts'],
            'totalSuppliers' => $staticMetrics['totalSuppliers'],
            'lowStockCount' => $staticMetrics['lowStockCount'],
            'criticalStockCount' => $staticMetrics['criticalStockCount'],
            'inventoryValue' => $staticMetrics['inventoryValue'],
            'pendingPurchases' => $staticMetrics['pendingPurchases'],
            'lowStockProducts' => $staticMetrics['lowStockProducts'],
            
            // Comparison metrics (cached)
            'todayRevenue' => $comparisonMetrics['todayRevenue'],
            'yesterdayRevenue' => $comparisonMetrics['yesterdayRevenue'],
            'revenueChange' => $comparisonMetrics['revenueChange'],
            'thisMonthRevenue' => $comparisonMetrics['thisMonthRevenue'],
            'lastMonthRevenue' => $comparisonMetrics['lastMonthRevenue'],
            'monthlyChange' => $comparisonMetrics['monthlyChange'],
            
            // Profit metrics (cached)
            'grossProfit' => $profitMetrics['grossProfit'],
            'profitMargin' => $profitMetrics['profitMargin'],
            
            // Turnover metrics (cached)
            'soldLast30Days' => $turnoverMetrics['soldLast30Days'],
            'averageStock' => $turnoverMetrics['averageStock'],
            'turnoverRate' => $turnoverMetrics['turnoverRate'],
            
            // Dynamic filtered data (NOT cached)
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'avgTransaction' => $avgTransaction,
            'recentSales' => $recentSales,
            'topProducts' => $topProducts,
            'dailyRevenue' => $dailyRevenue,
            'paymentMethodStats' => $paymentMethodStats,
            
            // New widgets
            'recentActivity' => $recentActivity,
            'topCustomers' => $topCustomers,
            
            // Date range info
            'dateRangeLabel' => $this->getDateRangeLabel(),
        ]);
    }

    /**
     * Clear all dashboard caches (call this when data changes significantly)
     */
    public function clearCache()
    {
        Cache::forget('dashboard_static_metrics');
        Cache::forget('dashboard_comparison_metrics');
        Cache::forget('dashboard_profit_metrics');
        Cache::forget('dashboard_turnover_metrics');
        
        $this->dispatch('notification', 
            message: 'Dashboard cache cleared successfully!',
            type: 'success'
        );
    }
}
