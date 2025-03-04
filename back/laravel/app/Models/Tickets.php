<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'session_id', 'seat_id', 'price'];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
