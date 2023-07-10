<?php


namespace App\Swep\Services;


use App\Jobs\PRReceivedNotification;
use App\Models\Transactions;
use App\Swep\BaseClasses\BaseService;
use App\Swep\Helpers\Arrays;
use App\Swep\Mail\MailNotifierService;
use Illuminate\Support\Carbon;

class TransactionService extends BaseService
{
    protected $mailNotifierService;
    public function __construct(MailNotifierService $mailNotifierService)
    {
        parent::__construct();
        $this->mailNotifierService = $mailNotifierService;
    }

    public function findBySlug($slug){
        $trans = Transactions::query()->where('slug','=',$slug)->first();
        if(empty($trans)){
            abort(503,'Transaction not found.');
        }
        return $trans;
    }

    public function receiveTransaction($request){
        $trans = $this->findBySlug($request->trans);
        $trans->received_at = Carbon::now();
        $trans = $this->findBySlug($request->trans);
        $trans->received_at = Carbon::now();
        $trans->user_received = \Auth::user()->user_id;
        $trans->is_locked = 1;

        //EMAIL DETAILS
        $to = 'gguance221@gmail.com';
        $subject = Arrays::acronym($trans->ref_book).' No. '.$trans->ref_no;
        $body = view('mailables.email_notifier.body-pr-receipt')->with(['transaction' => $trans])->render();

        if($trans->save()){
            PRReceivedNotification::dispatch($to,$subject,$body);
            return $trans->only('slug');
        }

    }

}