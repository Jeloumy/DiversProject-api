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
                'last_name' => 'string|required',
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
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->pseudo = $request->input('pseudo');
        $user->save();


        $token = $user->createToken("test");
        return [
            'token' => $token->plainTextToken,
            'user' => $user
        ];

    }

    public function login(Request $request){
        $request->validate([
            'identifier' => 'required',
            'password' => 'string|required'
        ]);

        $user = User::where('email', $request->input('identifier'))
            ->orWhere('pseudo', $request->input('identifier'))
            ->first();

        if($user && Hash::check($request->input('password'), $user->password)){
            $token = $user->createToken('NomDuToken')->plainTextToken;

            return [
                'token' => $token,
                'user' => $user,
                'admin' => $user->admin,
                'teamId' => $user->team_id
            ];
        }

        return response()->json([
            'message' => 'Identifiant ou mot de passe incorrect'
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

    public function checkAdminStatus()
    {
        $user = auth()->user(); // Obtenez l'utilisateur connecté

        return [
            'admin' => $user->admin == 1, // Vérifiez le statut d'administration de l'utilisateur
        ];
    }
    public function destroy(User $user)
    {
        // Supprimer tous les tournois créés par l'utilisateur
        Tournoi::where('user_id', $user->id)->delete();

        // Supprimer toutes les équipes dont l'utilisateur est le capitaine
        Team::where('captain_id', $user->id)->delete();

        // Retirer l'utilisateur de toutes les équipes où il est membre
        Team::where('user_id', $user->id)->update(['user_id' => null]);

        // Supprimer l'utilisateur
        $user->delete();

        return response()->json([
            'message' => "Utilisateur, ses équipes et tournois associés supprimés"
        ]);
    }



    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'string|nullable',
            'pseudo' => 'string|nullable|unique:users,pseudo,' . $user->id,
        ]);

        // Mettez à jour les champs si fournis
        if ($request->has('name')) {
            $user->name = $request->input('name');
        }
        if ($request->has('pseudo')) {
            $user->pseudo = $request->input('pseudo');
        }

        $user->save();

        return response()->json($user);
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required',
            'newPassword' => [
                'string',
                'different:oldPassword',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->uncompromised()
            ]
        ]);

        $user = Auth::user();

        // Vérifier si l'ancien mot de passe est correct
        if (!Hash::check($request->input('oldPassword'), $user->password)) {
            return response()->json(['message' => 'Ancien mot de passe incorrect'], 400);
        }

        // Mise à jour du mot de passe
        $user->password = Hash::make($request->input('newPassword'));
        $user->save();

        return response()->json(['message' => 'Mot de passe mis à jour avec succès']);
    }

}
