<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\EventRepo;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    protected $event;

    public function __construct(EventRepo $event)
    {
        $this->event = $event;
    }

    public function index()
    {
        if (Qs::userIsTeamSA())
            $data['events'] = $this->event->all();

        $data['selected'] = false;

        return view('pages.schedule.index', $data);
    }

    public function create_event(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->id();
        $this->event->create($data);

        return Qs::json('The event ' . '"' . $data['name'] . '"' . ' added successfully.');
    }

    public function edit_event($event_id)
    {
        $d['event'] = $this->event->getRecord(['id' => $event_id])->first();
        $d['events'] = $this->event->all();
        $d['selected'] = true;

        return view('pages.schedule.index', $d);
    }

    public function update_event(Request $request, $event_id)
    {
        $this->event->update($event_id, $request->except("_token"));

        return Qs::jsonUpdateOk();
    }

    public function delete_event(Request $req)
    {
        // If the event is the one that is required for the functioning of the calendar codes deny deleting.
        // Note: at least one event is required for the calendar codes to display dates.
        if ($req->id === 1)
            return back()->with('pop_warning', 'Cannot delete required event.');

        $this->event->delete($req->only("id"));

        return back()->with('flash_success', __('msg.del_ok'));
    }
}
