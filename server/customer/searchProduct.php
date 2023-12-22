<?php
    include_once("../dataConnect.php");

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchTerm'])){

        $selectProduct = "SELECT product_id, product_name FROM product 
        WHERE (product_name LIKE '%".$_POST['searchTerm']."%' 
        OR product_detail LIKE '%".$_POST['searchTerm']."%' 
        OR cat_id IN (SELECT cat_id FROM category WHERE cat_name LIKE '%".$_POST['searchTerm']."%')) AND activate = 1;";
        $returnHTML = '';

        $result = $conn->query($selectProduct);
        if($result->num_rows){
            while($data = $result->fetch_assoc()){
                $returnHTML .= "<p id = '".$data['product_id']."'>".$data['product_name']."</p>";
            }
        } else{
            $returnHTML = "<p>no such product</p>";
        }

        echo $returnHTML;
    }

$conn->close();
?>