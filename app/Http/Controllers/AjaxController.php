<?php


namespace App\Http\Controllers;


use App\Models\Articles;
use App\Models\JR;
use App\Models\PAP;
use App\Models\PR;
use App\Models\Transactions;
use App\Models\User;
use App\Models\UserDetails;
use App\Swep\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Response;

class AjaxController extends Controller
{
    public function get($for){
        if($for == 'educational_background'){
            return view('ajax.employee.add_school');
        }

        if($for == 'eligibility'){
            return view('ajax.employee.add_eligibility');
        }

        if($for == 'work_experience'){
            $rand = Str::random(16);
            return [
                'view' => view('ajax.employee.add_work_experience')->with([
                                'rand' => $rand,
                            ])->render(),
                'rand' => $rand,
            ];
        }

        if($for == 'add_ppmp_row'){
            return view('ajax.ppmp.add_row');
        }

        if($for == 'add_row'){
            return view('dynamic_rows.'.request('view'));
        }


        if($for == 'pap_codes'){
            $arr = [];
            $like = '%'.request('q').'%';
            $limit = 2;
            $papCodes = PAP::query()
                ->select('year' ,'pap_code','pap_title','pap_desc')
                ->where(function ($query) use ($like){
                    $query->where('pap_code','like',$like)
                        ->orWhere('pap_desc','like',$like)
                        ->orWhere('pap_title','like',$like);
                });
            if(!empty(Auth::user()->userDetails)){
                $papCodes = $papCodes->whereHas('responsibilityCenter',function ($query){
                    $query->where('rc','=',Auth::user()->userDetails->rc);
                });
            }

            $papCodes = $papCodes->limit(10)->get();

            if(!empty($papCodes)){
                foreach ($papCodes as $papCode){
                    array_push($arr,[
                        'id' => $papCode->pap_code,
                        'text' => $papCode->pap_code.' | '.$papCode->pap_title,
                        'year' => $papCode->year,
                        'pap_code' => $papCode->pap_code,
                        'pap_title' => $papCode->pap_title,
                    ]);
                }
            }

//            array_push($arr,[
//                'id' => 'UNPROGRAMMED',
//                'text' => 'UNPROGRAMMED',
//                'pap_code' => '',
//                'pap_title' => '',
//            ]);
            return Helper::wrapForSelect2($arr);
        }

        if($for == 'articles'){
            $arr = [];
            $like = '%'.request('q').'%';
            $articles = Articles::query()
                ->select('stockNo' ,'article','uom','unitPrice','modeOfProc')
                ->where('article','like',$like)
                ->orderBy('article','asc')
                ->limit(10)
                ->get();

            if(!empty($articles)){
                foreach ($articles as $article){
                    array_push($arr,[
                        'id' => $article->stockNo,
                        'text' => $article->article,
                        'populate' => [
                            'uom' => $article->uom,
                            'unitPrice' => $article->unitPrice,
                            'modeOfProc' => $article->modeOfProc,
                            'unitCost' => $article->unitPrice,
                        ]
                    ]);
                }
            }
            return Helper::wrapForSelect2($arr);
        }


    }


    public function post($for){

        switch ($for){
            case 'assign_rc':
                $request = request()->all();
                request()->validate([
                    'resp_center' => ' required|string',
                ]);
                Auth::user()->userDetails()->delete();

                $ud = new UserDetails();
                $ud->user_id = Auth::user()->user_id;
                $ud->rc = request()->get('resp_center');
                $ud->save();
                return 1;
                break;

            case 'rfq_prNo':
                $request = request();
                switch ($request->get('prJr')){
                    case 'pr':
                        $prNo = $request->get('prNo');
                        $validator = \Validator::make($request->all(),[
                            'prNo' => [
                                'required','string','max:12',
                                Rule::exists('transactions','ref_no')->where('ref_book','PR'),
                            ]
                        ],[
                            'prNo.exists' => 'Purchase Request does not exist.',
                        ]);
                        break;

                    case 'jr':
                        $jrNo = $request->get('jrNo');
                        $validator = \Validator::make($request->all(),[
                            'jrNo' => [
                                'required','string','max:12',
                                Rule::exists('transactions','ref_no')->where('ref_book','JR'),
                            ]
                        ],[
                            'jrNo.exists' => 'Job Request does not exist.',
                        ]);
                        break;
                    default:
                        break;
                }
                if($validator->fails()){
                    return Response::json(array(
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray()
                    ), 422);
                }else{
                    $prJr = $request->get('prJr');
                    $transNo = $request->get('prNo');
                    $trans = Transactions::query()->where('ref_no','=',$transNo)
                        ->where('ref_book','=',strtoupper($prJr))
                        ->first();
                    if(!empty($trans)){
                        return [
                            'transaction_slug' => $trans->slug,
                        ];
                    }
                    abort(503,'Transaction not found');
                }
                return 1;
                break;
            default:
                abort(503,'For assignment not found.');
                break;
        }
    }
}