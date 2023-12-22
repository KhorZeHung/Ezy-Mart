<?php
    $returnObj = array();
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['searchTerm'])){
        include_once("../dataConnect.php");

        $selectQuery = "SELECT * FROM product INNER JOIN category ON product.cat_id = category.cat_id 
        WHERE (product.product_id LIKE '%".$_POST['searchTerm']."%' 
        OR product.product_name LIKE '%".$_POST['searchTerm']."%'
        OR category.cat_name LIKE '%".$_POST['searchTerm']."%');";

        $result = $conn->query($selectQuery);

        $searchResult = array();
        while($data = $result -> fetch_assoc()){
            $searchResult[] = $data;
        }

        $returnObj = array("success" => true, "detail" => $searchResult);
    }else{
        $returnObj = array("success" => false, "detail" => "unauthorize access");
    }

    echo json_encode($returnObj);
?>