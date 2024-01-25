<?php
 
namespace App\Swep\Services;


use App\Models\PPURespCodes;
use App\Models\RCDesc;
use App\Models\Transactions;
use App\Swep\Interfaces\EmployeeInterface;
use App\Swep\Interfaces\UserInterface;
use App\Swep\BaseClasses\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


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
        $reportYear = \App\Swep\Helpers\Helper::getSetting('dashboard_report_year')->int_value;
        $userProjectId = Auth::user()->project_id;
        $allTransactions = DB::table('transactions')
            ->where(function ($query) use ($reportYear) {
                $query->whereYear('transactions.date', '=', $reportYear)
                    ->orWhereYear('transactions.created_at', '=', $reportYear);
            })
            ->where('transactions.project_id', '=', $userProjectId);
        $prClone = clone $allTransactions;
        $trans_pr = $prClone
            ->where('ref_book', '=', 'PR')
            ->count();
        $trans_jrClone = clone $allTransactions;
        $trans_jr = $trans_jrClone->where('ref_book', '=', 'JR')
            ->count();
        $trans_aqClone = clone $allTransactions;
        $trans_aq = $trans_aqClone->where('ref_book', '=', 'AQ')
            ->count();
        $trans_rfqClone = clone $allTransactions;
        $trans_rfq = $trans_rfqClone->where('ref_book', '=', 'RFQ')
            ->count();
        $trans_poClone = clone $allTransactions;
        $trans_po = $trans_poClone->where('ref_book', '=', 'PO')
            ->count();
        $trans_joClone = clone $allTransactions;
        $trans_jo = $trans_joClone->where('ref_book', '=', 'JO')
            ->count();
        $trans_pr_cancelledClone = clone $allTransactions;
        $trans_pr_cancelled =$trans_pr_cancelledClone->where('ref_book', '=', 'PR')
            ->where('cancelled_at', '!=', null)
            ->count();
        $trans_jr_cancelledClone = clone $allTransactions;
        $trans_jr_cancelled = $trans_jr_cancelledClone->where('ref_book', '=', 'JR')
            ->where('cancelled_at', '!=', null)
            ->count();
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
        $reportYear = \App\Swep\Helpers\Helper::getSetting('dashboard_report_year')->int_value;
        $userProjectId = Auth::user()->project_id;
        $allTransactions = DB::table('transactions')
            ->where(function ($query) use ($reportYear) {
                $query->whereYear('transactions.date', '=', $reportYear)
                    ->orWhereYear('transactions.created_at', '=', $reportYear);
            })
            ->where('transactions.project_id', '=', $userProjectId);
        $OBQuery = clone $allTransactions;
        $OBCount = $OBQuery
            ->join('resp_codes as rc1', 'transactions.resp_center', '=', 'rc1.rc_code')
            ->where('rc1.rc', '=', '010')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $OBCountJRQuery = clone $allTransactions;
        $OBCountJR = $OBCountJRQuery
            ->join('resp_codes as rc2', 'transactions.resp_center', '=', 'rc2.rc_code')
            ->where('rc2.rc', '=', '010')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $OBCountPOQuery = clone $allTransactions;
        $OBCountPO = $OBCountPOQuery
            ->join('resp_codes as rc3', 'transactions.resp_center', '=', 'rc3.rc_code')
            ->join('order as o1', 'transactions.order_slug', '=', 'o1.slug')
            ->where('rc3.rc', '=', '010')
            ->where('o1.ref_book', '=', 'PO')
            ->whereYear('o1.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o1.total_gross');
        $OBCountJOQuery = clone $allTransactions;
        $OBCountJO = $OBCountJOQuery
            ->join('resp_codes as rc4', 'transactions.resp_center', '=', 'rc4.rc_code')
            ->join('order as o2', 'transactions.order_slug', '=', 'o2.slug')
            ->where('rc4.rc', '=', '010')
            ->where('o2.ref_book', '=', 'JO')
            ->whereYear('o2.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o2.total_gross');
        $IADCountQuery = clone $allTransactions;
        $IADCount = $IADCountQuery
            ->join('resp_codes as rc5', 'transactions.resp_center', '=', 'rc5.rc_code')
            ->where('rc5.rc', '=', '020')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $IADCountJRQuery = clone $allTransactions;
        $IADCountJR = $IADCountJRQuery
            ->join('resp_codes as rc6', 'transactions.resp_center', '=', 'rc6.rc_code')
            ->where('rc6.rc', '=', '020')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $IADCountPOQuery = clone $allTransactions;
        $IADCountPO = $IADCountPOQuery
            ->join('resp_codes as rc7', 'transactions.resp_center', '=', 'rc7.rc_code')
            ->join('order as o3', 'transactions.order_slug', '=', 'o3.slug')
            ->where('rc7.rc', '=', '020')
            ->where('o3.ref_book', '=', 'PO')
            ->whereYear('o3.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o3.total_gross');
        $IADCountJOQuery = clone $allTransactions;
        $IADCountJO = $IADCountJOQuery
            ->join('resp_codes as rc8', 'transactions.resp_center', '=', 'rc8.rc_code')
            ->join('order as o4', 'transactions.order_slug', '=', 'o4.slug')
            ->where('rc8.rc', '=', '020')
            ->where('o4.ref_book', '=', 'JO')
            ->whereYear('o4.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o4.total_gross');
        $OACountQuery = clone $allTransactions;
        $OACount = $OACountQuery
            ->join('resp_codes as rc9', 'transactions.resp_center', '=', 'rc9.rc_code')
            ->where('rc9.rc', '=', '030')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $OACountJRQuery = clone $allTransactions;
        $OACountJR = $OACountJRQuery
            ->join('resp_codes as rc10', 'transactions.resp_center', '=', 'rc10.rc_code')
            ->where('rc10.rc', '=', '030')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $OACountPOQuery = clone $allTransactions;
        $OACountPO = $OACountPOQuery
            ->join('resp_codes as rc11', 'transactions.resp_center', '=', 'rc11.rc_code')
            ->join('order as o5', 'transactions.order_slug', '=', 'o5.slug')
            ->where('rc11.rc', '=', '030')
            ->where('o5.ref_book', '=', 'PO')
            ->whereYear('o5.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o5.total_gross');
        $OACountJOQuery = clone $allTransactions;
        $OACountJO = $OACountJOQuery
            ->join('resp_codes as rc12', 'transactions.resp_center', '=', 'rc12.rc_code')
            ->join('order as o6', 'transactions.order_slug', '=', 'o6.slug')
            ->where('rc12.rc', '=', '030')
            ->where('o6.ref_book', '=', 'JO')
            ->whereYear('o6.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o6.total_gross');
        $LEGALCountOQuery = clone $allTransactions;
        $LEGALCount = $LEGALCountOQuery
            ->join('resp_codes as rc13', 'transactions.resp_center', '=', 'rc13.rc_code')
            ->where('rc13.rc', '=', '040')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $LEGALCountJRQuery = clone $allTransactions;
        $LEGALCountJR = $LEGALCountJRQuery
            ->join('resp_codes as rc14', 'transactions.resp_center', '=', 'rc14.rc_code')
            ->where('rc14.rc', '=', '040')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $LEGALCountPOQuery = clone $allTransactions;
        $LEGALCountPO = $LEGALCountPOQuery
            ->join('resp_codes as rc15', 'transactions.resp_center', '=', 'rc15.rc_code')
            ->join('order as o7', 'transactions.order_slug', '=', 'o7.slug')
            ->where('rc15.rc', '=', '040')
            ->where('o7.ref_book', '=', 'PO')
            ->whereYear('o7.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o7.total_gross');
        $LEGALCountJOQuery = clone $allTransactions;
        $LEGALCountJO = $LEGALCountJOQuery
            ->join('resp_codes as rc16', 'transactions.resp_center', '=', 'rc16.rc_code')
            ->join('order as o8', 'transactions.order_slug', '=', 'o8.slug')
            ->where('rc16.rc', '=', '040')
            ->where('o8.ref_book', '=', 'JO')
            ->whereYear('o8.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o8.total_gross');

        $PPSPDCountQuery = clone $allTransactions;
        $PPSPDCount = $PPSPDCountQuery
            ->join('resp_codes as rc17', 'transactions.resp_center', '=', 'rc17.rc_code')
            ->where('rc17.rc', '=', '050')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $PPSPDCountJRQuery = clone $allTransactions;
        $PPSPDCountJR = $PPSPDCountJRQuery
            ->join('resp_codes as rc18', 'transactions.resp_center', '=', 'rc18.rc_code')
            ->where('rc18.rc', '=', '050')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $PPSPDCountPOQuery = clone $allTransactions;
        $PPSPDCountPO = $PPSPDCountPOQuery
            ->join('resp_codes as rc19', 'transactions.resp_center', '=', 'rc19.rc_code')
            ->join('order as o9', 'transactions.order_slug', '=', 'o9.slug')
            ->where('rc19.rc', '=', '050')
            ->where('o9.ref_book', '=', 'PO')
            ->whereYear('o9.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o9.total_gross');
        $PPSPDCountJOQuery = clone $allTransactions;
        $PPSPDCountJO = $PPSPDCountJOQuery
            ->join('resp_codes as rc20', 'transactions.resp_center', '=', 'rc20.rc_code')
            ->join('order as o10', 'transactions.order_slug', '=', 'o10.slug')
            ->where('rc20.rc', '=', '050')
            ->where('o10.ref_book', '=', 'JO')
            ->whereYear('o10.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o10.total_gross');

        $AFDLMCountQuery = clone $allTransactions;
        $AFDLMCount = $AFDLMCountQuery
            ->join('resp_codes as rc21', 'transactions.resp_center', '=', 'rc21.rc_code')
            ->where('rc21.rc', '=', '060')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $AFDLMCountJRQuery = clone $allTransactions;
        $AFDLMCountJR = $AFDLMCountJRQuery
            ->join('resp_codes as rc22', 'transactions.resp_center', '=', 'rc22.rc_code')
            ->where('rc22.rc', '=', '060')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $AFDLMCountPOQuery = clone $allTransactions;
        $AFDLMCountPO = $AFDLMCountPOQuery
            ->join('resp_codes as rc23', 'transactions.resp_center', '=', 'rc23.rc_code')
            ->join('order as o11', 'transactions.order_slug', '=', 'o11.slug')
            ->where('rc23.rc', '=', '060')
            ->where('o11.ref_book', '=', 'PO')
            ->whereYear('o11.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o11.total_gross');
        $AFDLMCountJOQuery = clone $allTransactions;
        $AFDLMCountJO = $AFDLMCountJOQuery
            ->join('resp_codes as rc24', 'transactions.resp_center', '=', 'rc24.rc_code')
            ->join('order as o12', 'transactions.order_slug', '=', 'o12.slug')
            ->where('rc24.rc', '=', '060')
            ->where('o12.ref_book', '=', 'JO')
            ->whereYear('o12.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o12.total_gross');

        $AFDVISCountQuery = clone $allTransactions;
        $AFDVISCount = $AFDVISCountQuery
            ->join('resp_codes as rc25', 'transactions.resp_center', '=', 'rc25.rc_code')
            ->where('rc25.rc', '=', '065')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $AFDVISCountJRQuery = clone $allTransactions;
        $AFDVISCountJR = $AFDVISCountJRQuery
            ->join('resp_codes as rc26', 'transactions.resp_center', '=', 'rc26.rc_code')
            ->where('rc26.rc', '=', '065')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $AFDVISCountPOQuery = clone $allTransactions;
        $AFDVISCountPO = $AFDVISCountPOQuery
            ->join('resp_codes as rc27', 'transactions.resp_center', '=', 'rc27.rc_code')
            ->join('order as o13', 'transactions.order_slug', '=', 'o13.slug')
            ->where('rc27.rc', '=', '065')
            ->where('o13.ref_book', '=', 'PO')
            ->whereYear('o13.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o13.total_gross');
        $AFDVISCountJOQuery = clone $allTransactions;
        $AFDVISCountJO = $AFDVISCountJOQuery
            ->join('resp_codes as rc28', 'transactions.resp_center', '=', 'rc28.rc_code')
            ->join('order as o14', 'transactions.order_slug', '=', 'o14.slug')
            ->where('rc28.rc', '=', '065')
            ->where('o14.ref_book', '=', 'JO')
            ->whereYear('o14.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o14.total_gross');

        $RDELMCountQuery = clone $allTransactions;
        $RDELMCount = $RDELMCountQuery
            ->join('resp_codes as rc29', 'transactions.resp_center', '=', 'rc29.rc_code')
            ->where('rc29.rc', '=', '070')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $RDELMCountJRQuery = clone $allTransactions;
        $RDELMCountJR = $RDELMCountJRQuery
            ->join('resp_codes as rc30', 'transactions.resp_center', '=', 'rc30.rc_code')
            ->where('rc30.rc', '=', '070')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $RDELMCountPOQuery = clone $allTransactions;
        $RDELMCountPO = $RDELMCountPOQuery
            ->join('resp_codes as rc31', 'transactions.resp_center', '=', 'rc31.rc_code')
            ->join('order as 015', 'transactions.order_slug', '=', '015.slug')
            ->where('rc31.rc', '=', '070')
            ->where('015.ref_book', '=', 'PO')
            ->whereYear('015.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('015.total_gross');
        $RDELMCountJOQuery = clone $allTransactions;
        $RDELMCountJO = $RDELMCountJOQuery
            ->join('resp_codes as rc32', 'transactions.resp_center', '=', 'rc32.rc_code')
            ->join('order as o16', 'transactions.order_slug', '=', 'o16.slug')
            ->where('rc32.rc', '=', '070')
            ->where('o16.ref_book', '=', 'JO')
            ->whereYear('o16.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o16.total_gross');

        $RDEVISCountQuery = clone $allTransactions;
        $RDEVISCount = $RDEVISCountQuery
            ->join('resp_codes as rc33', 'transactions.resp_center', '=', 'rc33.rc_code')
            ->where('rc33.rc', '=', '075')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $RDEVISCountJRQuery = clone $allTransactions;
        $RDEVISCountJR = $RDEVISCountJRQuery
            ->join('resp_codes as rc34', 'transactions.resp_center', '=', 'rc34.rc_code')
            ->where('rc34.rc', '=', '075')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $RDEVISCountPOQuery = clone $allTransactions;
        $RDEVISCountPO = $RDEVISCountPOQuery
            ->join('resp_codes as rc35', 'transactions.resp_center', '=', 'rc35.rc_code')
            ->join('order as 017', 'transactions.order_slug', '=', '017.slug')
            ->where('rc35.rc', '=', '075')
            ->where('017.ref_book', '=', 'PO')
            ->whereYear('017.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('017.total_gross');
        $RDEVISCountJOuery = clone $allTransactions;
        $RDEVISCountJO = $RDEVISCountJOuery
            ->join('resp_codes as rc36', 'transactions.resp_center', '=', 'rc36.rc_code')
            ->join('order as o18', 'transactions.order_slug', '=', 'o18.slug')
            ->where('rc36.rc', '=', '075')
            ->where('o18.ref_book', '=', 'JO')
            ->whereYear('o18.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o18.total_gross');

        $RDLMCountQuery = clone $allTransactions;
        $RDLMCount = $RDLMCountQuery
            ->join('resp_codes as rc37', 'transactions.resp_center', '=', 'rc37.rc_code')
            ->where('rc37.rc', '=', '080')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $RDLMCountJRuery = clone $allTransactions;
        $RDLMCountJR = $RDLMCountJRuery
            ->join('resp_codes as rc38', 'transactions.resp_center', '=', 'rc38.rc_code')
            ->where('rc38.rc', '=', '080')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $RDLMCountPOQuery = clone $allTransactions;
        $RDLMCountPO = $RDLMCountPOQuery
            ->join('resp_codes as rc39', 'transactions.resp_center', '=', 'rc39.rc_code')
            ->join('order as o19', 'transactions.order_slug', '=', 'o19.slug')
            ->where('rc39.rc', '=', '080')
            ->where('o19.ref_book', '=', 'PO')
            ->whereYear('o19.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o19.total_gross');
        $RDLMCountJOQuery = clone $allTransactions;
        $RDLMCountJO = $RDLMCountJOQuery
            ->join('resp_codes as rc40', 'transactions.resp_center', '=', 'rc40.rc_code')
            ->join('order as o20', 'transactions.order_slug', '=', 'o20.slug')
            ->where('rc40.rc', '=', '080')
            ->where('o20.ref_book', '=', 'JO')
            ->whereYear('o20.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o20.total_gross');

        $RDVISCountQuery = clone $allTransactions;
        $RDVISCount = $RDVISCountQuery
            ->join('resp_codes as rc41', 'transactions.resp_center', '=', 'rc41.rc_code')
            ->where('rc41.rc', '=', '085')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $RDVISCountJRQuery = clone $allTransactions;
        $RDVISCountJR = $RDVISCountJRQuery
            ->join('resp_codes as rc42', 'transactions.resp_center', '=', 'rc42.rc_code')
            ->where('rc42.rc', '=', '085')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $RDVISCountPOQuery = clone $allTransactions;
        $RDVISCountPO = $RDVISCountPOQuery
            ->join('resp_codes as rc43', 'transactions.resp_center', '=', 'rc43.rc_code')
            ->join('order as o21', 'transactions.order_slug', '=', 'o21.slug')
            ->where('rc43.rc', '=', '085')
            ->where('o21.ref_book', '=', 'PO')
            ->whereYear('o21.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o21.total_gross');
        $RDVISCountJOQuery = clone $allTransactions;
        $RDVISCountJO = $RDVISCountJOQuery
            ->join('resp_codes as rc44', 'transactions.resp_center', '=', 'rc44.rc_code')
            ->join('order as o22', 'transactions.order_slug', '=', 'o22.slug')
            ->where('rc44.rc', '=', '085')
            ->where('o22.ref_book', '=', 'JO')
            ->whereYear('o22.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o22.total_gross');

        $GADCountQuery = clone $allTransactions;
        $GADCount = $GADCountQuery
            ->join('resp_codes as rc45', 'transactions.resp_center', '=', 'rc45.rc_code')
            ->where('rc45.rc', '=', '090')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $GADCountJRQuery = clone $allTransactions;
        $GADCountJR = $GADCountJRQuery
            ->join('resp_codes as rc46', 'transactions.resp_center', '=', 'rc46.rc_code')
            ->where('rc46.rc', '=', '090')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $GADCountPOQuery = clone $allTransactions;
        $GADCountPO = $GADCountPOQuery
            ->join('resp_codes as rc47', 'transactions.resp_center', '=', 'rc47.rc_code')
            ->join('order as o23', 'transactions.order_slug', '=', 'o23.slug')
            ->where('rc47.rc', '=', '090')
            ->where('o23.ref_book', '=', 'PO')
            ->whereYear('o23.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o23.total_gross');
        $GADCountJOQuery = clone $allTransactions;
        $GADCountJO = $GADCountJOQuery
            ->join('resp_codes as rc48', 'transactions.resp_center', '=', 'rc48.rc_code')
            ->join('order as o24', 'transactions.order_slug', '=', 'o24.slug')
            ->where('rc48.rc', '=', '090')
            ->where('o24.ref_book', '=', 'JO')
            ->whereYear('o24.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o24.total_gross');

        $SIDABFPCountQuery = clone $allTransactions;
        $SIDABFPCount = $SIDABFPCountQuery
            ->join('resp_codes as rc49', 'transactions.resp_center', '=', 'rc49.rc_code')
            ->where('rc49.rc', '=', '100')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $SIDABFPCountJRQuery = clone $allTransactions;
        $SIDABFPCountJR = $SIDABFPCountJRQuery
            ->join('resp_codes as rc50', 'transactions.resp_center', '=', 'rc50.rc_code')
            ->where('rc50.rc', '=', '100')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $SIDABFPCountPOuery = clone $allTransactions;
        $SIDABFPCountPO = $SIDABFPCountPOuery
            ->join('resp_codes as rc51', 'transactions.resp_center', '=', 'rc51.rc_code')
            ->join('order as o25', 'transactions.order_slug', '=', 'o25.slug')
            ->where('rc51.rc', '=', '100')
            ->where('o25.ref_book', '=', 'PO')
            ->whereYear('o25.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o25.total_gross');
        $SIDABFPCountJOuery = clone $allTransactions;
        $SIDABFPCountJO = $SIDABFPCountJOuery
            ->join('resp_codes as rc52', 'transactions.resp_center', '=', 'rc52.rc_code')
            ->join('order as 026', 'transactions.order_slug', '=', '026.slug')
            ->where('rc52.rc', '=', '100')
            ->where('026.ref_book', '=', 'JO')
            ->whereYear('026.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('026.total_gross');

        $SIDASCPCountQuery = clone $allTransactions;
        $SIDASCPCount = $SIDASCPCountQuery
            ->join('resp_codes as rc53', 'transactions.resp_center', '=', 'rc53.rc_code')
            ->where('rc53.rc', '=', '110')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $SIDASCPCountJRQuery = clone $allTransactions;
        $SIDASCPCountJR = $SIDASCPCountJRQuery
            ->join('resp_codes as rc54', 'transactions.resp_center', '=', 'rc54.rc_code')
            ->where('rc54.rc', '=', '110')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $SIDASCPCountPOQuery = clone $allTransactions;
        $SIDASCPCountPO = $SIDASCPCountPOQuery
            ->join('resp_codes as rc55', 'transactions.resp_center', '=', 'rc55.rc_code')
            ->join('order as 027', 'transactions.order_slug', '=', '027.slug')
            ->where('rc55.rc', '=', '110')
            ->where('027.ref_book', '=', 'PO')
            ->whereYear('027.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('027.total_gross');
        $SIDASCPCountJOQuery = clone $allTransactions;
        $SIDASCPCountJO = $SIDASCPCountJOQuery
            ->join('resp_codes as rc56', 'transactions.resp_center', '=', 'rc56.rc_code')
            ->join('order as 028', 'transactions.order_slug', '=', '028.slug')
            ->where('rc56.rc', '=', '110')
            ->where('028.ref_book', '=', 'JO')
            ->whereYear('028.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('028.total_gross');

        $SIDAHRDCountQuery = clone $allTransactions;
        $SIDAHRDCount = $SIDAHRDCountQuery
            ->join('resp_codes as rc57', 'transactions.resp_center', '=', 'rc57.rc_code')
            ->where('rc57.rc', '=', '120')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $SIDAHRDCountJRQuery = clone $allTransactions;
        $SIDAHRDCountJR = $SIDAHRDCountJRQuery
            ->join('resp_codes as rc58', 'transactions.resp_center', '=', 'rc58.rc_code')
            ->where('rc58.rc', '=', '120')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $SIDAHRDCountPOQuery = clone $allTransactions;
        $SIDAHRDCountPO = $SIDAHRDCountPOQuery
            ->join('resp_codes as rc59', 'transactions.resp_center', '=', 'rc59.rc_code')
            ->join('order as 029', 'transactions.order_slug', '=', '029.slug')
            ->where('rc59.rc', '=', '120')
            ->where('029.ref_book', '=', 'PO')
            ->whereYear('029.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('029.total_gross');
        $SIDAHRDCountJOuery = clone $allTransactions;
        $SIDAHRDCountJO = $SIDAHRDCountJOuery
            ->join('resp_codes as rc60', 'transactions.resp_center', '=', 'rc60.rc_code')
            ->join('order as o30', 'transactions.order_slug', '=', 'o30.slug')
            ->where('rc60.rc', '=', '120')
            ->where('o30.ref_book', '=', 'JO')
            ->whereYear('o30.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o30.total_gross');

        $SIDAFMRCountQuery = clone $allTransactions;
        $SIDAFMRCount = $SIDAFMRCountQuery
            ->join('resp_codes as rc61', 'transactions.resp_center', '=', 'rc61.rc_code')
            ->where('rc61.rc', '=', '130')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $SIDAFMRCountJRuery = clone $allTransactions;
        $SIDAFMRCountJR = $SIDAFMRCountJRuery
            ->join('resp_codes as rc62', 'transactions.resp_center', '=', 'rc62.rc_code')
            ->where('rc62.rc', '=', '130')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $SIDAFMRCountPOQuery = clone $allTransactions;
        $SIDAFMRCountPO = $SIDAFMRCountPOQuery
            ->join('resp_codes as rc63', 'transactions.resp_center', '=', 'rc63.rc_code')
            ->join('order as o31', 'transactions.order_slug', '=', 'o31.slug')
            ->where('rc63.rc', '=', '130')
            ->where('o31.ref_book', '=', 'PO')
            ->whereYear('o31.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o31.total_gross');
        $SIDAFMRCountJOQuery = clone $allTransactions;
        $SIDAFMRCountJO = $SIDAFMRCountJOQuery
            ->join('resp_codes as rc64', 'transactions.resp_center', '=', 'rc64.rc_code')
            ->join('order as o32', 'transactions.order_slug', '=', 'o32.slug')
            ->where('rc64.rc', '=', '130')
            ->where('o32.ref_book', '=', 'JO')
            ->whereYear('o32.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o32.total_gross');

        $SIDARDCountQuery = clone $allTransactions;
        $SIDARDCount = $SIDARDCountQuery
            ->join('resp_codes as rc65', 'transactions.resp_center', '=', 'rc65.rc_code')
            ->where('rc65.rc', '=', '140')
            ->where('transactions.ref_book', '=', 'PR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $SIDARDCountJRQuery = clone $allTransactions;
        $SIDARDCountJR = $SIDARDCountJRQuery
            ->join('resp_codes as rc66', 'transactions.resp_center', '=', 'rc66.rc_code')
            ->where('rc66.rc', '=', '140')
            ->where('transactions.ref_book', '=', 'JR')
            ->whereNull('transactions.cancelled_at')
            ->sum('transactions.abc');
        $SIDARDCountPOQuery = clone $allTransactions;
        $SIDARDCountPO = $SIDARDCountPOQuery
            ->join('resp_codes as rc67', 'transactions.resp_center', '=', 'rc67.rc_code')
            ->join('order as o33', 'transactions.order_slug', '=', 'o33.slug')
            ->where('rc67.rc', '=', '140')
            ->where('o33.ref_book', '=', 'PO')
            ->whereYear('o33.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o33.total_gross');
        $SIDARDCountJOQuery = clone $allTransactions;
        $SIDARDCountJO = $SIDARDCountJOQuery
            ->join('resp_codes as rc68', 'transactions.resp_center', '=', 'rc68.rc_code')
            ->join('order as o34', 'transactions.order_slug', '=', 'o34.slug')
            ->where('rc68.rc', '=', '140')
            ->where('o34.ref_book', '=', 'JO')
            ->whereYear('o34.date', '=', $reportYear)
            ->whereNull('transactions.cancelled_at')
            ->sum('o34.total_gross');

        $obj = [(object)['name'=>'OB', 'count'=>$OBCount, 'countJR'=>$OBCountJR, 'countPO'=>$OBCountPO, 'countJO'=>$OBCountJO],
            (object)['name'=>'IAD', 'count'=>$IADCount, 'countJR'=>$IADCountJR, 'countPO'=>$IADCountPO, 'countJO'=>$IADCountJO],
            (object)['name'=>'OA', 'count'=>$OACount, 'countJR'=>$OACountJR, 'countPO'=>$OACountPO, 'countJO'=>$OACountJO],
            (object)['name'=>'LEGAL', 'count'=>$LEGALCount, 'countJR'=>$LEGALCountJR, 'countPO'=>$LEGALCountPO, 'countJO'=>$LEGALCountJO],
            (object)['name'=>'PPSPD', 'count'=>$PPSPDCount, 'countJR'=>$PPSPDCountJR, 'countPO'=>$PPSPDCountPO, 'countJO'=>$PPSPDCountJO],
            (object)['name'=>'AFD-LM', 'count'=>$AFDLMCount, 'countJR'=>$AFDLMCountJR, 'countPO'=>$AFDLMCountPO, 'countJO'=>$AFDLMCountJO],
            (object)['name'=>'AFD-VIS', 'count'=>$AFDVISCount, 'countJR'=>$AFDVISCountJR, 'countPO'=>$AFDVISCountPO, 'countJO'=>$AFDVISCountJO],
            (object)['name'=>'RDE-LM', 'count'=>$RDELMCount, 'countJR'=>$RDELMCountJR, 'countPO'=>$RDELMCountPO, 'countJO'=>$RDELMCountJO],
            (object)['name'=>'RDE-VIS', 'count'=>$RDEVISCount, 'countJR'=>$RDEVISCountJR, 'countPO'=>$RDEVISCountPO, 'countJO'=>$RDEVISCountJO],
            (object)['name'=>'RD-LM', 'count'=>$RDLMCount, 'countJR'=>$RDLMCountJR, 'countPO'=>$RDLMCountPO, 'countJO'=>$RDLMCountJO],
            (object)['name'=>'RD-VIS', 'count'=>$RDVISCount, 'countJR'=>$RDVISCountJR, 'countPO'=>$RDVISCountPO, 'countJO'=>$RDVISCountJO],
            (object)['name'=>'GAD', 'count'=>$GADCount, 'countJR'=>$GADCountJR, 'countPO'=>$GADCountPO, 'countJO'=>$GADCountJO],
            (object)['name'=>'SIDA-BFP', 'count'=>$SIDABFPCount, 'countJR'=>$SIDABFPCountJR, 'countPO'=>$SIDABFPCountPO, 'countJO'=>$SIDABFPCountJO],
            (object)['name'=>'SIDA-SCP', 'count'=>$SIDASCPCount, 'countJR'=>$SIDASCPCountJR, 'countPO'=>$SIDASCPCountPO, 'countJO'=>$SIDASCPCountJO],
            (object)['name'=>'SIDA-HRD', 'count'=>$SIDAHRDCount, 'countJR'=>$SIDAHRDCountJR, 'countPO'=>$SIDAHRDCountPO, 'countJO'=>$SIDAHRDCountJO],
            (object)['name'=>'SIDA- FMR', 'count'=>$SIDAFMRCount, 'countJR'=>$SIDAFMRCountJR, 'countPO'=>$SIDAFMRCountPO, 'countJO'=>$SIDAFMRCountJO],
            (object)['name'=>'SIDA-R&D', 'count'=>$SIDARDCount, 'countJR'=>$SIDARDCountJR, 'countPO'=>$SIDARDCountPO, 'countJO'=>$SIDARDCountJO]];
        return $obj;
        /*return ['IAD'=>$IADCount, 'OA'=>$OACount, 'LEGAL'=>$LEGALCount, 'PPSPD'=>$PPSPDCount, 'AFD-LM'=>$AFDLMCount, 'AFD-VIS'=>$AFDVISCount,
            'RDE-LM'=>$RDELMCount, 'RDE-VIS'=>$RDEVISCount, 'RD-LM'=>$RDLMCount, 'RD-VIS'=>$RDVISCount, 'GAD'=>$GADCount,
            'SIDA-BFP'=>$SIDABFPCount, 'SIDA-SCP'=>$SIDASCPCount, 'SIDA-HRD'=>$SIDAHRDCount, 'SIDA- FMR'=>$SIDAFMRCount, 'SIDA-R&D'=>$SIDARDCount];*/
    }

    private function getTransByDept(){
        $reportYear = \App\Swep\Helpers\Helper::getSetting('dashboard_report_year')->int_value;
        $userProjectId = Auth::user()->project_id;
        $allTransactions = DB::table('transactions')
            ->where(function ($query) use ($reportYear) {
                $query->whereYear('transactions.date', '=', $reportYear)
                    ->orWhereYear('transactions.created_at', '=', $reportYear);
            })
            ->whereIn('ref_book', ['PR', 'JR'])
            ->where('transactions.project_id', '=', $userProjectId);
        $OBCountClone = clone $allTransactions;
        $OBCount = $OBCountClone
            ->join('resp_codes as rc1', 'transactions.resp_center', '=', 'rc1.rc_code')
            ->where('rc1.rc', '=', '010')
            ->count('transactions.id');

        $IADCountClone = clone $allTransactions;
        $IADCount = $IADCountClone
            ->join('resp_codes as rc2', 'transactions.resp_center', '=', 'rc2.rc_code')
            ->where('rc2.rc', '=', '020')
            ->count('transactions.id');

        $OACountClone = clone $allTransactions;
        $OACount = $OACountClone
            ->join('resp_codes as rc3', 'transactions.resp_center', '=', 'rc3.rc_code')
            ->where('rc3.rc', '=', '030')
            ->count('transactions.id');

        $LEGALCountClone = clone $allTransactions;
        $LEGALCount = $LEGALCountClone
            ->join('resp_codes as rc4', 'transactions.resp_center', '=', 'rc4.rc_code')
            ->where('rc4.rc', '=', '040')
            ->count('transactions.id');

        $PPSPDCountClone = clone $allTransactions;
        $PPSPDCount = $PPSPDCountClone
            ->join('resp_codes as rc5', 'transactions.resp_center', '=', 'rc5.rc_code')
            ->where('rc5.rc', '=', '050')
            ->count('transactions.id');

        $AFDLMCountClone = clone $allTransactions;
        $AFDLMCount = $AFDLMCountClone
            ->join('resp_codes as rc6', 'transactions.resp_center', '=', 'rc6.rc_code')
            ->where('rc6.rc', '=', '060')
            ->count('transactions.id');

        $AFDVISCountClone = clone $allTransactions;
        $AFDVISCount = $AFDVISCountClone
            ->join('resp_codes as rc7', 'transactions.resp_center', '=', 'rc7.rc_code')
            ->where('rc7.rc', '=', '065')
            ->count('transactions.id');

        $RDELMCountClone = clone $allTransactions;
        $RDELMCount = $RDELMCountClone
            ->join('resp_codes as rc8', 'transactions.resp_center', '=', 'rc8.rc_code')
            ->where('rc8.rc', '=', '070')
            ->count('transactions.id');

        $RDEVISCountClone = clone $allTransactions;
        $RDEVISCount = $RDEVISCountClone
            ->join('resp_codes as rc9', 'transactions.resp_center', '=', 'rc9.rc_code')
            ->where('rc9.rc', '=', '075')
            ->count('transactions.id');

        $RDLMCountClone = clone $allTransactions;
        $RDLMCount = $RDLMCountClone
            ->join('resp_codes as rc10', 'transactions.resp_center', '=', 'rc10.rc_code')
            ->where('rc10.rc', '=', '080')
            ->count('transactions.id');

        $RDVISCountClone = clone $allTransactions;
        $RDVISCount = $RDVISCountClone
            ->join('resp_codes as rc11', 'transactions.resp_center', '=', 'rc11.rc_code')
            ->where('rc11.rc', '=', '085')
            ->count('transactions.id');

        $GADCountClone = clone $allTransactions;
        $GADCount = $GADCountClone
            ->join('resp_codes as rc12', 'transactions.resp_center', '=', 'rc12.rc_code')
            ->where('rc12.rc', '=', '090')
            ->count('transactions.id');

        $SIDABFPCountClone = clone $allTransactions;
        $SIDABFPCount = $SIDABFPCountClone
            ->join('resp_codes as rc13', 'transactions.resp_center', '=', 'rc13.rc_code')
            ->where('rc13.rc', '=', '100')
            ->count('transactions.id');

        $SIDASCPCountClone = clone $allTransactions;
        $SIDASCPCount = $SIDASCPCountClone
            ->join('resp_codes as rc14', 'transactions.resp_center', '=', 'rc14.rc_code')
            ->where('rc14.rc', '=', '110')
            ->count('transactions.id');

        $SIDAHRDCountClone = clone $allTransactions;
        $SIDAHRDCount = $SIDAHRDCountClone
            ->join('resp_codes as rc15', 'transactions.resp_center', '=', 'rc15.rc_code')
            ->where('rc15.rc', '=', '120')
            ->count('transactions.id');

        $SIDAFMRCountClone = clone $allTransactions;
        $SIDAFMRCount = $SIDAFMRCountClone
            ->join('resp_codes as rc16', 'transactions.resp_center', '=', 'rc16.rc_code')
            ->where('rc16.rc', '=', '130')
            ->count('transactions.id');

        $SIDARDCountClone = clone $allTransactions;
        $SIDARDCount = $SIDARDCountClone
            ->join('resp_codes as rc17', 'transactions.resp_center', '=', 'rc17.rc_code')
            ->where('rc17.rc', '=', '140')
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