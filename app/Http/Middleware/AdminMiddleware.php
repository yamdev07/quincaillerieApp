<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Vérifie si l'utilisateur est admin
        // Ici, on suppose que tu as un champ 'is_admin' dans ta table users
        if (Auth::user()->is_admin != 1) {
            abort(403, 'Accès refusé');
        }

        return $next($request);
    }
}
