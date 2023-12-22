<?php

include_once("../dataConnect.php");
include_once("../function.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_COOKIE['reset_password']) || (isset($_POST['resetPassword']) && isset($_COOKIE['user_id'])))) {
    if(isset($_POST['pin']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])){
        // Retrieve PIN and new password from form
        $pin = checkVar($_POST['pin'], "6 pin number");
        $new_password = checkVar($_POST['new_password'], "new password");
        $confirm_password = checkVar($_POST['confirm_password'], "repeat password");
        
        // Verify if new password matches confirm password
        if ($new_password !== $confirm_password) {
            echo "passwords do not match";
            exit();
        } 
        
        if(strlen($new_password) < 8){
            echo "password need atleast 8 character";
            exit();
        }
        $selectUserQuery = !isset($_POST['resetPassword']) ? "SELECT user_password FROM user WHERE user_email = ?;" : "SELECT user_password FROM user WHERE user_id = ?;";
        if ($stmt = $conn->prepare($selectUserQuery)) {
            if(isset($_POST['resetPassword'])){
                $stmt->bind_param("i", $_COOKIE['user_id']);
            }else{
                $stmt->bind_param("s", $_COOKIE['reset_password']);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
    
            // Compare the entered PIN with the stored PIN from the database
            if (password_verify($pin, $row['user_password'])) {
                $stmt->data_seek(0);
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $updatePasswordQuery = !isset($_POST['resetPassword']) ? "UPDATE user SET user_password = ? WHERE user_email = ?;" : "UPDATE user SET user_password = ? WHERE user_id = ?;";
                if ($stmt->prepare($updatePasswordQuery)) {
                    if(isset($_POST['resetPassword'])){
                        $stmt->bind_param("si", $hashed_password, $_COOKIE['user_id']);
                    }else{
                        $stmt->bind_param("ss", $hashed_password, $_COOKIE['reset_password']);
                    }
                    $stmt->execute();
                    setcookie("reset_password", "", time() - 60*100, "/");
                    echo "password reset successful";
                } else {
                    echo "something went wrong";
                }
            } else {
                if(isset($_POST['resetPassword'])){
                    echo "password incorrect";
                }else{
                    echo "6 pin number incorrect";
                }
            }
            $stmt->close();
            $conn->close();
        } else {
            echo "something went wrong";
        }
        
    } else{
        echo "unauthorize access";
    }
}else{
    echo "unauthorize access";
}
?>