<?php


namespace App\Http\Controllers;


use App\Http\Requests\PR\PRFormRequest;
use App\Jobs\EmailNotification;
use App\Models\Articles;
use App\Models\Employee;
use App\Models\PR;
use App\Models\PRItems;
use App\Models\TransactionAttachments;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Arrays;
use App\Swep\Helpers\Helper;
use App\Swep\Services\PRService;
use App\Swep\Traits\PRTimelineTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Str;

class MyPrController extends Controller
{
    use PRTimelineTrait;
    protected $prService;
    public function __construct(PRService $prService)
    {
        $this->prService = $prService;
    }

    public function index(Request $request){
        if(\request()->ajax() && \request()->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.pr_my.index');
    }

    public function dataTable($request){
        $prs = Transactions::query()
            ->with(['transDetails','rc'])
            ->where('ref_book','=','PR')
            ->where('user_created','=',\Auth::user()->user_id);
        $search = $request->get('search')['value'] ?? null;

        $dt = \DataTables::of($prs);

        $dt = $dt->filter(function ($query) use($search){
            if($search != null){
                $query->whereHas('transDetails',function ($q) use($search){
                    return $q->where('item','like','%'.$search.'%')
                        ->orWhere('description','like','%'.$search.'%')
                        ->orWhere('date','like','%'.$search.'%');
                });
            }
        });

        $dt = $dt->addColumn('dept',function($data){
                return ($data->rc->description->name ?? null).
                    '<div class="table-subdetail" style="margin-top: 3px">'
                    .($data->rc->department ?? null)
                    .'<br>'.($data->rc->division ?? null).
                    '</div>';
            })
            ->addColumn('div_sec',function($data){
                return $data->rc->division ?? null;
            })
            ->addColumn('transDetails',function($data){
                if(!empty($data->transDetails)){
                    return view('ppu.pr_my.dtItems')->with([
                        'items' => $data->transDetails
                    ]);
                }
            })
            ->addColumn('action',function($data){
                return view('ppu.pr_my.dtActions')->with([
                    'pr' => $data,
                ]);
            })
            ->editColumn('ref_no',function($data){
                if($data->cancelled_at != null){
                    return '<s class="text-danger">'.$data->ref_no.'</s><br><small class="text-danger text-strong" style="border-top: 1px solid black;">CANCELLED</small>';
                }
                return $data->ref_no;
            })
            ->editColumn('date',function($data){
                return !empty($data->date) ? Carbon::parse($data->date)->format('M. d, Y') : null;
            })
            ->addColumn('total',function($data){
                return number_format($data->transDetails()->sum('total_cost'),2);
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();
        return $dt;
    }

    public function getArticle($stockNo){
        $a = Articles::query()->where('stockNo','=',$stockNo)->first();
        return $a ?? null;
    }

    function removeTitles($inputString) {
        $titlesToRemove = array('ENGR', 'Engr', 'engr', 'ENGR.', 'Engr.', 'engr.', 'ENGINEER', 'Engineer', 'engineer', 'ATTY', 'Atty', 'atty', 'ATTY.', 'Atty.', 'atty.', 'ATTORNEY', 'Attorney', 'attorney');
        $cleanedString = str_replace($titlesToRemove, '', $inputString);
        $cleanedString = trim($cleanedString, ". "); // Trim spaces and periods from the beginning and end

        return trim($cleanedString); // Trim to remove any leading or trailing spaces
    }

    function removeTitles2($inputString) {
        $titlesToRemove = array('ENGR', 'Engr', 'engr', 'ENGR.', 'Engr.', 'engr.', 'ENGINEER', 'Engineer', 'engineer');
        $cleanedString = str_replace($titlesToRemove, '', $inputString);
        $cleanedString = trim($cleanedString, ". "); // Trim spaces and periods from the beginning and end

        return trim($cleanedString); // Trim to remove any leading or trailing spaces
    }

    public function store(PRFormRequest $request){

        $trans = new Transactions();
        $trans->slug = Str::random();
        $trans->ref_book = 'PR';
        $trans->resp_center = $request->resp_center;
        $trans->pap_code = $request->pap_code;
        $trans->ref_no = $this->prService->getNextPRNo();

        //Attachments
        $files = [
            'path_market_survey' => '',
            'path_specs' => '',
            'path_ppmp' => '',
            'path_app' => '',
        ];
        $attachmentToInsert = $files;
        $attachmentToInsert['slug'] = $trans->slug;
        foreach ($files as $input => $null){
            if(!empty($request->$input) && $request->$input != ''){
                $upload = $this->uploadAttachment($request->$input,$trans,$input);
                if($upload){
                    $attachmentToInsert[$input] = $upload;
                }
            }
        }
        if(count($attachmentToInsert) > 0){
            TransactionAttachments::insert($attachmentToInsert);
        }
        //End Attachment;

//        $trans->date = Carbon::now()->format('Y-m-d');
        $trans->account_code = $request->account_code;
        $trans->document_type = $request->document_type;
        $trans->date = $request->date;
        $trans->sai = $request->sai;
        $trans->sai_date = $request->sai_date;
        $trans->purpose = $request->purpose;
        $employee = Employee::query()->where('employee_no', '=', $request->requested_by)->first();
        $trans->requested_by = $employee->firstname . ' ' . substr($employee->middlename, 0, 1) . '. ' . $employee->lastname;
        //$trans->requested_by = $this->removeTitles($request->requested_by);
        $trans->requested_by_designation = $request->requested_by_designation;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;

        $abc = 0;
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'transaction_slug' => $trans->slug,
                    'stock_no' => $item['stockNo'],
                    'unit' => $item['unit'],
                    'item' => $item['itemName'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'total_cost' => $item['qty'] * Helper::sanitizeAutonum($item['unit_cost']),
                ]);
                $abc = $abc + $item['qty'] * Helper::sanitizeAutonum($item['unit_cost']);
            }
        }
        $trans->abc = $abc;
        if($trans->save()){
            TransactionDetails::insert($arr);
            //Send Mail
            $to = $trans->userCreated->email;
            $subject = Arrays::acronym($trans->ref_book).' No. '.$trans->ref_no;
            $cc = $trans->rc->emailRecipients->pluck('email_address')->toArray();
            $body = view('mailables.email_notifier.body-transaction-created')->with(['transaction' => $trans])->render();
            EmailNotification::dispatch($to,$subject,$body,$cc);

            return $trans->only('slug');
        }
        abort(503,'Error creating PR. [PRController::store]');
    }

