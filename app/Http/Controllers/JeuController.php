<?php

namespace App\Http\Controllers;

use App\Models\Jeu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JeuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jeu = Jeu::all();
        return $jeu;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'image' => 'required|string',
        ]);

        $jeu = Jeu::create($data);
        $jeu->save();
        return $jeu;
    }

    /**
     * Display the specified resource.
     */
    public function show(Jeu $jeu)
    {
        return $jeu;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jeu $jeu)
    {

        $data = $request->validate([
            'name' => 'string',
            'logo' => 'string',
        ]);

        $jeu->update($data);
        return $jeu;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jeu $jeu)
    {
        $jeu->delete();

        return response()->json([
            'message'=>"Jeu supprimé"
        ]);
    }
}
