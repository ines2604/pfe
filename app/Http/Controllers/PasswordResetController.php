<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        try {

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['error' => 'L\'email n\'est pas inscrit.'], 404);
            }

            Log::info('Received request to send reset link for email: ' . $request->email);

            $request->validate(['email' => 'required|email|exists:users,email']);

            Log::info('Validation passed for email: ' . $request->email);


            $token = Str::random(60);
            $user->password_reset_token = $token;
            $user->password_reset_expires_at = now()->addHours(1);
            $user->save();

            Log::info('Reset token generated for email: ' . $request->email);

            Mail::to($user->email)->send(new ResetPasswordMail($token, $user->email));

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

            $user = User::where('email', $request->email)->first();

            if (!$user || $user->password_reset_token !== $request->token || $user->password_reset_expires_at < now()) {
                return response()->json(['error' => 'Token invalide ou expiré.'], 400);
            }

            $user->password = Hash::make($request->password);
            $user->password_reset_token = null;
            $user->password_reset_expires_at = null;
            $user->save();

            return response()->json(['message' => 'Mot de passe réinitialisé avec succès!'], 200);
        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur interne est survenue.'], 500);
        }
    }
}