<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        foreach ($roles as $role) {
            // Check if user has the role
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // If user doesn't have any of the required roles
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized. Insufficient permissions.'], 403);
        }

        // Redirect based on user's actual role
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('vendor')) {
            return redirect()->route('vendor.dashboard');
        } else {
            return redirect()->route('customer.dashboard')
                ->with('error', 'You do not have permission to access this area.');
        }
    }
}
