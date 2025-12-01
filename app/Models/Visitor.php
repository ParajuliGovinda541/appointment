<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = ['name', 'mobile_no', 'email', 'status'];



    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
