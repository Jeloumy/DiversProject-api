<?php

namespace App\Http\Controllers;

use App\Models\Tournoi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'name' => 'required|string',
            'description' => 'nullable|string',
            'begin_date' => 'required|date',
            'end_date' => 'required|date',
            'jeu_id' => 'required|exists:jeux,id',
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
        $data = $request->validate([
            'name' => 'string',
            'description' => 'string',
            'begin_date' => 'date',
            'end_date' => 'date',
            'jeu_id' => 'exists:jeux,id',
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
}
