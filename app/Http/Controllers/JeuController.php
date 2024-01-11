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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

    static function storeImage($image, $jeu){
        $resizedImage = Image::make($image)
            ->resize(256, null, function($constraint){
                $constraint->aspectRatio();
            })
            ->crop(256,256);

        $path ='jeu/' . time(). '-' . $jeu->id . '.' . $image->getClientOriginalExtension();
        Storage::disk('public')->put($path, (string) $resizedImage->encode());

        $jeu->image = $path;

        return $jeu;
    }

}
