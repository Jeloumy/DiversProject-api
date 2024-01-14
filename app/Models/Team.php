<?php

namespace App\Models;

use App\Casts\RelativeUrlCast;
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
        'logo' => RelativeUrlCast::class,
    ];



    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function tournoi(): BelongsToMany
    {
        return $this->belongsToMany(Tournoi::class);
    }
}
