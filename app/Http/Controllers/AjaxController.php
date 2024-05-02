<?php


namespace App\Http\Controllers;


use App\Models\Articles;
use App\Models\InventoryPPE;
use App\Models\PAP;
use App\Models\PR;
use App\Models\Suppliers;
use App\Models\Transactions;
use App\Models\User;
use App\Models\UserDetails;
use App\Swep\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Response;

class AjaxController extends Controller
{
    public function get($for){
        if($for == 'add_ppmp_row'){
            return view('ajax.ppmp.add_row');
        }

        if($for == 'add_row'){
            return view('dynamic_rows.'.request('view'));
        }


        if($for == 'pap_codes'){
            $currentYear = \App\Swep\Helpers\Helper::getSetting('dashboard_report_year')->int_value;
            $arr = [];
            $like = '%'.request('q').'%';
            $papCodes = PAP::query()
                ->select('year' ,'pap_code','pap_title','pap_desc')
                ->where('year', '=', $currentYear)
                ->where(function ($query) use ($like){
                    $query->orWhere('pap_code','like',$like)
                        ->orWhere('pap_desc','like',$like)
                        ->orWhere('pap_title','like',$like);
                });

            if(Request::get('respCode') == ''){
                return  [];
            }else{
                $papCodes = $papCodes->where('resp_center','=',Request::get('respCode'));
            }

            if(!empty(Auth::user()->userDetails)){
                $papCodes = $papCodes->whereHas('responsibilityCenter',function ($query){
                    $query->where(function ($q){
                        foreach (Auth::user()->availablePaps as $availablePap){
                            $q->orWhere('rc','=',$availablePap->rc);
                        }
                    });
                });
            }


            $papCodes = $papCodes->limit(10)
                ->offset(10*(request('page') - 1))
                ->orderBy('pap_code','asc')
                ->get();

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
            return Helper::wrapForSelect2($arr,10);
        }

        if($for == 'articles'){
            $request = Request::capture();
            $arr = [];
            $like = '%'.request('q').'%';
            $articles = Articles::query()
                ->select('stockNo' ,'article','uom','unitPrice','modeOfProc')
                ->where('article','like',$like);

            if($request->has('page')){
                $articles = $articles->offset(10*(request('page') - 1));
            }

            $articles = $articles->orderBy('article','asc')
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
                            'itemName' => $article->article,
                        ]
                    ]);
                }
            }
            return Helper::wrapForSelect2($arr);
        }

        if($for == 'par_articles'){
            $arr = [];
            $like = '%'.request('q').'%';
            $parArticles = InventoryPPE::query()
                ->select('slug','article','uom','propertyno','acquiredcost','serial_no')
                ->where('article','like',$like);

            $parArticles = $parArticles->orderBy('article','asc')
                ->get();
            if(!empty($parArticles)){
                foreach ($parArticles as $parArticle){
                    array_push($arr,[
                        'id' => $parArticle->slug,
                        'text' => $parArticle->article.' - '.$parArticle->propertyno,
                        'populate' => [
                            'itemName' => $parArticle->article,
                            'uom' => $parArticle->uom,
                            'propertyno' => $parArticle->propertyno,
                            'acquiredcost' => $parArticle->acquiredcost,
                            'serial_no' => $parArticle->serial_no,
                        ]
                    ]);
                }
            }
            return Helper::wrapForSelect2($arr);
        }

        if($for == 'suppliers'){
            $arr = [];
            $like = '%'.request('q').'%';
            $suppliers = Suppliers::query()
                ->select('slug' ,'name','address')
                ->where('name','like',$like)
                ->orderBy('name','asc')
                ->limit(10)->offset(10*(request('page') - 1))
                ->get();

            if(!empty($suppliers)){
                foreach ($suppliers as $supplier){
                    array_push($arr,[
                        'id' => $supplier->slug,
                        'text' => $supplier->name,
                        'populate' => [
                            'address' => $supplier->address,
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