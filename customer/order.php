<?php
include("./components/header.php");
if(!isset($_COOKIE['user_id'])){
    header('Location: index.php?login=1');
    die();
}

if(isset($_GET['order_id'])){
    include_once("../server/dataConnect.php");

    $selectOrder = "SELECT * FROM `order`as o INNER JOIN address as a ON o.address_id = a.address_id INNER JOIN payment ON o.payment_id = payment.payment_id WHERE o.order_id = ? AND o.user_id = ?;";
    if($stmt = $conn->prepare($selectOrder)){
        $stmt->bind_param("ii", $_GET['order_id'], $_COOKIE['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $orderInfo = array();
        if($result->num_rows == 1){
            $orderInfo = $result->fetch_assoc();
            $datetime = new DateTime($orderInfo['payment_datetime']);
            $datetime->setTimezone(new DateTimeZone('+0800'));
            $date = $datetime->format('H:i A, d-m-Y');
            $orderInfo['payment_datetime'] = $date;

            $datetime = new DateTime($orderInfo['deliver_date']);
            $datetime->setTimezone(new DateTimeZone('+0800'));
            $date = $datetime->format('H:i A, d-m-Y');
            $orderInfo['deliver_date'] = $date;

            $stmt->data_seek(0);
            $selectOrderProduct = "SELECT * FROM orderlist INNER JOIN product ON orderlist.product_id = product.product_id WHERE order_id = ?;";
            if($stmt = $conn->prepare($selectOrderProduct)){
                $stmt->bind_param("i", $_GET['order_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $orderProductInfo = array();
                if($result->num_rows > 0){
                    while($data = $result->fetch_assoc()){
                        $orderProductInfo[] = $data;
                    }
                } else{
                    header('Location: ./error.php');
                    die();
                }
            }else{
                header('Location: ./error.php');
                die();
            }
        } else{
            header('Location: ./error.php');
            die();
        }
        $conn->close();
    }else{
        header('Location: ./error.php');
        die();
    }
}else{
    header('Location: ./profile.php?order=1');
    die();
}

?>

<div class="contentBody">
    <div class="checkoutSec">
        <h3>ORDER</h3>
        <div class="deliveryAddressSec">
            <p>ORDER INFO</p>
            <div class="checkOutSubBody address" >
                <div style = "padding: 10px 0;">STATUS : <b>order <?php 
                    switch($orderInfo['status']){
                        case 0:
                            echo "pending";
                            break;
                        case 1:
                            echo "accepted";
                            break;
                        case 2:
                            echo "delivering";
                            break;
                        case 3:
                            echo "delivered";
                            break;
                    }
                ?></b></div>
                <br />
                <div style = "padding: 10px 0;">DELIVERY ADDRESS : <b> <?php echo $orderInfo['address'];?> </b>
                </div> 
            </div>

            <p>PAYMENT METHOD</p>
            <div class="checkOutSubBody address">
                <div style = "padding: 10px 0;"><?php echo ($orderInfo['payment_option']); ?> on <b><?php echo $orderInfo['payment_datetime']; ?></b> </div> 
            </div>
        </div>
        <div class="orderSummarySec">
            <p>order summary</p>
            <div class="checkOutSubBody">
                <div class="orderSummaryHeader">
                    <p>price-unit</p>
                    <p>sub-total</p>
                </div>
                <?php
                    foreach($orderProductInfo as $orderProduct){
                        $subtotal = $orderProduct['product_price'] * $orderProduct['Olist_quantity'];
                        echo '<div class="orderSummaryList" id = "'.$orderProduct['product_id'].'">
                        <div>
                        <img src="../assets/image/'.$orderProduct['front_imageurl'].'" alt="'.$orderProduct['product_name'].'">
                        <div>
                        <p class="cartItemName">'.$orderProduct['product_name'].'</p>
                                <p class="cartProductQuantity"> x '.$orderProduct['Olist_quantity'].'</p>
                            </div>
                        </div>
                        <div>
                            <p class="cartItemPrice">RM '.$orderProduct['product_price'].'</p>
                            <p class="cartItemPrice sub-total">RM '.number_format($subtotal, 2, '.', '').'</p>
                        </div>
                    </div>';
                    }
                ?>
                <div class="orderSummaryTotalPrice">
                    <p id="OSTotalPrice">total : RM <?php echo number_format($orderInfo['total_price'], 2, '.', ''); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include "./components/footer.php";
?>
