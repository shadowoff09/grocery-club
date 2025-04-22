<?php

use App\Http\Middleware\CheckIsBoardMember;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', CheckIsBoardMember::class])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth', CheckIsBoardMember::class])->group(function () {
    Route::view('board', 'board')->name('board');
    Route::view('board/meetings', 'board.meetings')->name('board.meetings');
    Route::view('board/members', 'board.members')->name('board.members');
    Route::view('board/finances', 'board.finances')->name('board.finances');
});

require __DIR__.'/auth.php';
