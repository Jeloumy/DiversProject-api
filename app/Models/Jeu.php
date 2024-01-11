<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\RelativeUrlCast;
use Illuminate\Database\Eloquent\Relations\HasMany;

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


    public function tournoi() : hasMany
    {
        return $this->hasMany(Tournoi::class);
    }

}
