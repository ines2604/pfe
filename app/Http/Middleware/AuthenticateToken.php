<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AuthenticateToken
{ 
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorizationHeader = $request->header('Authorization');

        // Vérifier si l'en-tête contient un token au format "Bearer {token}"
        if (!$authorizationHeader || !preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        $token = $matches[1]; // Extraire uniquement le token

        // Vérifier l'existence du token dans la base de données
        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        // Utiliser setUserResolver pour définir l'utilisateur sur la requête
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}