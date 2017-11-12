<?php

require_once('vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function notifyTutor($data, $zip_path) {
    $config = require('../config.php');
    $mail = new PHPMailer(true);
    try {
        // $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $config['smtp']['host'];  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $config['smtp']['user'];                 // SMTP username
        $mail->Password = $config['smtp']['pass'];                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;// TCP port to connect to
        $mail->CharSet = 'UTF-8';

        //Recipients
        $mail->setFrom($config['smtp']['from'], $config['smtp']['from']);
        $tutorial = null;
        foreach($config['tutorials'] as $t) {
            if ($t['id'] == $data->tutorial) {
                $tutorial = $t;
                break;
            }
        }
        $mail->addAddress($tutorial['mail']);     // Add a recipient

        //Attachments
        $mail->addAttachment($zip_path);         // Add attachments

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Neue Abgabe für ' . $tutorial["tutor"];
        $mail->Body    = "<p>Hallo " . $tutorial['tutor'] . "!</p>" .
            "<p>Im Anhang findest du die neue Abgabe der Matrikelnummern " . implode(', ', $data->matriculations) . ".</p>" .
            "<p>Viele Grüße!<br />HAL</p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}