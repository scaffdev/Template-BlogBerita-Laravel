<?php

use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Model;

if (! function_exists('activity_log')) {
    function activity_log(string $description, ?Model $subject = null, array $props = []): \App\Models\ActivityLog
    {
        return ActivityLogger::log($description, $subject, $props);
    }
}
