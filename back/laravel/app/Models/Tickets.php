<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'session_id', 'seats', 'price'];

    protected $casts = [
        'seats' => 'array', 
    ];

    public function session()
    {
        return $this->belongsTo(filmSessions::class);
    }
    
    public function seat()
    {
        return $this->belongsTo(Seats::class, 'seat_id');
    }

}

