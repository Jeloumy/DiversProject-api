<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\RelativeUrlCast;

class Jeu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
    ];

    protected $casts = [
        'image'=>RelativeUrlCast::class,
    ];

    protected $table = 'jeux';


    public function tournois()
    {
        return $this->hasMany(Tournoi::class);
    }

}
