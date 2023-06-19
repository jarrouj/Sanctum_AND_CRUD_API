<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmailAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $allowedEmail = 'super_admin@gmail.com';
        $user = Auth::user();

        if ($user && $user->email === $allowedEmail) {
            return $next($request);
        }

        return response()->json(['message' => 'You do not have access'], 403);
    }
}
