<?php

namespace App\Listeners;

use App\Events\SuccessfulLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SuccessfulLogin $event): void
    {
        // Update the user's last login timestamp
        $event->user->update([
            'last_login_at' => now(),
        ]);

        // Log the successful login
        Log::info('User logged in successfully', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'timestamp' => now(),
        ]);
    }
}
