<?php


namespace App\Http\Controllers;


use App\Models\AwardNoticeAbstract;
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
        if($request->has('item') && $request->item != ''){
            $dt = $dt->filter(function ($query) use($request){
                if($request->item != null){
                    $query->whereHas('transDetails',function ($q) use($request){
                        return $q->where('item','like','%'.$request->item.'%')
                            ->orWhere('description','like','%'.$request->item.'%');
                    });
                }
            });
        }


        /*$dt = $dt->filter(function ($query) use($search){
            if($search != null){
                $query->where('ref_no', 'like', '%'.$search.'%')
                    ->orWhereHas('transDetails',function ($q) use($search){
                        return $q->where('item','like','%'.$search.'%')
                            ->orWhere('description','like','%'.$search.'%');
                    });
            }
        });*/

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
        if(\request()->ajax() && \request()->has('draw')){
            return $this->monitoringDataTable($request);
        }
        return view('ppu.monitoring.jr.index');
    }

    public function monitoringDataTable($request){
        $trans = Transactions::query()->where('ref_book','=','JR');

        if($request->has('resp_center') && $request->resp_center != ''){
            $trans = $trans->where('resp_center','=',$request->resp_center);
        }
        if($request->has('year') && $request->year != ''){
            $trans = $trans->where('date','like',$request->year.'%');
        }


        $transAll = Transactions::all();
        $ana = AwardNoticeAbstract::all();
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
                $item = $ana->where('ref_book', '=', 'JR')
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