<?php
include_once("../dataConnect.php");
include_once("../function.php");
session_start();

// Check if the user is logged in. Redirect to login page if not.
if (!isset($_COOKIE['user_id'])) {
    echo "login required";
    exit();
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(!isset($_POST['address_id']) || !isset($_POST['payment_option_id']) || !isset($_POST['subtotal']) || !isset($_POST['productList'])){
        echo "Something when wrong";
    }else{
        $user_id = $_COOKIE['user_id'];
        $address_id = checkVar($_POST['address_id'], "address");
        $payment_option_id = checkVar($_POST['payment_option_id'], "payment method");
        $productList = checkVar($_POST['productList'], "product");
        $date = new DateTime();
        $payment_datetime = $date->format('Y-m-d H:i:s');
        $total_price = checkVar($_POST['subtotal'], "something");

        $checkAddress = "SELECT `address` FROM address WHERE user_id = '$user_id' AND address_id = '$address_id';";
        $result = $conn->query($checkAddress);
        if($result->num_rows != 1){
            echo "wrong address";
            $conn->close();
            exit();
        }
        

        // Check if the product is already in the user's cart
        $add_order_query = "INSERT INTO `order` (user_id, payment_datetime, address_id, total_price, payment_id)
                           VALUES (?, ?, ?, ?, ?)";
        if($stmt = $conn->prepare($add_order_query)){
            $stmt->bind_param("isidi", $user_id, $payment_datetime, $address_id, $total_price, $payment_option_id);
            $stmt->execute();
            $stmt->get_result();
            $order_id = $stmt->insert_id;

            foreach ($productList as $key => $product) {
                $quantity = $product['quantity'];
                $unitPrice = $product['unitPrice'];
                $product_id = $product['product_id'];
                $sql_orderlist = "INSERT INTO Orderlist (Olist_quantity, product_id, order_id, unit_price)
                                    VALUES ('$quantity', '$product_id', '$order_id', '$unitPrice')";
                $sql_cartDelete = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id';";
                if(!$conn->query($sql_orderlist) || !$conn->query($sql_cartDelete)){
                    echo $conn->error;
                    $deleteOrderList = "DELETE FROM Orderlist WHERE order_id = '$order_id';";
                    $conn->query($deleteOrderList);
                    $deleteOrder = "DELETE FROM order WHERE order_id = '$order_id';";
                    $conn->query($deleteOrder);
                    exit();
                }
            }
            $_SESSION['cart'] -= count($productList);
            echo "Order added successful"; 
            $stmt->close();
        } else{
            echo "something went wrong";
        }
    }

} else{
    echo "Unauthorize access";
}

// Close the database connection
$conn->close();
?>