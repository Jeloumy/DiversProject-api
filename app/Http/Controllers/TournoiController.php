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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tournoi $tournoi)
    {
        //
    }
}
