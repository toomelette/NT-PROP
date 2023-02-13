<?php


namespace App\Http\Controllers;


use App\Models\JR;
use App\Models\JRItems;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
use App\Swep\Services\JRService;
use App\Swep\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class JRController extends Controller
{
    protected $jrService;
    protected  $transactionService;
    public function __construct(JRService $jrService, TransactionService $transactionService)
    {
        $this->jrService = $jrService;
        $this->transactionService = $transactionService;
    }

    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }

        if(\request()->ajax() && \request()->has('receive_pr')){
            return $this->transactionService->receiveTransaction($request);
        }

        return view('ppu.jr.index');
    }
    public function dataTable($request){
        $trans = Transactions::query()->with(['transDetails'])
            ->where('ref_book','=','JR');
        $search = $request->get('search')['value'] ?? null;

        $dt = \DataTables::of($trans);

        $dt = $dt->filter(function ($query) use($search){
            if($search != null){
                $query->whereHas('transDetails',function ($q) use($search){
                    return $q->where('item','like','%'.$search.'%')
                        ->orWhere('description','like','%'.$search.'%');
                });
            }
        });

        $dt = $dt->addColumn('action',function($data){
                return view('ppu.jr.dtActions')->with([
                    'jr' => $data,
                ]);
            })
            ->addColumn('dept',function($data){
                return ($data->rc->description->name ?? null).
                    '<div class="table-subdetail" style="margin-top: 3px">'.($data->rc->department ?? null).
                    '<br>'.($data->rc->division ?? null).
                    '</div>';
            })
            ->addColumn('divSec',function($data){
                return $data->rc->division ?? null;
            })
            ->addColumn('items',function($data){
                return view('ppu.jr.dtItems')->with([
                    'items' => $data->transDetails,
                ]);
            })
            ->editColumn('ref_no',function($data){
                if($data->cancelled_at != null){
                    return '<s class="text-danger">'.$data->ref_no.'</s><br><small class="text-danger">CANCELLED</small>';
                }
                return $data->ref_no;
            })
            ->editColumn('abc',function($data){
                return number_format($data->abc,2);
            })
            ->editColumn('date',function($data){
                return $data->date ? Carbon::parse($data->date)->format('M. d, Y') : '';
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();

        return $dt;
    }

    public function store(Request $request){
        $jr = new JR();
        $jr->slug = Str::random();
        $jr->respCenter = $request->respCenter;
        $jr->papCode = $request->papCode;
        $jr->jrDate = $request->jrDate;
        $jr->jrNo = $this->jrService->getNextJRNo();
        $jr->purpose = $request->purpose;
        $jr->certifiedBy = $request->certifiedBy;
        $jr->certifiedByDesignation = $request->certifiedByDesignation;
        $jr->requestedBy = $request->requestedBy;
        $jr->requestedByDesignation = $request->requestedByDesignation;
        $jr->approvedBy = $request->approvedBy;
        $jr->approvedByDesignation = $request->approvedByDesignation;
        $jr->abc = Helper::sanitizeAutonum($request->abc);
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'jr_slug' => $jr->slug,
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'natureOfWork' => $item['natureOfWork'],
                    'propertyNo' => $item['propertyNo'],
                    'uom' => $item['uom'],
                    'qty' => $item['qty'],
                ]);
            }
        }
        if($jr->save()){
            if(count($arr ) > 0){
                JRItems::insert($arr);
            }
            return $jr->only('slug');
        }
    }

    public function print($slug){
        return view('printables.jr.jr_front')->with([
            'jr' => $this->jrService->findBySlug($slug),
        ]);
    }

    public function edit($slug){
        $jr = $this->jrService->findBySlug($slug);
        return view('ppu.jr.edit')->with([
            'jr' => $jr,
        ]);
    }

    public function update(Request $request,$slug){
        $jr = $this->jrService->findBySlug($slug);
        $jr->respCenter = $request->respCenter;
        $jr->papCode = $request->papCode;
        $jr->jrDate = $request->jrDate;
        $jr->jrNo = $this->jrService->getNextJRNo();
        $jr->purpose = $request->purpose;
        $jr->certifiedBy = $request->certifiedBy;
        $jr->certifiedByDesignation = $request->certifiedByDesignation;
        $jr->requestedBy = $request->requestedBy;
        $jr->requestedByDesignation = $request->requestedByDesignation;
        $jr->approvedBy = $request->approvedBy;
        $jr->approvedByDesignation = $request->approvedByDesignation;
        $jr->abc = Helper::sanitizeAutonum($request->abc);
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'jr_slug' => $jr->slug,
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'natureOfWork' => $item['natureOfWork'],
                    'propertyNo' => $item['propertyNo'],
                    'uom' => $item['uom'],
                    'qty' => $item['qty'],
                ]);
            }
        }
        if($jr->save()){
            if(count($arr ) > 0){
                $jr->items()->delete();
                JRItems::insert($arr);
            }
            return $jr->only('slug');
        }
    }

    public function destroy($slug){
        $jr = $this->jrService->findBySlug($slug);
        if($jr->delete()){
            $jr->items()->delete();
            return 1;
        }
        abort(503,'Error deleting item.');
    }

    public function cancel(Request $request,$slug){
        $request->validate([
            'cancellation_reason' => 'required|string',
        ]);
        $jr = $this->transactionService->findBySlug($slug);
        $jr->cancelled_at = Carbon::now();
        $jr->user_cancelled = \Auth::user()->user_id;
        $jr->cancellation_reason = $request->cancellation_reason;
        $jr->is_locked = 1;
        if($jr->save()){
            return $jr->only('slug');
        }
        abort(503,'Error in cancellation of transaction. JRController::cancel()');
    }
}