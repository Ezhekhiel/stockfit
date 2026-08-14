<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chemical___move extends Model
{
    public $timestamps = true;
    protected $table = "chemical___moves";
    protected $guarded  = [];

    protected $casts = [
        'expired_at' => 'datetime',
        'reminder_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];
}
