<?php

use App\Livewire\Dashboard\DashboardView;
use App\Livewire\Products\CreateProductPage;
use App\Livewire\Products\ProductList;
use App\Livewire\Purchases\PurchaseList;
use App\Livewire\Sales\CreateSale;
use App\Livewire\Sales\SaleList;
use App\Livewire\Sales\SalesReport;
use App\Livewire\Suppliers\SupplierList;
use Illuminate\Support\Facades\Route;

// Public Health Check Route
Route::get('/health', [App\Http\Controllers\HealthCheckController::class, 'check'])->name('health.check');

Route::middleware(['auth'])->group(function () {
    // Dashboard - accessible by admin and manager only
    Route::middleware('role:admin,manager')->group(function () {
    });
    Route::get('/dashboard', DashboardView::class)->name('dashboard');

    // Products - accessible by admin and manager
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/products', ProductList::class)->name('products.index');
        Route::get('/products/create', CreateProductPage::class)->name('products.create');
    });

    // Suppliers - accessible by admin and manager
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/suppliers', SupplierList::class)->name('suppliers.index');
    });

    // Customers - accessible by admin and manager
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/customers', \App\Livewire\Customers\CustomerList::class)->name('customers.index');
    });

    // Purchases - accessible by admin and manager
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/purchases', PurchaseList::class)->name('purchases.index');
    });

    // Inventory Management - accessible by admin and manager
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/inventory/adjust', \App\Livewire\Inventory\StockAdjustment::class)->name('inventory.adjust');
        Route::get('/inventory/movements', \App\Livewire\Inventory\MovementHistory::class)->name('inventory.movements');
    });

    // Sales - accessible by all roles (admin, manager, cashier)
    Route::get('/sales', SaleList::class)->name('sales.index');
    Route::get('/sales/create', CreateSale::class)->name('sales.create');

    // Health Check Routes (protected by role)
    Route::middleware('role:admin')->group(function () {
        Route::get('/health/status', [App\Http\Controllers\HealthCheckController::class, 'status'])->name('health.status');
        Route::get('/admin/activity-logs', \App\Livewire\Admin\ActivityLogViewer::class)->name('admin.activity-logs');
    });

    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/sales-report', SalesReport::class)->name('sales-report');
    });

    // Reports - accessible by admin and manager
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/reports', \App\Livewire\Reports\ReportsDashboard::class)->name('reports.index');
        Route::get('/reports/sales', \App\Livewire\Reports\SalesReportView::class)->name('reports.sales');
        Route::get('/reports/inventory', \App\Livewire\Reports\InventoryReportView::class)->name('reports.inventory');
        Route::get('/reports/customers', \App\Livewire\Reports\CustomerReportView::class)->name('reports.customers');
        Route::get('/reports/financial', \App\Livewire\Reports\FinancialReportView::class)->name('reports.financial');
    });

    Route::get('/sales/{saleId}/receipt', \App\Livewire\Sales\PrintReceipt::class)->name('sales.receipt');

    // PDF Routes
    Route::get('/pdf/sale/{sale}/receipt', [\App\Http\Controllers\PdfController::class, 'saleReceipt'])->name('pdf.sale.receipt');
    Route::get('/pdf/sale/{sale}/receipt/view', [\App\Http\Controllers\PdfController::class, 'viewSaleReceipt'])->name('pdf.sale.receipt.view');
    Route::get('/pdf/sale/{sale}/invoice', [\App\Http\Controllers\PdfController::class, 'invoice'])->name('pdf.sale.invoice');
    Route::get('/pdf/purchase/{purchase}/order', [\App\Http\Controllers\PdfController::class, 'purchaseOrder'])->name('pdf.purchase.order');
    Route::get('/pdf/purchase/{purchase}/order/view', [\App\Http\Controllers\PdfController::class, 'viewPurchaseOrder'])->name('pdf.purchase.order.view');
});

Route::get('/receipt/{sale}/{token}', function(\App\Models\Sale $sale, $token) {
    // Verify token to prevent unauthorized access
    if (!$sale->verifyReceiptToken($token)) {
        abort(403, 'Invalid receipt link');
    }

     // Check if receipt is expired (30 days)
    if ($sale->created_at->lt(now()->subDays(30))) {
        return view('receipts.expired', compact('sale'));
    }
    
    // Load relationships
    $sale->load(['saleItems.product', 'user']);
    
    return view('receipts.digital', compact('sale'));
})->name('receipt.digital');

Route::view('/', 'welcome');

Route::controller(\App\Http\Controllers\LegalController::class)->group(function () {
    Route::get('/privacy-policy', 'privacy')->name('legal.privacy');
    Route::get('/terms-of-service', 'terms')->name('legal.terms');
    Route::get('/faq', 'faq')->name('support.faq');
    Route::get('/manual', 'manual')->name('support.manual');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile.edit');

require __DIR__.'/auth.php';
