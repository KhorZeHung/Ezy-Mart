<?php
require "./component/header.php";
require "./component/sidebar.php";
if(!isset($_COOKIE['admin_id'])){
    header("Location: ./login.php");
    die();
}else if (!isset($_GET['order_id'])){
    header("Location: ./orderList.php");
    die();
}else{
    include_once("../server/dataConnect.php");
    $selectQuery = "SELECT * FROM `order` INNER JOIN address on 
    `order`.address_id = address.address_id 
    INNER JOIN user ON `order`.user_id = user.user_id 
    WHERE `order`.`order_id` = ?;";
    if($stmt = $conn->prepare($selectQuery)){
        $stmt->bind_param("i", $_GET['order_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $orderDetail = $result->fetch_assoc();
            $stmt->data_seek(0);
            $selectOrderProduct = "SELECT * FROM orderlist INNER JOIN product 
            ON orderlist.product_id = product.product_id 
            WHERE order_id = ?;";
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
                    header('Location: ./orderList.php');
                    die();
                }
            }else{
                header('Location: ./orderList.php');
                die();
            }
        }else{
            header("Location: ./orderList.php");
        }
        $stmt->close();
        $conn->close();
    }else{
        header("Location: ./orderList.php");
        die();
    }
}
?>
<div class="contentBody"><span class="material-symbols-outlined backBtn" id="backToOrderBtn">
        arrow_back
    </span>
    <div class="contentSubBody">
        <h3>order detail</h3>
        <div class="orderDetailBody">
            <div class="orderDetailSec">
                <div class="orderDetailSubBody">
                    <p>Order_id : </p>
                    <?php echo "<p>".$orderDetail['order_id']."</p>";?>
                    <p>Order_date</p>
                    <?php echo "<p>".$orderDetail['payment_datetime']."</p>";?>
                    <p>Ttl price</p>
                    <?php echo "<p>RM ".$orderDetail['total_price']."</p>";?>
                </div>
                <div class="orderDetailSubBody">
                    <p>name : </p>
                    <?php echo "<p>".$orderDetail['user_name']."</p>";?>
                    <p>pnum : </p>
                    <?php echo "<p>".$orderDetail['user_phone']."</p>";?>
                    <p>e-mail : </p>
                    <?php echo "<p>".$orderDetail['user_email']."</p>";?>
                </div>
                <div class="orderDetailSubBody">
                    <p>d.date : </p>
                    <?php $isNull = ($orderDetail['deliver_date'] == NULL) ? "-": $orderDetail['deliver_date'];
                    echo "<p>".$isNull."</p>";?>
                    <p>d.address : </p>
                    <?php echo "<p>".$orderDetail['address']."</p>";?>
                    <p>status </p>
                    <form class="orderStatusForm">
                        <input type="hidden" name="order_id" value = <?php echo $_GET['order_id'];?>>
                        <select name="orderStatus" id="orderStatus">
                            <option value=0 <?php if($orderDetail['status'] == 0) echo "selected"?>>pending</option>
                            <option value=1 <?php if($orderDetail['status'] == 1) echo "selected"?>>accept</option>
                            <option value=2 <?php if($orderDetail['status'] == 2) echo "selected"?>>delivering</option>
                            <option value=3 <?php if($orderDetail['status'] == 3) echo "selected"?>>delivered</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="center">
                <div class="classicBtn spinnerBtn center" id="updateOrderStatusBtn">
                    update status
                </div>
            </div>
        </div>
    </div>
    <div class="contentSubBody">
        <h3>Order Summary</h3>
        <div class="orderSummaryBody">
            <div class="orderSummaryHeader">
                <p>price-unit</p>
                <p>sub-total</p>
            </div>
            <?php foreach($orderProductInfo as $orderProduct){?>
            <div class="orderSummaryList">
                <div>
                    <img src="../assets/image/<?php echo $orderProduct['front_imageurl']; ?>" alt="<?php echo $orderProduct['product_name']; ?>">
                    <div>
                        <p class="cartItemName"><?php echo $orderProduct['product_name']; ?></p>
                        <p class="cartProductQuantity"> x <?php echo $orderProduct['Olist_quantity']; ?></p>
                    </div>
                </div>
                <div>
                    <p class="cartItemPrice">RM <?php echo $orderProduct['unit_price']; ?></p>
                    <p class="cartItemPrice sub-total">RM <?php echo number_format($orderProduct['unit_price'] * $orderProduct['Olist_quantity'], 2, '.', ''); ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php
require "./component/footer.php";
?>