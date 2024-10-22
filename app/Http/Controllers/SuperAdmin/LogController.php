<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use App\Repositories\LogRepo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class LogController extends Controller
{
    protected $user, $my_class, $log;

    public function __construct(UserRepo $user, MyClassRepo $my_class, LogRepo $log)
    {
        $this->user = $user;
        $this->my_class = $my_class;
        $this->log = $log;
    }

    public function index()
    {
        $d['activities'] = $this->log->getActivites();
        $d['login_histories'] = $this->log->getLoginHistories()->sortBy('last_login', SORT_REGULAR, TRUE);
        $d['user_types'] = $this->user->getAllTypes();

        return view('pages.super_admin.logs', $d);
    }

    public function reset_login_hist($user_id)
    {
        $user_id = Qs::decodeHash($user_id);
        $current_timestamp = Carbon::now()->toDateTimeString();
        $data = ['login_times' => 0, 'last_reset' => $current_timestamp, 'last_login' => NULL];

        $this->log->updateLoginHist(['user_id' => $user_id], $data);

        return redirect()->back()->with('flash_success', __('msg.login_history_reset_success'));
    }

    public function delete_activity($log_id)
    {
        $this->log->deleteActivityLog($log_id);

        return back()->with('flash_success', __('msg.del_ok'));
    }

    // Clean activity log
    public function activity_log_clean()
    {
        $status = Artisan::call('activitylog:clean --force');
        if ($status === 0) {
            $ok = true;
            $message = 'All recorded activity older than ' . config('activitylog.delete_records_older_than_days') . ' deleted successfully.';
        } else {
            $ok = false;
            $message = 'Activity log cleaning failed.';
        }

        return Qs::json($message, $ok);
    }
}
