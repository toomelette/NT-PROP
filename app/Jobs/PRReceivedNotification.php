<?php

namespace App\Jobs;

use App\Models\CronLogs;
use App\Models\Transactions;
use App\Swep\Helpers\Arrays;
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
    public function __construct($to,$subject,$body)
    {
        parent::__construct();
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->mail->addAddress($this->to);
        $this->mail->Subject = $this->subject;
        $this->mail->Body = $this->body;

        if($this->mail->send()){
            $s = new CronLogs();
            $s->log = 'Email sent.';
            $s->type = 1;
            $s->save();
        }else{
            $this->release(10);
        }
    }
}
