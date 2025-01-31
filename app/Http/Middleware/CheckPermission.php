<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        // Get the logged-in user
        $user = Auth::user();

        // Check if the user exists and has the required permission
        if (!$user || !$user->hasPermissionTo($permission)) {
            abort(403, 'Unauthorized action.');
        }

        // Allow access
        return $next($request);
    }
}