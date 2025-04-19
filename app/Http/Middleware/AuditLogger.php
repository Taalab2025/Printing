<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuditLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Process the request
        $response = $next($request);

        // Only log write operations (POST, PUT, PATCH, DELETE)
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $this->logRequest($request, $response);
        }

        return $response;
    }

    /**
     * Log the request details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return void
     */
    protected function logRequest(Request $request, Response $response): void
    {
        $user = $request->user();
        $userId = $user ? $user->id : 'guest';
        $userRole = $user && method_exists($user, 'roles') ? $user->roles->pluck('name')->first() : 'guest';

        // Sanitize request data to remove sensitive information
        $requestData = $request->except(['password', 'password_confirmation', 'current_password', 'credit_card', 'card_number']);

        // Create audit log entry
        $logData = [
            'user_id' => $userId,
            'user_role' => $userRole,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route' => $request->route() ? $request->route()->getName() : 'unknown',
            'request_data' => json_encode($requestData),
            'status_code' => $response->getStatusCode(),
        ];

        // Save to database if we have a user
        if ($user && class_exists('App\Models\AuditLog')) {
            \App\Models\AuditLog::create($logData);
        }

        // Also log to file for system-level auditing
        Log::channel('audit')->info('Audit log entry', $logData);
    }
}
