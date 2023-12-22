<?php
include "./components/header.php";
if(!isset($_COOKIE['user_id'])){
    header('Location: index.php?login=1');
    die();
}else{
    include_once("../server/dataConnect.php");
    $user_id = $_COOKIE['user_id'];

    $selectUserInfo = "SELECT user_name, user_email, user_phone FROM user WHERE user_id = ?;";
    if($stmt = $conn->prepare($selectUserInfo)){
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $userInfo = $result->fetch_assoc();
        }
    }

    $stmt->data_seek(0);
    $selectOrderInfo = "SELECT * FROM `order` INNER JOIN address ON `order`.address_id = address.address_id WHERE `order`.user_id = ?";
    if($stmt = $conn->prepare($selectOrderInfo)){
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows >= 1){
            $orderInfo = array();
            while($data = $result->fetch_assoc()){
                $datetime = new DateTime($data['payment_datetime']);
                $datetime->setTimezone(new DateTimeZone('+0800'));
                $date = $datetime->format("d-m-Y");
                $data['payment_datetime'] = $date;

                switch($data['status']){
                    case 0: 
                        $data['status'] = "pending";
                        break;
                    case 1: 
                        $data['status'] = "accepted";
                        break;
                    case 2: 
                        $data['status'] = "delivering";
                        break;
                    case 3: 
                        $data['status'] = "delivered";
                        break;
                }
                $orderInfo[] = $data;                
            }
        }
    }

    $stmt->data_seek(0);
    $selectAddress = "SELECT * FROM address WHERE user_id = ?;";
    if($stmt = $conn->prepare($selectAddress)){
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $addressInfo = array();
            while($data = $result->fetch_assoc()){
                if(strlen($data['address']) > 25){
                    $data['address'] = substr($data['address'], 0, 22) . "...";
                }
                $addressInfo[] = $data;
            }
        }
    }
}
?>
<div class="contentBody">
    <div class="profileSec">
        <h3>profile</h3>
        <div class="profileBody">
            <input type="radio" name="profileCtrlCenter" id="account" class="profileCtrlCenter" <?php if(!isset($_GET['order']) && !isset($_GET['order'])) echo "checked"; ?>>
            <input type="radio" name="profileCtrlCenter" id="order" class="profileCtrlCenter" <?php if(isset($_GET['order'])) echo "checked";?>>
            <input type="radio" name="profileCtrlCenter" id="address" class="profileCtrlCenter" <?php if(isset($_GET['address'])) echo "checked";?>>
            <div class="center">
                <div class="profileMenu">
                    <p id="accountBtn">account</p>
                    <p id="orderBtn">order</p>
                    <p id="addressBtn">address</p>
                    <p id="logoutBtn">logout</p>
                </div>
            </div>
            <div class="profileDetail">
                <?php
                include "./components/profile/profileAccount.php";
                include "./components/profile/profileOrder.php";
                include "./components/profile/profileAddress.php";
                ?>
            </div>
        </div>
    </div>
</div>
<?php
include "./components/footer.php";
?>