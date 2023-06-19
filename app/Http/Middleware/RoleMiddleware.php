<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $superAdminRole = Role::where('name', 'super-admin')->first();


        // Check if the super-admin role exists and the authenticated user has that role
        if (!$superAdminRole || !Auth::user()->hasRole($superAdminRole)) {
            return new Response('Not authorized.', 403);
        }
        // $AdminRole = Role::where('name', 'admin')->first();
        // if (!$AdminRole || !Auth::user()->hasRole($AdminRole)) {
        //     return new Response('Not authorized.', 403);
        // }

        return $next($request);
    }
}
