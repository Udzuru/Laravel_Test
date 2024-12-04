<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{   
    protected $fillable = [
        'album_id', // Добавьте это поле
        'title',
        'track_number',
    ];
    use HasFactory;
    public function album() {
        return $this->belongsTo(Album::class);
    }
}
