<?php 
namespace App\Helpers;
use PHPMailer\PHPMailer\PHPMailer;

class MailHelper
{	
	/**
	 * send mail
	 * @param  array $from    ['from'=>from,'title'=>title]
	 * @param  array $address ['address'=>address,'title'=>title]
	 * @param  string $subject 
	 * @param  string $body    
	 * @param  string $altBody 
	 * @return 
	 */
	public static function send($title,$address,$subject,$body,$altBody=''){
		$mail = new PHPMailer(true);

        try {
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            //Server settings
            // $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = env('MAIL_HOST');  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = env('MAIL_USERNAME');                 // SMTP username
            $mail->Password = env('MAIL_PASSWORD');                           // SMTP password
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = env('MAIL_PORT');                                    // TCP port to connect to

            //Recipients
            $mail->setFrom(env('MAIL_USERNAME'), $title);
             $mail->addAddress($address);     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altBody;

            $mail->send();
            return 'Message has been sent';
        } catch (\Exception $e) {
            // \Log::info($e);
            return 'Message could not be sent!';
        }
	}
}