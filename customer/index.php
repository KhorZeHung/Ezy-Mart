<?php
include "./components/header.php";
include_once "../server/dataConnect.php";

if(!isset($_COOKIE['user_id'])){
    include "./components/loginSignUp.php";
}
?>

<div class="contentBody">
    <?php
$selectSlider = "SELECT * FROM slider_content;";
$result = $conn->query($selectSlider);
if($result->num_rows > 0){
    echo '<div class="slider">';
    while($data = $result->fetch_assoc()){
        echo '<img src="../assets/image/'.$data['contentLocation'].'" alt="Slider Image" class="slider-image">';
    }
    if($result->num_rows > 1){
        echo '<a class="prev">&#10094;</a>
        <a class="next">&#10095;</a>
        <div class="slideAuto">
        </div>';
    }
    echo "</div>";
}


$selectProduct = "SELECT * FROM product WHERE activate = 1;";
$result = $conn->query($selectProduct);
if($result->num_rows > 0){
    $beverage = array();
    $convenience_foods = array();
    $frozen_food = array();
    $household_supplies = array();
    $snacks = array();
    $productList = array("BEVERAGE" => [], "CONVENIENCE FOOD" => [], "FROZEN FOOD" => [], "HOUSEHOLD SUPPLIES" => [], "SNACKS" => []);
    while($data = $result->fetch_assoc()){
        switch($data['cat_id']){
            case "BEV001" :
                array_push($productList["BEVERAGE"],$data);
                break;
            case "CF002" :  
                array_push($productList["CONVENIENCE FOOD"],$data);
                break;  
            case "FRO005" :   
                array_push($productList["FROZEN FOOD"],$data);
                break;
            case "HOU004" :   
                array_push($productList["HOUSEHOLD SUPPLIES"],$data);
                break;
            case "SNA003" :
                array_push($productList["SNACKS"],$data);
                break;
            default:
                break;
        }
    }   
    foreach($productList as $key=>$products){
        if(!empty($products)){
            echo '<div class="productLayer">
            <h3>'.$key.'</h3>
            <div class="productSlider">';
                foreach($products as $product){
                    echo
                    '<div class="productCards">
                        <div class="productCard">
                            <img src="../assets/image/'.$product['front_imageurl'].'" alt="'.$product['product_name'].'" class="productImg">
                            <div class="productDetail">
                                <p class="productName">'.$product['product_name'].'</p>
                                <p class="productPrice">RM '.$product['product_price'].'</p>
                                <button class="productAddToCart" id = '.$product['product_id'].'>ADD TO CART</button>
                            </div>
                        </div>
                    </div>';
                }
            echo '</div>
            </div>';
        }
    }
}

$conn->close();
?>
    <div class="contactUsSec" id = "contactUsSec">
        <form class="contactUsForm" method="post">
            <h3>contact us</h3>
            <div class="formInputSubSec">
                <input type="text" name="c_name" placeholder="name (requried)" required>
                <input type="text" name="c_email" placeholder="e-mail (requried)" required>
            </div>
            <div class="formInputSubSec">
                <input type="text" name="c_phone" placeholder="phone-number (requried)" required>
                <input type="text" name="c_subject" placeholder="subject (requried)" required>
            </div>

            <textarea name="c_message" id="contactUsMsg" placeholder="message (optional)"></textarea>

            <div class="center">
                <div class="submitBtn spinnerBtn" id="contactUsBtn">
                    submit
                </div>
            </div>

        </form>
    </div>
</div>

<?php
include "./components/footer.php";
?>