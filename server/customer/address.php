<?php

include_once("../dataConnect.php");
include_once("../function.php");


if (!isset($_COOKIE['user_id'])) {
    echo "login required";
    exit();
} 

function uniqueAddressName($address_name){
    global $conn;
    $checkAddressQuery = "SELECT address_id FROM `address` WHERE address_name = ? AND user_id = ?;";
    $user_id = $_COOKIE['user_id'];
    if($stmt = $conn->prepare($checkAddressQuery)){
        $stmt->bind_param("si", $address_name, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_COOKIE['user_id'];

    if (isset($_POST['deleteAddress']) && isset($_POST['address_id'])){
        $address_id = checkVar($_POST['address_id'], "something");
        $deleteAddressQuery = "DELETE FROM `address` WHERE address_id = ? AND user_id = ?;";
        if($stmt = $conn->prepare($deleteAddressQuery)){
            $stmt->bind_param("ii", $address_id, $user_id);
            if($stmt->execute()){
                echo "address delete successful";
            }else{
                echo "address cannnot be delete";
            }
            $stmt->close();
        } else{ 
            echo "unauthorize access";
        }
    }

    if(isset($_POST['address_name']) && isset($_POST['address'])){
        $address_name = checkVar($_POST['address_name'], 'address name');
        $address = checkVar($_POST['address'], 'address');
        $uniqueAddress = uniqueAddressName($address_name);
        if(strlen($address_name) > 10){
            echo "address name should below 10 character";
        }else{
            if(isset($_POST['insertAddress'])){
                if($uniqueAddress){
                    echo "repeat address name";
                }else{
                    $insertAddressQuery = "INSERT INTO address (address_name, `address`, user_id) VALUES (?, ?, ?);";
                    if($stmt = $conn->prepare($insertAddressQuery)){
                        $stmt->bind_param("ssi", $address_name, $address, $user_id);
                        $stmt->execute();
                        echo "address added successful";
                        $stmt->close();
                    } else{ 
                        echo "unauthorize access";
                    }
                }
            }else if (isset($_POST['updateAddress']) && isset($_POST['address_id'])){
                $address_id = checkVar($_POST['address_id'], 'something');
                if(empty($uniqueAddress) || $uniqueAddress['address_id'] == $address_id){
                    $updateAddressQuery = "UPDATE address SET address_name = ?, `address` = ? WHERE address_id = ? AND user_id = ?;";
                    if($stmt = $conn->prepare($updateAddressQuery)){
                        $stmt->bind_param("ssii", $address_name, $address, $address_id, $user_id);
                        $stmt->execute();
                        echo "address update successful";
                        $stmt->close();
                    } else{ 
                        echo "unauthorize access";
                    }
                }else{
                    echo "repeat address name";
                }
            } else{
                echo "unauthorize access";
            }
        }
    } 
    
} else{
    echo "unauthorize access";
}

$conn->close();