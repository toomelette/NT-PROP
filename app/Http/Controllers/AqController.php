<?php


namespace App\Http\Controllers;


use App\Models\Transactions;
use App\Swep\Services\TransactionService;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Str;

class AqController extends Controller
{
    protected $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            $rfqs = Transactions::allRfq();
            return \DataTables::of($rfqs)
                ->with(['transaction'])
                ->addColumn('action',function($data){
                    return view('ppu.aq.dtActions')->with([
                        'data' => $data,
                    ]);
                })
                ->addColumn('transRefBook',function($data){
                    return Helper::refBookLabeler($data->transaction->ref_book ?? '');
                })
                ->addColumn('transRefNo',function($data){
                    return ($data->transaction->ref_no ?? '').'
                    <div class="table-subdetail text-right" style="color: #31708f"></div>
                    <small class="text-muted"> Requested by:<br>'.Str::limit(($data->transaction->requested_by ?? null),15,'...').'</small>
                    ';
                })
                ->editColumn('rfq_deadline',function($data){
                    if($data->rfq_deadline < Carbon::now()){
                        return '<span class="text-danger">'.Carbon::parse($data->rfq_deadline)->format('M. d, Y').' <i class="fa fa-times small"></i></span>';
                    }
                    if(Carbon::parse($data->rfq_deadline)->diffInDays() <= 3 ){
                        return '<span class="text-warning">'.Carbon::parse($data->rfq_deadline)->format('M. d, Y').' <i class="fa fa-warning small"></i> </span>';
                    }
                    return Carbon::parse($data->rfq_deadline)->format('M. d, Y');
                })
                ->addColumn('dates',function($data){
                    return Carbon::parse($data->transaction->date ?? null)->format('M. d, Y').' <i class="fa-fw fa fa-arrow-right"></i>'. Carbon::parse($data->created_at)->format('M. d, Y');
                })
                ->addColumn('transDetails',function($data){
                    if(!empty($data->transaction)){
                        $type = strtolower($data->transaction->ref_book ?? null);
                        return view('ppu.'.$type.'.dtItems')->with([
                                'items' => $data->transaction->transDetails,
                            ])->render().
                            '<small class="pull-right text-strong text-info">'.number_format($data->transaction->abc,2).'</small>';
                    }
                })

                ->escapeColumns([])
                ->setRowId('slug')
                ->toJson();
        }
        return view('ppu.aq.index');
    }


    public function create($slug){
        return view('ppu.aq.create')->with([
            'trans' => $this->transactionService->findBySlug($slug),
        ]);
    }
}