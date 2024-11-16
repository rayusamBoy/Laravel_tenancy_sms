<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Http\Requests\Division\DivisionCreate;
use App\Http\Requests\Division\DivisionUpdate;
use App\Repositories\ExamRepo;
use App\Repositories\MyClassRepo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DivisionController extends Controller implements HasMiddleware
{
    protected $exam, $my_class;

    public function __construct(ExamRepo $exam, MyClassRepo $my_class)
    {
        $this->exam = $exam;
        $this->my_class = $my_class;
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
        $d['divisions'] = $this->exam->allDivisions();
        $d['class_types'] = $this->my_class->getTypes();

        return view('pages.support_team.divisions.index', $d);
    }

    public function store(DivisionCreate $req)
    {
        $data = $req->all();
        $this->exam->createDivision($data);

        return back()->with('flash_success', __('msg.store_ok'));
    }

    public function edit($id)
    {
        $d['class_types'] = $this->my_class->getTypes();
        $d['dv'] = $this->exam->findDivision($id);

        return view('pages.support_team.divisions.edit', $d);
    }

    public function update(DivisionUpdate $req, $id)
    {
        $data = $req->all();
        $this->exam->updateDivision($id, $data);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function destroy($id)
    {
        $this->exam->deleteDivision($id);

        return back()->with('flash_success', __('msg.del_ok'));
    }
}
