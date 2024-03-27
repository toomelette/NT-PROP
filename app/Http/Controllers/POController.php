<?php


namespace App\Http\Controllers;


use App\Http\Requests\PO\POFormRequest;
use App\Jobs\EmailNotification;
use App\Models\AQOfferDetails;
use App\Models\AQQuotation;
use App\Models\AwardNoticeAbstract;
use App\Models\Employee;
use App\Models\Order;
use App\Models\PODetails;
use App\Models\PPURespCodes;
use App\Models\RCDesc;
use App\Models\Suppliers;
use App\Models\TaxComputation;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Arrays;
use App\Swep\Helpers\Helper;
use App\Swep\Services\TransactionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class POController extends Controller
{
    public function index(Request $request){
        if(\request()->ajax() && \request()->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.purchase_order.index');
    }

    public function dataTable(Request $request){
        $po = Order::query()
            ->with(['transaction.rc'])
            ->where('ref_book', 'PO');
        if($request->has('year') && $request->year != ''){
            $po = $po->where('date','like',$request->year.'%');
        }
        if($request->has('resp_center') && $request->resp_center != '') {
            $po = $po->whereHas('transaction',function ($query) use ($request){
                return $query->where('resp_center','=',$request->resp_center);
            });
        }
        return DataTables::of($po)
            ->addColumn('action',function($data){
                return view('ppu.purchase_order.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->addColumn('dept', function ($data) {
                if ($data->transaction != null) {
                    return $data->transaction->rc->desc ?? '-';
                }

                return "";
            })
            ->editColumn('total',function($data){
                return number_format($data->total,2);
            })
            ->editColumn('created_at',function($data){
                return $data->created_at ? Carbon::parse($data->created_at)->format('M. d, Y') : '';
            })
            ->editColumn('ref_no',function($data){
                if($data->transaction != null) {
                    if($data->transaction->cancelled_at != null){
                        return '<span class="">'.$data->ref_no.'</span><br><small class="text-danger text-strong" style="border-top: 1px solid black;">CANCELLED</small>';
                    }
                }

                return $data->ref_no;
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
    }

    public function create(){
        $suppliers = Suppliers::orderBy('name')->pluck('name','slug');
        $po_number = $this->getNextPONo("PO");
        return view('ppu.purchase_order.create', compact('suppliers', 'po_number'));
    }

    public function createManual(){
        $suppliers = Suppliers::orderBy('name')->pluck('name','slug');
        $po_number = $this->getNextPONo("PO");
        return view('ppu.purchase_order.createManual', compact('suppliers', 'po_number'));
    }

    public function createpublicbidding(){
        $suppliers = Suppliers::orderBy('name')->pluck('name','slug');
        $po_number = $this->getNextPONo("PO");
        return view('ppu.purchase_order.createpublicbidding', compact('suppliers', 'po_number'));
    }

    public function getNextPONo($ref_book){
        $year = Carbon::now()->format('Y-');
        $trans = Order::query()
            ->where('ref_no','like',$year.'%')
            ->where('ref_book','=', $ref_book)
            ->orderBy('ref_no','desc')
            ->limit(1)->first();
        if(empty($trans)){
            $poNo = 0;
        }else{
//            $prNo = str_replace($year,'',$pr->ref_no);
            $poNo =  substr($trans->ref_no, -4);
        }

        $newPOBaseNo = str_pad($poNo +1,4,'0',STR_PAD_LEFT);

        return $year.Carbon::now()->format('m-').$newPOBaseNo;
    }

    public function findSupplier($slug){
        $s = Suppliers::query()->where('slug','=', $slug)->first();
        $s = $s??null;
        if($s == null) {
            return abort(503,'No record found.');
        }
        $sVat = $s->is_vat?"VAT":"NON_VAT";
        $tc = TaxComputation::query()->where('name','=',$sVat)->first();
        $tcPO = TaxComputation::query()->where('name','=','PO')->first();
        $result = [
            'supplier' => $s,
            'tax_computation' => $tc,
            'tcPO' => $tcPO
        ];
        return $result;
    }

    public function edit($slug) {
        $order = Order::query()->where('slug','=', $slug)->first();
        $trans = Transactions::query()->where('order_slug','=', $slug)->first();
        //$trans->transDetails()->delete();
        return view('ppu.purchase_order.edit')->with([
            'order' => $order,
            'trans' => $trans,
            'slug' => $slug,
        ]);
    }

    public function update(FormRequest $request,$slug){
        $trans = Transactions::query()->where('order_slug','=', $slug)->first();
        $order = Order::query()->where('slug', '=', $slug)->first();
        $order->mode = $request->mode;
        $order->date = $request->date;
        $order->supplier_address = $request->supplier_address;
        $order->supplier_tin = $request->supplier_tin;
        $order->supplier_representative = $request->supplier_representative;
        $order->place_of_delivery = $request->place_of_delivery;
        $order->delivery_term = $request->delivery_term;
        $order->payment_term = $request->payment_term;
        $order->delivery_date = $request->delivery_date??null;
        $order->authorized_official = $request->authorized_official;
        $order->authorized_official_designation = $request->authorized_official_designation;
        $order->funds_available = $request->funds_available;
        $order->funds_available_designation = $request->funds_available_designation;
        $order->remarks = $request->remarks;
        $order->vat = $request->vatValue;
        $order->withholding_tax = $request->poValue;
        $order->total_gross = Helper::sanitizeAutonum($request->total_gross);
        $order->total =  Helper::sanitizeAutonum($request->total);
        $order->total_in_words = $request->total_in_words;
        $order->tax_base_1 = Helper::sanitizeAutonum($request->tax_base_1);
        $order->tax_base_2 = Helper::sanitizeAutonum($request->tax_base_2);

        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item) {
                array_push($arr,[
                    'slug' => Str::random(),
                    'transaction_slug' => $trans->slug,
                    'rfq_slug' => $item['rfq_slug'],
                    'stock_no' => $item['stock_no'],
                    'unit' => $item['unit'],
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'total_cost' => Helper::sanitizeAutonum($item['total_cost']),
                    'property_no' => $item['property_no'],
                    'nature_of_work' => $item['nature_of_work'],
                ]);
            }
        }
        $trans->transDetails()->delete();
        if($order->save()){
            TransactionDetails::insert($arr);
            return $order->only('slug');
        }
        abort(503,'Error updating purchase order.');
    }

    public function store(POFormRequest $request) {
        $refBook = "PO";
        //$poNUmber = $this->getNextPONo($refBook);
        $poNumber = $request->po_number;
        $orderExist = Order::query()->where('ref_no','=',$request->po_number)
                                    ->where('ref_book', '=', $refBook)->first();
        if($orderExist != null) {
            return abort(503,'PO Number already exist.');
        }
        $randomSlug = Str::random();
        //$poNUmber = $this->getNextPONo($refBook);
        $s = Suppliers::query()->where('slug','=', $request->supplier)->first();

        $order = new Order();
        $order->ref_no = $request->po_number;
        $order->slug = $randomSlug;
        $order->date = $request->date;
        $order->supplier = $s->slug;
        $order->supplier_name = $s->name;
        $order->supplier_address = $request->supplier_address;
        $order->supplier_tin = $request->supplier_tin;
        $order->supplier_representative = $request->supplier_representative;
        $order->place_of_delivery = $request->place_of_delivery;
        $order->delivery_term = $request->delivery_term;
        $order->payment_term = $request->payment_term;
        $order->delivery_date = $request->delivery_date??null;
        $order->mode = $request->mode;
        $order->authorized_official = $request->authorized_official;
        $order->authorized_official_designation = $request->authorized_official_designation;
        $order->funds_available = $request->funds_available;
        $order->funds_available_designation = $request->funds_available_designation;
        $order->ref_book = $refBook;
        $order->remarks = $request->remarks;
        $order->vat = $request->vatValue;
        $order->withholding_tax = $request->poValue;

        $order->total_gross = Helper::sanitizeAutonum($request->total_gross);
        $order->total =  Helper::sanitizeAutonum($request->total);
        $order->total_in_words = $request->total_in_words;
        $order->tax_base_1 = Helper::sanitizeAutonum($request->tax_base_1);
        $order->tax_base_2 = Helper::sanitizeAutonum($request->tax_base_2);

        $arr = [];
        if($request->mode == "Public Bidding"){
            $refNumberArray = preg_split('/\s*,\s*/', $request->ref_number, -1, PREG_SPLIT_NO_EMPTY);
            $transRecords = Transactions::whereIn('ref_no', $refNumberArray)
                ->where('ref_book', 'PR')
                ->get();

            $transNewSlug = Str::random();
            $transNew = new Transactions();
            $transNew->slug = $transNewSlug;
            $transNew->ref_book = $refBook;
            $transNew->ref_no = $poNumber;
            $transNew->order_slug = $randomSlug;

            $transactionTotals = [];
            if(!empty($request->items)){
                foreach ($request->items as $item) {
                    $slug = $item['transaction_slug'];
                    $cost = Helper::sanitizeAutonum($item['total_cost']);

                    if (isset($transactionTotals[$slug])) {
                        $transactionTotals[$slug] += $cost;
                    } else {
                        $transactionTotals[$slug] = $cost;
                    }

                    array_push($arr,[
                        'slug' => Str::random(),
                        'transaction_slug' =>$transNewSlug,
                        'stock_no' => $item['stock_no'],
                        'unit' => $item['unit'],
                        'item' => $item['item'],
                        'description' => $item['description'],
                        'qty' => $item['qty'],
                        'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                        'total_cost' => Helper::sanitizeAutonum($item['total_cost']),
                        'property_no' => $item['property_no'],
                        'nature_of_work' => $item['nature_of_work'],
                    ]);
                }
            }
            foreach ($transactionTotals as $slug => $total_cost) {
                foreach ($refNumberArray as $refNumber) {
                    $trans = $transRecords->where('ref_no', $refNumber)->first();
                    if($trans->slug == $slug){
                        $poDetails = new PODetails();
                        $poDetails->slug = Str::random();
                        $poDetails->order_slug = $order->slug;
                        $poDetails->pr_number = $refNumber;
                        $poDetails->abc = $total_cost;
                        if ($trans) {
                            $poDetails->pap_code = $trans->pap_code;
                            $poDetails->resp_center = $trans->resp_center;
                            $poDetails->requested_by = $trans->requested_by;
                        }
                        $poDetails->save();
                    }
                }
            }
        }
        else {
            //$refNumber= $request->ref_number;
            $rfqtrans = Transactions::query()
                ->where('ref_no', '=', $request->ref_number)
                ->where('ref_book', '=', 'RFQ')
                ->first();
            $trans = Transactions::query()
                ->where('slug', '=', $rfqtrans->cross_slug)
                ->first();

            $transNewSlug = Str::random();
            $transNew = new Transactions();
            $transNew->slug = $transNewSlug;
            $transNew->resp_center = $trans->resp_center;
            $transNew->pap_code = $trans->pap_code;
            $transNew->ref_book = $refBook;
            $transNew->ref_no = $poNumber;
            $transNew->cross_slug = $trans->slug;
            $transNew->cross_ref_no = $trans->ref_no;
            $transNew->purpose = $trans->purpose;
            $transNew->jr_type =$trans->jr_type;
            $transNew->requested_by = $trans->requested_by;
            $transNew->requested_by_designation = $trans->requested_by_designation;
            $transNew->approved_by = $trans->approved_by;
            $transNew->approved_by_designation = $trans->approved_by_designation;
            $transNew->order_slug = $randomSlug;
            $transNew->date = $request->date;

            if(!empty($request->items)){
                foreach ($request->items as $item) {
                    array_push($arr,[
                        'slug' => Str::random(),
                        'transaction_slug' => $transNewSlug,
                        'stock_no' => $item['stock_no'],
                        'unit' => $item['unit'],
                        'item' => $item['item'],
                        'description' => $item['description'],
                        'qty' => $item['qty'],
                        'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                        'total_cost' => Helper::sanitizeAutonum($item['total_cost']),
                        'property_no' => $item['property_no'],
                        'nature_of_work' => $item['nature_of_work'],
                    ]);
                }
            }
        }

        if($order->save()){
            if($transNew->save()){
                TransactionDetails::insert($arr);
                if($request->mode == "Public Bidding"){
                    $refNumberArray = preg_split('/\s*,\s*/', $request->ref_number, -1, PREG_SPLIT_NO_EMPTY);
                    $transRecords = Transactions::whereIn('ref_no', $refNumberArray)
                        ->where('ref_book', 'PR')
                        ->get();

                    foreach ($transRecords as $tr) {
                        //EMAIL NOTIFICATION
                        $to = $tr->userCreated->email;
                        $subject = Arrays::acronym($tr->ref_book).' No. '.$tr->ref_no;
                        $cc = $tr->rc->emailRecipients->pluck('email_address')->toArray();
                        $body = view('mailables.email_notifier.body-po-created')->with([
                            'prOrJr' => $tr,
                            'po' => $transNew,
                        ])->render();

                        //QUEUE EMAIL
                        EmailNotification::dispatch($to,$subject,$body,$cc);
                    }
                }
                else {
                    //EMAIL NOTIFICATION
                    $to = $transNew->transaction->userCreated->email;
                    $subject = Arrays::acronym($transNew->transaction->ref_book).' No. '.$transNew->transaction->ref_no;
                    $cc = $transNew->rc->emailRecipients->pluck('email_address')->toArray();
                    $body = view('mailables.email_notifier.body-po-created')->with([
                        'prOrJr' => $transNew->transaction,
                        'po' => $transNew,
                    ])->render();

                    //QUEUE EMAIL
                    EmailNotification::dispatch($to,$subject,$body,$cc);
                }
            }else{
                abort(503,'Error saving PO.');
            }

            return $order->only('slug');
        }
        abort(503,'Error creating Purchase Order');
    }

    public function storeManual(POFormRequest $request) {
        $refBook = "PO";
        $poNumber = $request->po_number;
        $orderExist = Order::query()->where('ref_no','=',$request->po_number)
            ->where('ref_book', '=', $refBook)->first();
        if($orderExist != null) {
            return abort(503,'PO Number already exist.');
        }
        $randomSlug = Str::random();
        $s = Suppliers::query()->where('slug','=', $request->supplier)->first();

        $order = new Order();
        $order->ref_no = $request->po_number;
        $order->slug = $randomSlug;
        $order->date = $request->date;
        $order->supplier = $s->slug;
        $order->supplier_name = $s->name;
        $order->supplier_address = $request->supplier_address;
        $order->supplier_tin = $request->supplier_tin;
        $order->supplier_representative = $request->supplier_representative;
        $order->place_of_delivery = $request->place_of_delivery;
        $order->delivery_term = $request->delivery_term;
        $order->payment_term = $request->payment_term;
        $order->delivery_date = $request->delivery_date??null;
        $order->mode = $request->mode;
        $order->authorized_official = $request->authorized_official;
        $order->authorized_official_designation = $request->authorized_official_designation;
        $order->funds_available = $request->funds_available;
        $order->funds_available_designation = $request->funds_available_designation;
        $order->ref_book = $refBook;
        $order->remarks = $request->remarks;
        $order->vat = $request->vatValue;
        $order->withholding_tax = $request->poValue;

        $order->total_gross = Helper::sanitizeAutonum($request->total_gross);
        $order->total =  Helper::sanitizeAutonum($request->total);
        $order->total_in_words = $request->total_in_words;
        $order->tax_base_1 = Helper::sanitizeAutonum($request->tax_base_1);
        $order->tax_base_2 = Helper::sanitizeAutonum($request->tax_base_2);

        $arr = [];

        $transNewSlug = Str::random();
        $transNew = new Transactions();
        $transNew->slug = $transNewSlug;
        $transNew->resp_center = $request->resp_center;
        $transNew->pap_code = $request->pap_code;
        $transNew->ref_book = $refBook;
        $transNew->ref_no = $poNumber;
        $transNew->cross_ref_no = $request->ref_number;
        $employee = Employee::query()->where('employee_no', '=', $request->requested_by)->first();
        $transNew->requested_by = $employee->firstname . ' ' . substr($employee->middlename, 0, 1) . '. ' . $employee->lastname;
        $transNew->requested_by_designation = $request->requested_by_designation;
        $transNew->approved_by = $request->approved_by;
        $transNew->approved_by_designation = $request->approved_by_designation;
        $transNew->order_slug = $randomSlug;
        $transNew->date = $request->date;
        $transNew->document_type = "PO Manual";
        if(!empty($request->items)){
            foreach ($request->items as $item) {
                array_push($arr,[
                    'slug' => Str::random(),
                    'transaction_slug' => $transNewSlug,
                    'stock_no' => $item['stockNo'],
                    'unit' => $item['unit'],
                    'item' => $item['itemName'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'total_cost' => $item['qty'] * Helper::sanitizeAutonum($item['unit_cost']),
                    'property_no' => $item['property_no'],
                    'nature_of_work' => $item['nature_of_work'],
                ]);
            }
        }

        if($order->save()){
            if($transNew->save()){
                TransactionDetails::insert($arr);
                /*//EMAIL NOTIFICATION
                    $to = $transNew->transaction->userCreated->email;
                    $subject = Arrays::acronym($transNew->transaction->ref_book).' No. '.$transNew->transaction->ref_no;
                    $cc = $transNew->rc->emailRecipients->pluck('email_address')->toArray();
                    $body = view('mailables.email_notifier.body-po-created')->with([
                        'prOrJr' => $transNew->transaction,
                        'po' => $transNew,
                    ])->render();

                    //QUEUE EMAIL
                    EmailNotification::dispatch($to,$subject,$body,$cc);*/
            }else{
                abort(503,'Error saving PO.');
            }

            return $order->only('slug');
        }
        abort(503,'Error creating Purchase Order');
    }

    public function findTransByRefNumber($refNumber, $refBook, $action, $id){
        if($refBook != 'publicBIdding'){
            if($action == "add"){
                /*$rfqtrans = Transactions::query()
                    ->where('cross_slug', '=', $trans->slug)
                    ->where('ref_book', '=', 'RFQ')
                    ->first();*/
                $rfqtrans = Transactions::query()
                    ->where('ref_no', '=', $refNumber)
                    ->where('ref_book', '=', 'RFQ')
                    ->first();
                if ($rfqtrans==null) {
                    abort(503, 'No RFQ Found for this Reference Number.');
                }
                $trans = Transactions::query()
                    ->where('slug', '=', $rfqtrans->cross_slug)
                    ->first();
                if ($trans==null || $trans->ref_book != 'PR') {
                    abort(503, 'No record found');
                }
                $aq = Transactions::query()
                    ->where('cross_slug', '=', $trans->slug)
                    ->where('ref_book', '=', 'AQ')
                    ->first();
                if ($aq==null) {
                    abort(503, 'No AQ Found for this Reference Number.');
                }
                $aqQuotation = AQQuotation::query()
                    ->where('aq_slug','=', $aq->slug)
                    ->where('supplier_slug','=', $id)
                    ->first();
                $aqQuotation = $aqQuotation??null;
                if ($aqQuotation==null) {
                    abort(503, 'Not a supplier for this Reference Number.');
                }
                $aqOfferDetails = AQOfferDetails::query()
                    ->where('quotation_slug','=', $aqQuotation->slug)
                    ->get();
                /*$rfqtrans = $rfqtrans??null;
                if ($rfqtrans==null) {
                    abort(503, 'No RFQ found.');
                }*/

                $trans = $trans??null;
                $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $rfqtrans->slug)->get();
                return response()->json([
                    'trans' => $trans,
                    'transDetails' => $transDetails,
                    'aqOfferDetails' => $aqOfferDetails
                ]);
            }
            else if($action == "edit"){
                $transDetails = TransactionDetails::query()->where('transaction_slug', '=', $id)->get();
                return response()->json([
                    'transDetails' => $transDetails
                ]);
            }
        }
        else {
            if (strpos($refNumber, ',') !== false) {
                $refNumberArray = preg_split('/\s*,\s*/', $refNumber, -1, PREG_SPLIT_NO_EMPTY);
            } else {
                $refNumberArray = [$refNumber];
            }
            $transRecords = Transactions::with('transDetails')
                ->whereIn('ref_no', $refNumberArray)
                ->where('ref_book', 'PR')
                ->get();

            $detailsArray = collect([]);

            foreach ($transRecords as $trans) {
                $detailsArray = $detailsArray->merge($trans->transDetails);
            }

            if ($transRecords==null) {
                abort(503, 'No record found');
            }

            return response()->json([
                'transDetails' => $detailsArray
            ]);
        }
    }

    public function print1($slug){
        $order = Order::query()->where('slug','=', $slug)->first();
        $trans = Transactions::query()->where('order_slug','=', $order->slug)->first();
        $nature_of_work_arr = [];
        $td = TransactionDetails::query()->where('transaction_slug', '=', $trans->slug)->get();
        $supplier = Suppliers::query()->where('slug','=', $order->supplier)->first();
        if($order->mode == 'Public Bidding'){
            $poDetails = PODetails::query()->where('order_slug', $order->slug)->get();
            $rc = PPURespCodes::query()->get();

            foreach ($td as $tran){
                $nature_of_work_arr[] = $tran->nature_of_work;
            }

            return view('printables.po.po')->with([
                'order' => $order,
                'trans' => $trans,
                'td' => $td,
                'nature_of_work_arr' => $nature_of_work_arr,
                'rc' => $rc,
                'supplier' => $supplier,
                'poDetails' => $poDetails
            ]);
        }
        else {
            $rc = PPURespCodes::query()->where('rc_code','=', $trans->resp_center)->first();

            foreach ($td as $tran){
                $nature_of_work_arr[] = $tran->nature_of_work;
            }
            return view('printables.po.po')->with([
                'order' => $order,
                'trans' => $trans,
                'td' => $td,
                'nature_of_work_arr' => $nature_of_work_arr,
                'rc' => $rc,
                'supplier' => $supplier
            ]);
        }
    }

    public function printManual($slug){
        $order = Order::query()->where('slug','=', $slug)->first();
        $trans = Transactions::query()->where('order_slug','=', $order->slug)->first();
        $nature_of_work_arr = [];
        $td = TransactionDetails::query()->where('transaction_slug', '=', $trans->slug)->get();
        $supplier = Suppliers::query()->where('slug','=', $order->supplier)->first();
        $rc = PPURespCodes::query()->where('rc_code','=', $trans->resp_center)->first();

        foreach ($td as $tran){
            $nature_of_work_arr[] = $tran->nature_of_work;
        }
        return view('printables.po.poManual')->with([
            'order' => $order,
            'trans' => $trans,
            'td' => $td,
            'nature_of_work_arr' => $nature_of_work_arr,
            'rc' => $rc,
            'supplier' => $supplier
        ]);
    }

    public function findBySlug($slug){
        $trans = Transactions::query()->where('order_slug','=',$slug)->first();
        return $trans ?? abort(503,'PO not found');
    }

    public function cancel(Request $request,$slug){
        $request->validate([
            'cancellation_reason' => 'required|string',
        ]);
        $trans = $this->findBySlug($slug);
        $trans->cancelled_at = Carbon::now();
        $trans->user_cancelled = \Auth::user()->user_id;
        $trans->cancellation_reason = $request->cancellation_reason;
        $trans->is_locked = 1;
        if($trans->save()){
            return $trans->only('slug');
        }
        abort(503,'Error in cancellation of transaction. POController::cancel()');
    }
}