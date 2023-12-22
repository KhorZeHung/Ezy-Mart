<?php
//Include the database configuration file
include_once("../dataConnect.php");
include_once("../function.php");

session_start();

//Get the values from the HTML form
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['loginEmail']) && isset($_POST['loginPassword']) && !isset($_COOKIE['user_id'])){
    $email = checkVar($_POST['loginEmail'], "email");
    $password = checkVar($_POST['loginPassword'], "password");

    // Check if the user's credentials are valid
    $selectEmailQuery = "SELECT user_id, user_password FROM user WHERE user_email=?;";
    $stmt = $conn->prepare($selectEmailQuery);
    $stmt->bind_param("s", $email);
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows != 1){
            echo "unauthorize access";
        }else{
            $data = $result->fetch_assoc();
            if(!password_verify($password, $data['user_password'])){
                //wrong password
                echo "unauthorize access";
            }else if (!setcookie("user_id", $data['user_id'], time() + (60*60*2), "/")){
                //set 2 hours cookie fail
                echo "cannot set cookie";
            } else{
                $countCart = "SELECT count(*) FROM cart WHERE user_id = ".$data['user_id'].";";
                $result = $conn->query($countCart);
                $data = $result->fetch_assoc();
                $_SESSION['cart'] = $data['count(*)'];    
                echo "login successful";
            }            
        }
    }else{
        echo "something went wrong";
    }
    $stmt->close();
} else{
    echo "unauthorize access";
}

$conn->close();
?>
