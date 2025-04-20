<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
//use App\Traits\AuthenticatesUsers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        $login = request()->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        request()->merge([$field => $login]);
        return $field;
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
          //  'g-recaptcha-response' => 'required|captcha',
        ], [
           // 'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
           // 'g-recaptcha-response.captcha' => 'reCAPTCHA verification failed. Please try again.',
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Update last login information
        $user->last_login = Carbon::now();
        $user->last_ip = $request->ip();
        $user->save();

        // Create audit log entry
        if (class_exists('App\Models\AuditLog')) {
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
//'user_role' => $user->roles ? $user->roles->pluck('name')->first() : 'unknown',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'method' => 'POST',
                'url' => $request->fullUrl(),
                'route' => 'login',
                'request_data' => json_encode(['action' => 'user_login']),
                'status_code' => 200,
            ]);
        }

        // Log the login
        \Log::channel('security')->info('User logged in', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Redirect based on user role
if ($user->isAdmin()) {
    return redirect()->route('admin.dashboard');
} elseif ($user->isVendor()) {
    return redirect()->route('vendor.dashboard');
}


        return redirect()->route('customer.dashboard');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Create audit log entry for logout
            if (class_exists('App\Models\AuditLog')) {
                \App\Models\AuditLog::create([
                    'user_id' => $user->id,
                    'user_role' => $user->roles->pluck('name')->first() ?? 'unknown',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'method' => 'POST',
                    'url' => $request->fullUrl(),
                    'route' => 'logout',
                    'request_data' => json_encode(['action' => 'user_logout']),
                    'status_code' => 200,
                ]);
            }

            // Log the logout
            \Log::channel('security')->info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Check if user is locked out due to too many failed attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            
            // Log the lockout
            \Log::channel('security')->warning('User account locked due to too many failed login attempts', [
                'login' => $request->input('login'),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // Log failed login attempt
        \Log::channel('security')->warning('Failed login attempt', [
            'login' => $request->input('login'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Increment the failed login attempts
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
