<?php

namespace App\Traits;

trait AuthenticatesUsers
{
    // Basic implementation
    protected function authenticated($request, $user)
    {
        return redirect()->intended($this->redirectPath());
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function redirectPath()
    {
        return '/';
    }
}
