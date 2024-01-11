<?php

namespace App\Http\Controllers;

use App\Models\Jeu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;



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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);

        $jeu = Jeu::create($data);


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $jeu = self::storeImage($image, $jeu);
        }
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

    public function __construct()
    {
        $this->middleware('admin')->only('store');
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

    static function storeImage($image, $jeu) {
        $extension = $image->getClientOriginalExtension();
        $path = 'jeu/' . time() . '-' . $jeu->id . '.' . $extension;

        if (in_array($extension, ['jpeg', 'png', 'jpg', 'gif', 'svg'])) {
            // Traitement pour les images
            $resizedImage = Image::make($image)
                ->resize(256, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->crop(256, 256);
            Storage::disk('public')->put($path, (string) $resizedImage->encode());
        } else if ($extension == 'pdf') {
            // Traitement pour les PDF
            Storage::disk('public')->putFileAs('jeu/', $image, $path);
        } else {
            // Gestion des types de fichiers non pris en charge
            throw new \Exception("Type de fichier non pris en charge.");
        }

        $jeu->image = $path;
        return $jeu;
    }

}
