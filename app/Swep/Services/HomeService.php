<?php
 
namespace App\Swep\Services;


use App\Models\PPURespCodes;
use App\Models\RCDesc;
use App\Models\Transactions;
use App\Swep\Interfaces\EmployeeInterface;
use App\Swep\Interfaces\UserInterface;
use App\Swep\BaseClasses\BaseService;
use Illuminate\Support\Facades\DB;


class HomeService extends BaseService{

    protected $employee_repo;
    protected $user_repo;


    public function __construct(EmployeeInterface $employee_repo, UserInterface $user_repo){

        $this->employee_repo = $employee_repo;
        $this->user_repo = $user_repo;
        parent::__construct();

    }

    public function view(){
//        $trans = Transactions::query();
        $trans_pr = DB::table('transactions')->where('ref_book', '=', 'PR')->count();
        $trans_jr = DB::table('transactions')->where('ref_book', '=', 'JR')->count();
        $trans_aq = DB::table('transactions')->where('ref_book', '=', 'AQ')->count();
        $trans_rfq = DB::table('transactions')->where('ref_book', '=', 'RFQ')->count();
        $trans_po = DB::table('transactions')->where('ref_book', '=', 'PO')->count();
        $trans_jo = DB::table('transactions')->where('ref_book', '=', 'JO')->count();
        $trans_pr_cancelled = DB::table('transactions')->where('ref_book', '=', 'PR')
            ->where('cancelled_at', '!=', null)->count();
        $trans_jr_cancelled = DB::table('transactions')->where('ref_book', '=', 'JR')
            ->where('cancelled_at', '!=', null)->count();
        $count_active_emp = $this->employee_repo->getAll()->count();
        $count_male_emp = $this->employee_repo->getBySex('M')->count();
        $count_female_emp = $this->employee_repo->getBySex('F')->count();
        $count_online_users = $this->user_repo->getByIsOnline(1)->count();
        $trans_by_resp_center_bar = $this->getTransByDept();
        $trans_by_resp_center_pr_jr = $this->getPRJRTransByDept();

        return view('dashboard.home.index', compact(
                'count_active_emp',
                'count_male_emp',
                'count_female_emp',
                'count_online_users',
                'trans_pr',
                'trans_jr',
                'trans_rfq',
                'trans_aq',
                'trans_po',
                'trans_jo',
                'trans_pr_cancelled',
                'trans_jr_cancelled',
                'trans_by_resp_center_bar',
                'trans_by_resp_center_pr_jr'
        ));

    }

