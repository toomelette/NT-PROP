<?php


namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\GatePassDetails;
use App\Models\GatePass;
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


class GPController extends Controller
{

    public function create()
    {
        return view('ppu.gp.create');
    }

    public function index(Request $request)
    {
        if ($request->ajax() && $request->has('draw')) {
            return $this->dataTable($request);
        }
        return view('ppu.gp.index');
    }

    public function dataTable($request)
    {
        $gp = GatePass::all();
        return DataTables::of($gp)
            ->addColumn('action', function ($data) {
                return view('ppu.gp.dtActions')->with([
                    'data' => $data
                ]);
            })

            ->addColumn('item', function ($data) {
                return view('ppu.gp.dtItems')->with([
                    'data' => $data
                ]);
            })
            ->addColumn('qty', function ($data) {
                return view('ppu.gp.dtQty')->with([
                    'data' => $data
                ]);
            })
            ->addColumn('description', function ($data) {
                return view('ppu.gp.dtDesc')->with([
                    'data' => $data
                ]);
            })

            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();


    }

    public function getNextGPno()
    {
        $year = Carbon::now()->format('Y-');
        $gate_pass = GatePass::query()
            ->where('gp_number', 'like', $year . '%')
            ->orderBy('gp_number', 'desc')
            ->first();
        if (empty($gate_pass)) {
            $gpNo = 0;
        } else {
            $gpNo = substr($gate_pass->gp_number, -4);
        }

        $newGPBaseNo = str_pad($gpNo + 1, 4, '0', STR_PAD_LEFT);

        return $year . Carbon::now()->format('m-') . $newGPBaseNo;
    }

    public function store(FormRequest $request)
    {

        $transNewSlug = Str::random();
        $transNew = new GatePass();
        $transNew->slug = $transNewSlug;
        $transNew->gp_number = $this->getNextGPno();
        $transNew->date = $request->date;
        $transNew->bearer = $request->bearer;
        $transNew->originated_from = $request->originated_from;
        $transNew->approved_by = $request->approved_by;
        $transNew->approved_by_designation = $request->approved_by_designation;
        $transNew->received_by = $request->received_by;
        $transNew->guard_on_duty = $request->guard_on_duty;

        $totalabc = 0;

//        $items = Articles::query()->get();
        $arr = [];
        if (!empty($request->items)) {
            foreach ($request->items as $item) {

//                $itemName = $items->where('stockNo', $item['item'])->pluck('article')->first();
//                if($itemName == null){
//                    $itemName = $item['item'];
//                }
                array_push($arr, [
                    'slug' => Str::random(),
                    'transaction_slug' => $transNewSlug,
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                ]);
            }
        }
        if ($transNew->save()) {
            GatePassDetails::insert($arr);
            return $transNew->only('slug');
        }
        abort(503, 'Error saving Gate Pass');
    }

    public function print($slug){
        $gp = GatePass::query()->where('slug', $slug)->first();
        return view('printables.gp.print')->with([
            'gp' => $gp,
        ]);
    }


}