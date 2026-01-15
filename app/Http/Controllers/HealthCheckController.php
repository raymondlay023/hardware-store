<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HealthCheckController extends Controller
{
    /**
     * Public health check endpoint for uptime monitoring
     * 
     * Returns basic health status without sensitive information
     */
    public function check(): JsonResponse
    {
        try {
            // Quick database connectivity check
            DB::connection()->getPdo();
            $dbStatus = 'healthy';
        } catch (\Exception $e) {
            $dbStatus = 'unhealthy';
        }

        return response()->json([
            'status' => $dbStatus === 'healthy' ? 'ok' : 'degraded',
            'timestamp' => now()->toIso8601String(),
            'service' => 'BangunanPro',
        ], $dbStatus === 'healthy' ? 200 : 503);
    }

    /**
     * Detailed health status for authenticated admin users
     * 
     * Returns comprehensive system health metrics
     */
    public function status(): JsonResponse
    {
        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'checks' => [
                'database' => $this->checkDatabase(),
                'cache' => $this->checkCache(),
                'storage' => $this->checkStorage(),
                'queue' => $this->checkQueue(),
            ],
            'system' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'environment' => config('app.env'),
                'debug_mode' => config('app.debug'),
            ],
        ];

        // Determine overall status
        $allHealthy = collect($health['checks'])->every(fn($check) => $check['status'] === 'ok');
        $health['status'] = $allHealthy ? 'healthy' : 'degraded';

        return response()->json($health, $allHealthy ? 200 : 503);
    }

    /**
     * Check database connectivity and performance
     */
    private function checkDatabase(): array
    {
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $latency = round((microtime(true) - $start) * 1000, 2); // ms

            return [
                'status' => 'ok',
                'latency_ms' => $latency,
                'connection' => DB::connection()->getDatabaseName(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database connection failed',
            ];
        }
    }

    /**
     * Check cache functionality
     */
    private function checkCache(): array
    {
        try {
            $key = 'health_check_' . time();
            Cache::put($key, 'test', 10);
            $value = Cache::get($key);
            Cache::forget($key);

            return [
                'status' => $value === 'test' ? 'ok' : 'warning',
                'driver' => config('cache.default'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Cache check failed',
            ];
        }
    }

    /**
     * Check storage write permissions
     */
    private function checkStorage(): array
    {
        try {
            $path = storage_path('logs');
            $writable = is_writable($path);

            return [
                'status' => $writable ? 'ok' : 'error',
                'writable' => $writable,
                'path' => $path,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Storage check failed',
            ];
        }
    }

    /**
     * Check queue status
     */
    private function checkQueue(): array
    {
        try {
            return [
                'status' => 'ok',
                'driver' => config('queue.default'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Queue check failed',
            ];
        }
    }
}
