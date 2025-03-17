<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'session_id', 'seats', 'price'];

    public function session()
    {
        return $this->belongsTo(filmSessions::class);
    }
    
    // Si ya no utilizas la relación con Seats, puedes eliminar este método.
    // public function seat()
    // {
    //     return $this->belongsTo(Seats::class);
    // }
}
