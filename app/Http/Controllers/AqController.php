<?php


namespace App\Http\Controllers;


use App\Jobs\EmailNotification;
use App\Models\Offers;
use App\Models\PPURespCodes;
use App\Models\Quotations;
use App\Models\Suppliers;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Arrays;
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
        $aq = Transactions::where('ref_book', '=', 'AQ');
        $search = $request->get('search')['value'] ?? null;
        if ($search) {
            $aq = $aq->where(function ($query) use ($search) {
                $query->where('ref_no', 'like', '%' . $search . '%');
            });
        } else {
            $aq = $aq->whereRaw('1 = 0');
        }
        $aq = $aq->get();
        $dt = \DataTables::of($aq);

        $dt = $dt->with(['transaction'])
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
                    $rfqtrans = Transactions::query()
                        ->where('cross_slug', '=', $data->cross_slug)
                        ->where('ref_book', '=', 'RFQ')
                        ->get();
                    if($rfqtrans->count() > 1){
                        $rfqtrans = Transactions::query()
                            ->where('cross_slug', '=', $data->cross_slug)
                            ->where('ref_no', '=', $data->cross_ref_no)
                            ->where('ref_book', '=', 'RFQ')
                            ->first();
                    }
                    else {
                        if(!empty($data->cross_ref_no)){
                            $rfqtrans = Transactions::query()
                                ->where('cross_slug', '=', $data->cross_slug)
                                ->where('ref_no', '=', $data->cross_ref_no)
                                ->where('ref_book', '=', 'RFQ')
                                ->first();
                        }
                        else{
                            $rfqtrans = Transactions::query()
                                ->where('cross_slug', '=', $data->cross_slug)
                                ->where('ref_book', '=', 'RFQ')
                                ->first();
                        }
                    }
                    $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $rfqtrans->slug)->get();
                    $type = strtolower($data->transaction->ref_book ?? null);
                    return view('ppu.'.$type.'.dtItems')->with([
                            /*'items' => $data->transaction->transDetails,*/
                            'items' => $transDetails,
                        ])->render().
                        '<small class="pull-right text-strong text-info">'.number_format($rfqtrans->abc,2).'</small>';
                }
            })

            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
        return $dt;
    }

    public function pendingAqDataTable(Request $request){
        $trans = Transactions::where('ref_book', '=', 'RFQ')
            ->whereNotExists(function($query) {
                $query->select(\Illuminate\Support\Facades\DB::raw(1))
                    ->from('transactions as t')
                    ->whereRaw('t.cross_slug = transactions.cross_slug')
                    ->where('t.ref_book', '=', 'AQ');
            });

        $search = $request->get('search')['value'] ?? null;

        if ($search) {
            $trans = $trans->where(function ($query) use ($search) {
                $query->where('ref_no', 'like', '%' . $search . '%');
                /*$query->where('ref_no', 'like', '%' . $search . '%')
                    ->orWhereHas('transDetails', function ($q) use ($search) {
                        $q->where('item', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%');
                    });*/
            });
        } else {
            $trans = $trans->whereRaw('1 = 0'); // Add a condition that is always false to return no results
        }
        $trans = $trans->get();

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

        $dt = $dt->with(['transaction'])
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
                    $rfqtrans = Transactions::query()
                        ->where('slug', '=', $data->slug)
                        ->first();
                    return number_format($rfqtrans->abc,2);
                }
            })
            ->addColumn('dates',function($data){
                return Carbon::parse($data->transaction->date ?? null)->format('M. d, Y').' <i class="fa-fw fa fa-arrow-right"></i>'. Carbon::parse($data->created_at)->format('M. d, Y');
            })
            ->addColumn('transDetails',function($data){
                if(!empty($data->transaction)){
                    $rfqtrans = Transactions::query()
                        ->where('slug', '=', $data->slug)
                        ->first();
                    $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $rfqtrans->slug)->get();
                    $type = strtolower($data->transaction->ref_book ?? null);
                    return view('ppu.'.$type.'.dtItems')->with([
                            /*'items' => $data->transaction->transDetails,*/
                            'items' => $transDetails,
                        ])->render().
                        '<small class="pull-right text-strong text-info">'.number_format($rfqtrans->abc,2).'</small>';
                }
            })
            /*
            ->addColumn('transDetails',function($data){
                if(!empty($data->transaction)){
                    $type = strtolower($data->transaction->ref_book ?? null);
                    return view('ppu.'.$type.'.dtItems')->with([
                            'items' => $data->transaction->transDetails,
                        ])->render().
                        '<small class="pull-right text-strong text-info">'.number_format($data->transaction->abc,2).'</small>';
                }
            })*/

            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
        return $dt;
    }

    public function create($slug){
        $trans = $this->transactionService->findBySlug($slug);
        $aqExist = Transactions::query()
                    ->where('slug','=', $slug)
                    ->where('ref_book','=', 'AQ')
                    ->first();
       /* if(!empty($trans->aq)){*/

        if(!empty($aqExist)){
            return redirect(route('dashboard.aq.edit',$aqExist->slug));
        }
        else {
            $trans1 = new Transactions();
            $trans1->ref_no = $this->aqService->getNextAqNo();
            $trans1->slug = Str::random();
            $trans1->cross_slug = $trans->cross_slug;
            $trans1->cross_ref_no = $trans->ref_no;
            $trans1->ref_book = 'AQ';
            $trans1->date = now();
            $trans1->save();
            return redirect(route('dashboard.aq.edit',$trans1->slug));
        }
    }

    public function edit($slug){
        $aq = $this->transactionService->findBySlug($slug);
        $items = [];
        $quotations  = [];

        $rfqtrans = Transactions::query()
            ->where('ref_no', '=', $aq->cross_ref_no)
            ->where('ref_book', '=', 'RFQ')
            ->first();
        $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $rfqtrans->slug)->get();

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
            'transDetails' => $transDetails
        ]);
    }

    public function finalized($slug){
        $aq = $this->transactionService->findBySlug($slug);
        if($aq->is_locked){
            abort(503,'AQ is already final.');
        }
        $aq->is_locked = true;

        //EMAIL NOTIFICATION
        $to = $aq->transaction->userCreated->email;
        $subject =  $subject = Arrays::acronym($aq->transaction->ref_book).' No. '.$aq->transaction->ref_no;
        $cc = $aq->transaction->rc->emailRecipients->pluck('email_address')->toArray();
        $body = view('mailables.email_notifier.body-aq-finalized')->with([
            'transaction' => $aq->transaction,
            'aq' => $aq,
        ])->render();

        if($aq->update()){
            EmailNotification::dispatch($to,$subject,$body,$cc);
            return 1;
        };
        abort(503,'Error saving transaction.');
    }

    public function unlock($slug){
        $aq = $this->transactionService->findBySlug($slug);
        $aq->is_locked = null;
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
        /*$rfqtrans = Transactions::query()
            ->where('cross_slug', '=', $aq->cross_slug)
            ->where('ref_book', '=', 'RFQ')
            ->first();*/
        $rfqtrans = Transactions::query()
            ->where('ref_no', '=', $aq->cross_ref_no)
            ->where('ref_book', '=', 'RFQ')
            ->first();
        $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $rfqtrans->slug)->get();
        $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $rfqtrans->slug)->get();
        foreach ($transDetails as $tran){
            $nature_of_work_arr[] = $tran->nature_of_work;
        }
        /*foreach ($aq->transaction->transDetails as $tran){
            $nature_of_work_arr[] = $tran->nature_of_work;
        }*/
        return view('printables.aq.aq_front')->with([
            'trans' => $this->transactionService->findBySlug($transaction_slug),
            'items' => $items,
            'quotations' => $quotations,
            'pages' => $pages,
            'suppliers' => $suppliers,
            'prjr' => $prjr,
            'department' => $department,
            'nature_of_work_arr' => $nature_of_work_arr,
            'transDetails' => $transDetails
        ]);
    }
}