<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Tournoi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $team = Team::all();
        return $team;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);

        $team = Team::create($data);
        $team->captain_id = auth()->user()->id;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $team = self::storeImage($logo, $team);
        }
        $team->save();
        $user = auth()->user();
        $user->team_id = $team->id;
        $user->save();
        return $team;
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        return $team;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        if ($team->captain_id !== auth()->user()->id) {
            return response()->json(['message' => 'Vous n\'êtes pas le capitaine de l\'équipe.'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'sometimes|string',
            'logo' => 'sometimes|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);


        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $team = self::storeImage($logo, $team); // Utiliser storeImage pour traiter et stocker le fichier
        }

        // Mettre à jour les autres données
        $team->update($data);

        return $team;
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $team->delete();

        return response()->json([
            'message'=>"Team supprimé"
        ]);
    }

    public function addUser(Request $request, Team $team)
    {
        $request->validate([
            'pseudo' => 'required|string|exists:users,pseudo',
        ]);

        if (auth()->user()->id !== $team->captain_id) {
            return response()->json(['message' => 'Seul le capitaine peut ajouter des membres à l\'équipe'], 403);
        }

        $user = User::where('pseudo', $request->input('pseudo'))->first();

        if ($user) {
            $user->team_id = $team->id;
            $user->save();
            return response()->json(['message' => 'Utilisateur ajouté à l\'équipe avec succès'], 200);
        } else {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
    }


    public function setCaptain(Request $request, Team $team, User $newCaptain)
    {
        // Vérifie si l'utilisateur actuel est le capitaine de l'équipe
        if ($team->captain_id !== auth()->user()->id) {
            return response()->json(['message' => 'Vous n\'êtes pas le capitaine de l\'équipe.'], 403);
        }

        $team->load('users');
        //dd($team->users);
        // Vérifie si le nouvel utilisateur est membre de l'équipe
        if (!$team->users()->whereId($newCaptain->id)) {

            return response()->json(['message' => 'Cet utilisateur n\'est pas membre de l\'équipe.'], 400);
        }

        // Met à jour le capitaine
        $team->captain_id = $newCaptain->id;
        $team->save();

        return response()->json(['message' => 'Le nouveau capitaine a été défini avec succès.']);
    }

    static function storeImage($file, $team) {
        $extension = $file->getClientOriginalExtension();
        $basePath = 'team/' . time() . '-' . $team->id;

        if (in_array($extension, ['jpeg', 'png', 'jpg', 'gif', 'svg'])) {
            // Traitement pour les images
            $resizedImage = Image::make($file)
                ->resize(256, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->crop(256, 256);

            $path = $basePath . '.' . $extension;
            Storage::disk('public')->put($path, (string) $resizedImage->encode());
        } else if ($extension == 'pdf') {
            // Traitement pour les PDF
            $path = $basePath . '.' . $extension;
            Storage::disk('public')->putFileAs('team/', $file, $path);
        } else {
            // Gestion des types de fichiers non pris en charge
            throw new \Exception("Type de fichier non pris en charge.");
        }

        $team->logo = $path;

        return $team;
    }

    public function getTeamByUserId($userId)
    {
        $team = Team::with(['users' => function ($query) {
            $query->select('id', 'pseudo', 'team_id');
        }, 'tournoi'])->whereHas('users', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->first(['id', 'name', 'description', 'captain_id']);

        if ($team) {
            return response()->json($team);
        } else {
            return response()->json(['message' => 'Aucune équipe trouvée pour cet utilisateur'], 404);
        }
    }
    public function getTournamentsByTeam($teamId)
    {
        $tournois = Tournoi::with('teams')->whereHas('teams', function ($query) use ($teamId) {
            $query->where('team_id', $teamId);
        })->get();

        return response()->json($tournois);
    }

    public function getTeamMembers(Team $team)
    {
        $members = $team->users()->select('id', 'name', 'pseudo')->get();

        return response()->json($members);

    }
    }
