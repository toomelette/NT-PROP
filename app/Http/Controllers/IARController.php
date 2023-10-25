<?php


namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\IAR;
use App\Models\Options;
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


class IARController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() && $request->has('draw')) {
            return $this->dataTable($request);
        }
        return view('ppu.iar.index');
    }

    public function dataTable($request)
    {
        $resp_center = PPURespCodes::all();
        $iar = Transactions::query()->where('ref_book', '=', 'IAR');
        return DataTables::of($iar)
            ->addColumn('action', function ($data) {
                return view('ppu.iar.dtActions')->with([
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

    public function create()
    {
        return view('ppu.iar.create');
    }

    public function findTransByRefNumber($refNumber)
    {
        $order = Order::query()->where('ref_no', '=', $refNumber)
            ->where('ref_book', '=', 'PO')->first();
        $trans = Transactions::query()->where('order_slug', '=', $order->slug)->first();
        $rc = PPURespCodes::query()->where('rc_code', '=', $trans->resp_center)->first();
        $transDetails = $trans->transDetails;
        return response()->json([
            'trans' => $trans,
            'order' => $order,
            'rc' => $rc,
            'transDetails' => $transDetails
        ]);

    }

    public function store(FormRequest $request)
    {
        $transNewSlug = Str::random();
        $transNew = new Transactions();
        $transNew->slug = $transNewSlug;
        $transNew->date = $request->date;
        $transNew->ref_book = 'IAR';
        $transNew->ref_no = $this->getNextIARno();
        $transNew->po_number = $request->ref_number;
        $transNew->po_date = $request->po_date;
        $transNew->invoice_number = $request->invoice_number;
        $transNew->invoice_date = $request->invoice_date;
        $transNew->date_inspected = $request->date_inspected;
        $transNew->requested_by = $request->requested_by;
        $transNew->requested_by_designation = $request->requested_by_designation;
        $transNew->resp_center = $request->resp_center;
        $transNew->pap_code = $request->pap_code;
        $transNew->supplier = $request->supplier_name;
        $transNew->cross_ref_no = $request->cross_ref_no;
        if ($request->ref_number != ""){
            $order = Order::query()->where('ref_no', '=', $request->ref_number)
                ->where('ref_book', '=', 'PO')->first();
            $trans = Transactions::query()->where('order_slug', '=', $order->slug)->first();
            $transNew->resp_center = $trans->resp_center   !=null? $trans->resp_center: $request->resp_center;
            $transNew->pap_code = $trans->pap_code;
            $transNew->cross_slug = $trans->slug;
            $transNew->cross_ref_no = $trans->cross_ref_no;
            $transNew->purpose = $trans->purpose;
            $transNew->jr_type = $trans->jr_type;
            $transNew->requested_by = $trans->requested_by;
            $transNew->requested_by_designation = $trans->requested_by_designation;
            $transNew->approved_by = $trans->approved_by;
            $transNew->approved_by_designation = $trans->approved_by_designation;
            $transNew->supplier = $order->supplier_name;
            $transNew->supplier_address = $order->supplier_address;
            $transNew->supplier_tin = $order->supplier_tin;
        }
        else {
            $transNew->cross_slug = "";
            $transNew->purpose = "";
            $transNew->jr_type = "";
            $transNew->approved_by = "";
            $transNew->approved_by_designation = "";
            $transNew->supplier_address = "";
            $transNew->supplier_tin = "";
            $transNew->resp_center = $request->resp_center;
        }

        $items = Articles::query()->get();
        $totalabc = 0;
        $arr = [];
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
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'total_cost' => Helper::sanitizeAutonum($item['total_cost']),
                ]);
                $totalabc = $totalabc + Helper::sanitizeAutonum($item['total_cost']);
            }
        }
        $transNew->abc = $totalabc;
        if ($transNew->save()) {
            TransactionDetails::insert($arr);
            return $transNew->only('slug');
        }
        abort(503, 'Error saving IAR');
    }

    public function getNextIARno()
    {
        $year = Carbon::now()->format('Y-');
        $pr = Transactions::query()
            ->where('ref_no', 'like', $year . '%')
            ->where('ref_book', '=', 'IAR')
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
        $iar = Transactions::query()->where('slug', $slug)->first();
        $rc = PPURespCodes::query()->where('rc_code', $iar->resp_center)->first();
        if($iar->cross_slug != ""){
            $po = Transactions::query()->where('slug', '=', $iar->cross_slug)->first();
            $pr = Transactions::query()->where( 'slug', '=', $po->cross_slug)->first();
//            if($pr == null) {
//                $poDetails = PODetails::query()->where('order_slug', $order->slug)->get();
//            }

                return view('printables.iar.print')->with([
                'iar' => $iar,
                'rc' => $rc,
                'pr' => $pr,
                'po' => $po
            ]);
        }
        return view('printables.iar.print')->with([
            'iar' => $iar,
            'rc' => $rc,
        ]);

    }

    public function findBySlug($slug){
        $iar = Transactions::query()
            ->with(['transDetails','rc','transDetails.article'])
            ->where('slug','=',$slug)->first();

        return $iar ?? abort(503,'PR not found');
    }

    public function edit($slug){
//        $iar = Transactions::query()->where('slug','=', $slug)->first();
        $iar =$this->findBySlug($slug);
        return view('ppu.iar.edit')->with([
            'iar' => $iar
        ]);
    }



    public function update(FormRequest $request, $slug)
    {
        // Find the existing transaction by slug
        $trans = Transactions::query()->where('slug', '=', $slug)->first();

        if (!$trans) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $trans->po_date = $request->po_date;
        $trans->po_number = $request->po_number;
        $trans->invoice_date = $request->invoice_date;
        $trans->invoice_number = $request->invoice_number;
        $trans->date_inspected = $request->date_inspected;
        $trans->supplier = $request->supplier;
        $trans->resp_center = $request->resp_center;
        $trans->ref_no = $request->ref_no;
        $trans->requested_by = $request->requested_by;


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
                    'transaction_slug' => $trans->slug,
                    'stock_no' => $item['stock_no'],
                    'unit' => $item['unit'],
                    'item' => $itemName,
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'total_cost' => Helper::sanitizeAutonum($item['total_cost']),
                ]);
                $totalabc = $totalabc + Helper::sanitizeAutonum($item['total_cost']);
            }
        }
        $trans->abc = $totalabc;
        $trans->transDetails()->delete();
        if ($trans->update()) {
            TransactionDetails::insert($arr);
            return $trans->only('slug');
        }
        abort(503, 'Error saving IAR');
    }




}