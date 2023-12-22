<?php
require "./component/header.php";
require "./component/sidebar.php";
?>
<div class="contentBody"><span class="material-symbols-outlined backBtn" id="backToOrderBtn">
        arrow_back
    </span>
    <div class="contentSubBody">
        <h3>product detail</h3>
        <p class="reminder">* drag to arrange sequence, prefer (front-view, back-view, additional), max 3</p>
        <div class="productImgSec">
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
                    <input type="text" name="pname" id = "pname" placeholder = "product name">
                    <input type="hidden" name="product_id" id = "product_id">
                </div>
                <div>
                    <label for="price">product price</label>
                    <input type="text" name="price" id = "price" product_price = "product price">
                </div>
                <div>
                    <label for="category">category</label>
                    <select name="category" id="productCat">
                        <option value="BEV001">beverages</option>
                        <option value="CF002">convenience food</option>
                        <option value="SNA003">snacks</option>
                        <option value="HOU004">household supplies</option>
                        <option value="FRO005">frozen food</option>
                    </select>
                </div>
            </div>
            <div>
                <div class="largeInput">
                    <label for="pdetail">product detail</label>
                    <textarea name="pdetail" placeholder="product detail" id = "pdetail"></textarea>
                </div>
                <div class="center">
                    <div class="classicBtn spinnerBtn center" id="updateProductDetailBtn">
                        add
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