<?php


namespace App\Http\Controllers;

use App\Models\RIS;
use App\Models\Order;
use App\Models\PPURespCodes;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;


class RISController extends Controller
{

    public function create()
    {
        return view('ppu.ris.create');
    }

    public function findTransByRefNumber($refNumber)
    {
        $trans = Transactions::query()->where('ref_no', '=', $refNumber)
            ->where('ref_book', '=', 'IAR')->first();
        $rc = PPURespCodes::query()->where('rc_code', '=', $trans->resp_center)->first();
        $transDetails = $trans->transDetails;
        return response()->json([
            'trans' => $trans,
            'rc' => $rc,
            'transDetails' => $transDetails
        ]);

    }

    public function store(FormRequest $request)
    {
        $trans = Transactions::query()->where('ref_no', '=', $request->ref_number)
            ->where('ref_book', '=', 'IAR')->first();

        $transNewSlug = Str::random();
        $transNew = new Transactions();
        $transNew->slug = $transNewSlug;
        $transNew->date = $request->date;
        $transNew->resp_center = $trans->resp_center;
        $transNew->pap_code = $trans->pap_code;
        $transNew->ref_book = 'RIS';
        $transNew->ref_no = $this->getNextRISno();
        $transNew->cross_slug = $trans->slug;
        $transNew->cross_ref_no = $trans->ref_no;
        $transNew->purpose = $trans->purpose;
        $transNew->jr_type = $trans->jr_type;
        $transNew->requested_by = $trans->requested_by;
        $transNew->requested_by_designation = $trans->requested_by_designation;
        $transNew->approved_by = $trans->approved_by;
        $transNew->approved_by_designation = $trans->approved_by_designation;
        $transNew->account_code = $trans->account_code;
        $transNew->fund_cluster = $trans->fund_cluster;
        $transNew->po_number = $request->ref_number;
        $transNew->po_date = $request->po_date;
        $transNew->invoice_number = $trans->invoice_number;
        $transNew->invoice_date = $trans->invoice_date;
        $transNew->date_inspected = $trans->date_inspected;
        $transNew->supplier = $trans->supplier_name;
        $transNew->actual_qty = $request->actual_qty;
        $transNew->remarks = $request->remarks;

        $totalabc = 0;
        $arr = [];
        if (!empty($request->items)) {
            foreach ($request->items as $item) {
                array_push($arr, [
                    'slug' => Str::random(),
                    'transaction_slug' => $transNewSlug,
                    'stock_no' => $item['stock_no'],
                    'unit' => $item['unit'],
                    'description' => $item['description'],
                    'qty' => Helper::sanitizeAutonum($item['qty']),
                    'actual_qty' => Helper::sanitizeAutonum($item['actual_qty']),
                    'remarks' => $item['remarks'],
                ]);
                $totalabc = $totalabc + Helper::sanitizeAutonum($item['actual_qty']);
            }
        }
        $transNew->abc = $totalabc;
        if ($transNew->save()) {
            TransactionDetails::insert($arr);
            return $transNew->only('slug');
        }
        abort(503, 'Error saving RIS');
    }

    public function getNextRISno()
    {
        $year = Carbon::now()->format('Y-');
        $pr = Transactions::query()
            ->where('ref_no', 'like', $year . '%')
            ->where('ref_book', '=', 'RIS')
            ->orderBy('ref_no', 'desc')->limit(1)->first();
        if (empty($pr)) {
            $prNo = 0;
        } else {
//            $prNo = str_replace($year,'',$pr->ref_no);
            $prNo = substr($pr->ref_no, -4);
        }

        $newPrBaseNo = str_pad($prNo + 1, 4, '0', STR_PAD_LEFT);

        return $year . Carbon::now()->format('m-') . $newPrBaseNo;
    }



}