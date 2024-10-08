<?php


namespace App\Http\Controllers;


use App\Http\Requests\JR\JRFormRequest;
use App\Jobs\EmailNotification;
use App\Models\Employee;
use App\Models\JR;
use App\Models\JRItems;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Swep\Helpers\Arrays;
use App\Swep\Helpers\Helper;
use App\Swep\Services\JRService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Str;

class MyJrController extends Controller
{
    protected $jrService;
    public function __construct(JRService $jrService)
    {
        $this->jrService = $jrService;
    }

    public function index(Request $request){
        if($request->ajax() && $request->has('draw')){
            return $this->dataTable($request);
        }
        return view('ppu.jr_my.index');
    }
    public function dataTable($request){
        $jrs = Transactions::query()
            ->with(['transDetails','rc'])
            ->where('ref_book','=','JR')
            ->where('user_created','=',\Auth::user()->user_id);
        $search = $request->get('search')['value'] ?? null;

        $dt = \DataTables::of($jrs);

        $dt = $dt->filter(function ($query) use($search){
            if($search != null){
                $query->whereHas('transDetails',function ($q) use($search){
                    return $q->where('item','like','%'.$search.'%')
                        ->orWhere('description','like','%'.$search.'%')
                        ->orWhere('date','like','%'.$search.'%');
                });
            }
        });

        $dt = $dt->addColumn('action',function($data){
                return view('ppu.jr_my.dtActions')->with([
                    'jr' => $data,
                ]);
            })
            ->addColumn('dept',function($data){
                return ($data->rc->description->name ?? null).
                    '<div class="table-subdetail" style="margin-top: 3px">'.($data->rc->department ?? null).
                    '<br>'.($data->rc->division ?? null).
                    '</div>';
            })
            ->addColumn('div_sec',function($data){
                return $data->rc->division ?? null;
            })
            ->addColumn('items',function($data){
                return view('ppu.jr_my.dtItems')->with([
                    'items' => $data->transDetails,
                ]);
            })
            ->editColumn('ref_no',function($data){
                if($data->cancelled_at != null){
                    return '<s class="text-danger">'.$data->ref_no.'</s><br><small class="text-danger text-strong" style="border-top: 1px solid black;">CANCELLED</small>';
                }
                return $data->ref_no;
            })
            ->editColumn('abc',function($data){
                return number_format($data->abc,2);
            })
            ->editColumn('date',function($data){
                return $data->date ? Carbon::parse($data->date)->format('M. d, Y') : '';
            })
            ->escapeColumns([])
            ->setRowId('slug')
            ->toJson();

        return $dt;
    }

    function removeTitles($inputString) {
        $titlesToRemove = array('ENGR', 'Engr', 'engr', 'ENGR.', 'Engr.', 'engr.', 'ENGINEER', 'Engineer', 'engineer', 'ATTY', 'Atty', 'atty', 'ATTY.', 'Atty.', 'atty.', 'ATTORNEY', 'Attorney', 'attorney');
        $cleanedString = str_replace($titlesToRemove, '', $inputString);
        $cleanedString = trim($cleanedString, ". "); // Trim spaces and periods from the beginning and end

        return trim($cleanedString); // Trim to remove any leading or trailing spaces
    }

    function removeTitles2($inputString) {
        $titlesToRemove = array('ATTY', 'Atty', 'atty', 'ATTY.', 'Atty.', 'atty.', 'ATTORNEY', 'Attorney', 'attorney');
        $cleanedString = str_replace($titlesToRemove, '', $inputString);
        $cleanedString = trim($cleanedString, ". "); // Trim spaces and periods from the beginning and end

        return trim($cleanedString); // Trim to remove any leading or trailing spaces
    }

