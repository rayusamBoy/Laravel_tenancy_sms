<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Repositories\NoticeRepo;
use App\Repositories\UserRepo;
use App\Http\Requests\Notice\NoticeCreate;
use App\Http\Requests\Notice\NoticeSetAsViewed;
use App\Http\Requests\Notice\NoticeUpdate;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    protected $notice, $user, $year;

    public function __construct(NoticeRepo $notice, UserRepo $user)
    {
        $this->notice = $notice;
        $this->user = $user;
        $this->year = Qs::getCurrentSession();
    }

    public function index()
    {
        $d['notices'] = $this->notice->allOrOnlyMine();
        $d['edit'] = false;

        return view('pages.support_team.notices.index', $d);
    }

    public function store(NoticeCreate $req)
    {
        $data = $req->all();
        $data['from_id'] = Auth::id();
        $this->notice->create($data);

        return Qs::jsonStoreOk();
    }

    public function update_record(NoticeUpdate $req, $id)
    {
        $data = $req->all();
        $data['editor_id'] = Auth::id();
        $this->notice->update($id, $data);

        return Qs::jsonUpdateOk();
    }

    public function edit($id)
    {
        $id = Qs::decodeHash($id);
        $d['notice'] = $this->notice->getById($id)->first();
        $d['notices'] = $this->notice->allOrOnlyMine();
        $d['edit'] = true;

        return view('pages.support_team.notices.index', $d);
    }

    public function set_as_viewed(NoticeSetAsViewed $req)
    {
        $ntc_id = $req->id;
        $viewers_ids = json_decode($this->notice->getById($ntc_id)->value("viewers_ids"));
        // Push auth user id to viewers ids
        $viewers_ids[] = Auth::id();
        // Update the viewers ids
        $this->notice->update($ntc_id, ["viewers_ids" => json_encode($viewers_ids)]);

        return Qs::json("updated");
    }

    public function destroy($notice_id)
    {
        $this->notice->delete($notice_id);

        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
