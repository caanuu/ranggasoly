<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityHelper
{
    public static function log($activity, $model = null, $modelId = null, $description = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
            'model' => $model,
            'model_id' => $modelId,
            'description' => $description,

        ]);
    }
}
