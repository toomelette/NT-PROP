<?php


namespace App\Http\Controllers;


use App\Http\Requests\PPU\PAPFormRequest;
use App\Models\PAP;
use App\Models\PapParent;
use App\Models\PPURespCodes;
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

    public function index(\Illuminate\Http\Request $request){
        if(request()->ajax() && request()->has('draw')){
            return $this->dataTable($request);
        }


        return view('ppu.pap.index');
    }

    public function dataTable($request){
        $paps = PAP::query()->with(['prs','prs.items','responsibilityCenter']);
        $RespCenter = PPURespCodes::all();
        $search = $request->get('search')['value'] ?? null;

        $dt =  \DataTables::of($paps);

        $dt = $dt->filter(function ($query) use($search){
            if($search != null){
                $query->where('pap_code', 'like', '%'.$search.'%')
                    ->orwhere('pap_title', 'like', '%'.$search.'%')
                    ->orWhereHas('responsibilityCenter',function ($q) use($search){
                        return $q->where('desc','like','%'.$search.'%');
                    });
            }
        });

        $dt = $dt->addColumn('action',function ($data){
                return view('ppu.pap.dtActions')->with([
                    'data' => $data,
                ]);
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
            ->editColumn('resp_center', function ($data) use ($RespCenter) {
                return $RespCenter->firstWhere('rc_code', $data->resp_center)->desc;
            })
            ->editColumn('co',function($data){
                return number_format($data->co);
            })
            ->editColumn('mooe',function($data){
                return number_format($data->mooe);
            })
            ->addColumn('totalBudget',function($data){
                return number_format($data->ps + $data->co + $data->mooe,2);
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->make(true);
        return $dt;
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
        $pap->ps = Helper::sanitizeAutonum($request->ps);
        $pap->co = Helper::sanitizeAutonum($request->co);
        $pap->mooe = Helper::sanitizeAutonum($request->mooe);
        $pap->pcent_share = $request->pcent_share;
        $pap->type = $request->type ?? 'final';
        $pap->status = $request->status ?? 'active';
        $pap->budget_type = $request->budget_type;
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

    public function edit($slug){
        return view('ppu.pap.edit')->with([
            'pap' => $this->papService->findBySlug($slug),
        ]);
    }

    public function update(PAPFormRequest $request, $slug){
        $pap = $this->papService->findBySlug($slug);
        $pap->year = $request->year;
        $pap->resp_center = $request->resp_center;
        $pap->base_pap_code = 1;
        $pap->pap_code = $this->papService->newPapCode($request->year,$request->resp_center);
        $pap->pap_title = $request->pap_title;
        $pap->pap_desc = $request->pap_desc;
        $pap->ps = Helper::sanitizeAutonum($request->ps);
        $pap->co = Helper::sanitizeAutonum($request->co);
        $pap->mooe = Helper::sanitizeAutonum($request->mooe);
        $pap->pcent_share = $request->pcent_share;
        $pap->type = $request->type ?? 'final';
        $pap->status = $request->status ?? 'active';
        $pap->budget_type = $request->budget_type;
        if($pap->save()){
            return $pap->only('slug');
        }

        abort('500','Error saving data');
    }

    public function show($slug){
        return view('ppu.pap.show')->with([
            'pap' => $this->papService->findBySlug($slug)
        ]);
    }

    public function destroy($slug){
        $pap = $this->papService->findBySlug($slug);
        if($pap->delete()){
            return 1;
        }
        abort(503,'Error deleting PAP');
    }
}