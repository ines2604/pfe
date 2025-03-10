<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|min:2|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/',
            'prenom' => 'required|string|min:2|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/',
            'email' => 'required|string|email|unique:users',
            'tel' => 'required',
            'naissance' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->toDateString(), 'after:1900-01-01'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[\W]/',
            ],
            'pays' => 'required|string',
        ]);

        $user = new User([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'tel' => $request->tel,
            'naissance' => $request->naissance,
            'password' => Hash::make($request->password),
            'pays' => $request->pays,
        ]);
        $user->save();

        $token = $user->generateToken();

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user->makeHidden(['password', 'remember_token']),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Adresse e-mail introuvable.'
            ], 404);
        }
        
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Mot de passe incorrect.'
            ], 401);
        }

        $token = $user->generateToken();

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user->makeHidden(['password', 'remember_token']),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->removeToken();
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'User not authenticated'], 401);
    }
}