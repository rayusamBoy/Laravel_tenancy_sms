<?php

namespace App\Repositories;

use App\Models\Activity;
use App\Models\LoginHistory;

class LogRepo
{
    /*********** Activities ***************/

    public function getActivites()
    {
        return Activity::with('user')->get();
    }

    public function deleteActivityLog(int $log_id)
    {
        return Activity::find($log_id)->delete();
    }

    /*********** Login History ***************/

    public function getLoginHistories()
    {
        return LoginHistory::with('user')->get();
    }

    public function updateLoginHist($where, $data)
    {
        return LoginHistory::where($where)->update($data);
    }
}
