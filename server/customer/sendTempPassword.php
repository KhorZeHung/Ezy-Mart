<?php
require '../../assets/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

include_once("../dataConnect.php");
include_once("../function.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_COOKIE['user_id'])) {
    if(isset($_POST['getPinNum']) && isset($_POST['email'])){
        $email = checkVar($_POST["email"], "email");
        
        // Generate 6-digit random PIN
        $pin = mt_rand(100000, 999999);
        $hashed_password = password_hash($pin, PASSWORD_DEFAULT);

        // Update the user's password field in the database with the new PIN
        $updateTempPword = "UPDATE user SET user_password = ? WHERE user_email = ?;";
        if($stmt = $conn->prepare($updateTempPword)){
            $stmt->bind_param("ss", $hashed_password, $email);
            $stmt->execute();
            if($stmt->affected_rows == 1){
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
                    $mail->addAddress($email);

                    $mail->Subject = "Password Reset Verification";
                    $mail->Body = "Your verification PIN is: $pin.";
                    $mail->isHTML(true);

                    // Check if email was sent successfully
                    if ($mail->send()) {
                        setcookie("reset_password", $email, time() + (60*5), "/");
                        echo 'send successful';
                    } else {
                        echo "something when wrong";
                    }
                } catch (Exception $e) {
                    echo 'Error: '. $e;
                }
            }else{
                echo "user not found";
            }
            $stmt->close();
            $conn->close();
        }else{
            echo "something when wrong";
        }
    }else {
        echo "unauthorize access";
    }
} else {
    echo "unauthorize access";
}
?>