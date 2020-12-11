<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function eventDates()
    {
        return $this->hasMany('App\Models\EventDate');
    }
}
