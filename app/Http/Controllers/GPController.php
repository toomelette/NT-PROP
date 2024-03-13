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
        $gp = GatePass::query();
        if($request->has('year') && $request->year != ''){
            $gp = $gp->where('gp_number','like',$request->year.'%');
        }

        if($request->has('item') && $request->item != null){
            $gp->whereIn('slug',function ($q) use ($request){
                $q->select('transaction_slug')
                    ->from(with(new GatePassDetails)->getTable())
                    ->where('item','like','%'.$request->item.'%')
                    ->orWhere('description','like','%'.$request->item.'%');
            });

        }

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

    public function receiveGp($slug){
        $gp = $this->findBySlug($slug);
        if($gp->is_locked){
            abort(503,'Gate Pass is Locked');
        }
        $gp->is_locked = true;

        if($gp->update()){
            return 1;
        };
        abort(503,'Error saving transaction.');
    }

    public function getNextGPno()
    {
        $year = Carbon::now()->format('Y-');
        $gate_pass = GatePass::query()
            ->where('gp_number', 'like', $year . '%')
            ->whereRaw('LENGTH(gp_number)=8')
            ->orderBy('gp_number', 'desc')->limit(1)->first();

        if (empty($gate_pass)) {
            $gpNo = 0;
        } else {
            $gpNo = substr($gate_pass->gp_number, -3);
        }

        $newGPBaseNo = str_pad($gpNo + 1, 3, '0', STR_PAD_LEFT);

        return $year . $newGPBaseNo;
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
        $transNew->justification = $request->justification;
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
                    'unit' => $item['unit'],
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

    public function findBySlug($slug){
        $gp = GatePass::query()
            ->with(['GatePassDetails'])
            ->where('slug','=',$slug)->first();

        return $gp ?? abort(503,'GP not found');
    }

    public function edit($slug){
        $gp =$this->findBySlug($slug);
        return view('ppu.gp.edit')->with([
            'gp' => $gp
        ]);
    }

    public function update(FormRequest $request, $slug)
    {

        $trans = GatePass::query()->where('slug', '=', $slug)->first();

        if (!$trans) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
        $trans->date = $request->date;
        $trans->bearer = $request->bearer;
        $trans->originated_from = $request->originated_from;
        $trans->justification = $request->justification;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;
        $trans->received_by = $request->received_by;
        $trans->guard_on_duty = $request->guard_on_duty;


        $arr = [];
        if (!empty($request->items)) {
            foreach ($request->items as $item) {

                array_push($arr, [
                    'slug' => Str::random(),
                    'transaction_slug' => $trans->slug,
                    'item' => $item['item'],
                    'unit' => $item['unit'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                ]);
            }
        }
        $trans->GatePassDetails()->delete();
        if ($trans->update()) {
            GatePassDetails::insert($arr);
            return $trans->only('slug');
        }
        abort(503, 'Error saving Gate Pass');
    }





}