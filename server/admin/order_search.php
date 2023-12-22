<?php
    $returnObj = array();
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['searchTerm'])){
        include_once("../dataConnect.php");

        $selectQuery = "SELECT * FROM `order` INNER JOIN address ON `order`.address_id = address.address_id 
        INNER JOIN user ON `order`.user_id = user.user_id
        WHERE (order_id LIKE '%".$_POST['searchTerm']."%' 
        OR user.user_name LIKE '%".$_POST['searchTerm']."%'
        OR address.address LIKE '%".$_POST['searchTerm']."%'
        OR user.user_phone LIKE '%".$_POST['searchTerm']."%');";

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