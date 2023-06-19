<?php

namespace App\Http\Controllers;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SeederController extends Controller
{
    public function __invoke(Request $request, Closure $next)
    {
        // Check if the authenticated user has the "super_admin" role
        if ($request->user() && $request->user()->hasRole('super_admin')) {
            return $next($request);
        }

        return response()->json(['message' => 'You do not have access.'], 403);
    }
}
