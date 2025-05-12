<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// GUEST ROUTES
// -----------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    // Authentication
    Volt::route('login', 'auth.login')
        ->name('login');
    Volt::route('register', 'auth.register')
        ->name('register');
    
    // Password Reset
    Volt::route('forgot-password', 'auth.forgot-password')
        ->name('password.request');
    Volt::route('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');
});

// AUTHENTICATED ROUTES
// -----------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    // Email Verification
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    
    // Password Confirmation
    Volt::route('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');
});

// SHARED ROUTES
// -----------------------------------------------------------------------------
Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
