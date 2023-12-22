<?php
include_once("../dataConnect.php");
include_once("function.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $messageDetail = array('success' => false, 'detail' => "unauthorize access");
} else if(isset($_POST['addNewFile']) && isset($_FILES['productImgFiles'])){
    $imgArray = array("front_imageurl", "back_imageurl", "add_imageurl");
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
    $msg = array();
    $files = $_FILES['productImgFiles'];
    $productID = isset($_POST['product_id']) ? $_POST['product_id'] : null;
    $imgSeq = $_POST['addNewFile'];

    $newImgName = array();

    foreach ($files['name'] as $key => $filename) {
        $file_temp = $files['tmp_name'][$key];
        $file_size = $files['size'][$key];
        $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $subMsg = array();

        // Check file extension
        if (!in_array($file_extension, $allowed_extensions)) {
            $subMsg['msg'] = "Invalid extension : '" . $filename . "', only accept .jpg, .jpeg, .png, .gif .";
            $msg[] = $subMsg;
            continue;
        }

        // Check file size
        if ($file_size > 50000000) {
            $subMsg['msg'] = "File too large : '" . $filename . "' exceeds 50MB.";
            $msg[] = $subMsg;
            continue;
        }
        
        do{
            // Store file in the target directory
            $unique_file_name = md5(uniqid(rand(), true)) . '.' . $file_extension;
            $target_file = "../../assets/image/" . $unique_file_name;
        }while(file_exists($target_file));

        if (!move_uploaded_file($file_temp, $target_file)) {
            $subMsg['msg'] = "The file '" . $filename . "' could not be uploaded.";
        } else if($productID){
            $insertQuery = "UPDATE product set ".$imgArray[$imgSeq]." = ? WHERE product_id = ?;";
            if($stmt = $conn->prepare($insertQuery)){
                $stmt->bind_param("si", $unique_file_name, $productID);
                $stmt->execute();
                $subMsg['msg'] = "The file '".$filename . "' uploaded successful";
                $subMsg['id'] = $conn->insert_id;
                $subMsg['location'] = $target_file;
                $imgSeq++;
                $stmt->close();
            }else{
                $subMsg['msg'] = "The file '" . $filename . "' could not be uploaded in database.";
            }
        } else{
            $newImgName[] = $unique_file_name;
        }

        $msg[] = $subMsg;
    }

    if(count($newImgName) > 0){
        $subMsg = array();
        $insertQuery = "INSERT INTO product (front_imageurl";
        for($a = 1; $a < count($newImgName); $a++ ){
            $insertQuery.= (", ".$imgArray[$a]);            
        }
        $insertQuery.= ") VALUES ('".$newImgName[0] . "'" ;
        for($a = 1; $a < count($newImgName); $a++ ){
            $insertQuery.= (", '".$newImgName[$a] . "'");            
        }
        $insertQuery.= ");";
        $conn->query($insertQuery);
        unset($msg);
        for($a = 0; $a < count($newImgName); $a++){
            $subMsg['msg'] = "The file '".$files['name'][$a] . "' uploaded successful";
            $subMsg['id'] = $conn->insert_id;
            $subMsg['location'] = $newImgName[$a];
            $msg[] = $subMsg;
        }
    }

    $messageDetail = $msg;
    
}else if(isset($_POST['deletePhoto']) && isset($_POST['sequence']) && isset($_POST['imgSrc'])){
    $imgArray = array("front_imageurl", "back_imageurl", "add_imageurl");
    $imgSrcArray = explode(',', $_POST['imgSrc']);
    $sequence = $_POST['sequence'];

    
    $updateQuery = "UPDATE product SET add_imageurl = null ";
    for($a = $sequence; $a <= 1; $a++){
        $value = isset($imgSrcArray[$a + 1]) && (count($imgSrcArray) >= ($a + 1)) ? $imgSrcArray[$a + 1]: null;
        $extendQuery = ", ".$imgArray[$a]." = '$value'";
        $updateQuery .= $extendQuery;
    }

    $updateQuery .= " WHERE product_id = ".$_POST['deletePhoto'].";";
    $conn->query($updateQuery);
    if($conn->affected_rows == 1){
        if(file_exists("../../assets/image/".$imgSrcArray[$sequence])){
            unlink("../../assets/image/".$imgSrcArray[$sequence]);
        } 
        $messageDetail = array("success"=>true, "detail" => "photo deleted successful");
    } else{
        $messageDetail = array("success"=>false, "detail" => "photo deletion fail");
    }  
}else if(isset($_POST['updateProduct']) && isset($_POST['imgSrc']) && isset($_POST['pname']) && isset($_POST['product_id']) && isset($_POST['price']) && isset($_POST['pdetail']) && isset($_POST['category'])){
    
    include_once("function.php");
    $productID = checkVar($_POST['product_id'], "product_id");
    $name = checkVar($_POST['pname'], "product name") ;
    $price = checkVar($_POST['price'], "price") ;
    $category = checkVar($_POST['category'], "category"); 
    $detail = nl2br(checkVar($_POST['pdetail'], "detail"));

    $imgSrcArray = $array = explode(',', $_POST['imgSrc']);

    $updateQuery = "UPDATE product SET 
        product_name = '$name', 
        product_detail = '$detail', 
        product_price = '$price',";

    $imgArray = array("front_image", "back_image", "add_image");

    foreach($imgArray as $index=>$imgName){
        if(isset($imgSrcArray[$index])){                
            $updateQuery .= ($imgName . "url = '".$imgSrcArray[$index]."',");
        } 
    }

    //if message is true 
    if (!empty($name) && !empty($price) && !empty($category)) {
        $updateQuery .= "cat_id = '$category' WHERE product_id = $productID";
         
        if ($conn->query($updateQuery)) {
            $messageDetail = array('success' => true, 'detail' => 'product updated successful');
        } else {
            // $messageDetail = array('success' => false, 'detail' => "Failed to Update Product. Please Try Again.");
            $messageDetail = array('success' => false, 'detail' => $updateQuery);
        }
    } else {
        $messageDetail = array('success' => false, 'detail' => "Product Name, Price, Category and Front Image Cannot Be Empty!");
    }
} else if(isset($_POST['activate']) && isset($_POST['product_id'])){
    $deactivate = ($_POST['activate'] === "activate") ? 1 : 0;
    $productID = $_POST['product_id'];
    $updateQuery = "UPDATE product SET activate = $deactivate WHERE product_id = $productID;";

    $conn->query($updateQuery);

    if($conn->affected_rows == 1){
        $messageDetail = array('success' => true, 'detail' => "product update successful");
    }else{
        $messageDetail = array('success' => false, 'detail' => "product update failed");
    }
    
} else{
    $messageDetail = array('success' => false, 'detail' => "unauthorize access");
}

$conn->close();

echo json_encode($messageDetail);