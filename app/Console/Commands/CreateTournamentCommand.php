<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tournoi;
use Illuminate\Support\Facades\Validator;

class CreateTournamentCommand extends Command
{
    protected $signature = 'tournament:create {name} {begin_date} {end_date} {jeu_id} {--D|description=}';
    protected $description = 'Creates a new tournament';

    public function handle()
    {
        $data = [
            'name' => $this->argument('name'),
            'description' => $this->option('description'),
            'begin_date' => $this->argument('begin_date'),
            'end_date' => $this->argument('end_date'),
            'jeu_id' => $this->argument('jeu_id'),
            'user_id' => 1 // Ici, définissez l'ID de l'utilisateur qui crée le tournoi.
        ];

        $validator = Validator::make($data, [
            'name' => 'required|string|unique:tournois',
            'description' => 'nullable|string',
            'begin_date' => 'required|date',
            'end_date' => 'required|date',
            'jeu_id' => 'required|exists:jeux,id',
        ]);

        if ($validator->fails()) {
            $this->error('Erreur de validation:');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        $tournoi = Tournoi::create($data);

        $this->info("Tournoi '{$tournoi->name}' créé avec succès.");
        return 1;
    }

}
