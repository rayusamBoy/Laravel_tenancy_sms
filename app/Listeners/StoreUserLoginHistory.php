<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Models\LoginHistory;
use Carbon\Carbon;

class StoreUserLoginHistory
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\UserLoggedIn  $event
     * @return void
     */
    public function handle(UserLoggedIn $event)
    {
        $user = $event->user;
        $current_timestamp = Carbon::now()->toDateTimeString();
        
        $saved_hist = LoginHistory::firstOrCreate(['user_id' => $user->id]);
        $saved_hist->update(['last_login' => $current_timestamp, 'login_times' => (int) $saved_hist->login_times + 1]);
    }
}
