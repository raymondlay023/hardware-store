<?php

namespace App\Livewire\Dashboard;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardView extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        // If cashier/staff, show cashier dashboard
        if ($user->roles()->whereIn('name', ['cashier', 'staff'])->exists()) {
            $todaysSales = Sale::whereDate('date', today())
                ->latest('created_at')
                ->get();

            return view('livewire.dashboard.cashier-dashboard', [
                'stats' => [
                    'sales_count' => $todaysSales->count(),
                    'total_revenue' => $todaysSales->sum('total_amount'),
                    'total_items' => $todaysSales->sum(fn($sale) => $sale->saleItems->sum('quantity')),
                    'avg_transaction' => $todaysSales->count() > 0 
                        ? $todaysSales->sum('total_amount') / $todaysSales->count() 
                        : 0,
                ],
                'todaysSales' => $todaysSales,
                'userName' => $user->name,
            ]);
        }

        // Key metrics
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $lowStockCount = Product::where('current_stock', '<', 10)->count();
        $criticalStockCount = Product::where('current_stock', '<', 5)->count();

        // Revenue metrics
        $totalRevenue = Sale::sum('total_amount');
        $todayRevenue = Sale::whereDate('date', today())->sum('total_amount');
        $thisMonthRevenue = Sale::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('total_amount');

        // Inventory value
        $inventoryValue = Product::get()->sum(function ($product) {
            return $product->current_stock * $product->price;
        });

        // Purchase metrics
        $totalPurchases = Purchase::sum('total_amount');
        $pendingPurchases = Purchase::where('status', 'pending')->count();

        // Low stock products
        $lowStockProducts = Product::where('current_stock', '<', 10)
            ->orderBy('current_stock', 'asc')
            ->limit(5)
            ->get();

        // Recent sales
        $recentSales = Sale::with('saleItems.product')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Top selling products
        $topProducts = Product::with('saleItems')
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'quantity_sold' => $product->saleItems()->sum('quantity'),
                    'revenue' => $product->saleItems()->get()->sum(function ($item) {
                        return $item->quantity * $item->unit_price;
                    }),
                ];
            })
            ->filter(fn($p) => $p['quantity_sold'] > 0)
            ->sortByDesc('quantity_sold')
            ->take(5);

        // Daily revenue (last 7 days)
        $dailyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenue = Sale::whereDate('date', $date)->sum('total_amount');
            $dailyRevenue[$date] = $revenue;
        }

        return view('livewire.dashboard.dashboard-view', [
            'totalProducts' => $totalProducts,
            'totalSuppliers' => $totalSuppliers,
            'lowStockCount' => $lowStockCount,
            'criticalStockCount' => $criticalStockCount,
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue,
            'thisMonthRevenue' => $thisMonthRevenue,
            'inventoryValue' => $inventoryValue,
            'totalPurchases' => $totalPurchases,
            'pendingPurchases' => $pendingPurchases,
            'lowStockProducts' => $lowStockProducts,
            'recentSales' => $recentSales,
            'topProducts' => $topProducts,
            'dailyRevenue' => $dailyRevenue,
        ]);
    }
}
