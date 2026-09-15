<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class ActivityHelper
{
    public static function log($action, $description = null)
    {
        if (auth()->check()) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'description' => $description,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        }
    }
}