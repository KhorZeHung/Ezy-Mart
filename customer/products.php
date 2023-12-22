<?php
include "./components/header.php";
include_once "../server/dataConnect.php";
if(!isset($_COOKIE['user_id'])){
    include "./components/loginSignUp.php";
}
?>
<div class="contentBody">
    <div class="productFilterSec">
        <span>
            <span class="material-symbols-outlined">
                filter_alt
            </span>
            <span>Filter</span>
        </span>
        <select name="filter" class = "filter">
            <option value="None" selected>None</option>
            <option value="PriceL2H">Price: Low to High</option>
            <option value="PriceH2L">Price: High to Low</option>
        </select>
    </div>
<?php
$selectProduct = "SELECT * FROM product WHERE activate = 1";
if(isset($_GET['cat']) && $_GET['cat'] != "All product"){
    $selectProduct .= " AND cat_id IN (SELECT cat_id FROM category WHERE LOWER(cat_name) = LOWER('".$_GET['cat']."'))";
}
$selectProduct .= ";";

$result = $conn->query($selectProduct);
if($result->num_rows){
    echo '<div class="productCards2">';
    while($data = $result->fetch_assoc()){
        $mainImg = $data['front_imageurl'];
        $productName = $data['product_name'];
        $productPrice = $data['product_price'];
        $productId = $data['product_id'];
        
        echo '<div class="productCard productCard2">
                <img src="../assets/image/'.$mainImg.'" alt="'.$productName.'" class="productImg">
                <div class="productDetail">
                    <p class="productName">'.$productName.'</p>
                    <p class="productPrice">RM '.$productPrice.'</p>
                    <button class="productAddToCart" id = '.$productId.'>ADD TO CART</button>
                </div>
            </div>';
    }
    echo "</div>";
}else{
    echo '<div class="center" style = "height:300px;">no product</div>';
}
$conn->close();
?>
</div>

<?php
include "./components/footer.php";
?>