<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user() || !auth()->user()->admin) {

            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        return $next($request);
    }
}

