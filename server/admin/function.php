<?php

require "../../assets/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function checkVar($var, $value){
    if(empty($var)){
        echo json_encode(array('success' => false, 'detail' => "Please fill in ". $value));
        exit();
    }
    return $var;
}

function sendConfirmation($name, $email, $subject, $message){
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Gmail account details
        $mail->Username = "ezymart123@gmail.com";
        $mail->Password = "abcazjsnfkshmprp";

        // Sender and recipient details
        $mail->setFrom("ezymart123@gmail.com", "E-ZyStore");
        $mail->addAddress($email, $name);

        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->isHTML(true);

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}