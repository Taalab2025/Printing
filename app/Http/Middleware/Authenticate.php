<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Determine which login route to redirect to based on the requested URL
        if ($request->is('admin/*')) {
            return route('admin.login');
        } elseif ($request->is('vendor/*')) {
            return route('vendor.login');
        }

        return route('login');
    }
}
