<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventDate extends Model
{
    use HasFactory;

    protected $fillable = ['name','date','is_production_day'];

    public function event()
    {
        return $this->belongsTo('App/Models/Event');
    }
}
