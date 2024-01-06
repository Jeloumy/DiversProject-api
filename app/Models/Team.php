<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tournoi()
    {
        return $this->belongsToMany(Tournoi::class);
    }
}