    public function store(JRFormRequest $request){
        $trans = new Transactions();
        $trans->slug = Str::random();
        $trans->ref_book = 'JR';
        $trans->resp_center = $request->resp_center;
        $trans->pap_code = $request->pap_code;
        $trans->account_code = $request->account_code;
        $trans->document_type = $request->document_type;
        $trans->jr_type = $request->jr_type;
        $trans->date = $request->date;
        $trans->ref_no = $this->jrService->getNextJRNo();
        $trans->purpose = $request->purpose;
        $trans->certified_by = $request->certified_by;
        $trans->certified_by_designation = $request->certified_by_designation;
        $employee = Employee::query()->where('employee_no', '=', $request->requested_by)->first();
        $trans->requested_by = $employee->firstname . ' ' . substr($employee->middlename, 0, 1) . '. ' . $employee->lastname;
        //$trans->requested_by = $this->removeTitles($request->requested_by);
        $trans->requested_by_designation = $request->requested_by_designation;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;
        $trans->abc = Helper::sanitizeAutonum($request->abc);
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'transaction_slug' => $trans->slug,
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'nature_of_work' => $item['nature_of_work'],
                    'property_no' => $item['property_no'],
                    'unit' => $item['unit'],
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'qty' => Helper::sanitizeAutonum($item['qty']),
                    'total_cost' => $item['qty'] * Helper::sanitizeAutonum($item['unit_cost']),
                ]);
            }
        }
        if($trans->save()){
            if(count($arr ) > 0){
                TransactionDetails::insert($arr);
            }

            //Send Mail
            $to = $trans->userCreated->email;
            $subject = Arrays::acronym($trans->ref_book).' No. '.$trans->ref_no;
            $cc = $trans->rc->emailRecipients->pluck('email_address')->toArray();
            $body = view('mailables.email_notifier.body-transaction-created')->with(['transaction' => $trans])->render();
            EmailNotification::dispatch($to,$subject,$body,$cc);
            return $trans->only('slug');
        }
        abort(503,'Error creating JR. [JRController::store]');
    }

    public function print($slug){
        return view('printables.jr.jr_front')->with([
            'jr' => $this->jrService->findBySlug($slug),
        ]);
    }

    public function edit($slug){
        $jr = $this->jrService->findBySlug($slug);
        if($jr->is_locked == 1){
            abort(510,'This transaction is already locked from editing');
        }
        return view('ppu.jr_my.edit')->with([
            'jr' => $jr,
        ]);
    }

    public function update(JRFormRequest $request,$slug){
        $trans = $this->jrService->findBySlug($slug);
        $trans->ref_book = 'JR';
        $trans->resp_center = $request->resp_center;
        $trans->pap_code = $request->pap_code;
        $trans->account_code = $request->account_code;
        $trans->document_type = $request->document_type;
        $trans->jr_type = $request->jr_type;
        $trans->purpose = $request->purpose;
        $trans->date = $request->date;
        $trans->certified_by = $request->certified_by;
        $trans->certified_by_designation = $request->certified_by_designation;
        $trans->requested_by = (Auth::user()->project_id == 1 ? $this->removeTitles($request->requested_by) : $this->removeTitles2($request->requested_by));;
        //$trans->requested_by = $request->requested_by;
        $trans->requested_by_designation = $request->requested_by_designation;
        $trans->approved_by = $request->approved_by;
        $trans->approved_by_designation = $request->approved_by_designation;
        $trans->abc = Helper::sanitizeAutonum($request->abc);
        $arr = [];
        if(!empty($request->items)){
            foreach ($request->items as $item){
                array_push($arr,[
                    'slug' => Str::random(),
                    'transaction_slug' => $trans->slug,
                    'item' => $item['item'],
                    'description' => $item['description'],
                    'nature_of_work' => $item['nature_of_work'],
                    'property_no' => $item['property_no'],
                    'unit' => $item['unit'],
                    'unit_cost' => Helper::sanitizeAutonum($item['unit_cost']),
                    'qty' => $item['qty'],
                    'total_cost' => $item['qty'] * Helper::sanitizeAutonum($item['unit_cost']),
                ]);
            }
        }
        if($trans->save()){
            if(count($arr ) > 0){
                DB::table('transaction_details')
                    ->where('transaction_slug', '=', $trans->slug)
                    ->delete();
//                $trans->transDetails()->delete();
                TransactionDetails::insert($arr);
            }
            return $trans->only('slug');
        }
    }

    public function destroy($slug){
        $jr = $this->jrService->findBySlug($slug);
        if($jr->is_locked == 1){
            abort(503,'This transaction is already locked');
        }
        if($jr->delete()){
            $jr->transDetails()->delete();
            return 1;
        }
        abort(503,'Error deleting item.');
    }

    public function create(){
        return view('ppu.jr_my.create');
    }
}