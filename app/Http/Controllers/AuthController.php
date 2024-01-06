<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $request->validate(
            [
                'name' => 'string | required',
                'email' => 'email | required|unique:users,email',
                'pseudo' => 'required|string|unique:users',
                'password' => [
                    'required',
                    'string',
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->uncompromised()

                ]
            ]
        );

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->pseudo = $request->input('pseudo');
        $user->save();

        $token = $user->createToken("teste");

        return [
            'token' => $token->plainTextToken,
            'user' => $user
        ];
    }

    public function login (Request $request){
        $request->validate([
            'identifier'=>'required',
            'password' =>'string|required'
        ]);

        $user = User::where('email', $request->input('identifier'))
            ->orWhere('pseudo', $request->input('identifier'))
            ->first();

        if($user && Hash::check($request->input('password'), $user->password)){

            $token = $user->createToken("teste");

            return [
                'token' => $token->plainTextToken,
                'user' => $user
            ];
        }
        return response()->json([
            'message' =>'identifiant ou mot de passe incorrect'
        ], 400);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'message'=>"Vous êtes déconnecté"
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message'=>"Utilisateur supprimé"
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $user->name = $request->input('name');
        $user->save();

        return response()->json($user);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->uncompromised()
            ]
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json(['message' => 'Mot de passe mis à jour avec succès']);
    }
}
