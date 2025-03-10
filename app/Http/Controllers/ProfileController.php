<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{       
    public function getUser(Request $request) {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Utilisateur non trouvé'], 404);
            }
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur interne du serveur'], 500);
        }
    }
    public function update(Request $request){
        
        $user = User::findOrFail(Auth::id());
        $request->validate([
            'nom' => 'required|string|min:2|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/',
            'prenom' => 'required|string|min:2|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'tel' => 'required',
            'naissance' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->toDateString(), 'after:1900-01-01'],
            'pays' => 'required|string',
        ]);
    
        $user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'tel' => $request->tel,
            'naissance' => $request->naissance,
            'pays' => $request->pays,
        ]);
        return response()->json(['message' => 'Profil mis à jour avec succès !', 'user' => $user]);
    }
    public function update_password(Request $request){
        
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'ancienPassword' => 'required|string',
            'nouveauPassword' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[\W]/',
            ],
        ]);
        if (!Hash::check($request->ancienPassword, $user->password)) {
            return response()->json(['errors' => ['ancienPassword' => ['L\'ancien mot de passe est incorrect.']]], 422);
        }

        $user->update( [
            'password'=> Hash::make($request->nouveauPassword),
        ]);

        return response()->json(['message' => 'Mot de passe mis à jour avec succès.'], 200);
    }
}