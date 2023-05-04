<?php


namespace App\Http\Controllers;


use App\Http\Requests\CancellationRequest\CRFormRequest;
use App\Models\CancellationRequest;
use App\Models\Transactions;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CancellationRequestController extends Controller
{
    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.cancellation_request.index');
    }

    public function dataTable($request){
        $cr = CancellationRequest::query();
        return DataTables::of($cr)
            ->addColumn('action',function($data){
                return view('ppu.cancellation_request.dtActions')->with([
                    'data' => $data, 'myIndex' => false,
                ]);
            })
            ->editColumn('total_amount',function($data){
                return number_format($data->total_amount,2);
            })
            ->editColumn('ref_date',function($data){
                return $data->ref_date ? Carbon::parse($data->ref_date)->format('M. d, Y') : '';
            })
            ->editColumn('is_cancelled',function($data){
                return $data->is_cancelled ? '<span class="text-danger text-strong">Cancelled</span>' : 'For Approval';
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

    public function myIndex(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->myDataTable($request);
        }
        return view('ppu.cancellation_request.myIndex');
    }

    public function myDataTable($request){
        $cr = CancellationRequest::query()->where('user_created', Auth::user()->user_id);
        return DataTables::of($cr)
            ->addColumn('action',function($data){
                return view('ppu.cancellation_request.dtActions')->with([
                    'data' => $data, 'myIndex' => true,
                ]);
            })
            ->editColumn('is_cancelled',function($data){
                return $data->is_cancelled ? '<span class="text-danger text-strong">Cancelled</span>' : 'For Approval';
            })
            ->editColumn('total_amount',function($data){
                return number_format($data->total_amount,2);
            })
            ->editColumn('ref_date',function($data){
                return $data->ref_date ? Carbon::parse($data->ref_date)->format('M. d, Y') : '';
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

    public function create(){
        return view('ppu.cancellation_request.create');
    }

    public function findTransactionByRefNumber($refNumber, $refBook){
        $cr = CancellationRequest::query()
            ->where('ref_book', '=', $refBook)
            ->where('ref_number', '=', $refNumber)
            ->first();
        if($cr != null){
            abort(503,'Already requested.');
        }
        $trans = Transactions::query()
            ->where('ref_book', '=', $refBook)
            ->where('ref_no', '=', $refNumber)
            ->first();
        $trans = $trans??null;
        return $trans?? abort(503,'No record found');
    }

    public function store(CRFormRequest $request){
        $trans = Transactions::query()->where('slug', '=', $request->slug)->first();
        $s = new CancellationRequest();
        $s->slug = Str::random(16);
        $s->request_no = $this->createNewRequestNo();
        $s->reason = $request->reason;
        $s->ref_book = $trans->ref_book;
        $s->ref_number =  $trans->ref_no;
        $s->ref_date =  $trans->date;
        $s->total_amount =  $trans->abc;
        //$s->requisitioner =  Auth::user()->employee->firstname . ' ' . Auth::user()->employee->middlename . ' ' . Auth::user()->employee->lastname;
        $s->requisitioner = $trans->requested_by;
        if($s->save()){
            $slug = $s->slug;
            return [
                'route' => route('dashboard.cancellationRequest.print', $slug),
            ];
            //return $s->only('id') + compact('slug');
        }
        abort(503,'Error saving request.');
    }

    private function createNewRequestNo(){
        $r = CancellationRequest::query()->orderBy('id','desc')->first();
        if(empty($r)){
            $new_no = Carbon::now()->format('Y').'-0001';
        }else{
            if(explode('-',$r->request_no)[0] == Carbon::now()->format('Y')){
                $new_no =  Carbon::now()->format('Y').'-'.str_pad(explode('-',$r->request_no)[1]+1,4,'0',STR_PAD_LEFT);
            }else{
                $new_no = Carbon::now()->format('Y').'-0001';
            }
        }
        return $new_no;
    }

    public function print($slug){
        return view('printables.cancellation_request.print')->with([
            'cr' => CancellationRequest::query()->where('slug', $slug)->first(),
        ]);
    }

    public function approve($slug){
        $cr = CancellationRequest::query()->where('slug', '=', $slug)->first();
        $cr->is_cancelled = true;
        $cr->save();

        $trans = Transactions::query()->where('ref_no','=',$cr->ref_number)
                ->where('ref_book','=',$cr->ref_book)->first();
        $trans->cancelled_at = Carbon::now();
        $trans->user_cancelled = \Auth::user()->user_id;
        $trans->cancellation_reason = $cr->reason;
        $trans->is_locked = 1;
        if($trans->save()){
            return $trans->only('slug');
        }
        abort(503,'Error in cancellation of transaction.');
    }
}