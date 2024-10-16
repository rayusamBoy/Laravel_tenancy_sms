<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\AssessmentRepo;
use App\Repositories\BookRepo;
use App\Repositories\UserRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\ExamRepo;
use App\Repositories\PaymentRepo;
use App\Repositories\NoticeRepo;
use App\Repositories\StudentRepo;
use App\Repositories\MarkRepo;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpReq;

class HomeController extends Controller
{
    protected $user, $class, $section, $exam, $payment, $notice, $student, $mark, $year, $assessment, $book;
    public function __construct(UserRepo $user, MyClassRepo $class, MyClassRepo $section, BookRepo $book, ExamRepo $exam, PaymentRepo $payment, NoticeRepo $notice, StudentRepo $student, MarkRepo $mark, AssessmentRepo $assessment)
    {
        $this->user = $user;
        $this->class = $class;
        $this->section = $section;
        $this->exam = $exam;
        $this->payment = $payment;
        $this->notice = $notice;
        $this->student = $student;
        $this->mark = $mark;
        $this->assessment = $assessment;
        $this->book = $book;
        $this->year = Qs::getSetting('current_session');
    }

    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function privacy_policy()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');

        return view('pages.other.privacy_policy', $data);
    }

    public function terms_of_use()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');

        return view('pages.other.terms_of_use', $data);
    }

    public function dashboard(HttpReq $request)
    {
        $unviewed_notices = $viewed_notices = $d = [];
        $unviewed_count = 0; // Unviewed notices count

        if (Qs::userIsAdministrative() or Qs::userIsLibrarian()) {
            $d['users'] = $this->user->all();
            $d['subjects'] = $this->class->getUniqueSubjectsNames();
            $d['class'] = $this->class->all();
            $d['section'] = $this->section->getAllSections();
            $d['exams_count'] = $this->exam->getPublishedExamsCount();
            $d['assessments_count'] = $this->assessment->get()->whereNotNull('exam')->count();
            $d['total_pay_records'] = $this->payment->countAllCleanPaidRecs();
            $d['student_recs'] = $this->student->all()->whereNotNull('user');
            $d['grad_students'] = $this->student->getPromotions(['grad' => 1]);
            $d['books_count'] = $this->book->countAll();
            $d['books_requests'] = $this->book->allRequests();
        }

        $notices = $this->notice->allExceptAuth()->sortBy("id", SORT_REGULAR, true);
        // Count all users who did not view notice(s) - whose id not in viewers_ids.
        foreach ($notices as $ntc) {
            $v_ids = $ntc->viewers_ids;
            if ($v_ids == NULL || !in_array(Auth::id(), json_decode($v_ids))) {
                $unviewed_count++;
                $unviewed_notices[] = $ntc;
            } else {
                $viewed_notices[] = $ntc;
            }
        }

        $un_paginated = $this->get_paginator($request, $unviewed_notices, "unviewed-notices-page");
        $vn_paginated = $this->get_paginator($request, $viewed_notices, "viewed-notices-page");

        $d["unviewed_count"] = $unviewed_count;

        if ($request->ajax()) {
            if (str_contains($request->fullUrl(), 'unviewed'))
                $d["unviewed_notices"] = $un_paginated;
            else
                $d["viewed_notices"] = $vn_paginated;

            $d['current_url'] = $request->url();

            return view('pages/support_team/notices/show', $d);
        }

        $d["unviewed_notices"] = $un_paginated;
        $d["viewed_notices"] = $vn_paginated;

        return view('pages.support_team.dashboard', $d);
    }

    private function get_paginator(HttpReq $request, array $items, string $page_name)
    {
        $total = count($items);
        $per_page = 4;
        $current_page = $request->input($page_name) ?? 1;
        $starting_point = $current_page * $per_page - $per_page;
        $viewed_notices = array_slice($items, $starting_point, $per_page, true);
        return new LengthAwarePaginator($viewed_notices, $total, $per_page, $current_page, [
            'pageName' => $page_name,
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }
}
