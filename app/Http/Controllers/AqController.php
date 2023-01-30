<?php


namespace App\Http\Controllers;


use App\Models\Offers;
use App\Models\Quotations;
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
        $trans = $this->transactionService->findBySlug($slug);
        if(!empty($trans->aq)){
            return redirect(route('dashboard.aq.edit',$trans->aq->slug));
        }
        $trans = new Transactions();
        $trans->slug = Str::random();
        $trans->cross_slug = $slug;
        $trans->ref_book = 'AQ';
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

    public function update(Request $request,$slug){
        return $request;
        $aq = $this->transactionService->findBySlug($slug);
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
                'supplier_slug' => $request->suppliers[$key],
                'warranty' => $request->warranty,
                'price_validity' => $request->price_validity,
                'delivery_term' => $request->delivery_term,
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
        $items = [];
        $quotations  = [];
        $by = 3;
        if(!empty($aq->quotationOffers)){
            foreach ($aq->quotationOffers as $offer){
                $items[$offer->item_slug][$offer->quotation->slug]['obj'] = $offer;
                $quotations[$offer->quotation->slug]['obj'] = $offer->quotation;
            }
        }

        $pages = [];
        $start = 0;
        foreach ($quotations as $key => $quotation){
                $pages[floor($start/$by)][$key] = $quotation;
                $start++;
        }

        return view('printables.aq.aq_front')->with([
            'trans' => $this->transactionService->findBySlug($transaction_slug),
            'items' => $items,
//            'quotations' => $quotations,
            'pages' => $pages,
        ]);
    }
}