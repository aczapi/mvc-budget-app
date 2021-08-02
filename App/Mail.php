<?php

namespace App;

header('Content-type: text/html; charset=utf-8');

use App\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Load Composer's autoloader
// require_once '../vendor/autoload.php';

/**
 * Mail
 * 
 */

class Mail
{

  /**
   * Send a message
   * 
   * @param string $to Recipient
   * @param string $subject Subject
   * @param $text Text-only content of the message
   * @param string $hmtl HTML content of the message
   * 
   * @return mixed
   */
  public static function send($to, $subject, $text, $html)
  {
    $mail = new PHPMailer(TRUE);

    try {
      //Server settings
      // $mail->SMTPDebug = 2; 
      // Enable verbose debug output
      $mail->isSMTP();                                            // Set mailer to use SMTP
      $mail->Host = Config::HOST_SMTP;  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                                   // Enable SMTP authentication
      $mail->Username = Config::EMAIL_FROM;                     // SMTP username
      $mail->Password = Config::EMAIL_FROM_PASSWORD;                               // SMTP password
      $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, [ICODE]ssl[/ICODE] also accepted
      $mail->Port = 465;
      // $mail->CharSet = 'UTF-8';                                   // TCP port to connect to

      //Recipients
      $mail->setFrom(Config::EMAIL_FROM, 'Mailer');
      $mail->addAddress($to, '');     // Add a recipient


      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = $subject;
      $mail->Body    = $html;
      $mail->AltBody = $text;

      $mail->send();

      // echo 'Message has been sent';
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }
}
