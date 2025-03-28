<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Seats;

class FilmSessions extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'date', 'time', 'vip_enabled', 'is_discount_day'];

    // Relacions
    public function movie()
    {
        return $this->belongsTo(Movies::class);
    }

    public function seats()
    {
        return $this->hasMany(Seats::class);
    }

    public function tickets()
    {
        return $this->hasMany(Tickets::class);
    }

    protected static function booted()
    {
        static::created(function ($session) {
            $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
            foreach ($rows as $row) {
                for ($i = 1; $i <= 10; $i++) {
                    $type = ($session->vip_enabled && $row === 'F') ? 'VIP' : 'Normal';
                    Seats::create([
                        'session_id' => $session->id,
                        'row'        => $row,
                        'number'     => $i,
                        'type'       => $type,
                        'status'     => 'Disponible'
                    ]);
                }
            }
        });
    }

}
