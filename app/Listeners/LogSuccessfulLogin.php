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
        // Get the current custom data or initialize an empty array
        $custom = $event->user->custom ?? [];
        
        // If custom is a JSON string, decode it
        if (is_string($custom)) {
            $custom = json_decode($custom, true) ?? [];
        }
        
        // Update the last_login timestamp in the custom data
        $custom['last_login_at'] = now()->toDateTimeString();
        
        // Update the user's custom field with the new data
        $event->user->update([
            'custom' => $custom,
        ]);

        // Log the successful login
        Log::info('User logged in successfully', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'timestamp' => now(),
        ]);
    }
}
