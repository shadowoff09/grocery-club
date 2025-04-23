<?php

use App\Http\Controllers\BoardController;
use App\Http\Middleware\CheckIsBoardMember;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/security', 'settings.security')->name('settings.security');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Volt::route('settings/danger-zone', 'settings.danger-zone')->name('settings.danger-zone');
});

Route::middleware(['auth', CheckIsBoardMember::class])->group(function () {
    Route::get('/board/users', [BoardController::class, 'userManagement'])->name('board.users');
    Route::get('/board/users/{user}', [BoardController::class, 'userDetail'])->name('board.users.show');
});

require __DIR__.'/auth.php';
