<?php
include "./components/header.php";
if(!isset($_COOKIE['user_id'])){
    include "./components/loginSignUp.php";
}

if(isset($_GET['product_id'])){
    include_once("../server/dataConnect.php");
    $selectProduct = "SELECT * FROM product WHERE product_id = ".$_GET['product_id'].";";
    $quantity = 1;
    $result = $conn->query($selectProduct);
    if($result->num_rows == 1){
        $product_data = $result->fetch_assoc();
        if(isset($_COOKIE['user_id'])){
            $selectQuantity = "SELECT cart_quantity FROM cart WHERE product_id = ".$_GET['product_id']." AND user_id = ".$_COOKIE['user_id'].";";
            $result = $conn->query($selectQuantity);
            if($result->num_rows > 0){
                $data = $result->fetch_assoc();
                $quantity = $data['cart_quantity'];
            }
        }
        $conn->close();
    }else{
        header('Location: ./error.php');
        die();
    }
}else{
    header('Location: ./products.php');
    die();
}
?>
<div class="contentBody">
    <div class="productDetailSec">
        <div class="imgSec">
            <img src="../assets/image/<?php echo $product_data['front_imageurl'];?>" alt="product front view" id="mainProductImg">
            <?php 
                if(isset($product_data['back_imageurl']) || isset($product_data['add_imageurl'])){
                    echo '<span class="material-symbols-outlined arrow leftArrow">
                    arrow_back_ios
                </span>
                <span class="material-symbols-outlined  arrow rightArrow">
                    arrow_forward_ios
                </span>
                <div class="imgSlider">
                    <img src="../assets/image/'.$product_data['front_imageurl'].'" alt="product front view" class="imgSelected">';
                    if(isset($product_data['back_imageurl'])){
                        echo '<img src="../assets/image/'.$product_data['back_imageurl'].'" alt="product back view">';
                    }
                    if(isset($product_data['add_imageurl'])){
                        echo '<img src="../assets/image/'.$product_data['add_imageurl'].'" alt="product view">';
                    }
                echo '</div>';
                }
            ?>
        </div>
        <div class = "productInfoSec">
            <p class="productName"><?php echo $product_data['product_name'];?></p>
            <p class="productDescription"><?php echo $product_data['product_detail'];?></p>
            <p class="productPrice">RM <span id = "productPrice"><?php echo number_format((float)($product_data['product_price'] * $quantity), 2, '.', '');?></p>
            <p class="productPrice">RM (<span class = "prodcutSubTotal"><?php echo number_format((float)($product_data['product_price'] * $quantity), 2, '.', '');?></span>)</p>
            <form class="addToCartForm">
                <div class="productQuantitySec">
                    <input type="hidden" name="product_id" id="product_id" value = <?php echo $product_data['product_id'];?>>
                    <span class="plusMinQuantity">-</span>
                    <input type="number" name="cart_quantity" inputmode="none" class="productQuantity" min="1" max="999" value=<?php echo $quantity;?>>
                    <span class="plusMinQuantity">+</span>
                </div>
                <div class="productAddToCart spinnerBtn" id="productAddToCartSubmit">
                    <p>Add to cart</p>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include "./components/footer.php";
?>