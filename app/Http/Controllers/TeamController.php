<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'logo' => 'required|string',
        ]);

        $team = Team::create($data);

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
        $data = $request->validate([
            'name' => 'string',
            'description' => 'string',
            'logo' => 'string',
        ]);

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
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $user = User::find($request->input('user_id'));

        if ($user) {
            $user->team_id = $team->id;
            $user->save();

            return response()->json(['message' => 'Utilisateur ajouté à l\'équipe avec succès'], 200);
        } else {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
    }

}
