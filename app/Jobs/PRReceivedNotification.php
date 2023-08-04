<?php

namespace App\Jobs;

use App\Models\CronLogs;
use App\Models\Transactions;
use App\Swep\Helpers\Arrays;
use App\Swep\Helpers\Helper;
use App\Swep\Mail\MailNotifierService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;

class PRReceivedNotification extends Mail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $to;
    public $subject;
    public $body;
    public $cc;
    public function __construct($to,$subject,$body,$cc = [])
    {
        parent::__construct();
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->cc = $cc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        if(Helper::getSetting('send_email_notification')->int_value == 1){
            if($this->to != null){
                $this->mail->addAddress($this->to);
                $this->mail->Subject = $this->subject;
                $this->mail->Body = $this->body;
                if(count($this->cc) > 0){
                    foreach ($this->cc as $cc){
                        $this->mail->addCC($cc);
                    }
                }
                if($this->mail->send()){
                    $s = new CronLogs();
                    $s->log = 'Email sent.';
                    $s->type = 1;
                    $s->save();
                }else{
                    $this->release(10);
                }
            }else{
                $s = new CronLogs();
                $s->log = 'Err: No TO address indicated. '.$this->subject;
                $s->save();
            }
        }
    }
}