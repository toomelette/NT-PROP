<?php


namespace App\Swep\Services;


use App\Models\PPMP;
use App\Swep\BaseClasses\BaseService;
use Yajra\DataTables\DataTables;

class PPMPService extends BaseService
{
    public function getNextPPMPCode(){
        $ppmp = PPMP::query()->whereNotNull('ppmpCode')->orderBy('ppmpCode','desc')->first();

        if(empty($ppmp)){
            return '00001';
        }else{
            return str_pad(floatval($ppmp->ppmpCode) + 1,5,'0',STR_PAD_LEFT);
        }
    }

    public function dataTable($request,$subAccount = false, $ppmp_slug = null){
        if($subAccount == false){
            $ppmps = PPMP::query()->with(['article','pap','pap.responsibilityCenter','pap.responsibilityCenter.description'])
            ->where('parentPpmp','=',null);
        }else{
            $ppmps = PPMP::query()
                ->with(['article','pap','pap.responsibilityCenter','pap.responsibilityCenter.description'])
                ->where('parentPpmp','=',$ppmp_slug);
        }
        if($request->has('dept') && $request->dept != ''){
            $ppmps = $ppmps->whereHas('pap.responsibilityCenter',function ($query) use($request){
                return $query->where('department','=',$request->dept);
            });
        }
        if($request->has('div') && $request->div != ''){
            $ppmps = $ppmps->whereHas('pap.responsibilityCenter',function ($query) use($request){
                return $query->where('division','=',$request->div);
            });
        }

        if($request->has('sec') && $request->sec != ''){
            $ppmps = $ppmps->whereHas('pap.responsibilityCenter',function ($query) use($request){
                return $query->where('section','=',$request->sec);
            });
        }
        if($request->has('budgetType') && $request->budgetType != ''){
            $ppmps = $ppmps->where('budgetType','=',$request->budgetType);
        }

        if($request->has('modeOfProc') && $request->modeOfProc != ''){
            $ppmps = $ppmps->where('modeOfProc','=',$request->modeOfProc);
        }


        return DataTables::of($ppmps)
            ->addColumn('article',function($data){
                return $data->article->article ?? '<small>-</small>';
            })
            ->addColumn('cost',function($data){
                return view('ppu.ppmp.costColumn')->with([
                    'data' => $data,
                ]);
            })
            ->addColumn('dept',function($data){
                return $data->pap->responsibilityCenter->description->name ?? '';
            })
            ->addColumn('div',function($data){

                $section = $data->pap->responsibilityCenter->section ?? null;
                $division = $data->pap->responsibilityCenter->division ?? null;
                return $division .' '.$section;
            })
            ->addColumn('action',function($data){
                return view('ppu.ppmp.dtActions')->with([
                    'data' => $data,
                ]);
            })
            ->editColumn('papCode',function($data){
                $title = $data->pap->pap_title ?? null;
                return '<a title="'.$title.'" href="#" target="_blank">'.$data->papCode.'</a>' ;
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
    }
    public function findBySlug($slug){
        $ppmp = PPMP::query()->where('slug','=',$slug)->first();
        if(empty($ppmp)){
            abort(503,'PPMP not found. [PPMPService::findBySlug]');
        }
        return $ppmp;
    }

}