    private function uploadAttachment($file,$trans,$input){

        if(Helper::convertFromBytes($file->getSize(),'MB') > 5){
            abort(503,'Max file size: 5Mb');
        }
        $original_ext = $file->getClientOriginalExtension();
        $original_file_name_only = str_replace('.'.$original_ext,'',$file->getClientOriginalName());
        $new_file_name_full = Str::of($input)->replace('path_','')->upper().' '.Carbon::now()->format('Y-m-d His').'.'.$original_ext;
        $fullPath = '/prjr_attachments/'.Auth::user()->project_id.'/'.$trans->ref_no.'/'.$new_file_name_full;
        if(\Storage::disk('local')->put($fullPath,$file->get())){
            return $fullPath;
        }

    }

    public function edit($slug){
        $pr =$this->findBySlug($slug);
        if($pr->is_locked == 1){
            abort(510,'This transaction is already locked from editing.');
        }
        return view('ppu.pr_my.edit')->with([
            'pr' => $pr,
        ]);
    }

    public function update(PRFormRequest $request,$slug){
        $trans = $this->findBySlug($slug);
        $trans->ref_book = 'PR';
        $trans->resp_center = $request->resp_center;
        $trans->pap_code = $request->pap_code;
//        $trans->date = Carbon::now()->format('Y-m-d');
        $trans->account_code = $request->account_code;
        $trans->document_type = $request->document_type;
        $trans->date = $request->date;
        $trans->sai = $request->sai;
        $trans->sai_date = $request->sai_date;
        $trans->purpose = $request->purpose;
        $trans->requested_by = (Auth::user()->project_id == 1 ? $this->removeTitles($request->requested_by) : $this->removeTitles2($request->requested_by));
        $trans->requested_by_designation = $request->requested_by_designation;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;

        $abc = 0;
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'transaction_slug' => $trans->slug,
                    'stock_no' => $item['stockNo'],
                    'unit' => $item['unit'],
                    'item' => $item['itemName'],
                    'description' => $item['description'],
                    'qty' => $item['qty'],
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'total_cost' => $item['qty'] * Helper::sanitizeAutonum($item['unit_cost']),
                ]);
                $abc = $abc + $item['qty'] * Helper::sanitizeAutonum($item['unit_cost']);
            }
        }
        $trans->transDetails()->delete();
        $trans->abc = $abc;
        if($trans->save()){
            TransactionDetails::insert($arr);
            return $trans->only('slug');
        }
    }

    public function findBySlug($slug){
        $pr = Transactions::query()
            ->with(['transDetails','rc','transDetails.article'])
            ->where('slug','=',$slug)->first();

        return $pr ?? abort(503,'PR not found');
    }
    public function destroy($slug){
        $pr = $this->findBySlug($slug);
        if($pr->is_locked == 1){
            abort(503,'This transaction is already locked');
        }
        if($pr->delete()){
            $pr->transDetails()->delete();
            return 1;
        }
        abort(503,'Error deleting item.');
    }

    public function print($slug){
        $pr = $this->findBySlug($slug);
        return view('printables.pr.pr_front_and_back')->with([
            'pr' => $pr,
        ]);
    }

    public function create(){
        return view('ppu.pr_my.create');
    }

    public function show($slug){
        $pr = $this->findBySlug($slug);
        $timeline = $this->prTimeline($slug,$pr);
        return view('ppu.pr_my.show')->with([
            'pr' => $pr,
            'timeline' => $timeline,
        ]);
    }
}