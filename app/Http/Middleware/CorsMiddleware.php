<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Ajout des en-têtes CORS
        $response->headers->set('Access-Control-Allow-Origin', '*'); 
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        // Gérer la méthode OPTIONS
        if ($request->getMethod() == "OPTIONS") {
            return response()->json([], 200);
        }

        return $response;
    }
}
