<?php
namespace Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper {

    public static function sendOtpEmail($toEmail, $toName, $otp) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = "opololinda@gmail.com";
            $mail->Password   = "rdfx yfyg sxme ojra"; // App password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom("opololinda@gmail.com", "VolunTrack Team");
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = 'Your Verification Code';
            $mail->Body    = "Hello <b>$toName</b>,<br>Your OTP code is: <b>$otp</b><br>This code expires in 5 minutes.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer failed: " . $e->getMessage());
            return false;
        }
    }
    
    public static function sendWelcomeEmail($toEmail, $toName) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = "opololinda@gmail.com";
            $mail->Password   = "rdfx yfyg sxme ojra";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom("opololinda@gmail.com", "VolunTrack Team");
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = 'Welcome to VolunTrack';
            $mail->Body    = "Hello <b>$toName</b>, welcome to VolunTrack! <b>Your Journey to Giving Back Starts Here.</b>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer failed: " . $e->getMessage());
            return false;
        }
    }
}