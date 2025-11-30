<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    protected $fillable = [
        'officer_id',
        'day_of_week',
    ];

    public function officer()
    {
        return $this->belongsTo(Officer::class);
    }
}
