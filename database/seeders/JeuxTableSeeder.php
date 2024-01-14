<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jeu;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class JeuxTableSeeder extends Seeder
{
    public function run() : void
    {
        $jeux = [
            [
                'name' => 'Rocket League',
                'image' => 'rocket_league.jpg',
            ],
            [
                'name' => 'League Of Legends',
                'image' => 'league_of_legends.png',
            ],
            [
                'name' => 'Valorant',
                'image' => 'valorant.png',
            ],
            [
                'name' => 'Counter Strike',
                'image' => 'counter_strike.png',
            ],

        ];

        foreach ($jeux as $jeu) {
            $jeuModel = Jeu::create($jeu);

            $sourceImagePath = public_path('storage/jeu/' . $jeu['image']);
            $destinationImagePath = 'jeu/' . time() . '-' . $jeuModel->id . '.' . pathinfo($jeu['image'], PATHINFO_EXTENSION);

            if (File::exists($sourceImagePath)) {
                Storage::disk('public')->put($destinationImagePath, file_get_contents($sourceImagePath));
                $jeuModel->image = $destinationImagePath;
                $jeuModel->save();
            }
        }
    }
}

