<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public static function log($action, $model = null, $model_id = null, $old_data = null, $new_data = null)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'petugas_id' => Auth::id(),
                'action' => $action,
                'model' => $model,
                'model_id' => $model_id,
                'old_data' => $old_data ? json_encode($old_data) : null,
                'new_data' => $new_data ? json_encode($new_data) : null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }
}
