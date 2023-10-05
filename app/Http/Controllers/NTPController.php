<?php


namespace App\Http\Controllers;

use App\Http\Requests\AwardNoticeAbstract\ANAFormRequest;
use App\Models\AwardNoticeAbstract;
use App\Models\NoticeOfAward;
use App\Models\NoticeToProceed;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class NTPController extends Controller
{
    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.ntp.index');
    }

    public function dataTable($request){
        $ana = NoticeToProceed::query();
        return DataTables::of($ana)
            ->addColumn('action',function($data){
                return view('ppu.ntp.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->toJson();
    }

    public function create(){
        //$suppliers = Suppliers::orderBy('name')->pluck('name','slug');
        //return view('ppu.ics.create', compact('suppliers'));
        return view('ppu.ntp.create');
    }

    public function edit($slug){
        $ntp = NoticeToProceed::query()
            ->where('slug','=', $slug)->first();
        return view('ppu.ntp.edit')->with([
            'ntp' => $ntp
        ]);
    }

    public function store(FormRequest $request){
        $n = new NoticeToProceed();
        $n->slug = Str::random(16);
        $n->notice_number = $this->getNextNTPNo();
        $n->ref_no = $request->ref_no;
        $n->document_no = $request->document_no;
        $n->date = $request->date;
        $n->supplier = $request->supplier;
        $n->supplier_address = $request->supplier_address;
        $n->supplier_representative = $request->supplier_representative;
        $n->supplier_representative_position = $request->supplier_representative_position;
        $n->contents = $request->contents;
        $n->approved_by = $request->approved_by;
        $n->approved_by_designation = $request->approved_by_designation;
        if($n->save()){
            return $n->only('slug');
        }
        abort(503,'Error saving notice of award.');
    }


    public function update(Request $request, $slug){
        $n = NoticeToProceed::query()->where('slug', '=', $slug)->first();
        $n->ref_no = $request->ref_no;
        $n->document_no = $request->document_no;
        $n->date = $request->date;
        $n->supplier = $request->supplier;
        $n->supplier_address = $request->supplier_address;
        $n->supplier_representative = $request->supplier_representative;
        $n->supplier_representative_position = $request->supplier_representative_position;
        $n->contents = $request->contents;
        $n->approved_by = $request->approved_by;
        $n->approved_by_designation = $request->approved_by_designation;
        if($n->update()){
            return $n->only('slug');
        }
        abort(503,'Error updating NTP.');
    }

    public function getNextNTPNo(){
        $year = Carbon::now()->format('Y');
        $ana = NoticeToProceed::query()
            ->where('notice_number','like',$year.'%')
            ->orderBy('notice_number','desc')
            ->first();
        if(empty($ana)){
            $newAna = $year.'-0001';
        }else{
            $newAna = $year.'-'.str_pad(substr($ana->notice_number,5) + 1, 4,0,STR_PAD_LEFT);
        }
        return $newAna;
    }

    public function print($slug){
        return view('printables.notice_to_proceed.print')->with([
            'noa' => NoticeToProceed::query()->where('slug', $slug)->first(),
        ]);
    }
}