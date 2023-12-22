<?php
require "./component/header.php";
require "./component/sidebar.php";
if(!isset($_COOKIE['admin_id'])){
    header("Location: ./login.php");
    die();
}else{
    include_once("../server/dataConnect.php");
    $selectQuery = "SELECT * FROM product INNER JOIN category ON product.cat_id = category.cat_id ORDER BY product.product_id ASC;";

    $result = $conn->query($selectQuery);
    $productsInfo = array();

    if($result->num_rows > 0){
        while($data = $result->fetch_assoc()){
            $productsInfo[] = $data;
        }
    }
}
?>
<div class="contentBody">
    <div class="contentSubBody">
        <h3>Product</h3>
    </div>
    <div class="filterSec">
        <div class="searchBar">
            <input type="text" placeholder="product id, product name" class = 'productSearchBar'>
            <span class="material-symbols-outlined">
                search
            </span>
            <div class="searchResult" id = "productSearchResult"></div>
        </div>
        <div class="filter">
            category:
            <select name="category" id="productCatFilter">
                <option value="*">all</option>
                <option value="Beverages">Beverages</option>
                <option value="Convenience Foods">Convenience Foods</option>
                <option value="Snacks">Snacks</option>
                <option value="Household Supplies">Household Supplies</option>
                <option value="Frozen Foods">Frozen Foods</option>
            </select>
            <a href="add_product.php">add product</a>
        </div>
    </div>
            <p class = "reminder">* double click to select</p>
    <table id="productTable">
        <thead>
            <th>
            </th>
            <th>
                id
            </th>
            <th>
                name
            </th>
            <th>
                categories
            </th>
            <th>
                unit price
            </th>
            <th>
                action
            </th>
        </thead>
        <tbody>
            <?php foreach($productsInfo as $productInfo){?>
            <tr id = <?php echo $productInfo['product_id'];?>>
                <td>
                    <img src="../assets/image/<?php echo $productInfo['front_imageurl'];?>" alt="<?php echo $productInfo['product_name'];?>">
                </td>
                <td>
                    <?php echo $productInfo['product_id'];?>
                </td>
                <td>
                    <?php echo $productInfo['product_name'];?>
                </td>
                <td><?php echo $productInfo['cat_name'];?></td>
                <td>
                    RM <?php echo $productInfo['product_price'];?>
                </td>
                <td>
                    <?php echo $productInfo['activate'] ? 
                    '<p class = "deactivate activate" value = "'.$productInfo['product_id'].'">delete</p>' 
                    : '<p class = "activate" value = "'.$productInfo['product_id'].'">activate</p>';?>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
</div>
<?php
require "./component/footer.php";
?>