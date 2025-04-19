<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for database migrations
        Schema::defaultStringLength(191);
        
        // Register custom validation rules
        $this->registerCustomValidators();
        
        // Register custom Blade directives
        $this->registerBladeDirectives();
        
        // Define authorization gates
        $this->defineGates();
    }
    
    /**
     * Register custom validation rules
     */
    protected function registerCustomValidators(): void
    {
        // Validate Arabic text
        Validator::extend('arabic', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}]/u', $value);
        });
        
        // Validate phone number format
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[+]?[0-9]{8,15}$/', $value);
        });
        
        // Validate file is a valid printable document
        Validator::extend('printable_document', function ($attribute, $file, $parameters, $validator) {
            $allowedTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'ai', 'psd', 'eps', 'indd'];
            return in_array(strtolower($file->getClientOriginalExtension()), $allowedTypes);
        });
        
        // Validate secure password (min 8 chars, at least one uppercase, one lowercase, one number, one special char)
        Validator::extend('secure_password', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value);
        });
    }
    
    /**
     * Register custom Blade directives
     */
    protected function registerBladeDirectives(): void
    {
        // Role-based directive
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$role})): ?>";
        });
        
        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });
        
        // Permission-based directive
        Blade::directive('permission', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$permission})): ?>";
        });
        
        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });
        
        // Language directive for bilingual content
        Blade::directive('lang', function ($expression) {
            return "<?php echo app()->getLocale() == 'ar' && isset({$expression}_ar) ? {$expression}_ar : {$expression}; ?>";
        });
    }
    
    /**
     * Define authorization gates
     */
    protected function defineGates(): void
    {
        // Define gates for admin access
        Gate::define('access-admin', function (User $user) {
            return $user->hasRole('admin');
        });
        
        // Define gates for vendor access
        Gate::define('access-vendor', function (User $user) {
            return $user->hasRole('vendor');
        });
        
        // Define gates for managing vendors
        Gate::define('manage-vendors', function (User $user) {
            return $user->hasRole('admin');
        });
        
        // Define gates for managing services
        Gate::define('manage-services', function (User $user) {
            return $user->hasRole('admin') || $user->hasRole('vendor');
        });
        
        // Define gates for managing own services (vendors)
        Gate::define('manage-own-services', function (User $user, $service) {
            if ($user->hasRole('admin')) {
                return true;
            }
            
            if ($user->hasRole('vendor') && $user->vendor && $service->vendor_id === $user->vendor->id) {
                return true;
            }
            
            return false;
        });
        
        // Define gates for managing orders
        Gate::define('manage-orders', function (User $user, $order = null) {
            if ($user->hasRole('admin')) {
                return true;
            }
            
            if ($user->hasRole('vendor') && $user->vendor && $order && $order->service->vendor_id === $user->vendor->id) {
                return true;
            }
            
            if ($order && $order->user_id === $user->id) {
                return true;
            }
            
            return false;
        });
    }
}
