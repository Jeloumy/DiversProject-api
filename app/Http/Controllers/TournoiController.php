<?php

namespace App\Http\Controllers;

use App\Models\Tournoi;
use Illuminate\Http\Request;
use App\Models\Team;

class TournoiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tournois = Tournoi::all();
        return $tournois;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:tournois',
            'description' => 'nullable|string',
            'begin_date' => 'required|date',
            'end_date' => 'required|date',
            'jeu_id' => 'required|exists:jeux,id',
            'stream_url' => 'nullable|url',
        ]);

        $data['user_id'] = auth()->id();
        $tournoi = Tournoi::create($data);
        return $tournoi;
    }


    /**
     * Display the specified resource.
     */
    public function show(Tournoi $tournoi)
    {
        return $tournoi;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tournoi $tournoi)
    {
        if ($tournoi->user_id !== auth()->id()) {
            return response()->json(['message' => 'Vous n\'êtes pas le propriétaire de ce tournoi.'], 403);
        }

        $data = $request->validate([
            'name' => 'string|unique:tournois,name,' . $tournoi->id,
            'description' => 'string',
            'begin_date' => 'date',
            'end_date' => 'date',
            'jeu_id' => 'exists:jeux,id',
            'stream_url' => 'nullable|url',
        ]);

        $tournoi->update($data);
        return $tournoi;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tournoi $tournoi)
    {
        $tournoi->delete();

        return response()->json([
            'message'=>"Tournoi supprimé"
        ]);
    }

    public function search($searchQuery)
    {


        $results = Tournoi::where('name', 'like', '%' . $searchQuery . '%')
            ->orWhereHas('jeu', function ($query) use ($searchQuery) {
                $query->where('name', 'like', '%' . $searchQuery . '%');
            })
            ->get();

        return response()->json($results);
    }

    public function addTeamToTournament(Request $request, $tournoiId)
    {
        // Vérifier que l'équipe existe
        $teamId = $request->input('team_id');
        $team = Team::find($teamId);
        if (!$team) {
            return response()->json(['message' => 'Équipe non trouvée'], 404);
        }

        // Vérifier que le tournoi existe
        $tournoi = Tournoi::find($tournoiId);
        if (!$tournoi) {
            return response()->json(['message' => 'Tournoi non trouvé'], 404);
        }

        // Vérifier que l'utilisateur actuel est le capitaine de l'équipe
        if ($team->captain_id !== auth()->user()->id) {
            return response()->json(['message' => 'Seul le capitaine de l\'équipe peut s\'inscrire au tournoi.'], 403);
        }

        // Ajouter l'équipe au tournoi
        $tournoi->teams()->attach($teamId);

        return response()->json(['message' => 'Équipe ajoutée au tournoi avec succès']);
    }

    public function leaveTournament(Request $request, $tournoiId)
    {
        // Vérifier que le tournoi existe
        $tournoi = Tournoi::find($tournoiId);
        if (!$tournoi) {
            return response()->json(['message' => 'Tournoi non trouvé'], 404);
        }

        // Vérifier que l'utilisateur actuel est le capitaine de l'équipe
        if ($tournoi->captain_id !== auth()->user()->id) {
            return response()->json(['message' => 'Seul le capitaine de l\'équipe peut se désinscrire du tournoi.'], 403);
        }

        // Retirer l'équipe du tournoi
        $tournoi->teams()->detach(auth()->user()->team->id);

        return response()->json(['message' => 'Équipe retirée du tournoi avec succès']);
    }

    public function getTournoisCarrousel()
    {
        $tournoisCarrousel = Tournoi::orderBy('created_at', 'desc')->take(5)->get(); // par exemple
        return response()->json($tournoisCarrousel);
    }

    public function getTournoisRecommandes()
    {
        $tournoisRecommandes = Tournoi::where('recommande', true)->get(); // Hypothétique champ 'recommande'
        return response()->json($tournoisRecommandes);
    }

    public function rechercherParJeu($jeuId)
    {
        $tournois = Tournoi::where('jeu_id', $jeuId)->get();
        return response()->json($tournois);
    }


}
