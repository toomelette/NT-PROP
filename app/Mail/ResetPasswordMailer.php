<?php

namespace App\Mail;

use App\Models\MisRequestsEmailRecipients;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ResetPasswordMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $recipient;
    public $userSlug;
    public $verificationSlug;
    public function __construct($recipient,$userSlug,$verificationSlug)
    {

        $this->recipient = $recipient;
        $this->userSlug = $userSlug;
        $this->verificationSlug = $verificationSlug;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        return $this
            ->to($this->recipient)
            ->subject('Reset password - POMD')
            ->view('mailables.reset_password',['userSlug'=>$this->userSlug,'verificationSlug' => $this->verificationSlug]);
    }
}