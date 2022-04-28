<?php


namespace App\Http\Controllers;


use App\Http\Requests\PPMP\PPMPFormRequest;
use App\Http\Requests\PPMP\PPMPFormRequestEdit;
use App\Models\PAP;
use App\Models\PPMP;
use App\Swep\Helpers\Helper;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PPMPModalController extends Controller
{
    public function index(PapController $papController){

        if(request()->has('draw')){

            $pap = $papController->findBySlug(request('pap_code'));
            $ppmp = PPMP::query()->with('pap')->where('pap_code','=',$pap->pap_code);
            return DataTables::of($ppmp)
                ->addColumn('action',function ($data) use ($ppmp){
                    $destroy_route = "'".route("dashboard.ppmp_modal.destroy","slug")."'";
                    $edit_route = route("dashboard.ppmp_modal.edit",$data->slug);
                    $show_route = route("dashboard.ppmp_modal.show",$data->slug);
                    $edit_history = get_class($ppmp->getModel());
                    $slug = "'".$data->slug."'";
                    return '<div class="btn-group">
                                <button type="button" uri="'.$show_route.'" edit_history_model="'.$edit_history.'" edit_history_id="'.$data->id.'" data="'.$data->slug.'" class="btn btn-default btn-sm show_ppmp_btn" data-toggle="modal" data-target="#show_ppmp_modal" title="" data-placement="top" data-original-title="Edit">
                                    <i class="fa fa-list"></i>
                                </button>
                                <button type="button" uri="'.$edit_route.'" data="'.$data->slug.'" class="btn btn-default btn-sm edit_ppmp_btn" data-toggle="modal" data-target="#edit_ppmp_modal" title="" data-placement="top" data-original-title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" onclick="delete_data('.$slug.','.$destroy_route.')" data="'.$data->slug.'" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>';
                })
                ->editColumn('total_budget',function ($data){
                    $budget_type = strtolower($data->budget_type);
                    return '<div class="text-right"><span class="pull-left text-strong">'.$data->budget_type.':</span><span class="text-strong text-right">'.number_format($data->$budget_type,2).'</span></div>
                                <div class="table-subdetail text-right" style="color: #31708f">'.number_format($data->unit_cost,2).' x '.$data->qty.' '.$data->uom.'</div>';
                })
                ->editColumn('mode_of_proc',function ($data){
                    return strtoupper(Helper::camelCaseToWords($data->mode_of_proc));
                })
                ->addColumn('details',function ($data){
                    return
                        '<table style="width: 100%;" class="table-borderless">
                            <tbody>
                                <tr>
                                  <td style="width: 130px">Mode of Proc.:</td>
                                  <td class="text-strong">'.ucfirst(Helper::camelCaseToWords($data->mode_of_proc)).'</td>
                                </tr>
                                <tr>
                                  <td>Fund Source:</td>
                                  <td class="text-strong">'.$data->source_of_fund.'</td>
                                </tr>
                                <tr>
                                  <td>Budget Type:</td>
                                  <td class="text-strong">'.$data->budget_type.'</td>
                                </tr>
                             </tbody>
                         </table>';
                })
                ->addColumn('milestone',function ($data){
                    $table = '<table class="milestone" style="width: 100%; font-size: 11px">
                        <tbody>
                        <tr class="text-center">';

                    foreach (Helper::milestones() as $milestone){
                        $table = $table.'<td>'.$milestone.'</td>';
                    }
                    $table = $table.'</tr><tr style="height: 15px">';
                    foreach (Helper::milestones() as $milestone){
                        $var = 'qty_'.strtolower($milestone);
                        $disp = $data->$var;
                        $table = $table.'<td class="text-center">'.$disp.'</td>';
                    }
                    $table = $table.'</tr>
                    </tbody>
                    </table>';
                    return $table;
                })
                ->escapeColumns([])
                ->setRowId('slug')
                ->make(true);
        }

        if(request()->has('typeahead')){
            $query = request('query');
            return $this->typeahead($query);
        }
        $pap = $papController->findBySlug(request('pap'));
        return view('ppu.ppmp_modal.index')->with([
            'pap' => $pap,
        ]);
    }

    private function typeahead($query){

        $ppmps = PPMP::query()
            ->select(['slug','gen_desc'])
            ->where('gen_desc','like','%'.$query.'%')
            ->groupBy('gen_desc')
            ->limit(10);

        $ppmps = $ppmps->get();

        $list = [];
        if(!empty($ppmps)){
            foreach ($ppmps as $ppmp){
                $to_push = [
                    'id'=> $ppmp->slug ,
                    'name' => $ppmp->gen_desc,
                ];
                array_push($list,$to_push);
            }
        }
        return $list;
    }

    private  function findBySlug($slug){
        $ppmp = PPMP::query()->with('pap')->where('slug','=',$slug)->first();
        if(!empty($ppmp)){
            return $ppmp;
        }
        abort(503,'PPMP not found.');
    }

    public function show($slug){
        $ppmp = $this->findBySlug($slug);
        return view('ppu.ppmp_modal.show')->with([
            'ppmp' => $ppmp,
        ]);
    }

    public function store(PPMPFormRequest $request){
        if(!empty($request->gen_desc)){
            $return_slugs['slug'] = [];
            foreach ($request->gen_desc as $key=> $gen_desc){
                $ppmp_check = PPMP::query()->orderBy('ppmp_code','desc')->first();
                if(!empty($ppmp_check)){
                    $ppmp_code = $ppmp_check->ppmp_code + 1;
                }else{
                    $ppmp_code = 1;
                }
                $budget_type = strtolower($request->budget_type);

                $ppmp = new PPMP;
                $ppmp->slug = Str::random();
                $ppmp->ppmp_code = str_pad($ppmp_code,5,'0',STR_PAD_LEFT);
                $ppmp->pap_code = $request->pap_code;
                $ppmp->source_of_fund = $request->source_of_fund[$key];
                $ppmp->gen_desc = $request->gen_desc[$key];
                $ppmp->unit_cost = Helper::sanitizeAutonum($request->unit_cost[$key]);
                $ppmp->qty = $request->qty[$key];
                $ppmp->uom = $request->uom[$key];
                $ppmp->budget_type = $request->budget_type;
                $ppmp->$budget_type = Helper::sanitizeAutonum($request->unit_cost[$key])*$request->qty[$key];
                $ppmp->mode_of_proc = $request->mode_of_proc[$key];
                $ppmp->qty_jan = $request->qty_jan[$key];
                $ppmp->qty_feb = $request->qty_feb[$key];
                $ppmp->qty_mar = $request->qty_mar[$key];
                $ppmp->qty_apr = $request->qty_apr[$key];
                $ppmp->qty_may = $request->qty_may[$key];
                $ppmp->qty_jun = $request->qty_jun[$key];
                $ppmp->qty_jul = $request->qty_jul[$key];
                $ppmp->qty_aug = $request->qty_aug[$key];
                $ppmp->qty_sep = $request->qty_sep[$key];
                $ppmp->qty_oct = $request->qty_oct[$key];
                $ppmp->qty_nov = $request->qty_nov[$key];
                $ppmp->qty_dec = $request->qty_dec[$key];

                if($ppmp->save()){
                    array_push($return_slugs['slug'],$ppmp->slug);

                }
            }
            return $return_slugs;
        }
        abort(503,'Empty request not valid.');
    }

    public function edit($slug){
        $ppmp = $this->findBySlug($slug);
        return view('ppu.ppmp_modal.edit')->with([
            'ppmp' => $ppmp,
        ]);
    }

    public function update(PPMPFormRequestEdit $request, $slug){
        $budget_type = strtolower($request->budget_type);
        $ppmp = $this->findBySlug($slug);
        $ppmp->ps = null;
        $ppmp->co = null;
        $ppmp->mooe = null;
        $ppmp->source_of_fund = $request->source_of_fund;
        $ppmp->gen_desc = $request->gen_desc;
        $ppmp->unit_cost = Helper::sanitizeAutonum($request->unit_cost);
        $ppmp->qty = $request->qty;
        $ppmp->uom = $request->uom;
        $ppmp->budget_type = $request->budget_type;
        $ppmp->$budget_type = Helper::sanitizeAutonum($request->unit_cost)*$request->qty;
        $ppmp->mode_of_proc = $request->mode_of_proc;
        $ppmp->qty_jan = $request->qty_jan;
        $ppmp->qty_feb = $request->qty_feb;
        $ppmp->qty_mar = $request->qty_mar;
        $ppmp->qty_apr = $request->qty_apr;
        $ppmp->qty_may = $request->qty_may;
        $ppmp->qty_jun = $request->qty_jun;
        $ppmp->qty_jul = $request->qty_jul;
        $ppmp->qty_aug = $request->qty_aug;
        $ppmp->qty_sep = $request->qty_sep;
        $ppmp->qty_oct = $request->qty_oct;
        $ppmp->qty_nov = $request->qty_nov;
        $ppmp->qty_dec = $request->qty_dec;

        if($ppmp->update()){
            return $ppmp->only('slug');
        }
        abort(503,'Error updating data.');
    }

    public function destroy($slug){
        $ppmp = $this->findBySlug($slug);
        if($ppmp->delete()){
            return 1;
        }
        abort(503,'Error deleting data.');
    }
}