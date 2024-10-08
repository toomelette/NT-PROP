<?php


namespace App\Http\Controllers;


use App\Http\Requests\PR\PRFormRequest;
use App\Models\AwardNoticeAbstract;
use App\Models\PPURespCodes;
use App\Models\PR;
use App\Models\PRItems;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
use App\Swep\Services\PRService;
use App\Swep\Services\TransactionService;
use App\Swep\Traits\PRTimelineTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PRController extends Controller
{
    use PRTimelineTrait;
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
        if($request->has('print') && $request->print == true){
            return $this->printTable($request);
        }
        if(\request()->ajax() && \request()->has('draw')){
            return $this->monitoringDataTable($request);
        }
        return view('ppu.monitoring.pr.index');
    }

    private function printTable(Request $request){
        $trans = Transactions::query()
            ->with(['rfq','aq','anaPr'])
            ->where('ref_book','=','PR')
            ->where('cancelled_at','=', null);
        $resp_center = null;
        if(!empty($request->year) && $request->year != ''){
            $trans->where('date','like',$request->year.'%');
        }
        if(!empty($request->resp_center) && $request->resp_center != ''){
            $trans->where('resp_center','=',$request->resp_center);
            $resp_center = PPURespCodes::query()
                ->where('rc_code','=',$request->resp_center)
                ->first();
        }
        $trans = $trans->orderBy('pap_code')->orderBy('ref_no')->get();
        return view('printables.monitoring.pr')->with([
            'transactions' => $trans,
            'resp_center' => $resp_center,
            'request' => $request,
        ]);
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

    public function monitoringDataTable(Request $request){
        $rcs = PPURespCodes::query()->with(['description'])
            ->where(function($query){
                foreach (Auth::user()->availablePaps as $availablePap){
                    $query->orWhere('rc','=',$availablePap->rc);
                }
            })->get();

        $rcCodes = $rcs->pluck('rc_code')->toArray();
        $trans = Transactions::query()
            ->with(['rfq','aq','po'])
            ->where('ref_book','=','PR')
            ->whereIn('resp_center', $rcCodes);

        /*$trans = Transactions::query()
            ->with(['rfq','aq','po'])
            ->where('ref_book','=','PR');*/

        if($request->has('resp_center') && $request->resp_center != ''){
            $trans = $trans->where('resp_center','=',$request->resp_center);
        }
        if($request->has('year') && $request->year != ''){
            $trans = $trans->where('date','like',$request->year.'%');
        }

        $search = $request->get('search')['value'] ?? null;

        $dt = \DataTables::of($trans);
        $dt = $dt->filter(function ($query) use($search){
        if($search != null){
                $query->where('ref_no', 'like', '%'.$search.'%');
            }
        });

        $dt = $dt->addColumn('pr_no',function($data){
                 return '<a href="'.route('dashboard.pr.index').'?find='.$data->ref_no.'" target="_blank" class="no-margin" title="PR: '.$data->purpose.' ">'.$data->ref_no.'</a>';
            })
            ->addColumn('date_created',function($data){
                return !empty($data->date) ? Carbon::parse($data->date)->format('M. d, Y') : null;
            })
            ->addColumn('date_received',function($data){
                return !empty($data->received_at) ? Carbon::parse($data->date)->format('M. d, Y') : null;
            })
            ->addColumn('rfq_date', function($data){
                return Helper::dateFormat($data->rfq->created_at ?? null);
//                $item = $transAll->where('cross_slug', $data->slug)
//                    ->where('ref_book', 'RFQ')
//                    ->first();
//                if ($item) {
//                    return Carbon::parse($item->created_at)->format('M. d, Y');
//                } else {
//                    return null;
//                }
            })
            ->addColumn('aq_date', function($data) {
                return '<span class="">'.Helper::dateFormat($data->aq->created_at ?? null).'<br><a>'.($data->aq->ref_no ?? null).'</a></span>';
            })
            ->addColumn('rbac_reso_date',function($data){
                return "";
            })
            ->addColumn('noa_date',function($data){
                return Helper::dateFormat($data->noaPr->award_date ?? null,'M. d, Y');
            })
            ->addColumn('po_date',function($data){
                $output = "";
                foreach ($data->po as $item) {
                    $output .= Helper::dateFormat($item->date ?? null,'M. d, Y'). '<br>';
                }
                return $output;
            })
            ->addColumn('action',function($data){
                return "";
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
        return $dt;
    }

    public function dataTable(Request $request){
        $trans = Transactions::query()->with(['transDetails'])
            ->where('ref_book','=','PR');

        if($request->has('resp_center') && $request->resp_center != ''){
            $trans = $trans->where('resp_center','=',$request->resp_center);
        }
        if($request->has('requested_by') && $request->requested_by != ''){
            $trans = $trans->where('requested_by','=',$request->requested_by);
        }
        if($request->has('year') && $request->year != ''){
            $trans = $trans->where('date','like',$request->year.'%');
        }
//        $search = $request->get('search')['value'] ?? null;
//        if ($search) {
//            $trans = $trans->where(function ($query) use ($search) {
//                $query->where('ref_no', 'like', '%' . $search . '%')
//                    ->orWhere('requested_by', 'like', '%' . $search . '%');
//                /*$query->where('ref_no', 'like', '%' . $search . '%')
//                    ->orWhereHas('transDetails', function ($q) use ($search) {
//                        $q->where('item', 'like', '%' . $search . '%')
//                            ->orWhere('description', 'like', '%' . $search . '%');
//                    });*/
//            });
//        } else {
//            $trans = $trans->whereRaw('1 = 0'); // Add a condition that is always false to return no results
//        }

        if($request->has('item') && $request->item != null){
            $trans->whereIn('slug',function ($q) use ($request){
                $q->select('transaction_slug')
                    ->from(with(new TransactionDetails)->getTable())
                    ->where('item','like','%'.$request->item.'%')
                    ->orWhere('description','like','%'.$request->item.'%');
            });

        }


        $dt = \DataTables::of($trans);
//        if($request->has('item') && $request->item != ''){
//            $dt = $dt->filter(function ($query) use($request){
//                if($request->item != null){
//                    $query->whereHas('transDetails',function ($q) use($request){
//                        return $q->where('item','like','%'.$request->item.'%')
//                            ->orWhere('description','like','%'.$request->item.'%');
//                    });
//                }
//            });
//        }
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

    public function show($slug){
        $pr = $this->findBySlug($slug);
        $timeline = $this->prTimeline($slug,$pr);
        return view('ppu.pr_my.show')->with([
            'pr' => $pr,
            'timeline' => $timeline,
        ]);
    }

    public function edit_thru_admin($slug){
        $pr =$this->findBySlug($slug);
        if($pr->is_locked == 1){
            abort(510,'This transaction is already locked from editing.');
        }
        return view('ppu.pr_my.edit')->with([
            'pr' => $pr,
        ]);
    }

    public function unlock($slug){
        $pr = $this->transactionService->findBySlug($slug);
        $pr->is_locked = null;
        $pr->update();
        return 1;

        abort(503,'Error updating transaction.');
    }

}