<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jeu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
    ];

    protected $table = 'jeux';


    public function tournois()
    {
        return $this->hasMany(Tournoi::class);
    }

}
