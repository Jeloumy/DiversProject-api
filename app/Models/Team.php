<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo',
    ];

    protected $casts = [
       // 'logo' => 'boolean',
    ];


    public function users() : hasMany
    {
        return $this->hasMany(User::class);
    }

    public function tournoi() : belongsToMany
    {
        return $this->belongsToMany(Tournoi::class);
    }
}
