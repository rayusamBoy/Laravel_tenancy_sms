<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Http\Requests\Grade\GradeRequest;
use App\Repositories\ExamRepo;
use App\Repositories\MyClassRepo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GradeController extends Controller implements HasMiddleware
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
        $d['grades'] = $this->exam->allGrades();
        $d['class_types'] = $this->my_class->getTypes();

        return view('pages.support_team.grades.index', $d);
    }

    public function store(GradeRequest $req)
    {
        $data = $req->all();
        $this->exam->createGrade($data);

        return back()->with('flash_success', __('msg.store_ok'));
    }

    public function edit($id)
    {
        $d['class_types'] = $this->my_class->getTypes();
        $d['gr'] = $this->exam->findGrade($id);

        return view('pages.support_team.grades.edit', $d);
    }

    public function update(GradeRequest $req, $id)
    {
        $data = $req->all();
        $this->exam->updateGrade($id, $data);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function destroy($id)
    {
        $this->exam->deleteGrade($id);

        return back()->with('flash_success', __('msg.del_ok'));
    }
}
