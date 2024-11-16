<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Pay;
use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Http\Requests\Payment\PaymentCreate;
use App\Http\Requests\Payment\PaymentUpdate;
use App\Notifications\StudentPaymentPaid;
use App\Repositories\MyClassRepo;
use App\Repositories\PaymentRepo;
use App\Repositories\StudentRepo;
use Dompdf\Dompdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller implements HasMiddleware
{
    protected $my_class, $pay, $student, $year;

    public function __construct(MyClassRepo $my_class, PaymentRepo $pay, StudentRepo $student)
    {
        $this->my_class = $my_class;
        $this->pay = $pay;
        $this->year = Qs::getCurrentSession();
        $this->student = $student;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('teamAccount', except: ['status'])
        ];
    }

    public function index()
    {
        $d['selected'] = false;
        $d['years'] = $this->pay->getPaymentYears();

        return view('pages.support_team.payments.index', $d);
    }

    public function show($year)
    {
        $d['payments'] = $p = $this->pay->getPayment(['year' => $year])->get();

        if (($p->count() < 1))
            return Qs::goWithDanger('payments.index');

        $d['selected'] = true;
        $d['my_classes'] = $this->my_class->all();
        $d['years'] = $this->pay->getPaymentYears();
        $d['year'] = $year;

        return view('pages.support_team.payments.index', $d);
    }

    public function select_year(Request $req)
    {
        return Qs::goToRoute(['payments.show', $req->year]);
    }

    public function create()
    {
        $d['my_classes'] = $this->my_class->all();

        return view('pages.support_team.payments.create', $d);
    }

    public function invoice($st_id, $year = NULL)
    {
        if (!$st_id)
            return Qs::goWithDanger();

        $inv = $year ? $this->pay->getAllMyPR($st_id, $year) : $this->pay->getAllMyPR($st_id);
        $pr = $inv->get()->whereNotNull('payment');

        $d['sr'] = $sr = $this->student->getRecord(['user_id' => $st_id])->first();
        $d['uncleared'] = $pr->where('paid', 0);
        $d['cleared'] = $pr->where('paid', 1);
        $d['can_notify_parent'] = $this->can_notify_parent($sr);

        return view('pages.support_team.payments.invoice', $d);
    }

    public function can_notify_parent($sr): bool
    {
        return $sr->my_parent->email_verified_at != null && $sr->my_parent->is_notifiable;
    }

    public function status($st_id, $year = NULL)
    {
        if ($year)
            if ($year != $this->year)
                return back()->with('flash_danger', __('msg.year_invalid'));

        if (!$st_id)
            return Qs::goWithDanger();

        $inv = $year ? $this->pay->getAllMyPR($st_id, $year) : $this->pay->getAllMyPR($st_id);
        $pr = $inv->get()->whereNotNull('payment');

        $d['sr'] = $this->student->getRecord(['user_id' => $st_id])->first();
        $d['uncleared'] = $pr->where('paid', 0);
        $d['cleared'] = $pr->where('paid', 1);

        return view('pages.support_team.payments.status', $d);
    }

    public function receipts($pr_id, $notification_id = null)
    {
        if (!$pr_id)
            return Qs::goWithDanger();

        try {
            $d['pr'] = $pr = $this->pay->getRecord(['id' => $pr_id])->with('receipt')->first();
        } catch (ModelNotFoundException $ex) {
            return back()->with('flash_danger', __('msg.rnf'));
        }

        $d['receipts'] = $pr->receipt;
        $d['payment'] = $pr->payment;
        $d['sr'] = $this->student->getRecord(['user_id' => $pr->student_id])->first();
        $d['settings'] = Qs::getSettings();

        if ($notification_id !== null){
            app(NotificationController::class)->mark_as_read($notification_id);
        }

        return view('pages.support_team.payments.receipt', $d);
    }

    public function pdf_receipts($pr_id)
    {
        if (!$pr_id)
            return Qs::goWithDanger();

        try {
            $d['pr'] = $pr = $this->pay->getRecord(['id' => $pr_id])->with('receipt')->first();
        } catch (ModelNotFoundException $ex) {
            return back()->with('flash_danger', __('msg.rnf'));
        }

        $d['receipts'] = $pr->receipt;
        $d['payment'] = $pr->payment;
        $d['sr'] = $this->student->getRecord(['user_id' => $pr->student_id])->first();
        $d['settings'] = Qs::getSettings();

        $pdf_name = "Receipt_{$pr->ref_no}";
        $dompdfhtml = view('pages.support_team.payments.receipt', $d)->render();
        $options = [
            'chroot' => public_path(),
        ];

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($dompdfhtml);
        $dompdf->render();
        $dompdf->stream($pdf_name);
    }

    public function pay_now(Request $req, $pr_id)
    {
        Validator::make($req->toArray(), [
            'amt_paid' => 'required|numeric'
        ], [], ['amt_paid' => 'Amount Paid'])->validate();

        $pr = $this->pay->findRecord($pr_id);
        $payment = $this->pay->find($pr->payment_id);
        $d['amt_paid'] = $amt_p = $pr->amt_paid + $req->amt_paid;
        $d['balance'] = $bal = $payment->amount - $amt_p;
        $d['paid'] = $bal < 1 ? 1 : 0;

        $this->pay->updateRecord($pr_id, $d);

        $d2['amt_paid'] = $req->amt_paid;
        $d2['balance'] = $bal;
        $d2['pr_id'] = $pr_id;
        $d2['year'] = $this->year;

        $receipt = $this->pay->createReceipt($d2);
        $st_rec = $this->student->getRecord(['user_id' => $pr->student_id])->with(['my_parent', 'user'])->get();

        if ($payment->can_notify_on_pay && $this->can_notify_parent($st_rec->first())) {
            $parent = $st_rec->pluck('my_parent')->first();
            //return (new StudentPaymentPaid($receipt))->toMail($parent); // For Testing on browser
            $parent->notify(new StudentPaymentPaid($receipt, $st_rec->first()));
        }

        return Qs::jsonUpdateOk();
    }

    public function manage($class_id = NULL)
    {
        $d['my_classes'] = $this->my_class->all();
        $d['selected'] = false;

        if ($class_id) {
            $d['students'] = $st = $this->student->getRecord(['my_class_id' => $class_id])->get()->whereNotNull('user')->sortBy('user.name');
            if ($st->count() < 1)
                return Qs::goWithDanger('payments.manage');

            $d['selected'] = true;
            $d['my_class_id'] = $class_id;
        }

        return view('pages.support_team.payments.manage', $d);
    }

    public function select_class(Request $req)
    {
        Validator::make($req->toArray(), [
            'my_class_id' => 'required|exists:my_classes,id'
        ], [], ['my_class_id' => 'Class'])->validate();

        $wh['my_class_id'] = $class_id = $req->my_class_id;

        $pay1 = $this->pay->getPayment(['my_class_id' => $class_id, 'year' => $this->year])->get();
        $pay2 = $this->pay->getGeneralPayment(['year' => $this->year])->get();
        $payments = $pay2->count() ? $pay1->merge($pay2) : $pay1;
        $students = $this->student->getRecord($wh)->get();

        if ($payments->count() && $students->count()) {
            foreach ($payments as $p) {
                foreach ($students as $st) {
                    $pr['student_id'] = $st->user_id;
                    $pr['payment_id'] = $p->id;
                    $pr['year'] = $this->year;
                    $rec = $this->pay->createRecord($pr);
                    $rec->ref_no ?: $rec->update(['ref_no' => mt_rand(100000, 99999999)]);
                }
            }
        }

        return Qs::goToRoute(['payments.manage', $class_id]);
    }

    public function store(PaymentCreate $req)
    {
        $data = $req->all();
        $data['year'] = $this->year;
        $data['ref_no'] = Pay::genRefCode();

        $this->pay->create($data);

        return Qs::jsonStoreOk();
    }

    public function edit($id)
    {
        $d['payment'] = $pay = $this->pay->find($id);

        return $pay === null ? Qs::goWithDanger('payments.index') : view('pages.support_team.payments.edit', $d);
    }

    public function update(PaymentUpdate $req, $id)
    {
        $data = $req->all();
        $this->pay->update($id, $data);

        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        $this->pay->find($id)->delete();

        return Qs::deleteOk('payments.index');
    }

    public function reset_record($id)
    {
        $pr['amt_paid'] = $pr['paid'] = $pr['balance'] = 0;
        $this->pay->updateRecord($id, $pr);
        $this->pay->deleteReceipts(['pr_id' => $id]);

        return back()->with('flash_success', __('msg.update_ok'));
    }
}
