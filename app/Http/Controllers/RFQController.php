<?php


namespace App\Http\Controllers;


use App\Models\RFQ;
use App\Swep\Services\RFQService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RFQController extends Controller
{
    protected $rfqService;
    public function __construct(RFQService $rfqService)
    {
        $this->rfqService = $rfqService;
    }

    public function index(){
        return view('ppu.rfq.index');
    }

    public function store(Request $request){
        $rfq = new RFQ();
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
        $rfq = $this->rfqService->findBySlug($slug);
        return view('printables.rfq.rfq')->with([
            'rfq' => $rfq,
        ]);
    }
}