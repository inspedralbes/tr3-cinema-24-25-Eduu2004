<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movies extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'plot', 'runtime', 'genre', 'poster'];

    public function sessions()
    {
        return $this->hasMany(filmSessions::class);
    }
}
