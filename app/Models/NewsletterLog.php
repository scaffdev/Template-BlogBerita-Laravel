<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterLog extends Model
{
    protected $fillable = ['subject', 'body', 'recipients_count', 'created_by'];
}
