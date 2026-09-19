<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    public static function log(string $description, ?Model $subject = null, array $props = []): ActivityLog
    {
        return ActivityLog::create([
            'causer_id' => auth()->id(),
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->getKey(),
            'properties' => $props ?: null,
        ]);
    }
}
