<?php


namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\PAP;
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

use Auth;

class MyRISController extends Controller
{

    public function create()
    {
        return view('ppu.ris_my.create');
    }

    public function myIndex(Request $request)
    {
        if ($request->ajax() && $request->has('draw')) {
            return $this->myDataTable($request);
        }
        return view('ppu.ris_my.myIndex');
    }

    public function myDataTable($request)
    {


        $resp_center = PPURespCodes::all();
        $ris = Transactions::query()
            ->where('user_created', Auth::user()->user_id)
            ->where('ref_book', '=', 'RIS');
        return DataTables::of($ris)
            ->addColumn('action', function ($data) {
                return view('ppu.ris_my.dtActions')->with([
                    'data' => $data
                ]);
            })
            ->addColumn('item', function ($data) {
                return view('ppu.ris_my.dtItems')->with([
                    'data' => $data
                ]);
            })
            ->addColumn('qty', function ($data) {
                return view('ppu.ris_my.dtQty')->with([
                    'data' => $data
                ]);
            })
            ->addColumn('actual_qty', function ($data) {
                return view('ppu.ris_my.dtActualQty')->with([
                    'data' => $data
                ]);
            })

            ->editColumn('resp_center', function ($data) use ($resp_center) {
               $item = $resp_center->where("rc_code", $data->resp_center)->first();
               if($item){
                   return $item->desc;
               }else{
                   return null;
               }
            })

            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
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

    public function findIAR($refNumber){
        $trans = Transactions::query()->where('ref_no','=', $refNumber)
            ->where('ref_book','=', 'IAR')->first();
        $transDetails = $trans->transDetails;
        return response()->json([
            'trans' => $trans,
            'transDetails' => $transDetails
        ]);
    }

    public function store(FormRequest $request)
    {
        $transNew = new Transactions();
        $iar = Transactions::query()->where('ref_no','=',$request->iar_no)
            ->where('ref_book','=','IAR')->first();
        if($iar->cross_slug != null || $iar->cross_slug != ""){
            $po = Transactions::query()->where('slug', $iar->cross_slug)->first();
            $order = Order::query()->where('slug', $po->order_slug)->first();
            $transNew->po_number = $po->ref_no;
            $transNew->po_date = $order->date;
        }

        $transNewSlug = Str::random();
        $transNew->slug = $transNewSlug;
        $transNew->cross_slug = $iar->slug;
        $transNew->date = $request->date;
        $transNew->resp_center = $request->resp_center;
        $transNew->pap_code = $request->pap_code;
        $transNew->ref_book = 'RIS';
        $transNew->ref_no = $this->getNextRISno();
        $transNew->purpose = $request->purpose;
        $transNew->requested_by = $request->requested_by;
        $transNew->sai = $request->sai;
        $transNew->sai_date = $request->sai_date;
        $transNew->date = $request->date;
        $transNew->cross_ref_no = $request->iar_no;
        $transNew->requested_by_designation = $request->requested_by_designation;
        $transNew->approved_by = $request->approved_by;
        $transNew->approved_by_designation = $request->approved_by_designation;
        $transNew->prepared_by = $request->issued_by;
        $transNew->prepared_by_position = $request->issued_by_designation;
        $transNew->certified_by = $request->received_by;
        $transNew->certified_by_designation = $request->received_by_designation;

        $totalabc = 0;
        $arr = [];

        $items = Articles::query()->get();
        if (!empty($request->items)) {
            foreach ($request->items as $item) {

                $itemName = $items->where('stockNo', $item['item'])->pluck('article')->first();
                if($itemName == null){
                    $itemName = $item['item'];
                }

                array_push($arr, [
                    'slug' => Str::random(),
                    'transaction_slug' => $transNewSlug,
                    'stock_no' => $item['stock_no'],
                    'unit' => $item['unit'],
                    'item' => $itemName,
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'actual_qty' => $item['actual_qty'],
                    'remarks' => $item['remarks'],
                ]);
            }
        }
        if($transNew->save()){
            TransactionDetails::insert($arr);
        }
        else
            abort(503,'Error saving RIS.');

        return $transNew->only('slug');
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

    public function print($slug){
        $ris = Transactions::query()->where('slug', $slug)->first();
        $rc = PPURespCodes::query()->where('rc_code', $ris->resp_center)->first();
        return view('printables.ris.print')->with([
            'rc' => $rc,
            'ris' => $ris,
        ]);
    }

    public function findBySlug($slug){
        $ris = Transactions::query()
            ->with(['transDetails','rc','transDetails.article'])
            ->where('slug','=',$slug)->first();

        return $ris ?? abort(503,'PR not found');
    }

    public function edit($slug){
//        $ris = Transactions::query()->where('slug','=', $slug)->first();
        $ris =$this->findBySlug($slug);
        return view('ppu.ris.edit')->with([
            'ris' => $ris
        ]);
    }

    public function update(FormRequest $request, $slug)
    {
        $trans = $this->findBySlug($slug);
        $trans->resp_center = $request->resp_center;
        $trans->pap_code = $request->pap_code;
        $trans->purpose = $request->purpose;
        $trans->requested_by = $request->requested_by;
        $trans->sai = $request->sai;
        $trans->sai_date = $request->sai_date;
        $trans->date = $request->date;
        $trans->requested_by_designation = $request->requested_by_designation;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;
        $trans->prepared_by = $request->prepared_by;
        $trans->prepared_by_position = $request->prepared_by_position;
        $trans->certified_by = $request->certified_by;
        $trans->certified_by_designation = $request->certified_by_designation;


        $arr = [];
        if (!empty($request->items)) {
            foreach ($request->items as $item) {
                array_push($arr, [
                    'slug' => Str::random(),
                    'transaction_slug' => $trans->slug,
                    'stock_no' => $item['stock_no'],
                    'unit' => $item['unit'],
                    'item' => $item['itemName'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'actual_qty' => $item['actual_qty'],
                    'remarks' => $item['remarks'],
                ]);
            }
        }
        $trans->transDetails()->delete();
        if ($trans->update()) {
            TransactionDetails::insert($arr);
            return $trans->only('slug');
        }
        abort(503, 'Error saving RIS');
    }



}