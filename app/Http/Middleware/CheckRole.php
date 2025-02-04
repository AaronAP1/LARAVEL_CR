<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles  Roles permitidos para esta ruta
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Acceso no autorizado.'], 403);
        }

        // Verifica si el rol del usuario está en los roles permitidos
        if (!in_array($user->role, $roles)) {
            return response()->json(['error' => 'Acceso denegado. No tienes permisos para esta acción.'], 403);
        }

        return $next($request);
    }
}
