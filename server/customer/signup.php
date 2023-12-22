<?php
//Include the database configuration file
include_once("../dataConnect.php");
include_once("../function.php");

//Get the values from the HTML form
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['signUpName']) && isset($_POST['signUpEmail']) && isset($_POST['signUpPNum']) && isset($_POST['signUpPassword']) && isset($_POST['signUpRPassword'])){

    if(!empty($_POST['signUpName']) && !empty($_POST['signUpEmail']) && !empty($_POST['signUpPassword']) && !empty($_POST['signUpRPassword']) && !empty($_POST['signUpPNum'])){
        $name = $_POST['signUpName'];
        $email = emailValidation($_POST['signUpEmail']);
        $phone = phoneValidation($_POST['signUpPNum']);
        $address = $_POST['signUpDAddress'];
        $password = passwordValidation($_POST['signUpPassword']);
        $confirm_password = $_POST['signUpRPassword'];
        

        $selectEmailQuery = "SELECT user_id FROM user WHERE user_email=?;";
        $stmt = $conn->prepare($selectEmailQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            echo "email cannot be use";
            exit();
        }

        $stmt->data_seek(0);

        if($password !== $confirm_password){
            echo "password do not match";
            exit();
        }
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insertUserQuery = "INSERT INTO user (user_name, user_password, user_email, user_phone) VALUES (?, ?, ?, ?);";
        if($stmt = $conn->prepare($insertUserQuery)){
            $stmt->bind_param("ssss", $name, $hashed_password, $email, $phone);
            $stmt->execute();
            if(!empty($address)){
                $stmt->get_result();
                $user_id = $stmt->insert_id;
                $address_name = "default";
                $stmt->data_seek(0);
                $insertAddress = "INSERT INTO address(address_name, `address`, user_id) VALUES (?, ?, ?);";
                if($stmt->prepare($insertAddress)){
                    $stmt->bind_param("ssi", $address_name, $address, $user_id);
                    if(!$stmt->execute()){
                        echo $stmt->error;
                    }

                }else{
                    echo "something went wrong";
                }
            }
            echo "sign up successful";
        }else{
            echo "something went wrong";
        }
    } else{
        echo "please fill up all field";
    }
}else{
    echo "unauthorize access";
}
?>
