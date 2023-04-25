<?php


namespace App\Http\Controllers;


use App\Models\Offers;
use App\Models\PPURespCodes;
use App\Models\Quotations;
use App\Models\Suppliers;
use App\Models\Transactions;
use App\Swep\Services\AqService;
use App\Swep\Services\TransactionService;
use Barryvdh\DomPDF\PDF;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Str;

class AqController extends Controller
{
    protected $transactionService;
    protected $aqService;
    public function __construct(TransactionService $transactionService, AqService $aqService)
    {
        $this->transactionService = $transactionService;
        $this->aqService = $aqService;
    }

    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            if($request->has('all_aq') && $request->all_aq == true){
                return $this->allAqDataTable($request);
            }
            else{
                return $this->pendingAqDataTable($request);
            }
        }
        return view('ppu.aq.index');
    }

    public function allAqDataTable(Request $request){
        $aq = Transactions::where('ref_book', '=', 'AQ')->get();
        return \DataTables::of($aq)
            ->with(['transaction'])
            ->addColumn('action',function($data){
                return view('ppu.aq.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->addColumn('transRefBook',function($data){
                return Helper::refBookLabeler($data->transaction->ref_book ?? '');
            })
            ->editColumn('cross_ref_no',function($data){
                return ($data->transaction->ref_no ?? '').'
                    <div class="table-subdetail text-right" style="color: #31708f"></div>
                    <small class="text-muted"> Requested by:<br>'.Str::limit(($data->transaction->requested_by ?? null),15,'...').'</small>
                    ';
            })
            ->editColumn('abc',function($data){
                if(!empty($data->transaction->abc)){
                    return number_format($data->transaction->abc,2);
                }
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
        return view('ppu.aq.index');
    }

    public function pendingAqDataTable(Request $request){
        $trans = Transactions::where('ref_book', '=', 'RFQ')
            ->whereNotExists(function($query) {
                $query->select(\Illuminate\Support\Facades\DB::raw(1))
                    ->from('transactions as t')
                    ->whereRaw('t.cross_slug = transactions.cross_slug')
                    ->where('t.ref_book', '=', 'AQ');
            })
            ->get();
        return \DataTables::of($trans)
            ->with(['transaction'])
            ->addColumn('action',function($data){
                return view('ppu.aq.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->addColumn('transRefBook',function($data){
                return Helper::refBookLabeler($data->transaction->ref_book ?? '');
            })
            ->editColumn('cross_ref_no',function($data){
                return ($data->transaction->ref_no ?? '').'
                    <div class="table-subdetail text-right" style="color: #31708f"></div>
                    <small class="text-muted"> Requested by:<br>'.Str::limit(($data->transaction->requested_by ?? null),15,'...').'</small>
                    ';
            })
            ->editColumn('abc',function($data){
                if(!empty($data->transaction->abc)){
                    return number_format($data->transaction->abc,2);
                }
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

    public function create($slug){
        $trans = $this->transactionService->findBySlug($slug);
        if(!empty($trans->aq)){
            return redirect(route('dashboard.aq.edit',$trans->aq->slug));
        }
        $trans = new Transactions();
        $trans->ref_no = $this->aqService->getNextAqNo();
        $trans->slug = Str::random();
        $trans->cross_slug = $slug;
        $trans->ref_book = 'AQ';
        $trans->date = now();
        $trans->save();
        return redirect(route('dashboard.aq.edit',$trans->slug));
    }

    public function edit($slug){
        $aq = $this->transactionService->findBySlug($slug);
        $items = [];
        $quotations  = [];
        if(!empty($aq->quotationOffers)){
            foreach ($aq->quotationOffers as $offer){
                $items[$offer->item_slug][$offer->quotation->slug]['obj'] = $offer;
                $quotations[$offer->quotation->slug]['obj'] = $offer->quotation;
            }
        }
        return view('ppu.aq.edit')->with([
            'aq' => $aq,
            'items' => $items,
            'quotations' => $quotations,
        ]);
    }

    public function finalized($slug){
        $aq = $this->transactionService->findBySlug($slug);
        if($aq->is_locked){
            abort(503,'AQ is already final.');
        }
        $aq->is_locked = true;
        $aq->update();
        return 1;

        abort(503,'Error saving transaction.');
    }

    public function update(Request $request,$slug){
        $aq = $this->transactionService->findBySlug($slug);
        if($aq->is_locked){
            abort(503,'AQ is final and locked for editing.');
        }
        $aq->date = $request->date;
        $aq->remarks = $request->remarks;
        $aq->prepared_by = $request->prepared_by;
        $aq->prepared_by_position = $request->prepared_by_position;
        $aq->noted_by = $request->noted_by;
        $aq->noted_by_position = $request->noted_by_position;
        $aq->recommending_approval = $request->recommending_approval;
        $aq->recommending_approval_position = $request->recommending_approval_position;
        $aq->update();
        $arr = [];
        $quotationsArr = [];
        foreach ($request->offers as $key => $items) {
            $quotationSlug = Str::random();
            array_push($quotationsArr,[
                'slug' => $quotationSlug,
                'aq_slug' => $slug,
                'supplier_slug' => $request->suppliers[$key]['supplier_slug'],
                'warranty' => $request->suppliers[$key]['warranty'],
                'price_validity' => $request->suppliers[$key]['price_validity'],
                'has_attachments' => (isset($request->suppliers[$key]['has_attachments'])) ? 1 : null,
                'delivery_term' => $request->suppliers[$key]['delivery_term'],
                'payment_term' => $request->suppliers[$key]['payment_term'],
            ]);
            foreach ($items as $itemSlug => $offer ){
                array_push($arr,[
                    'slug' => Str::random(),
                    'quotation_slug' => $quotationSlug,
                    'item_slug' => $itemSlug,
                    'amount' => \App\Swep\Helpers\Helper::sanitizeAutonum($offer['amount']),
                    'description' => $offer['description'],
                ]);
            }
        }


        $aq->quotationOffers()->delete();
        $aq->quotations()->delete();
        Quotations::insert($quotationsArr);
        Offers::insert($arr);
        return 1;

        abort(503,'Error saving transaction. [AqController::store]');
    }

    public function print($transaction_slug){
        $aq = $this->transactionService->findBySlug($transaction_slug);
        $prjr = $this->transactionService->findBySlug($aq->cross_slug);
        $department = PPURespCodes::query()->where('rc_code', '=', $prjr->resp_center)->first();
        $items = [];
        $quotations  = [];
        $by = 3;
        if(!empty($aq->quotationOffers)){
            foreach ($aq->quotationOffers as $offer){
                $items[$offer->item_slug][$offer->quotation->slug]['obj'] = $offer;
                $quotations[$offer->quotation->slug]['obj'] = $offer->quotation;
            }
        }
        $suppliers = Suppliers::all();
        $pages = [];
        $start = 0;
        foreach ($quotations as $key => $quotation){
                $pages[floor($start/$by)][$key] = $quotation;
                $start++;
        }

//        $pdf = \PDF::loadView('printables.aq.aq_front',[
//            'trans' => $this->transactionService->findBySlug($transaction_slug),
//            'items' => $items,
////            'quotations' => $quotations,
//            'pages' => $pages,
//        ])->setPaper('folio', 'landscape');
//
//        return $pdf->stream('sss.pdf');
        $nature_of_work_arr = [];
        foreach ($aq->transaction->transDetails as $tran){
            $nature_of_work_arr[] = $tran->nature_of_work;
        }
        return view('printables.aq.aq_front')->with([
            'trans' => $this->transactionService->findBySlug($transaction_slug),
            'items' => $items,
            'quotations' => $quotations,
            'pages' => $pages,
            'suppliers' => $suppliers,
            'prjr' => $prjr,
            'department' => $department,
            'nature_of_work_arr' => $nature_of_work_arr,
        ]);
    }
}