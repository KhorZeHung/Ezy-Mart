<?php
require "./component/header.php";
require "./component/sidebar.php";
if(!isset($_COOKIE['admin_id'])){
    header("Location: ./login.php");
    die();
}else if (!isset($_GET['product_id'])){
    header("Location: ./productList.php");
    die();
}else{
    include_once("../server/dataConnect.php");
    $selectQuery = "SELECT * FROM `product` INNER JOIN category on 
    `product`.cat_id = category.cat_id 
    WHERE `product`.`product_id` = ?;";
    if($stmt = $conn->prepare($selectQuery)){
        $stmt->bind_param("i", $_GET['product_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $productDetail = $result->fetch_assoc();
            $productDetail['product_detail']= str_replace("<br />", " ", $productDetail['product_detail']);
        }else{
            header("Location: ./productList.php");
        }
        $stmt->close();
        $conn->close();
    }else{
        header("Location: ./productList.php");
        die();
    }
}
?>
<div class="contentBody"><span class="material-symbols-outlined backBtn" id="backToOrderBtn">
        arrow_back
    </span>
    <div class="contentSubBody">
        <h3>product detail</h3>
        <p class="reminder">* drag to arrange sequence, prefer (front-view, back-view, additional), max 3</p>
        <div class="productImgSec">
            <?php 
            $imgArray = array("front_imageurl", "back_imageurl", "add_imageurl");
            $numOfImg = 0;
            foreach($imgArray as $image){
                if(!empty($productDetail[$image])){
                    $numOfImg++;?>
                <div class="productImg center" id = <?php echo $productDetail['product_id'];?>>
                <span class="material-symbols-outlined delete">
                    cancel
                </span>
                <img src="../assets/image/<?php echo $productDetail[$image]; ?>" alt="<?php echo $productDetail['product_name'];?>">
                </div>
            <?php
                }
            }
            ?>
            <label class="custom-file-upload center" id = "productImgInputField">
                <input type="file" id="productImgFiles" name="productImgFiles[]" multiple>
                <span class="material-symbols-outlined addPhoto green">
                    add_circle
                </span>
            </label>
        </div>
        <p class="reminder" style = "padding-top:10px;">* click update after making chages, including new upload image</p>
        <form class="productDetailForm" method="post">
            <div>
                <div>
                    <label for="pname">product name</label>
                    <input type="text" name="pname" id = "pname" value = "<?php echo $productDetail['product_name']; ?>">
                    <input type="hidden" name="product_id" id = "product_id" value = "<?php echo $_GET['product_id']; ?>">
                </div>
                <div>
                    <label for="price">product price</label>
                    <input type="text" name="price" id = "price" value = "<?php echo $productDetail['product_price']; ?>">
                </div>
                <div>
                    <label for="category">category</label>
                    <select name="category" id="productCat">
                        <option value="BEV001" <?php if($productDetail['cat_id'] == "BEV001") {echo "selected";} ?>>beverages</option>
                        <option value="CF002" <?php if($productDetail['cat_id'] == "CF002") {echo "selected";} ?>>convenience food</option>
                        <option value="SNA003" <?php if($productDetail['cat_id'] == "SNA003") {echo "selected";} ?>>snacks</option>
                        <option value="HOU004" <?php if($productDetail['cat_id'] == "HOU004") {echo "selected";} ?>>household supplies</option>
                        <option value="FRO005" <?php if($productDetail['cat_id'] == "FRO005") {echo "selected";} ?>>frozen food</option>
                    </select>
                </div>
            </div>
            <div>
                <div class="largeInput">
                    <label for="pdetail">product detail</label>
                    <textarea name="pdetail" placeholder="product detail" id = "pdetail"><?php echo $productDetail['product_detail']; ?></textarea>
                </div>
                <div class="columnSplit">
                    <?php echo $productDetail['activate'] ? 
                    '<p class = "deactivate activate" value = "'.$productDetail['product_id'].'">delete</p>' 
                    : '<p class = "activate" value = "'.$productDetail['product_id'].'">activate</p>';?>
                    <div class="classicBtn spinnerBtn center" id="updateProductDetailBtn">
                        update
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="uploadStatus">
</div>
<?php
require "./component/footer.php";
?>