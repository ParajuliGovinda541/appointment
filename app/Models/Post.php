<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['name', 'status'];


    public function officers()
    {
        return $this->hasMany(Officer::class);
    }
}
