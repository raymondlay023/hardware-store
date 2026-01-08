<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HealthCheckController extends Controller
{
    /**
     * Health check endpoint for monitoring
     */
    public function check(): JsonResponse
    {
        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'checks' => [],
        ];

        // 1. Database connectivity check
        try {
            DB::connection()->getPdo();
            $health['checks']['database'] = 'ok';
        } catch (\Exception $e) {
            $health['checks']['database'] = 'failed';
            $health['status'] = 'unhealthy';
        }

        // 2. Filesystem writable check
        $storagePath = storage_path('app');
        if (File::isWritable($storagePath)) {
            $health['checks']['filesystem'] = 'ok';
        } else {
            $health['checks']['filesystem'] = 'not_writable';
            $health['status'] = 'degraded';
        }

        // 3. Application environment
        $health['checks']['environment'] = config('app.env');

        // 4. Application version (optional)
        $health['version'] = config('app.version', '1.0.0');

        $statusCode = match($health['status']) {
            'healthy' => 200,
            'degraded' => 200,
            'unhealthy' => 503,
            default => 500,
        };

        return response()->json($health, $statusCode);
    }

    /**
     * Detailed system status (admin only)
     */
    public function status(): JsonResponse
    {
        $status = [
            'database' => $this->getDatabaseStatus(),
            'storage' => $this->getStorageStatus(),
            'cache' => $this->getCacheStatus(),
        ];

        return response()->json($status);
    }

    private function getDatabaseStatus(): array
    {
        try {
            $pdo = DB::connection()->getPdo();
            return [
                'status' => 'connected',
                'driver' => DB::connection()->getDriverName(),
                'database' => DB::connection()->getDatabaseName(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'disconnected',
                'error' => $e->getMessage(),
            ];
        }
    }

    private function getStorageStatus(): array
    {
        $storagePath = storage_path();
        $totalSpace = disk_total_space($storagePath);
        $freeSpace = disk_free_space($storagePath);

        return [
            'total' => $this->formatBytes($totalSpace),
            'free' => $this->formatBytes($freeSpace),
            'used' => $this->formatBytes($totalSpace - $freeSpace),
            'percentage' => round((($totalSpace - $freeSpace) / $totalSpace) * 100, 2),
        ];
    }

    private function getCacheStatus(): array
    {
        return [
            'driver' => config('cache.default'),
        ];
    }

    private function formatBytes($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
