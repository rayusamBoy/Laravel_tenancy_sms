<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Repositories\ExamRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\PaymentRepo;
use App\Repositories\StudentRepo;
use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Storage;

class SoftDeleteController extends Controller
{
    protected $pay, $my_class, $user, $exm, $year, $student;

    public function __construct(MyClassRepo $my_class, PaymentRepo $pay, UserRepo $user, ExamRepo $exm, StudentRepo $student)
    {
        $this->my_class = $my_class;
        $this->pay = $pay;
        $this->user = $user;
        $this->exm = $exm;
        $this->student = $student;
        $this->year = Qs::getCurrentSession();
    }

    public function bin()
    {
        $data['payments'] = $this->pay->getTrashed();
        $data['users'] = $this->user->getTrashed();
        $data['my_classes'] = $this->my_class->getTrashed();
        $data['exams'] = $this->exm->getTrashed();
        $data['threads'] = Thread::getTrashed();

        return view('pages.super_admin.bin', $data);
    }

    public function empty_soft_deleted_model($model_name)
    {
        $can_soft_delete_models = ['users', 'pyaments', 'my_classes', 'exams', 'threads'];

        if (!in_array($model_name, $can_soft_delete_models))
            return back()->with('pop_error', __('msg.denied'));

        Qs::getDbFacadesTable($model_name)->whereNotNull("deleted_at")->delete();

        return redirect()->back()->with('flash_success', ucfirst($model_name) . ' ' . __('msg.force_del_ok'));
    }

    /** Payments */
    public function pay_restore($id)
    {
        $this->pay->restore($id);

        return redirect()->route('bin')->with('flash_success', 'Payments ' . __('msg.restore_ok'));
    }

    public function pay_force_delete($id)
    {
        $this->pay->forceDelete($id);

        return redirect()->route('bin')->with('flash_success', 'Payments ' . __('msg.force_del_ok'));
    }

    /** Users */
    public function user_restore($id)
    {
        $this->user->restore($id);

        return redirect()->route('bin')->with('flash_success', 'User ' . __('msg.restore_ok'));
    }

    public function user_force_delete($id)
    {
        // Delete user's photo from storage if exists
        $user = $this->user->findOnlyTrashed($id);
        $path = Qs::getUploadPath($user->user_type) . $user->code;
        Storage::exists($path) ? Storage::deleteDirectory($path) : false;

        $this->user->forceDelete($id);

        return redirect()->route('bin')->with('flash_success', 'User ' . __('msg.force_del_ok'));
    }

    /** My Classes */
    public function my_class_restore($id)
    {
        $this->my_class->restore($id);

        return redirect()->route('bin')->with('flash_success', 'Class ' . __('msg.restore_ok'));
    }

    public function my_class_force_delete($id)
    {
        $this->my_class->forceDelete($id);

        return redirect()->route('bin')->with('flash_success', 'Class ' . __('msg.force_del_ok'));
    }

    /** Exams */
    public function exam_restore($id)
    {
        $this->exm->restore($id);

        return redirect()->route('bin')->with('flash_success', 'Exam ' . __('msg.restore_ok'));
    }

    public function exam_force_delete($id)
    {
        $this->exm->forceDelete($id);

        return redirect()->route('bin')->with('flash_success', 'Exam ' . __('msg.force_del_ok'));
    }

    /** Message Threads */
    public function thread_restore($id)
    {
        Thread::restoreThread($id);

        return redirect()->route('bin')->with('flash_success', 'Message Thread ' . __('msg.restore_ok'));
    }

    public function thread_force_delete($id)
    {
        Thread::force_delete($id);

        return redirect()->route('bin')->with('flash_success', 'Message Thread ' . __('msg.force_del_ok'));
    }
}
