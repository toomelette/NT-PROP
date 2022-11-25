<?php


namespace App\Http\Controllers;


use App\Http\Requests\PPU\PAPFormRequest;
use App\Models\PAP;
use App\Models\PapParent;
use App\Swep\Helpers\Helper;
use App\Swep\Services\PAPService;
use App\Swep\ViewHelpers\__html;
use http\Env\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PapController extends Controller
{

    protected $papService;
    public function __construct(PAPService $papService)
    {
        $this->papService = $papService;
    }

    public function index(){
        if(request()->ajax() && request()->has('draw')){
            $paps = PAP::query()->with(['prs','prs.items']);
            return DataTables::of($paps)
                ->addColumn('action',function ($data){
                    $destroy_route = "'".route("dashboard.pap.destroy","slug")."'";
                    $slug = "'".$data->slug."'";
                    return '<div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm list_submenus_btn" menu_id="'.$data->menu_id.'" data="'.$data->slug.'" data-toggle="modal" data-target="#list_submenus" title="" data-placement="left" data-original-title="Submenus">
                                    <i class="fa fa-list"></i>
                                </button>
                                <button type="button" data="'.$data->slug.'" class="btn btn-default btn-sm edit_menu_btn" data-toggle="modal" data-target="#edit_menu_modal" title="" data-placement="top" data-original-title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" onclick="delete_data('.$slug.','.$destroy_route.')" data="'.$data->slug.'" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                               <a href="'.route('dashboard.ppmp_modal.index').'?pap='.$data->slug.'" ><button class="btn btn-default btn-sm" data="'.$data->slug.'" style="margin-top: 5px; width: 97px"> <i class="fa icon-procurement"></i> PPMP</button></a>
                            ';
                })
                ->addColumn('procurement',function($data){
                    return number_format($data->prItems()->sum('totalCost'),2);
                })
                ->addColumn('details',function ($data){
                    return 'd';
                })
                ->editColumn('pap_title',function ($data){
                    if($data->pap_desc != ''){
                        return $data->pap_title. '<div class="table-subdetail">'.$data->pap_desc.'</div>';
                    }
                    return $data->pap_title;
                })
                ->escapeColumns([])
                ->setRowId('slug')
                ->make(true);
        }


        return view('ppu.pap.index');
    }

    public function store(PAPFormRequest $request){

        $pap = new PAP;
        $pap->slug = Str::random();
        $pap->year = $request->year;
        $pap->resp_center = $request->resp_center;
        $pap->base_pap_code = 1;
        $pap->pap_code = $this->papService->newPapCode($request->year,$request->resp_center);
        $pap->pap_title = $request->pap_title;
        $pap->pap_desc = $request->pap_desc;
        $pap->ps = $request->ps;
        $pap->co = $request->co;
        $pap->mooe = $request->mooe;
        $pap->pcent_share = $request->pcent_share;
        if($pap->save()){
            return $pap->only('slug');
        }

        abort('500','Error saving data');
    }

    private function padPap($int){
        return str_pad($int,3,'0',STR_PAD_LEFT);
    }

    public function findBySlug($slug){
        $pap = PAP::query()->where('slug','=',$slug)->first();
        if(!empty($pap)){
            return $pap;
        }
        abort(503,'PAP not found.');
    }
}