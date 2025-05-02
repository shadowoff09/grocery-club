<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        $previousUrl = url()->previous();

        if (str_contains($previousUrl, '/dashboard')) {
            return redirect()->route('login');
        } else if (str_contains($previousUrl, '/catalog')) {
            return redirect()->route('catalog.index');
        } else {
            return redirect('/');
        }
    }
}
