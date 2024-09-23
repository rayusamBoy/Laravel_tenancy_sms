<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller implements HasMiddleware
{
    protected $my_class, $student;

    public function __construct(MyClassRepo $my_class, StudentRepo $student)
    {
        $this->my_class = $my_class;
        $this->student = $student;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('teamSA'),
        ];
    }

    public function promotion($status, $fc = NULL, $fs = NULL, $tc = NULL, $ts = NULL)
    {
        $d['old_year'] = $old_yr = Qs::getSetting('current_session');
        $old_yr = explode('-', $old_yr);
        $d['new_year'] = ++$old_yr[0] . '-' . ++$old_yr[1];
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['selected'] = false;

        if ($fc && $fs && $tc && $ts) {
            $d['selected'] = true;
            $d['status'] = $status; // Student status, whether is an active student (1) or graduated student (0)
            $d['fc'] = $fc;
            $d['fs'] = $fs;
            $d['tc'] = $tc;
            $d['ts'] = $ts;

            $d['students'] = $sts = ($status == 1) ? $this->student->getRecord(['my_class_id' => $fc, 'section_id' => $fs, 'session' => $d['old_year']])->with('promotion')->get()->whereNotNull('user') : $this->student->getRecord2(['my_class_id' => $fc, 'section_id' => $fs, 'session' => $d['old_year']])->with('promotion')->get()->whereNotNull('user');

            if ($sts->count() < 1)
                return redirect()->route('students.promotion')->with('flash_success', __('msg.nstp'));
        }

        return view('pages.support_team.students.promotion.index', $d);
    }

    public function selector(Request $req)
    {
        return redirect()->route('students.promotion', [$req->status, $req->fc, $req->fs, $req->tc, $req->ts]);
    }

    public function promote(Request $req, $status, $fc, $fs, $tc, $ts)
    {
        $d = [];
        $oy = Qs::getSetting('current_session');
        $old_yr = explode('-', $oy);
        $ny = ++$old_yr[0] . '-' . ++$old_yr[1];
        $students = ($status == 1) ? $this->student->getRecord(['my_class_id' => $fc, 'section_id' => $fs, 'session' => $oy])->get()->whereNotNull('user')->sortBy('user.name') : $this->student->getRecord2(['my_class_id' => $fc, 'section_id' => $fs, 'session' => $oy])->get()->whereNotNull('user')->sortBy('user.name');

        if ($students->count() < 1)
            return redirect()->route('students.promotion')->with('flash_danger', __('msg.srnf'));

        foreach ($students as $st) {
            $p = 'p-' . $st->id;
            $p = $req->$p;

            if ($p === 'P') { // Promote
                $d['my_class_id'] = $tc;
                $d['section_id'] = $ts;
                $d['grad'] = 0;
                $d['grad_date'] = NULL;
                $d['session'] = $ny;
            }
            if ($p === 'D') { // Don't Promote
                $d['my_class_id'] = $fc;
                $d['section_id'] = $fs;
                $d['grad'] = $st->grad ?? 0;
                $d['grad_date'] = NULL;
                $d['session'] = $oy;
            }
            if ($p === 'G') { // Graduated
                $d['my_class_id'] = $fc;
                $d['section_id'] = $fs;
                $d['grad'] = 1;
                $d['grad_date'] = $oy;
            }

            $this->student->updateRecord($st->id, $d);

            //  Insert New Promotion Data
            $promote['from_class'] = $fc;
            $promote['from_section'] = $fs;
            $promote['grad'] = ($p === 'G') ? 1 : 0;
            $promote['to_class'] = in_array($p, ['D', 'G']) ? $fc : $tc;
            $promote['to_section'] = in_array($p, ['D', 'G']) ? $fs : $ts;
            $promote['student_id'] = $st->id;
            $promote['user_id'] = $st->user_id;
            $promote['user'] = $st->user_id;
            $promote['from_session'] = $oy;
            $promote['to_session'] = $ny;
            $promote['status'] = $p; // Student status whether is Promoted (P), Did not promoted (D), or Graduated (G)

            $this->student->createPromotion($promote);
        }

        return redirect()->route('students.promotion')->with('flash_success', __('msg.update_ok'));
    }

    public function remarks(Request $req, $p_id)
    {
        Validator::make($req->toArray(), [
            'remarks' => 'sometimes|nullable|string|max:500'
        ], [], [])->validate();

        $data = $req->only('remarks');

        $this->student->updatePromotion(['id' => $p_id], $data);

        return Qs::jsonUpdateOk();
    }


    public function manage()
    {
        $data['promotions'] = $this->student->getAllPromotions()->whereNotNull('user');
        $data['old_year'] = Qs::getCurrentSession();
        $data['new_year'] = Qs::getNextSession();

        return view('pages.support_team.students.promotion.reset', $data);
    }

    public function reset($promotion_id)
    {
        $this->reset_single($promotion_id);

        return redirect()->route('students.promotion_manage')->with('flash_success', __('msg.update_ok'));
    }

    public function reset_all()
    {
        $where = ['from_session' => Qs::getCurrentSession(), 'to_session' => Qs::getNextSession()];
        $proms = $this->student->getPromotions($where);

        if ($proms->count() > 0)
            foreach ($proms as $prom) {
                $this->reset_single($prom->id);
            }

        return redirect()->route('students.promotion_manage')->with('flash_success', __('msg.update_ok'));
    }

    protected function delete_old_marks($student_id, $year)
    {
        Mark::where(['student_id' => $student_id, 'year' => $year])->delete();
    }

    protected function reset_single($promotion_id)
    {
        $prom = $this->student->findPromotion($promotion_id);

        $data['my_class_id'] = $prom->from_class;
        $data['section_id'] = $prom->from_section;
        $data['session'] = $prom->from_session;
        $data['grad'] = 0;
        $data['grad_date'] = null;

        $this->student->updateRecord2(['user_id' => $prom->user_id], $data);

        // Delete Marks if Already Inserted for New Session
        $this->delete_old_marks($prom->student_id, Qs::getNextSession());
        
        return $this->student->deletePromotion($promotion_id);
    }
}
