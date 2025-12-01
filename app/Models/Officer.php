<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    protected $fillable = [
        'name',
        'post_id',
        'status',
        'work_start_time',
        'work_end_time',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function workDays()
    {
        return $this->hasMany(WorkDay::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
