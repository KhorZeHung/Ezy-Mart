<?php
require "./component/header.php";
require "./component/sidebar.php";
require "../server/function.php";

if(!isset($_COOKIE['admin_id'])){
    header("Location: ./login.php");
    die();
}else{
    include_once("../server/dataConnect.php");
    $selectQuery = "SELECT * FROM `order` INNER JOIN address on 
    `order`.address_id = address.address_id 
    AND `order`.`user_id` = address.user_id;";

    $result = $conn->query($selectQuery);
    $checkedRows = array();
    $uncheckRows = array();

    if($result->num_rows > 0){
        while($data = $result->fetch_assoc()){
            if($data['admin_id'] != null){
                $checkedRows[] = $data;
            }else{
                $uncheckRows[] = $data;
            }
        }
    }
}
?>
<div class="contentBody">
    <div class="contentSubBody">
        <h3>new order</h3>
        <?php if(count($uncheckRows) > 0){
        ?>
            <p class = "reminder">* double click to select</p>
        <table class="orderTable">
            <thead>
                <th>
                    order id
                </th>
                <th>
                    order date
                </th>
                <th>
                    delivery address
                </th>
                <th>
                    total price (RM)
                </th>
                <th>
                    status
                </th>
            </thead>
            <tbody>
                <?php 
                foreach($uncheckRows as $uncheckRow){
                    echo "<tr id = ".$uncheckRow['order_id'].">
                    <td>
                        ".cutString($uncheckRow['order_id'], 5)."
                    </td>
                    <td>
                        ".cutString($uncheckRow['payment_datetime'], 30)."
                    </td>
                    <td>
                        ".cutString($uncheckRow['address'], 25)."
                    </td>
                    <td>
                    ".cutString($uncheckRow['total_price'], 20)."
                    </td>
                    <td class = 'red' value = 1>
                            pending
                    </td>
                    </tr>";
                }?>
            </tbody>
        </table>
        
        <?php } else{echo "<p class = 'center' style = 'height:100px;'>no new order</p>";} ?>
    </div>
    <div class="contentSubBody">
        <h3>history</h3>
        <div class="filterSec">
            <div class="searchBar">
                <input type="text" placeholder="id, name, phone, address" class = "orderSearchBar">
                <span class="material-symbols-outlined">
                    search
                </span>
                <div class="searchResult"  id = "orderSearchResult">
                </div>
            </div>
            <div class="filter">
                status:
                <select name="status" id="statusOfOrder">
                    <option value="*">all</option>
                    <option value="accepted">accepted</option>
                    <option value="delivering">delivering</option>
                    <option value="delivered">delivered</option>
                </select>
            </div>
        </div>
        <p class = "reminder">* double click to select</p>
        <table class = 'orderTable' id = "acceptedOrderTable">
            <thead>
                <th>
                    order id
                </th>
                <th>
                    order date
                </th>
                <th>
                    delivery address
                </th>
                <th>
                    total price (RM)
                </th>
                <th>
                    status
                </th>
            </thead>
            <tbody>
                <?php
                foreach($checkedRows as $checkedRow){
                    echo "<tr id = ".$checkedRow['order_id'].">
                    <td>
                        ".cutString($checkedRow['order_id'], 5)."
                    </td>
                    <td>
                        ".cutString($checkedRow['payment_datetime'], 25)."
                    </td>
                    <td>
                        ".cutString($checkedRow['address'], 25)."
                    </td>
                    <td>
                    ".cutString($checkedRow['total_price'], 20)."
                    </td>";

                    switch($checkedRow['status']){
                        case 1:
                            echo "<td class = 'blue' value = 1>accepted</td>";
                            break;
                        case 2:
                            echo "<td class = 'orange' value = 2>delivering</td>";
                            break;
                        case 3:
                            echo "<td class = 'green' value = 3>delivered</td>";
                            break;
                    }
                    
                    echo "</tr>";
                }?>
            </tbody>
        </table>
    </div>
</div>
<?php
require "./component/footer.php";
?>