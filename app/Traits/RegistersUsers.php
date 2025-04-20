<?php

namespace App\Traits;

trait RegistersUsers
{
    // Basic implementation
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function registered($request, $user)
    {
        return redirect($this->redirectPath());
    }

    public function redirectPath()
    {
        return '/';
    }
}
