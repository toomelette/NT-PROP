<?php


namespace App\Swep\Mail;


use PHPMailer\PHPMailer\PHPMailer;

class MailNotifierService
{
    public function sendMail($to,$subject,$body){
        $mail = new PHPMailer(true);
        try {

            // Email server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.googlemail.com';             //  smtp host
            $mail->SMTPAuth = true;
            $mail->Username = 'swep.afd@gmail.com';   //  sender username
            $mail->Password = 'yvksqzghcakrkahz';       // sender password
            $mail->SMTPSecure = 'ssl';                  // encryption - ssl/tls
            $mail->Port = 465;                          // port - 587/465

            $mail->setFrom('swep.afd@gmail.com', 'SRA Visayas - PPBTMS');
            $mail->addAddress($to);
//            $mail->addCC($request->emailCc);
//            $mail->addBCC($request->emailBcc);

            $mail->addReplyTo('sys.srawebportal@gmail.com', 'SWEP AFD');

            /* FOR FILE ATTACHMENTS
            if(isset($_FILES['emailAttachments'])) {
                for ($i=0; $i < count($_FILES['emailAttachments']['tmp_name']); $i++) {
                    $mail->addAttachment($_FILES['emailAttachments']['tmp_name'][$i], $_FILES['emailAttachments']['name'][$i]);
                }
            }
            */

            $mail->isHTML(true);                // Set email content format to HTML

            $mail->Subject = $subject;
            $mail->Body    = $body;

            // $mail->AltBody = plain text version of email body;

            if( !$mail->send() ) {
                return 'Mail not sent. : '.$mail->ErrorInfo;
            }

            else {
                return 'Email has been sent.';
            }

        } catch (\Exception $e) {
            abort(503,'Message could not be sent. : '.$e->getMessage());
        }
    }
}