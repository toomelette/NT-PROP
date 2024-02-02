<?php


namespace App\Jobs;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mail
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer();
        if(env('APP_DEBUG') == true){
            $this->mail->SMTPDebug = true;
        }else{
            $this->mail->SMTPDebug = SMTP::DEBUG_OFF;
        }
        $this->mail->isSMTP();
        $this->mail->Host = 'mail.sra.gov.ph';             //  smtp host
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'no-reply@sra.gov.ph';   //  sender username
        $this->mail->Password = 'no-reply@sra.gov.ph';       // sender password
        $this->mail->SMTPSecure = 'ssl';                  // encryption - ssl/tls
        $this->mail->Port = 465;                          // port - 587/465
        $this->mail->From = 'no-reply@sra.gov.ph';
        $this->mail->FromName = 'SRA - PPBTMS';

        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );


//        $this->mail->setFrom('swep.afd@sra.gov.ph', 'SRA Visayas - PPBTMS');
        $this->mail->addEmbeddedImage(public_path().'/images/email/ppbtms.png','sra');
        $this->mail->isHTML(true);
    }
}