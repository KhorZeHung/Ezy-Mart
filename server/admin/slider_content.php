<?php
include_once ("../dataConnect.php");

global $conn;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST["addNewFile"]) && isset($_FILES['sliderContentFiles'])){
        $fileObj = $_FILES['sliderContentFiles'];        
        //pass to file handler and process file upload
        if($response = handleFileUpload($fileObj, "../../assets/image/")){
            echo json_encode($response);
        }
    }
    
    if(isset($_POST['updateContentSlider']) && !empty($_POST['imgSrc'])){
        $imgSrcArray = $array = explode(',', $_POST['imgSrc']);
        $result = $conn->query("SELECT contentID FROM slider_content;");
        if($result->num_rows < 1){
            echo "something went wrong";
        }else{
            $imgId = array();
            while($data = $result->fetch_assoc()){
                $imgId[] = $data['contentID'];
            }

            // update slider content according to the sequence
            if($updateStmt = $conn->prepare("UPDATE slider_content SET contentLocation = ? WHERE contentID = ?;")){
            $updateStatus = true;
                for($a = 0; $a < count($imgId); $a++){
                    $updateStmt->bind_param("si",$imgSrcArray[$a], $imgId[$a]);
                    if(!$updateStmt->execute()){
                        $updateStatus = false;
                    } 
                }
                $updateStmt->close();
            
            echo $updateStatus? "Content update successfull" : "Content update fail";
            } else{
                echo ("Content update fail");
            }
        }
    }

    if(isset($_POST['deletePhoto'])){
        if(file_exists("../".$_POST['imgUrl'])){
            unlink("../".$_POST['imgUrl']);
        } 
        $splitStr = explode("/",$_POST['imgUrl']);
        $contentLocation = $splitStr[count($splitStr) - 1];
        if($deleteStmt = $conn->prepare("DELETE FROM slider_content WHERE contentLocation = ?;")){
            $deleteStmt->bind_param('s', $contentLocation);
            $deleteStmt->execute();
            if($deleteStmt->affected_rows > 0){
                echo("Photo deleted successful");
            } else{
                echo ("Photo deletion fail");
            }
            $deleteStmt->close();        
        } else{
            echo ("Cannot delete photo from database");
        }
    }
}else{
    echo json_encode("unauthorize access");
}



function handleFileUpload($files, $target_dir) {
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
    $msg = array();

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
            $target_file = $target_dir . $unique_file_name;
        }while(file_exists($target_file));

        
        if ( !move_uploaded_file($file_temp, $target_file)) {
            $subMsg['msg'] = "The file '" . $filename . "' could not be uploaded.";
        } else{
            global $conn;
            $insertQuery = "INSERT INTO slider_content (contentLocation) VALUES (?);";
            if($insertStmt = $conn->prepare($insertQuery)){
                $insertStmt->bind_param("s", $unique_file_name);
                if($insertStmt->execute()){
                    $subMsg['msg'] = "The file '".$filename . "' uploaded successful";
                    $subMsg['id'] = $conn->insert_id;;
                    $subMsg['location'] = $target_file;
                }else{
                    $subMsg['msg'] = "The file '" . $filename . "' could not be uploaded in database.";
                }
                $insertStmt->close();
            }else{
                $subMsg['msg'] = "The file '" . $filename . "' could not be uploaded in database.";
            }
        }

        $msg[] = $subMsg;
    }

    return $msg;
}

$conn->close();