    private function getPRJRTransByDept(){
        $OBCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '010')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $OBCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '010')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $IADCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '020')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $IADCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '020')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $OACount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '030')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $OACountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '030')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $LEGALCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '040')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $LEGALCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '040')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $PPSPDCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '050')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $PPSPDCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '050')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $AFDLMCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '060')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $AFDLMCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '060')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $AFDVISCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '065')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $AFDVISCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '065')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $RDELMCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '070')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $RDELMCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '070')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $RDEVISCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '075')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $RDEVISCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '075')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $RDLMCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '080')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $RDLMCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '080')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $RDVISCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '085')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $RDVISCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '085')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $GADCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '090')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $GADCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '090')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $SIDABFPCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '100')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $SIDABFPCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '100')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $SIDASCPCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '110')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $SIDASCPCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '110')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $SIDAHRDCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '120')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $SIDAHRDCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '120')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $SIDAFMRCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '130')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $SIDAFMRCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '130')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $SIDARDCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '140')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');
        $SIDARDCountJR = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '140')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->count('transactions.id');

        $obj = [(object)['name'=>'OB', 'count'=>$OBCount, 'countJR'=>$OBCountJR],
            (object)['name'=>'IAD', 'count'=>$IADCount, 'countJR'=>$IADCountJR],
            (object)['name'=>'OA', 'count'=>$OACount, 'countJR'=>$OACountJR],
            (object)['name'=>'LEGAL', 'count'=>$LEGALCount, 'countJR'=>$LEGALCountJR],
            (object)['name'=>'PPSPD', 'count'=>$PPSPDCount, 'countJR'=>$PPSPDCountJR],
            (object)['name'=>'AFD-LM', 'count'=>$AFDLMCount, 'countJR'=>$AFDLMCountJR],
            (object)['name'=>'AFD-VIS', 'count'=>$AFDVISCount, 'countJR'=>$AFDVISCountJR],
            (object)['name'=>'RDE-LM', 'count'=>$RDELMCount, 'countJR'=>$RDELMCountJR],
            (object)['name'=>'RDE-VIS', 'count'=>$RDEVISCount, 'countJR'=>$RDEVISCountJR],
            (object)['name'=>'RD-LM', 'count'=>$RDLMCount, 'countJR'=>$RDLMCountJR],
            (object)['name'=>'RD-VIS', 'count'=>$RDVISCount, 'countJR'=>$RDVISCountJR],
            (object)['name'=>'GAD', 'count'=>$GADCount, 'countJR'=>$GADCountJR],
            (object)['name'=>'SIDA-BFP', 'count'=>$SIDABFPCount, 'countJR'=>$SIDABFPCountJR],
            (object)['name'=>'SIDA-SCP', 'count'=>$SIDASCPCount, 'countJR'=>$SIDASCPCountJR],
            (object)['name'=>'SIDA-HRD', 'count'=>$SIDAHRDCount, 'countJR'=>$SIDAHRDCountJR],
            (object)['name'=>'SIDA- FMR', 'count'=>$SIDAFMRCount, 'countJR'=>$SIDAFMRCountJR],
            (object)['name'=>'SIDA-R&D', 'count'=>$SIDARDCount, 'countJR'=>$SIDARDCountJR]];
        return $obj;
        /*return ['IAD'=>$IADCount, 'OA'=>$OACount, 'LEGAL'=>$LEGALCount, 'PPSPD'=>$PPSPDCount, 'AFD-LM'=>$AFDLMCount, 'AFD-VIS'=>$AFDVISCount,
            'RDE-LM'=>$RDELMCount, 'RDE-VIS'=>$RDEVISCount, 'RD-LM'=>$RDLMCount, 'RD-VIS'=>$RDVISCount, 'GAD'=>$GADCount,
            'SIDA-BFP'=>$SIDABFPCount, 'SIDA-SCP'=>$SIDASCPCount, 'SIDA-HRD'=>$SIDAHRDCount, 'SIDA- FMR'=>$SIDAFMRCount, 'SIDA-R&D'=>$SIDARDCount];*/
    }

    private function getTransByDept(){
        $OBCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '010')
            ->count('transactions.id');
        $IADCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '020')
            ->count('transactions.id');
        $OACount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '030')
            ->count('transactions.id');
        $LEGALCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '040')
            ->count('transactions.id');
        $PPSPDCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '050')
            ->count('transactions.id');
        $AFDLMCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '060')
            ->count('transactions.id');
        $AFDVISCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '065')
            ->count('transactions.id');
        $RDELMCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '070')
            ->count('transactions.id');
        $RDEVISCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '075')
            ->count('transactions.id');
        $RDLMCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '080')
            ->count('transactions.id');
        $RDVISCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '085')
            ->count('transactions.id');
        $GADCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '090')
            ->count('transactions.id');
        $SIDABFPCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '100')
            ->count('transactions.id');
        $SIDASCPCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '110')
            ->count('transactions.id');
        $SIDAHRDCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '120')
            ->count('transactions.id');
        $SIDAFMRCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '130')
            ->count('transactions.id');
        $SIDARDCount = DB::table('transactions')
            ->join('resp_codes', 'transactions.resp_center', '=', 'resp_codes.rc_code')
            ->where('resp_codes.rc', '=', '140')
            ->count('transactions.id');


        $obj = [(object)['name'=>'OB', 'count'=>$OBCount],
            (object)['name'=>'IAD', 'count'=>$IADCount],
            (object)['name'=>'OA', 'count'=>$OACount],
            (object)['name'=>'LEGAL', 'count'=>$LEGALCount],
            (object)['name'=>'PPSPD', 'count'=>$PPSPDCount],
            (object)['name'=>'AFD-LM', 'count'=>$AFDLMCount],
            (object)['name'=>'AFD-VIS', 'count'=>$AFDVISCount],
            (object)['name'=>'RDE-LM', 'count'=>$RDELMCount],
            (object)['name'=>'RDE-VIS', 'count'=>$RDEVISCount],
            (object)['name'=>'RD-LM', 'count'=>$RDLMCount],
            (object)['name'=>'RD-VIS', 'count'=>$RDVISCount],
            (object)['name'=>'GAD', 'count'=>$GADCount],
            (object)['name'=>'SIDA-BFP', 'count'=>$SIDABFPCount],
            (object)['name'=>'SIDA-SCP', 'count'=>$SIDASCPCount],
            (object)['name'=>'SIDA-HRD', 'count'=>$SIDAHRDCount],
            (object)['name'=>'SIDA- FMR', 'count'=>$SIDAFMRCount],
            (object)['name'=>'SIDA-R&D', 'count'=>$SIDARDCount]];
        return $obj;
        /*return ['IAD'=>$IADCount, 'OA'=>$OACount, 'LEGAL'=>$LEGALCount, 'PPSPD'=>$PPSPDCount, 'AFD-LM'=>$AFDLMCount, 'AFD-VIS'=>$AFDVISCount,
            'RDE-LM'=>$RDELMCount, 'RDE-VIS'=>$RDEVISCount, 'RD-LM'=>$RDLMCount, 'RD-VIS'=>$RDVISCount, 'GAD'=>$GADCount,
            'SIDA-BFP'=>$SIDABFPCount, 'SIDA-SCP'=>$SIDASCPCount, 'SIDA-HRD'=>$SIDAHRDCount, 'SIDA- FMR'=>$SIDAFMRCount, 'SIDA-R&D'=>$SIDARDCount];*/
    }

    private function getEmpByDept(){

        $afd = $this->employee_repo->getByDepartmentId('D1001')->count();
        $iad = $this->employee_repo->getByDepartmentId('D1002')->count();
        $ppd = $this->employee_repo->getByDepartmentId('D1003')->count();
        $rde = $this->employee_repo->getByDepartmentId('D1004')->count();
        $rd = $this->employee_repo->getByDepartmentId('D1005')->count();
        $legal = $this->employee_repo->getByDepartmentId('D1006')->count();

        return ['AFD' => $afd,'IAD' => $iad,'PPD' => $ppd,'RDE' => $rde,'RD' => $rd,'LEGAL' => $legal];

    }








}