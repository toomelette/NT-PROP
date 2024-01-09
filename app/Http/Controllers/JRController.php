<?php


namespace App\Http\Controllers;


use App\Models\AwardNoticeAbstract;
use App\Models\JR;
use App\Models\JRItems;
use App\Models\PPURespCodes;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
use App\Swep\Services\JRService;
use App\Swep\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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

    public function dataTable(Request $request){
        $trans = Transactions::query()->with(['transDetails'])
            ->where('ref_book','=','JR');
        if($request->has('resp_center') && $request->resp_center != ''){
            $trans = $trans->where('resp_center','=',$request->resp_center);
        }
        if($request->has('requested_by') && $request->requested_by != ''){
            $trans = $trans->where('requested_by','=',$request->requested_by);
        }
        if($request->has('year') && $request->year != ''){
            $trans = $trans->where('date','like',$request->year.'%');
        }
        //search by item
        if($request->has('item') && $request->item != null){
            $trans->whereIn('slug',function ($q) use ($request){
                $q->select('transaction_slug')
                    ->from(with(new TransactionDetails)->getTable())
                    ->where('item','like','%'.$request->item.'%')
                    ->orWhere('description','like','%'.$request->item.'%');
            });

        }


//        $search = $request->get('search')['value'] ?? null;
//
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

        $dt = \DataTables::of($trans);

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
                    return '<s class="text-danger">'.$data->ref_no.'</s><br><small class="text-danger text-strong" style="border-top: 1px solid black;">CANCELLED</small>';
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

    public function monitoringIndex(Request $request){
        if($request->has('print') && $request->print == true){
            return $this->printTable($request);
        }
        if(\request()->ajax() && \request()->has('draw')){
            return $this->monitoringDataTable($request);
        }
        return view('ppu.monitoring.jr.index');
    }

    public function monitoringDataTable($request){
        $rcs = PPURespCodes::query()->with(['description'])
            ->where(function($query){
                foreach (Auth::user()->availablePaps as $availablePap){
                    $query->orWhere('rc','=',$availablePap->rc);
                }
            })->get();

        $rcCodes = $rcs->pluck('rc_code')->toArray();
        $trans = Transactions::query()
            ->with(['rfq','aq','po'])
            ->where('ref_book','=','JR')
            ->whereIn('resp_center', $rcCodes);

        //$trans = Transactions::query()->where('ref_book','=','JR');


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

        $dt = $dt->addColumn('jr_no',function($data){
                return '<a href="'.route('dashboard.jr.index').'?find='.$data->ref_no.'" target="_blank" class="no-margin" title="JR: '.$data->purpose.' ">'.$data->ref_no.'</a>';
            })
            ->addColumn('date_created',function($data){
                return !empty($data->date) ? Carbon::parse($data->date)->format('M. d, Y') : null;
            })
            ->addColumn('date_received',function($data){
                return !empty($data->received_at) ? Carbon::parse($data->date)->format('M. d, Y') : null;
            })
            ->addColumn('rfq_date', function($data){
                return Helper::dateFormat($data->rfq->created_at ?? null);
            })
            ->addColumn('aq_date', function($data) {
                return '<span class="">'.Helper::dateFormat($data->aq->created_at ?? null).'<br><a>'.($data->aq->ref_no ?? null).'</a></span>';
            })
            ->addColumn('rbac_reso_date',function($data){
                return "";
            })
            ->addColumn('noa_date',function($data){
                return Helper::dateFormat($data->anaPr->award_date ?? null,'M. d, Y');
            })
            ->addColumn('jo_date',function($data){
                $output = "";
                foreach ($data->po as $item) {
                    $output += $item->date. '<br>';
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

    private function printTable(Request $request){
        $trans = Transactions::query()
            ->with(['rfq','aq','anaPr'])
            ->where('ref_book','=','JR')
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
        return view('printables.monitoring.jr')->with([
            'transactions' => $trans,
            'resp_center' => $resp_center,
            'request' => $request,
        ]);
    }

    public function edit_thru_admin($slug){
        $jr =$this->findBySlug($slug);
        if($jr->is_locked == 1){
            abort(510,'This transaction is already locked from editing.');
        }
        return view('ppu.jr_my.edit')->with([
            'jr' => $jr,
        ]);
    }

    public function unlock($slug){
        $pr = $this->transactionService->findBySlug($slug);
        $pr->is_locked = null;
        $pr->update();
        return 1;

        abort(503,'Error updating transaction.');
    }

    public function findBySlug($slug){
        $jr = Transactions::query()->with(['transDetails','rc','transDetails.article'])->where('slug','=',$slug)->first();
        return $jr ?? abort(503,'JR not found');
    }
}