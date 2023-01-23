<?php


namespace App\Http\Controllers;


use App\Http\Requests\RFQ\RFQFormRequest;
use App\Models\RFQ;
use App\Models\Transactions;
use App\Swep\Services\RFQService;
use App\Swep\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RFQController extends Controller
{
    protected $rfqService;
    protected $transactionService;
    public function __construct(RFQService $rfqService, TransactionService $transactionService)
    {
        $this->rfqService = $rfqService;
        $this->transactionService = $transactionService;
    }

    public function index(){
        if(\request()->ajax() && \request()->has('draw')){
            $trans = Transactions::query()
                ->where(function($query){
                    $query->where('ref_book','=','PR')
                        ->orWhere('ref_book','=','JR');
                })
                ->whereDoesntHave('rfq');
            return \DataTables::of($trans)
                ->addColumn('action',function($data){
                    return view('ppu.rfq.dtActions')->with([
                        'data' => $data,
                    ]);
                })
                ->addColumn('transDetails',function($data){

                })
                ->editColumn('abc',function($data){
                    return number_format($data->abc,2);
                })
                ->escapeColumns([])
                ->setRowId('slug')
                ->toJson();
        }
        return view('ppu.rfq.index');
    }

    public function create(){
        $trans = $this->transactionService->findBySlug(\request('trans'));
        return view('ppu.rfq.create')->with([
            'trans' => $trans,
        ]);
    }

    public function store(RFQFormRequest $request){


        $trans = new Transactions();
        $trans->slug = Str::random();
        $trans->ref_book = 'RFQ';
        $trans->ref_no = $this->rfqService->getNextRFQNo();
        $trans->cross_slug = $request->trans;
        $trans->cross_ref_no = $this->transactionService->findBySlug($request->trans)->ref_no;
        $trans->rfq_deadline = $request->rfq_deadline;
        $trans->rfq_s_name = $request->rfq_s_name;
        $trans->rfq_s_position = $request->rfq_s_position;
        $trans->rfq_user_created = \Auth::user()->user_id;
        $trans->rfq_created_at = Carbon::now();
        $trans->save();
        return $trans->only('slug');
        $rfq->slug = Str::random();
        $rfq->type = strtoupper($request->prJr);
        $rfq->prOrJrNo = $request->{$request->prJr.'No'};
        $rfq->deadline = $request->deadline;
        if($rfq->save()){
            return $rfq->only('slug');
        }
        abort(503,'Error creating RFQ');
    }

    public function print($slug){
        $trans = $this->transactionService->findBySlug($slug);
        return view('printables.rfq.rfq_new')->with([
            'trans' => $trans,
        ]);
    }
}