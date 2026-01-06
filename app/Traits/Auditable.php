<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function (Model $model) {
            self::logAudit($model, 'created');
        });

        static::updated(function (Model $model) {
            self::logAudit($model, 'updated', $model->getChanges());
        });

        static::deleted(function (Model $model) {
            self::logAudit($model, 'deleted');
        });
    }

    protected static function logAudit(Model $model, string $action, array $changes = null)
    {
        if (!Auth::check()) {
            return;
        }

        // If it's an update, we might want to filter out 'updated_at' if it's the only change
        if ($action === 'updated' && $changes) {
            unset($changes['updated_at']);
            if (empty($changes)) {
                return;
            }
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'changes' => $changes,
            'ip_address' => request()->ip(),
        ]);
    }
}
