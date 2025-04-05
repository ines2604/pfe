<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOTP;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|min:2|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/',
            'prenom' => 'required|string|min:2|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/',
            'email' => 'required|string|email|unique:client',
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

        $client = new Client([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'tel' => $request->tel,
            'naissance' => $request->naissance,
            'password' => Hash::make($request->password),
            'pays' => $request->pays,
        ]);
        $otp = Str::random(6);
        $client->otp = $otp;
        $client->otp_expires_at = Carbon::now()->addMinutes(10);

        $client->save();

        Mail::to($client->email)->send(new SendOTP($otp));

        return response()->json([
            'message' => 'Un code OTP a été envoyé à votre adresse e-mail. Veuillez vérifier votre e-mail pour activer votre compte.',
            'user' => $client->makeHidden(['password', 'remember_token']),
            'user_id' => $client->_id,
        ], 201);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:client,_id',
            'otp' => 'required|string',
        ]);
    
        $client = Client::find($request->user_id);
    
        if (!$client) {
            return response()->json([
                'message' => 'Utilisateur introuvable.',
            ], 404);
        }

        if ($client->otp_attempts >= 3) {
            return response()->json([
                'message' => 'Trop de tentatives. Veuillez réessayer plus tard.',
            ], 429);
        }
    
        if ($client->otp !== $request->otp) {
            $client->otp_attempts += 1;
            $client->save();
    
            return response()->json([
                'message' => 'Code OTP invalide.',
            ], 400);
        }
    
        if (Carbon::now()->gt($client->otp_expires_at)) {
            return response()->json([
                'message' => 'Le code OTP a expiré. Veuillez en demander un nouveau.',
            ], 400);
        }
    
        $client->email_verified_at = Carbon::now();
        $client->otp = null;
        $client->otp_expires_at = null;
        $client->otp_attempts = 0;
        $client->save();
    
        return response()->json([
            'message' => 'Adresse e-mail vérifiée avec succès. Vous pouvez maintenant vous connecter.',
        ]);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:client,_id',
        ]);

        $client = Client::find($request->user_id);

        if (!$client) {
            return response()->json([
                'message' => 'Utilisateur introuvable.',
            ], 404);
        }

        $otp = Str::random(6);
        $client->otp = $otp;
        $client->otp_expires_at = Carbon::now()->addMinutes(10);
        $client->otp_attempts = 0; 
        $client->save();

        Mail::to($client->email)->send(new SendOTP($otp));

        return response()->json([
            'message' => 'Un nouveau code OTP a été envoyé à votre adresse e-mail.',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $client = Client::where('email', $request->email)->first();

        if (!$client) {
            return response()->json([
                'message' => 'Adresse e-mail introuvable.',
            ], 404);
        }

        if (!Hash::check($request->password, $client->password)) {
            return response()->json([
                'message' => 'Mot de passe incorrect.',
            ], 401);
        }

        if (!$client->email_verified_at) {
            return response()->json([
                'message' => 'Veuillez vérifier votre adresse e-mail avant de vous connecter.',
            ], 403);
        }

        $token = $client->generateToken();

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => $client->makeHidden(['password', 'remember_token']),
        ]);
    }

    public function logout(Request $request)
    {
        $client = $request->user();
        if ($client) {
            $client->removeToken();
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'User not authenticated'], 401);
    }
}