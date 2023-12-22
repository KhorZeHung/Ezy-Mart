<?php

require "../../assets/vendor/autoload.php";
include_once "./function.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

if(!isset($_COOKIE['admin_id'])){
    header("Location : ../admin/login.php");
    die();
}

$return = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['replySubject'])&& isset($_POST['replyEmail'])&& isset($_POST['replyName']) && isset($_POST['message'])&& isset($_POST['replyId'])) {
    $subject = $_POST['replySubject'];
    $email = $_POST['replyEmail'];
    $name = $_POST['replyName'];
    $message = checkVar($_POST['message'], "message");
    $id = $_POST['replyId'];

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

        $mail->Subject = "Thanks you for contacting us";
        $mail->Body = "<p><b>Subject: </b>" . $subject . "</p><br>
        <p><b>Message: </b>" . $message . "</p>";
        $mail->isHTML(true);

        if($mail->send()){
            include_once("../dataConnect.php");
            $updateQuery = "UPDATE contact SET admin_id = ".$_COOKIE['admin_id']." WHERE contact_id = ".$id.";";
            $conn->query($updateQuery);
            if($conn->affected_rows <= 1){
               $return = array("success" => true, 'detail' => "send successful");
            } else{
                $return = array("success" => false, 'detail' => "something went wrong");
            }
            $conn->close();
        }else{
            $return = array("success" => false, 'detail' => "mail cannot send");
        }
    } catch (Exception $e) {
            $return = array("success" => false, 'detail' => "something went wrong");
    }
}else{
    $return = array("success" => false, 'detail' => "unauthorize access");
}

echo json_encode($return);

?>