<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function jeu(): belongsTo
    {
        return $this->belongsTo(Jeu::class);
    }
    public function team() : belongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function user() : belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
