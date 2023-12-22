<?php

include_once("../dataConnect.php");
session_start();
// Check if the user is logged in. Redirect to login page if not.
if (!isset($_COOKIE['user_id'])) {
    echo "login required";
    exit();
} 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id']) && !empty($_POST['product_id']) && isset($_POST['cart_quantity'])) {

    $user_id = $_COOKIE['user_id'];
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['cart_quantity'];

    // Check if the product is already in the user's cart
    $check_cart_query = "SELECT cart_quantity FROM cart WHERE user_id = ? AND product_id = ?;";
    
    if($stmt = $conn->prepare($check_cart_query)){
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $oriQuantity = $result->fetch_assoc();
            $stmt->data_seek(0);
            if($product_quantity){
                //update cart quantity
                $update_cart_query = "UPDATE cart set cart_quantity = ? WHERE user_id = ? AND product_id = ?;";
                if($stmt = $conn->prepare($update_cart_query)){
                    $stmt->bind_param("iii", $product_quantity, $user_id, $product_id);
                    if(!$stmt->execute()){
                        echo $stmt->error;
                    }else{
                        echo "item update successful";
                    }
                }else{
                    echo "Something when wrong";
                }
            }else{
                //delete the cart item for the user
                $delete_cart_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?;";

                if($stmt = $conn->prepare($delete_cart_query)){
                    $stmt->bind_param("ii", $user_id, $product_id);
                    if(!$stmt->execute()){
                        echo $stmt->error;
                    }else{
                        $_SESSION['cart']--;
                        echo "item delete successful";
                    }
                }else{
                    echo "Something when wrong";
                }
            }
        } else if($product_quantity){
            $stmt->data_seek(0);
            //insert new item to user cart
            $insert_cart_query = "INSERT INTO cart (cart_quantity, user_id, product_id) VALUES (?, ?, ?)";

                if($stmt = $conn->prepare($insert_cart_query)){
                    $stmt->bind_param("iii", $product_quantity, $user_id, $product_id);
                    if(!$stmt->execute()){
                        echo $stmt->error;
                    }else{
                        if(isset($_SESSION['cart'])){
                            $_SESSION['cart']++;
                        }else{
                            $_SESSION['cart'] = 1;
                        }
                        echo "item insert successful";
                    }
                }else{
                    echo "Something when wrong";
                }
        } else{
            echo "please enter proper quantity";
        }

        $conn->close();
    }else{
        echo "Something when wrong";
    }
} else{
    echo "Unauthorize access";
}

?>