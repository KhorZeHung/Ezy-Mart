<?php
//Include the database configuration file
include_once("../dataConnect.php");
include_once("./function.php");
//Get the values from the HTML form
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['loginEmail']) && isset($_POST['loginPassword']) && !isset($_COOKIE['admin_id'])){
    $email = checkVar($_POST['loginEmail'], "email");
    $password = checkVar($_POST['loginPassword'], "password");
    $return = array();
    // Check if the user's credentials are valid
    $selectEmailQuery = "SELECT admin_id, admin_password FROM admin WHERE admin_email=?;";
    $stmt = $conn->prepare($selectEmailQuery);
    $stmt->bind_param("s", $email);
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows != 1){
            $return = array('success' => false, 'detail' => "unauthorize access");
        }else{
            $data = $result->fetch_assoc();
            if(!password_verify($password, $data['admin_password'])){
                //wrong password
                $return = array('success' => false, 'detail' => "unauthorize access");
            }else if (!setcookie("admin_id", $data['admin_id'], time() + (60*60*9), "/")){
                //set 2 hours cookie fail
                $return = array('success' => false, 'detail' => "cannot set cookie");
            } else{
                $return = array('success' => true, 'detail' => "login successful");
            }            
        }
    }else{
        $return = array('success' => false, 'detail' => "something went wrong");
    }
    $stmt->close();
} else{
    $return = array('success' => false, 'detail' => "unauthorize access");
}

echo json_encode($return);

$conn->close();
?>
