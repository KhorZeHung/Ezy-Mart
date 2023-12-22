<?php
    $returnObj = array();
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['searchTerm'])){
        include_once("../dataConnect.php");

        $selectQuery = "SELECT * FROM contact WHERE (contact_subject LIKE '%".$_POST['searchTerm']."%' 
        OR contact_name LIKE '%".$_POST['searchTerm']."%' 
        OR contact_email LIKE '%".$_POST['searchTerm']."%'
        OR contact_phone LIKE '%".$_POST['searchTerm']."%');";

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