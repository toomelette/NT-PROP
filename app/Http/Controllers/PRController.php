<?php


namespace App\Http\Controllers;


use App\Http\Requests\PR\PRFormRequest;
use App\Models\AwardNoticeAbstract;
use App\Models\PR;
use App\Models\PRItems;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
use App\Swep\Services\PRService;
use App\Swep\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PRController extends Controller
{
    protected $prService;
    protected $transactionService;
    public function __construct(PRService $prService, TransactionService $transactionService)
    {
        $this->prService = $prService;
        $this->transactionService = $transactionService;
    }

    public function index(Request $request){
        if(\request()->ajax() && \request()->has('draw')){
            return $this->dataTable($request);
        }
        if(\request()->ajax() && \request()->has('receive_pr')){
            return $this->transactionService->receiveTransaction($request);
        }
        return view('ppu.pr.index');
    }

    public function monitoringIndex(Request $request){
        if(\request()->ajax() && \request()->has('draw')){
            return $this->monitoringDataTable($request);
        }
        return view('ppu.monitoring.pr.index');
    }


    public function receivePr($request){
        $trans = $this->transactionService->findBySlug($request->trans);
        $trans->received_at = Carbon::now();
        $trans->user_received = \Auth::user()->user_id;
        $trans->is_locked = 1;
        if($trans->save()){
            return $trans->only('slug');
        }
        abort(503,'Error in receiving. [PRController::receivePr()]');
    }

    public function monitoringDataTable($request){
        $trans = Transactions::query()->where('ref_book','=','PR');
        $transAll = Transactions::all();
        $ana = AwardNoticeAbstract::all();
        $search = $request->get('search')['value'] ?? null;

        $dt = \DataTables::of($trans);

        $dt = $dt->filter(function ($query) use($search){
            if($search != null){
                $query->where('ref_no', 'like', '%'.$search.'%');
            }
        });

        $dt = $dt->addColumn('pr_no',function($data){
            return ($data->ref_no);
            })
            ->addColumn('date_created',function($data){
                return !empty($data->date) ? Carbon::parse($data->date)->format('M. d, Y') : null;
            })
            ->addColumn('date_received',function($data){
                return !empty($data->received_at) ? Carbon::parse($data->date)->format('M. d, Y') : null;
            })
            ->addColumn('rfq_date', function($data) use ($transAll) {
                $item = $transAll->where('cross_slug', $data->slug)
                    ->where('ref_book', 'RFQ')
                    ->first();
                if ($item) {
                    return Carbon::parse($item->created_at)->format('M. d, Y');
                } else {
                    return null;
                }
            })
            ->addColumn('aq_date', function($data) use ($transAll) {
                $item = $transAll->where('cross_slug', $data->slug)
                    ->where('ref_book', 'AQ')
                    ->first();
                if ($item) {
                    return Carbon::parse($item->created_at)->format('M. d, Y');
                } else {
                    return null;
                }
            })
            ->addColumn('rbac_reso_date',function($data){
                return "";
            })
            ->addColumn('noa_date',function($data) use ($ana){
                $item = $ana->where('ref_book', '=', 'PR')
                        ->where('ref_number', '=', $data->ref_no)
                        ->last();
                if ($item) {
                    return Carbon::parse($item->award_date)->format('M. d, Y');
                } else {
                    return null;
                }
            })
            ->addColumn('po_jo_date',function($data){
                return "";
            })
            ->addColumn('action',function($data){
                return "";
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
        return $dt;
    }

    public function dataTable($request){
        $trans = Transactions::query()->with(['transDetails'])
            ->where('ref_book','=','PR');
        $search = $request->get('search')['value'] ?? null;

        if ($search) {
            $trans = $trans->where(function ($query) use ($search) {
                $query->where('ref_no', 'like', '%' . $search . '%')
                    ->orWhere('requested_by', 'like', '%' . $search . '%');
                /*$query->where('ref_no', 'like', '%' . $search . '%')
                    ->orWhereHas('transDetails', function ($q) use ($search) {
                        $q->where('item', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%');
                    });*/
            });
        } else {
            $trans = $trans->whereRaw('1 = 0'); // Add a condition that is always false to return no results
        }

        $dt = \DataTables::of($trans);

        /*$dt = $dt->filter(function ($query) use($search){
            if($search != null){
                $query->where('ref_no', 'like', '%'.$search.'%')
                    ->orWhereHas('transDetails',function ($q) use($search){
                    return $q->where('item','like','%'.$search.'%')
                        ->orWhere('description','like','%'.$search.'%');
                });
            }
        });*/

        $dt = $dt->addColumn('dept',function($data){
                return ($data->rc->description->name ?? null).
                    '<div class="table-subdetail" style="margin-top: 3px">'.($data->rc->department ?? null).
                    '<br>'.($data->rc->division ?? null).
                    '</div>';
            })
            ->addColumn('divSec',function($data){
                return $data->rc->division ?? null;
            })
            ->addColumn('details',function($data){
                if(!empty($data->transDetails)){
                    return view('ppu.pr.dtItems')->with([
                        'items' => $data->transDetails
                    ]);
                }
            })
            ->editColumn('abc',function($data){
                return number_format($data->abc,2);
            })
            ->addColumn('action',function($data){
                return view('ppu.pr.dtActions')->with([
                    'pr' => $data,
                ]);
            })
            ->editColumn('ref_no',function($data){
                if($data->cancelled_at != null){
                    return '<span class="">'.$data->ref_no.'</span><br><small class="text-danger text-strong" style="border-top: 1px solid black;">CANCELLED</small>';
                }
                return $data->ref_no;
            })
            ->editColumn('date',function($data){
                return !empty($data->date) ? Carbon::parse($data->date)->format('M. d, Y') : null;
            })
            ->editColumn('total',function($data){
                return number_format($data->transDetails()->sum('total_cost'),2);
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
        return $dt;
    }

    public function store(PRFormRequest $request){
//        abort(503,$this->prService->getNextPRNo());
        $pr = new PR();
        $pr->slug = Str::random();
        $pr->department = $request->department;
        $pr->division = $request->division;
        $pr->respCenter = $request->respCenter;
        $pr->papCode = $request->papCode;
        $pr->prNo = $this->prService->getNextPRNo();
        $pr->prDate = $request->prDate;
        $pr->sai = $request->sai;
        $pr->saiDate = $request->saiDate;
        $pr->purpose = $request->purpose;
        $pr->requestedBy = $request->requestedBy;
        $pr->requestedByDesignation = $request->requestedByDesignation;
        $pr->approvedBy = $request->approvedBy;
        $pr->approvedByDesignation = $request->approvedByDesignation;

        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'pr_slug' => $pr->slug,
                    'stockNo' => $item['stockNo'],
                    'unit' => $item['unit'],
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unitCost' => Helper::sanitizeAutonum($item['unitCost']),
                    'totalCost' => $item['qty'] * Helper::sanitizeAutonum($item['unitCost']),
                ]);
            }
        }
        if($pr->save()){
            PRItems::insert($arr);
            return $pr->only('slug');
        }
    }



    public function edit($slug){
        return view('ppu.pr.edit')->with([
            'pr' => $this->findBySlug($slug),
        ]);
    }


    public function update(PRFormRequest $request,$slug){
        $pr = $this->findBySlug($slug);
        $pr->department = $request->department;
        $pr->division = $request->division;
        $pr->respCenter = $request->respCenter;
        $pr->papCode = $request->papCode;
        $pr->prDate = $request->prDate;
        $pr->sai = $request->sai;
        $pr->saiDate = $request->saiDate;
        $pr->purpose = $request->purpose;
        $pr->requestedBy = $request->requestedBy;
        $pr->requestedByDesignation = $request->requestedByDesignation;
        $pr->approvedBy = $request->approvedBy;
        $pr->approvedByDesignation = $request->approvedByDesignation;

        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'pr_slug' => $pr->slug,
                    'stockNo' => $item['stockNo'],
                    'unit' => $item['unit'],
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unitCost' => Helper::sanitizeAutonum($item['unitCost']),
                    'totalCost' => $item['qty'] * Helper::sanitizeAutonum($item['unitCost']),
                ]);
            }
        }
        if($pr->save()){
            $pr->items()->delete();
            PRItems::insert($arr);
            return $pr->only('slug');
        }
    }

    public function findBySlug($slug){
        $pr = Transactions::query()->with(['transDetails','rc','transDetails.article'])->where('slug','=',$slug)->first();
        return $pr ?? abort(503,'PR not found');
    }
    public function destroy($slug){
        $pr = $this->findBySlug($slug);
        if($pr->delete()){
            $pr->items()->delete();
            return 1;
        }
        abort(503,'Error deleting item.');
    }

    public function print($slug){
        $pr = $this->findBySlug($slug);
        return view('printables.pr.pr_front_and_back')->with([
            'pr' => $pr,
        ]);
    }

    public function cancel(Request $request,$slug){
        $request->validate([
            'cancellation_reason' => 'required|string',
        ]);
        $pr = $this->findBySlug($slug);
        $pr->cancelled_at = Carbon::now();
        $pr->user_cancelled = \Auth::user()->user_id;
        $pr->cancellation_reason = $request->cancellation_reason;
        $pr->is_locked = 1;
        if($pr->save()){
            return $pr->only('slug');
        }
        abort(503,'Error in cancellation of transaction. PRController::cancel()');
    }

}