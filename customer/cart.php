<?php
include "./components/header.php";
if(!isset($_COOKIE['user_id'])){
    header('Location: index.php?login=1');
    die();
}

include_once "../server/dataConnect.php";
$selectAddress = "SELECT a.address_id, a.address_name FROM user as u INNER JOIN address as a ON u.user_id = a.user_id WHERE a.user_id = ".$_COOKIE['user_id'].";";
$result = $conn->query($selectAddress);
$addressInfo = [];
if($result->num_rows > 0){
    while($data = $result->fetch_assoc()){
        $addressInfo[] = $data; 
    }
}

$selectCartProduct = "SELECT * FROM cart as c INNER JOIN product as p ON c.product_id = p.product_id WHERE user_id = ".$_COOKIE['user_id'].";";
$result = $conn->query($selectCartProduct);

$cartProducts = [];
if($result->num_rows > 0){
    while($data = $result->fetch_assoc()){
        $cartProducts[] = $data; 
    }
}
$conn->close();
?>
<div class="contentBody">
    <div class="cartSec">
        <div class="center">
            <h3>cart</h3>
        </div>
        <form action="./checkout.php" class="cartForm" method = "post">
            <div class="cartItemBody">
                <div class="cartItemLists">
                    <?php
                        if(empty($cartProducts)){
                            echo '<p class="center" style = "height:200px;">No item in cart</p>';
                        }else{
                            echo'<div>
                                <input type="checkbox" id="selectAllItem">
                                <span>select all</span>
                            </div>';
                            $total = 0;
                            foreach($cartProducts as $cartProduct){
                                $total += floatval($cartProduct['product_price'] * $cartProduct['cart_quantity']) ;
                                echo'
                                <div class="cartItemList">
                                    <div>
                                        <input type="checkbox" name="selectedCartItem[]" class="selectedCartItem" value="'.$cartProduct['product_id'].'">
                                        <img src="../assets/image/'.$cartProduct['front_imageurl'].'" alt="'.$cartProduct['product_name'].'">
                                        <div>
                                            <p class="cartItemName">'.$cartProduct['product_name'].'</p>
                                            <p class="cartItemPrice">RM '.$cartProduct['product_price'].'</p>
                                            <p class="cartItemPrice">(RM <span class = "cartSubTotal">'.number_format((float)($cartProduct['product_price'] * $cartProduct['cart_quantity']), 2, '.', '').'</span>)</p>
                                            <div class="productQuantitySec">
                                                <span class="plusMinQuantity">-</span>
                                                <input type="number" name="productQuantity" inputmode="none" disabled id="productQuantity'.$cartProduct['cart_quantity'].'" class="productQuantity cartProductQuantity" min="1" max="999" value="'.$cartProduct['cart_quantity'].'">
                                                <span class="plusMinQuantity">+</span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined deleteLink deleteCartItem">
                                        delete
                                    </span>
                                </div>';
                            }
                        }
                    ?>
                </div>
                <div class="cartCheckOutPoint">
                    <h4>place order</h4>
                    <div>
                        <label for="deliveryAddress">
                            Delivery Address
                        </label>
                        
                            <?php
                            if(count($addressInfo) > 0){
                                echo '<select name="deliveryAddress" id="deliveryAddress" requried>';
                                foreach($addressInfo as $address){
                                    echo '<option value="'.$address['address_id'].'">'.$address['address_name'].'</option>';
                                }
                                echo "</select>";
                            } else{
                                echo '<a href="./profile.php?address=1" style = "text-decoration:none; color:blue; font-size:12px; padding-left:30px;">add address</a>';
                            }
                            ?>
                        <p class="totalPrice">total : RM <span id="totalprice">-</span></p>
                        <input type="submit" value="go to checkout" id="checkOut">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
include "./components/footer.php";
?>