<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournoi extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'begin_date',
        'end_date',
        'user_id',
        'jeu_id'
    ];

    public function jeu()
    {
        return $this->belongsTo(Jeu::class);
    }
    public function team()
    {
        return $this->belongsToMany(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
