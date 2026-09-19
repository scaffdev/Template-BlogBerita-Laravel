<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $fillable = ['from_slug', 'to_slug', 'status_code'];

    protected function casts(): array
    {
        return ['status_code' => 'integer'];
    }
}
