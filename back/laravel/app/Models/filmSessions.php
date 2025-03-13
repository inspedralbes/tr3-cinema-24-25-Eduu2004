<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class filmSessions extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'date', 'time', 'vip_enabled', 'is_discount_day'];

    public function movie()
    {
        return $this->belongsTo(Movies::class, 'movie_id');
    }

    public function seats()
    {
        return $this->hasMany(Seats::class);
    }

    public function tickets()
    {
        return $this->hasMany(Tickets::class);
    }
}
