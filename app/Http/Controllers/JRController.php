<?php


namespace App\Http\Controllers;


use App\Models\JR;
use App\Models\JRItems;
use App\Swep\Services\JRService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JRController extends Controller
{
    protected $jrService;
    public function __construct(JRService $jrService)
    {
        $this->jrService = $jrService;
    }

    public function index(){
        return view('ppu.jr.index');
    }

    public function store(Request $request){
        $jr = new JR();
        $jr->slug = Str::random();
        $jr->respCenter = $request->respCenter;
        $jr->papCode = $request->papCode;
        $jr->jrDate = $request->jrDate;
        $jr->jrNo = $this->jrService->getNextJRNo();
        $jr->purpose = $request->purpose;
        $jr->certifiedBy = $request->certifiedBy;
        $jr->certifiedByDesignation = $request->certifiedByDesignation;
        $jr->requestedBy = $request->requestedBy;
        $jr->requestedByDesignation = $request->requestedByDesignation;
        $jr->approvedBy = $request->approvedBy;
        $jr->approvedByDesignation = $request->approvedByDesignation;
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'jr_slug' => $jr->slug,
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'natureOfWork' => $item['natureOfWork'],
                    'propertyNo' => $item['propertyNo'],
                    'uom' => $item['uom'],
                    'qty' => $item['qty'],
                ]);
            }
        }
        if($jr->save()){
            if(count($arr ) > 0){
                JRItems::insert($arr);
            }
            return $jr->only('slug');
        }
    }

    public function print($slug){
        return view('printables.jr.jr_front')->with([
            'jr' => $this->jrService->findBySlug($slug),
        ]);
    }
}