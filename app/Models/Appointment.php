<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'officer_id',
        'visitor_id',
        'name',
        'status',
        'date',
        'start_time',
        'end_time',
        'added_on',
        'last_updated_on'
    ];

    public function officer()
    {
        return $this->belongsTo(Officer::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function activity()
    {
        return $this->hasOne(Activity::class, 'officer_id', 'officer_id')
            ->where('type', 'Appointment')
            ->where('start_date', $this->date)
            ->where('start_time', $this->start_time);
    }
}
