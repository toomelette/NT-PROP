<?php


namespace App\Http\Controllers;

use App\Http\Requests\AwardNoticeAbstract\ANAFormRequest;
use App\Models\AwardNoticeAbstract;
use App\Models\NoticeOfAward;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class NOAController extends Controller
{
    public function create(){
        //$suppliers = Suppliers::orderBy('name')->pluck('name','slug');
        //return view('ppu.ics.create', compact('suppliers'));
        return view('ppu.noa.create');
    }

    public function getNextNOANo(){
        $year = Carbon::now()->format('Y');
        $ana = NoticeOfAward::query()
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

    public function store(FormRequest $request){
        $n = new NoticeOfAward();
        $n->slug = Str::random(16);
        $n->notice_number = $this->getNextNOANo();
        $n->document_no = $request->document_no;
        $n->date = $request->date;
        $n->supplier = $request->supplier;
        $n->supplier_address = $request->supplier_address;
        $n->supplier_representative = $request->supplier_representative;
        $n->supplier_representative_position = $request->supplier_representative_position;
        $n->project_name = $request->project_name;
        $n->content = $request->contents;
        if($n->save()){
            return $n->only('slug');
        }
        abort(503,'Error saving notice.');
    }

    public function print($slug){
        return view('printables.notice_of_award.print')->with([
            'noa' => NoticeOfAward::query()->where('slug', $slug)->first(),
        ]);
    }
}