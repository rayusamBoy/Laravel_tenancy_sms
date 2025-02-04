<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Section\SectionCreate;
use App\Http\Requests\Section\SectionUpdate;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SectionController extends Controller implements HasMiddleware
{
    protected $my_class, $user;

    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->my_class = $my_class;
        $this->user = $user;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('teamSA', except: ['destroy']),
            new Middleware('superAdmin', only: ['destroy']),
        ];
    }

    public function index()
    {
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['teachers'] = $this->user->getUserByTypes(['teacher', 'admin', 'super_admin']);

        return view('pages.support_team.sections.index', $d);
    }

    public function store(SectionCreate $req)
    {
        $data = $req->all();
        $this->my_class->createSection($data);

        return Qs::jsonStoreOk();
    }

    public function edit($id)
    {
        $d['s'] = $s = $this->my_class->findSection($id);
        $d['teachers'] = $this->user->getUserByTypes(['teacher', 'admin', 'super_admin']);

        return $s === null ? Qs::goWithDanger('sections.index') : view('pages.support_team.sections.edit', $d);
    }

    public function update(SectionUpdate $req, $id)
    {
        $data = $req->only(['name', 'teacher_id']);
        $this->my_class->updateSection($id, $data);

        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        if ($this->my_class->isActiveSection($id))
            return back()->with('pop_warning', 'Every class must have a default section, You cannot delete It.');

        if ($this->my_class->sectionHasStudent($id))
            return back()->with('pop_warning', 'This Section has Student(s) with it, You cannot delete it.');

        $this->my_class->deleteSection($id);

        return back()->with('flash_success', __('msg.del_ok'));
    }
}
