<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'officer_id',
        'type',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'status',
    ];

    public function officer()
    {
        return $this->belongsTo(Officer::class);
    }
}
