<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
//use Illuminate\Foundation\Auth\RegistersUsers;
use App\Traits\RegistersUsers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'phone', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'secure_password', 'confirmed'],
            'terms' => ['required', 'accepted'],
            //'g-recaptcha-response' => ['required', 'captcha'],
        ], [
            'phone.phone' => 'The phone number format is invalid.',
            'password.secure_password' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'terms.accepted' => 'You must accept the terms and conditions.',
         //   'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
         //   'g-recaptcha-response.captcha' => 'reCAPTCHA verification failed. Please try again.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'last_ip' => request()->ip(),
            'last_login' => now(),
        ]);

        // Assign customer role by default
        $customerRole = Role::where('name', 'customer')->first();
        if ($customerRole) {
            $user->roles()->attach($customerRole);
        }

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        // Log the registration
        \Log::channel('security')->info('New user registered', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        // Create audit log entry
        if (class_exists('App\Models\AuditLog')) {
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'user_role' => 'customer',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'method' => 'POST',
                'url' => $request->fullUrl(),
                'route' => 'register',
                'request_data' => json_encode(['action' => 'user_registration']),
                'status_code' => 200,
            ]);
        }
    }
}
