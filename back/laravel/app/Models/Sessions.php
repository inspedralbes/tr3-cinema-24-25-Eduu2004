<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sessions extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'date', 'time', 'vip_enabled', 'is_discount_day'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
