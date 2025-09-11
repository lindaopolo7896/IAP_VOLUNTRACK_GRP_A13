<?php
namespace Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper {
    public static function sendWelcomeEmail($toEmail, $toName) {
        try {
            // Create PHPMailer instance
            $mail = new PHPMailer(true);

            // Server settings
            $mail->SMTPDebug = 0; // change to 2 for debugging
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = "opololinda@gmail.com"; // Gmail or .env
            $mail->Password   =  "rdfx yfyg sxme ojra";   // Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Recipients
            $mail->setFrom("opololinda@gmail.com", "VolunTrack Team");

           
            $mail->addAddress($toEmail, $toName);


            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Welcome to VolunTrack';
            $mail->Body    = "Hello <b>$toName</b>, welcome to VolunTrack! <b>Your Journey to Giving Back Starts Here.</b>";

            $mail->send();
            error_log("Welcome email sent to $toEmail");
            return true;

        } catch (Exception $e) {
            // Log error but do not throw
            error_log("Mailer failed: " . $e->getMessage());
            return false;
        }
    }
}
