<?php

namespace App\Listeners;

use App\Events\SaleCompleted;
use Illuminate\Support\Facades\Cache;

class UpdateDashboardMetrics
{
    public function handle(SaleCompleted $event): void
    {
        // Clear dashboard cache to force refresh with new data
        Cache::forget('dashboard_static_metrics');
        Cache::forget('dashboard_comparison_metrics');
        Cache::forget('dashboard_profit_metrics');
        
        // Could also update specific cached metrics instead of clearing
        // This ensures dashboard shows real-time data after sale
    }
}
