<?php
if(!isset($_COOKIE['admin_id'])){
    header("Location: ../../admin/login.php");
    die();
}else if($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['orderStatus']) || empty($_POST['orderStatus']) || !isset($_POST['order_id']) || empty($_POST['order_id'])){
    $returnObj = array("success" => false, "detail" => "unauthorize access");
}else{
    include_once("../dataConnect.php");
    include_once("function.php");
    $orderStatus = $_POST['orderStatus'];
    $orderId = $_POST['order_id'];
    $adminId = $_COOKIE['admin_id'];

    $selectQuery = "SELECT * FROM `order` INNER JOIN user ON `order`.user_id = user.user_id WHERE `order`.order_id = $orderId;";
    $result = $conn->query($selectQuery);
    if($result->num_rows == 1){
        $data = $result->fetch_assoc();
        $email = $data['user_email'];
        $name = $data['user_name'];
        $orderInfo = $data;

        $selectOrderProduct = "SELECT * FROM orderlist INNER JOIN product ON orderlist.product_id = product.product_id WHERE order_id = $orderId;";
        $result = $conn->query($selectQuery);
        $orderProductInfo = array();
        while($data = $result->fetch_assoc()){
            $orderProductInfo[] = $data;
        }

        if($orderInfo['status'] !== $orderStatus){
            switch($orderStatus){
                case 1:
                    $sendStatus = sendConfirmation($name, $email, "Order Confirmed","<p><d>Order id : </d>$orderId</p>");
                    break;
                case 2:
                    $sendStatus = sendConfirmation($name, $email, "Order Delivering", "<p><d>Order id : </d>$orderId</p>");
                    break;
                case 3:
                    $sendStatus = sendConfirmation($name, $email, "Order Delivered", "<p><d>Order id : </d>$orderId</p>");
                    $updateQuery = "UPDATE `order` SET deliver_date = CURRENT_TIMESTAMP() WHERE order_id = $orderId;";
                    $conn->query($updateQuery);
                    break;
            }
                        
            if($sendStatus){
                $updateQuery = "UPDATE `order` SET status = ?, admin_id = ? WHERE order_id = ?;";
                if($stmt = $conn->prepare($updateQuery)){
                    $stmt->bind_param("iii", $orderStatus, $adminId, $orderId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $returnObj = array("success" => true, "detail" => "update successful");        
                }else{
                    $returnObj = array("success" => false, "detail" => "unauthorize access");
                }
            }else{
                $returnObj = array("success" => false, "detail" => "something wrong");
            }
        } else{
            $returnObj = array("success" => true, "detail" => "update successful");  
        }
    }
}

echo json_encode($returnObj);
?>