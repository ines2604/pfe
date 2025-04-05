<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        try {

            $client = Client::where('email', $request->email)->first();

            if (!$client) {
                return response()->json(['error' => 'L\'email n\'est pas inscrit.'], 404);
            }

            Log::info('Received request to send reset link for email: ' . $request->email);

            $request->validate(['email' => 'required|email|exists:client,email']);

            Log::info('Validation passed for email: ' . $request->email);


            $token = Str::random(60);
            $client->password_reset_token = $token;
            $client->password_reset_expires_at = now()->addHours(1);
            $client->save();

            Log::info('Reset token generated for email: ' . $request->email);

            Mail::to($client->email)->send(new ResetPasswordMail($token, $client->email));

            return response()->json(['message' => 'Un lien de réinitialisation a été envoyé.', 'token' => $token], 200);
        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur interne est survenue.'], 500);
        }
    }

    public function reset(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
            ]);

            $client = Client::where('email', $request->email)->first();

            if (!$client || $client->password_reset_token !== $request->token || $client->password_reset_expires_at < now()) {
                return response()->json(['error' => 'Token invalide ou expiré.'], 400);
            }

            $client->password = Hash::make($request->password);
            $client->password_reset_token = null;
            $client->password_reset_expires_at = null;
            $client->save();

            return response()->json(['message' => 'Mot de passe réinitialisé avec succès!'], 200);
        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur interne est survenue.'], 500);
        }
    }
}