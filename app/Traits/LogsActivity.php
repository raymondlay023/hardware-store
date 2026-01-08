<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    protected static function bootLogsActivity()
    {
        // Log when a model is created
        static::created(function ($model) {
            $model->logActivity('created', [
                'new' => $model->getAttributes(),
            ]);
        });

        // Log when a model is updated
        static::updated(function ($model) {
            $model->logActivity('updated', [
                'old' => $model->getOriginal(),
                'new' => $model->getAttributes(),
            ]);
        });

        // Log when a model is deleted
        static::deleted(function ($model) {
            $model->logActivity('deleted', [
                'old' => $model->getAttributes(),
            ]);
        });
    }

    /**
     * Log an activity
     */
    protected function logActivity(string $action, array $changes = [])
    {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => get_class($this),
                'model_id' => $this->id ?? null,
                'changes' => $changes,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail to prevent breaking the application
            logger()->error('Activity logging failed: ' . $e->getMessage());
        }
    }

    /**
     * Get activity logs for this model
     */
    public function activityLogs()
    {
        return ActivityLog::where('model_type', get_class($this))
            ->where('model_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
