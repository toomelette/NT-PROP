<?php


namespace App\Http\Controllers;


use App\Http\Requests\AwardNoticeAbstract\ANAFormRequest;
use App\Models\AwardNoticeAbstract;
use App\Models\CancellationRequest;
use App\Models\Suppliers;
use App\Models\Transactions;
use App\Swep\Helpers\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class AwardNoticeAbstractController extends Controller
{
    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.award_notice_abstract.index');
    }

    public function dataTable($request){
        $ana = AwardNoticeAbstract::query();
        return DataTables::of($ana)
            ->addColumn('action',function($data){
                return view('ppu.award_notice_abstract.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->editColumn('approved_budget',function($data){
                return number_format($data->approved_budget,2);
            })
            ->editColumn('contract_amount',function($data){
                return number_format($data->contract_amount,2);
            })
            ->editColumn('award_date',function($data){
                return $data->award_date ? Carbon::parse($data->award_date)->format('M. d, Y') : '';
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

    public function create(){
        $suppliers = Suppliers::pluck('name','slug');
        return view('ppu.award_notice_abstract.create', compact('suppliers'));
    }

    public function getNextANANo(){
        $year = Carbon::now()->format('Y');
        $ana = AwardNoticeAbstract::query()
            ->where('award_notice_number','like',$year.'%')
            ->orderBy('award_notice_number','desc')
            ->first();
        if(empty($ana)){
            $newAna = $year.'-0001';
        }else{
            $newAna = $year.'-'.str_pad(substr($ana->award_notice_number,5) + 1, 4,0,STR_PAD_LEFT);
        }
        return $newAna;
    }

    public function store(ANAFormRequest $request){
        $trans = Transactions::query()
                ->where('ref_book', '=', $request->ref_book)
                ->where('ref_no', '=', $request->ref_number)
                ->first();
        if($trans == null){
            abort(503,'Invalid Ref Number');
        }
        $supplier = Suppliers::query()->where('slug', $request->awardee)->first();
        $s = new AwardNoticeAbstract();
        $s->slug = Str::random(16);
        $s->award_notice_number = $this->getNextANANo();
        $s->title_of_notice = "Notice of Award";
        $s->award_date = $request->date;
        $s->registry_number = $request->registry_number;
        $s->ref_book = $request->ref_book;
        $s->ref_number = $request->ref_number;
        $s->title = $request->title;
        $s->category = $request->category;
        $s->approved_budget = Helper::sanitizeAutonum($request->abc);
        $s->contract_amount = Helper::sanitizeAutonum($request->contract_amount);
        $s->remarks = $request->remarks;
        $s->reason_for_award = $request->reason;
        $s->awardee = $supplier->name;
        $s->awardee_address = $supplier->address;
        $s->contact_person = $request->contact_person;
        $s->contact_person_address = $request->contact_person_address;
        $s->phone_number_1 = $request->phone_number_1;
        $s->phone_number_2 = $request->phone_number_2;
        $s->fax_number = $request->fax_number;
        $s->corporate_title = $request->corporate_title;
        $s->awardee_tin = $supplier->tin;

        $s->organization_name = $request->organization_name;
        $s->contact_name = $request->contact_name;
        $s->signatory = $request->signatory_name;
        $s->designation = $request->signatory_title;
        if($s->save()){
            $slug = $s->slug;
            return [
                'route' => route('dashboard.awardNoticeAbstract.print', $slug),
            ];
        }
        abort(503,'Error saving notice.');
    }

    public function edit($slug){
        $suppliers = Suppliers::pluck('name','slug');
        $ana = AwardNoticeAbstract::query()
            ->where('slug','=', $slug)->first();
        $ana->awardee = Suppliers::query()->where('name','=', $ana->awardee)->first()->slug;
        return view('ppu.award_notice_abstract.edit')->with([
            'ana' => $ana,
            'suppliers' => $suppliers,
        ]);
    }

    public function update(Request $request, $slug){
        $supplier = Suppliers::query()->where('slug','=', $request->awardee)->first();
        $ana = AwardNoticeAbstract::query()->where('slug', '=', $slug)->first();
        $ana->award_date = $request->award_date;
        $ana->registry_number = $request->registry_number;
        $ana->title = $request->title;
        $ana->category = $request->category;
        $ana->approved_budget = Helper::sanitizeAutonum($request->approved_budget);
        $ana->contract_amount = Helper::sanitizeAutonum($request->contract_amount);
        $ana->remarks = $request->remarks;
        $ana->reason_for_award = $request->reason_for_award;
        $ana->awardee = $supplier->name;
        $ana->awardee_address = $supplier->address;
        $ana->contact_person = $request->contact_person;
        $ana->contact_person_address = $request->contact_person_address;
        $ana->phone_number_1 = $request->phone_number_1;
        $ana->phone_number_2 = $request->phone_number_2;
        $ana->fax_number = $request->fax_number;
        $ana->corporate_title = $request->corporate_title;
        $ana->awardee_tin = $supplier->tin;

        $ana->organization_name = $request->organization_name;
        $ana->contact_name = $request->contact_name;
        $ana->signatory = $request->signatory;
        $ana->designation = $request->designation;
        if($ana->update()){
            return $ana->only('id');
        }
        abort(503,'Error updating ANA.');
    }

    public function print($slug){
        return view('printables.award_notice_abstract.print')->with([
            'ana' => AwardNoticeAbstract::query()->where('slug', $slug)->first(),
        ]);
    }

    public function findTransactionByRefNumber($refNumber, $refBook){
        $trans = Transactions::query()
            ->where('ref_book', '=', $refBook)
            ->where('ref_no', '=', $refNumber)
            ->first();
        $trans = $trans??null;
        return $trans?? abort(503,'No record found');
    }

    public function findSupplier($slug){
        $s = Suppliers::query()->where('slug','=', $slug)->first();
        $s = $s??null;
        return $s?? abort(503,'No record found');
    }
}