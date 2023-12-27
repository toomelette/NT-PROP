<?php


namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\WasteMaterialDetails;
use App\Models\WMR;
use App\Models\Order;
use App\Models\PPURespCodes;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Models\WasteMaterial;
use App\Swep\Helpers\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;
use function Symfony\Component\Mime\Header\all;
use Auth;


class WMRController extends Controller
{

    public function create()
    {
        return view('ppu.wmr.create');
    }

    public function index(Request $request)
    {
        if ($request->ajax() && $request->has('draw')) {
            return $this->dataTable($request);
        }
        return view('ppu.wmr.index');
    }


    public function dataTable($request)
    {
        $wmr = WasteMaterial::all();
        return DataTables::of($wmr)
            ->addColumn('action', function ($data) {
                return view('ppu.wmr.dtActions')->with([
                    'data' => $data
                ]);
            })
            ->addColumn('item', function ($data) {
                return view('ppu.wmr.dtItems')->with([
                    'data' => $data
                ]);
            })

            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }


    public function myIndex(Request $request)
    {
        if ($request->ajax() && $request->has('draw')) {
            return $this->myDataTable($request);
        }
        return view('ppu.wmr.myIndex');
    }

    public function myDataTable($request)
    {
        $wmr = WasteMaterial::query()->where('user_created', Auth::user()->user_id);
        return DataTables::of($wmr)
            ->addColumn('action', function ($data) {
                return view('ppu.wmr.dtActions')->with([
                    'data' => $data, 'myIndex' => true,
                ]);
            })
            ->addColumn('item', function ($data) {
                return view('ppu.wmr.dtItems')->with([
                    'data' => $data
                ]);
            })

            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

    public function getNextWMRno()
    {
        $year = Carbon::now()->format('Y-');
        $wmr = WasteMaterial::query()
            ->where('wm_number', 'like', $year . '%')
            ->orderBy('wm_number', 'desc')
            ->first();
        if (empty($wmr)) {
            $wmrNo = 0;
        } else {
//            $wmrNo = str_replace($year,'',$wmr->ref_no);
            $wmrNo = substr($wmr->wm_number, -4);
        }

        $newWmrBaseNo = str_pad($wmrNo + 1, 4, '0', STR_PAD_LEFT);

        return $year . Carbon::now()->format('m-') . $newWmrBaseNo;
    }

    public function store(FormRequest $request)
    {

        $transNewSlug = Str::random();
        $transNew = new WasteMaterial();
        $transNew->slug = $transNewSlug;
        $transNew->date = $request->date;
        $transNew->wm_number = $this->getNextWMRno();
        $transNew->storage = $request->storage;
        $transNew->taken_from = $request->taken_from;
        $transNew->taken_through = $request->taken_through;
        $transNew->certified_by = $request->certified_by;
        $transNew->certified_by_designation = $request->certified_by_designation;
        $transNew->approved_by = $request->approved_by;
        $transNew->approved_by_designation = $request->approved_by_designation;
        $transNew->inspected_by = $request->inspected_by;
        $transNew->inspected_by_designation = $request->inspected_by_designation;
        $transNew->witnessed_by = $request->witnessed_by;
        $transNew->witnessed_by_designation = $request->witnessed_by_designation;

        $totalabc = 0;

        $items = Articles::query()->get();
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
                    'stock_no' => $item['stockNo'],
                    'unit' => $item['unit'],
                    'item' => $itemName,
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'or_no' => $item['or_no'],
                    'amount' => $item['amount'],
                ]);
            }
        }
        if ($transNew->save()) {
            WasteMaterialDetails::insert($arr);
            return $transNew->only('slug');
        }
        abort(503, 'Error saving WMR');
    }

    public function print($slug){
        $wmr = WasteMaterial::query()->where('slug', $slug)->first();
        return view('printables.wmr.print')->with([
            'wmr' => $wmr,
        ]);
    }

    public function findBySlug($slug){
        $wmr = WasteMaterial::query()
            ->with(['wasteDetails','wasteDetails.article'])
            ->where('slug','=', $slug)->first();

        return $wmr ?? abort(503,'WR not found');
    }

    public function edit($slug){
//        $ris = Transactions::query()->where('slug','=', $slug)->first();
        $wmr =$this->findBySlug($slug);
        return view('ppu.wmr.edit')->with([
            'wmr' => $wmr
        ]);
    }

    public function update(FormRequest $request, $slug)
    {

        $trans = WasteMaterial::query()->where('slug', '=', $slug)->first();

        if (!$trans) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
//        $trans = $this->findBySlug($slug);
        $trans->date = $request->date;
        $trans->storage = $request->storage;
        $trans->taken_from = $request->taken_from;
        $trans->taken_through = $request->taken_through;
        $trans->certified_by = $request->certified_by;
        $trans->certified_by_designation = $request->certified_by_designation;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;
        $trans->inspected_by = $request->inspected_by;
        $trans->inspected_by_designation = $request->inspected_by_designation;
        $trans->witnessed_by = $request->witnessed_by;
        $trans->witnessed_by_designation = $request->witnessed_by_designation;


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
                    'stock_no' => $item['stockNo'],
                    'unit' => $item['unit'],
                    'item' => $itemName,
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'or_no' => $item['or_no'],
                    'amount' => $item['amount'],
                ]);
            }
        }
        $trans->wasteDetails()->delete();
        if ($trans->update()) {
            WasteMaterialDetails::insert($arr);
            return $trans->only('slug');
        }
        abort(503, 'Error saving WMR');
    }


}