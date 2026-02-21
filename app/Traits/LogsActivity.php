<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected function logActivity(string $action, string $description, $model = null, ?array $changes = null): ActivityLog
    {
        return ActivityLog::log($action, $description, $model, $changes);
    }
}