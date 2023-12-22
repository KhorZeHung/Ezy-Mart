<?php
include "./components/header.php";
if(!isset($_COOKIE['user_id'])){
    header('Location: index.php?login=1');
    die();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    include_once("../server/dataConnect.php");
    
    if(isset($_POST['deliveryAddress']) && isset($_POST['selectedCartItem'])){
        $selectAddress = "SELECT `address` FROM address WHERE user_id = ? AND address_id = ?;";
        if($stmt = $conn->prepare($selectAddress)){
            $stmt->bind_param("ii", $_COOKIE['user_id'], $_POST['deliveryAddress']);
            $stmt->execute();
            $result = $stmt->get_result();
            $address = "";
            if($result->num_rows == 1){
                $data = $result->fetch_assoc();
                $address = $data['address'];
            }
            $stmt->data_seek(0);
            $product_ids_string = implode(',', $_POST['selectedCartItem']);
            $selectProduct = "SELECT * FROM cart as c INNER JOIN product as p ON c.product_id = p.product_id WHERE user_id = ? AND p.product_id IN ($product_ids_string)";
            if($stmt = $conn->prepare($selectProduct)){
                $stmt->bind_param("i", $_COOKIE['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $productInfo = array();
                if($result->num_rows > 0){
                    while($data = $result->fetch_assoc()){
                        $productInfo[] = $data;
                    }
                }
                $conn->close();
            } else{ 
                header('Location: ./error.php');
                die();
            }
        } else { 
            header('Location: ./error.php');
            die();
        }
    }else{ 
        header('Location: ./cart.php');
        die();
    }
} else{ 
    header('Location: ./cart.php');
    die();
}

?>
<div class="contentBody">
    <div class="checkoutSec">
        <h3>checkout</h3>
        <div class="deliveryAddressSec">
            <p>delivery address</p>
            <div class="checkOutSubBody split address" id = "<?php echo $_POST['deliveryAddress']; ?>">
            <?php echo $address; ?>
                <a href="./cart.php">changes</a>
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
                    $total = 0;
                    foreach($productInfo as $product){
                        $subtotal = $product['product_price'] * $product['cart_quantity'];
                        $total += $subtotal;
                        echo '<div class="orderSummaryList" id = "'.$product['product_id'].'">
                        <div>
                        <img src="../assets/image/'.$product['front_imageurl'].'" alt="'.$product['product_name'].'">
                        <div>
                        <p class="cartItemName">'.$product['product_name'].'</p>
                        <p class="cartProductQuantity"> x '.$product['cart_quantity'].'</p>
                            </div>
                        </div>
                        <div>
                            <p class="cartItemPrice">RM '.$product['product_price'].'</p>
                            <p class="cartItemPrice sub-total">RM '.number_format($subtotal, 2, '.', '').'</p>
                        </div>
                    </div>';
                    }
                ?>
                <div class="orderSummaryTotalPrice">
                    <p id="OSTotalPrice">total : RM <?php echo number_format($total, 2, '.', ''); ?></p>
                </div>
            </div>
        </div>
        <div class="paymentMethodSec">
            <p>payment method</p>
            <div class="checkOutSubBody">
                <div class="savePayment">
                    <p>
                        <span class="material-symbols-outlined">
                            credit_card
                        </span>
                        card payment
                    </p>
                    <input type="radio" name="selectedPaymentMethod" class = "selectedPaymentMethod" value=1>
                </div>
                <div class="savePayment">
                    <p>
                        <span class="material-symbols-outlined">
                            account_balance
                        </span>
                        online banking
                    </p>
                    <input type="radio" name="selectedPaymentMethod" class = "selectedPaymentMethod" value=2>
                </div>
                <div class="savePayment">
                    <p>
                        <span class="material-symbols-outlined">
                            payments
                        </span>
                        cash on delivery
                    </p>
                    <input type="radio" name="selectedPaymentMethod" class = "selectedPaymentMethod" value=3>
                </div>
            </div>
        </div>
        <div class="center">
            <button id="proceedPaymentBtn" class="spinnerBtn" value="payment">
                <span class="material-symbols-outlined">
                    shopping_cart_checkout
                </span>
                proceed to payment
            </button>
        </div>
    </div>
</div>
<?php
include "./components/footer.php";
?>