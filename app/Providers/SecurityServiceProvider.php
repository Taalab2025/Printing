<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configure security-related logging channels
        $this->configureSecurityLogging();
        
        // Configure rate limiting
        $this->configureRateLimiting();
        
        // Configure password policies
        $this->configurePasswordPolicies();
    }
    
    /**
     * Configure security-related logging channels
     */
    protected function configureSecurityLogging(): void
    {
        // Create a dedicated security log channel
        Config::set('logging.channels.security', [
            'driver' => 'daily',
            'path' => storage_path('logs/security.log'),
            'level' => 'debug',
            'days' => 14,
        ]);
        
        // Create a dedicated audit log channel
        Config::set('logging.channels.audit', [
            'driver' => 'daily',
            'path' => storage_path('logs/audit.log'),
            'level' => 'info',
            'days' => 30,
        ]);
    }
    
    /**
     * Configure rate limiting
     */
    protected function configureRateLimiting(): void
    {
        // Configure rate limiting for authentication routes
        Config::set('auth.throttle.enabled', true);
        Config::set('auth.throttle.max_attempts', 5);
        Config::set('auth.throttle.decay_minutes', 1);
        
        // Configure rate limiting for API routes
        Config::set('api.throttle.enabled', true);
        Config::set('api.throttle.max_attempts', 60);
        Config::set('api.throttle.decay_minutes', 1);
    }
    
    /**
     * Configure password policies
     */
    protected function configurePasswordPolicies(): void
    {
        // Set minimum password length
        Config::set('auth.password_min_length', 8);
        
        // Require mixed case
        Config::set('auth.password_require_mixed_case', true);
        
        // Require at least one number
        Config::set('auth.password_require_number', true);
        
        // Require at least one special character
        Config::set('auth.password_require_special_char', true);
        
        // Password expiration in days (0 = never)
        Config::set('auth.password_expiration_days', 90);
        
        // Number of previous passwords to remember (prevent reuse)
        Config::set('auth.password_history_count', 3);
    }
}
