<?php
    if(!isset($_COOKIE['user_id'])){
        header("Location: ../../customer/index.php?login=1");
        die();
    }

    if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST['email']) || !isset($_POST['name']) || !isset($_POST['phone'])) {
        echo "unauthorize access 1";
        exit();
    }

    include_once("../function.php");
    include_once("../dataConnect.php");
    $email = emailValidation(checkVar($_POST['email'], "email"));
    $phone = phoneValidation(checkVar($_POST['phone'], "phone number"));
    $name = checkVar($_POST['name'], "name");

    $checkUniqueQuery = "SELECT * FROM user WHERE (user_email = ? OR user_phone = ?) AND user_id != ?;";

    if($stmt = $conn->prepare($checkUniqueQuery)){
        $stmt->bind_param("ssi", $email, $phone,$_COOKIE['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $crash = "Repeat ";
            while($data = $result->fetch_assoc()){
                if($data['user_email'] == $email){
                    $crash .= " email";
                }
                if($data['user_phone'] == $phone){
                if(strlen($crash) > 9){
                    $crash .= " and";
                }
                $crash .= " phone number";
                }
            }
            $crash .= " in system";
            echo $crash;
        } else{
            $stmt->data_seek(0);

            $updateQuery = "UPDATE user SET user_email = ?, user_phone = ?, user_name = ? WHERE user_id = ?;";
            if($stmt = $conn->prepare($updateQuery)){
                $stmt->bind_param("sssi", $email, $phone, $name, $_COOKIE['user_id']);
                $stmt->execute();
                echo "update successful";
            } else{
                echo "something went wrong";
            }
        }
        $stmt->close();
        $conn->close();
    }else{
        echo "something went wrong";
    }
?>