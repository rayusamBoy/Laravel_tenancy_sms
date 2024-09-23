<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Events\UserLoggedIn;
use App\Models\LoginHistory;

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
        $current_timestamp = Carbon::now()->toDateTimeString();
        $user_info = $event->user;
        $saved_hist = LoginHistory::firstOrCreate(['user_id' => $user_info->id]);
        $saved_hist->update(['last_login' => $current_timestamp, 'login_times' => (int) $saved_hist->login_times + 1]);
    }
}
