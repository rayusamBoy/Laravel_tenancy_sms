<?php

namespace App\Repositories;

use App\Helpers\Qs;
use App\Models\Payment;
use App\Models\PaymentRecord;
use App\Models\Receipt;

class PaymentRepo
{
    /*********** Payment ***************/

    public function all()
    {
        return Payment::all();
    }

    public function getTrashed()
    {
        return Payment::onlyTrashed()->get();
    }

    public function restore($id)
    {
        return Payment::where('id', $id)->restore();
    }

    public function forceDelete($id)
    {
        return Payment::where('id', $id)->forceDelete();
    }

    public function getPayment($data)
    {
        return Payment::where($data)->with('my_class');
    }

    public function getGeneralPayment($data)
    {
        return Payment::whereNull('my_class_id')->where($data)->with('my_class');
    }

    public function getActivePayments()
    {
        return $this->getPayment(['year' => Qs::getCurrentSession()]);
    }

    public function getPaymentYears()
    {
        return Payment::select('year')->distinct()->get();
    }

    public function find($id)
    {
        return Payment::find($id);
    }

    public function create($data)
    {
        return Payment::create($data);
    }

    public function update($id, $data)
    {
        return Payment::find($id)->update($data);
    }

    public function delete($id)
    {
        return Payment::destroy($id);
    }

    /************** PAYMENT RECORDS ***************/

    public function findMyPR($st_id, $pay_id)
    {
        return $this->getRecord(['student_id' => $st_id, 'payment_id' => $pay_id]);
    }

    public function getAllMyPR($st_id, $year = NULL)
    {
        return $year ? $this->getRecord(['student_id' => $st_id, 'year' => $year]) : $this->getRecord(['student_id' => $st_id]);
    }

    public function getRecord($data, $order = 'year', $dir = 'desc')
    {
        return PaymentRecord::orderBy($order, $dir)->where($data)->with('payment');
    }

    public function createRecord($data)
    {
        return PaymentRecord::firstOrCreate($data);
    }

    public function findRecord($id)
    {
        return PaymentRecord::findOrFail($id);
    }

    public static function getRecordValue($id, $col_name)
    {
        return Payment::where('id', $id)->value($col_name);
    }

    public function updateRecord($id, $data)
    {
        return PaymentRecord::find($id)->update($data);
    }

    public function countAllPayRecs()
    {
        return PaymentRecord::all()->count();
    }

    public function countAllPaidRecs()
    {
        return PaymentRecord::where('paid', true)->get()->count();
    }

    public function countAllCleanPaidRecs()
    {
        return PaymentRecord::where('paid', true)->with('payment')->get()->whereNotNull('payment')->count();
    }

    /************** PAYMENT RECEIPTS ***************/

    public function createReceipt($data)
    {
        return Receipt::Create($data);
    }

    public function deleteReceipts($data)
    {
        return Receipt::where($data)->delete();
    }

    public function getReceipt($data)
    {
        return Receipt::where($data);
    }

    public function getAllMyReceipts($pr_id)
    {
        return $this->getRecord(['pr_id' => $pr_id])->get();
    }
}
