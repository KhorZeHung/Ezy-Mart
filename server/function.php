<?php

function checkVar($var, $value){
    if(empty($var)){
        echo "Please fill in ". $value;
        exit();
    }
    return $var;
}

//Check whether the email is valid
function emailValidation($email){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "Invalid email";
        exit();
    } return $email;
}

//Check whether the phone number is valid
function phoneValidation($phone){    
    if (!preg_match("/^01[0-9]{1}(-| )?[0-9]{3,4}(-| )?[0-9]{4}$/", $phone)){
        echo "Invalid phone number";
        exit();
    } 
    return $phone;
}

function passwordValidation($password){
    if(strlen($password) < 8){
        echo "Password need atleast 8 character";
        exit();
    }
    return $password;
}

function cutString($value, $length){
    if(strlen($value) > $length){
      $value = substr($value, 0, $length - 3) . "...";
    }
    return $value;
  }

  
?>