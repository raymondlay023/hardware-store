<?php

use App\Models\ActivityLog;
use App\Models\Sale;
use App\Models\Purchase;
use App\Rules\ValidPhoneNumber;
use Illuminate\Support\Facades\Validator;

echo "=== Phase 3D Feature Tests ===" . PHP_EOL . PHP_EOL;

// Test 1: Activity Logs Table
echo "1. Activity Logging System:" . PHP_EOL;
echo "   - Table exists: " . (Schema::hasTable('activity_logs') ? 'YES' : 'NO') . PHP_EOL;
echo "   - Total logs: " . ActivityLog::count() . PHP_EOL;

// Show recent logs
$recentLogs = ActivityLog::with('user')->latest()->take(3)->get();
if ($recentLogs->count() > 0) {
    echo "   Recent activity:" . PHP_EOL;
    foreach ($recentLogs as $log) {
        $userName = $log->user ? $log->user->name : 'System';
        echo "     - " . ucfirst($log->action) . " " . class_basename($log->model_type) . 
             " #" . $log->model_id . " by $userName" . PHP_EOL;
    }
}
echo PHP_EOL;

// Test 2: Create Activity Log
echo "2. Testing Activity Log Creation:" . PHP_EOL;
$logsBefore = ActivityLog::count();
$sale = Sale::first();
if ($sale) {
    $sale->update(['notes' => 'Test update at ' . now()]);
    $logsAfter = ActivityLog::count();
    echo "   - Logs before update: $logsBefore" . PHP_EOL;
    echo "   - Logs after update: $logsAfter" . PHP_EOL;
    echo "   - New log created: " . ($logsAfter > $logsBefore ? 'YES ✓' : 'NO ✗') . PHP_EOL;
} else {
    echo "   - No sale records to test" . PHP_EOL;
}
echo PHP_EOL;

// Test 3: Phone Validation
echo "3. Phone Number Validation:" . PHP_EOL;
$phoneTests = [
    '08123456789' => true,
    '+628123456789' => true,
    '628123456789' => true,
    '12345' => false,
    'invalid' => false,
];

foreach ($phoneTests as $phone => $shouldPass) {
    $validator = Validator::make(
        ['phone' => $phone],
        ['phone' => [new ValidPhoneNumber]]
    );
    $passed = !$validator->fails();
    $result = $passed === $shouldPass ? '✓ PASS' : '✗ FAIL';
    echo "   - $phone: " . ($passed ? 'Valid' : 'Invalid') . " $result" . PHP_EOL;
}
echo PHP_EOL;

// Test 4: Health Check Endpoint
echo "4. Health Check Endpoint:" . PHP_EOL;
echo "   - Visit: http://localhost:8000/health" . PHP_EOL;
echo "   - Admin Status: http://localhost:8000/health/status" . PHP_EOL;
echo PHP_EOL;

// Test 5: Database Backup
echo "5. Database Backup Command:" . PHP_EOL;
echo "   - Run: php artisan db:backup" . PHP_EOL;
echo "   - Note: Requires mysqldump in PATH" . PHP_EOL;
echo PHP_EOL;

// Test 6: Email Classes
echo "6. Email Classes Available:" . PHP_EOL;
echo "   - LowStockAlert: " . (class_exists('App\Mail\LowStockAlert') ? 'YES ✓' : 'NO ✗') . PHP_EOL;
echo "   - PurchaseConfirmation: " . (class_exists('App\Mail\PurchaseConfirmation') ? 'YES ✓' : 'NO ✗') . PHP_EOL;
echo "   - SaleReceipt: " . (class_exists('App\Mail\SaleReceipt') ? 'YES ✓' : 'NO ✗') . PHP_EOL;
echo PHP_EOL;

echo "=== Tests Complete ===" . PHP_EOL;
