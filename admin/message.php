<?php
require "./component/header.php";
require "./component/sidebar.php";
if(!isset($_COOKIE['admin_id'])){
    header("Location: login.php");
    die();
}

if(!isset($_GET['contact_id'])){
    header("Location: contactUs.php");
    die();
}

include_once("../server/dataConnect.php");
$selectQuery = "SELECT * FROM contact WHERE contact_id = ".$_GET['contact_id'].";";
$result = $conn->query($selectQuery);
if($result->num_rows == 1){
    $data = $result->fetch_assoc();
}else{
    header("Location: contactUs.php");
}
$conn->close();
?>
<div class="contentBody">
    <span class="material-symbols-outlined backBtn" id="backToOrderBtn">
        arrow_back
    </span>
    <div class="contentSubBody">
        <h3>message</h3>
        <table class = "msgTable">
            <tr>
                <td>name</td>
                <td><?php echo $data['contact_name'];?></td>
            </tr>
            <tr>
                <td>phone number</td>
                <td><?php echo $data['contact_phone'];?></td>
            </tr>
            <tr>
                <td>email</td>
                <td><?php echo $data['contact_email'];?></td>
            </tr>
            <tr>
                <td>subject</td>
                <td><?php echo $data['contact_subject'];?></td>
            </tr>
            <tr>
                <td>message</td>
                <td><?php echo $data['contact_message'];?></td>
            </tr>
        </table>
    </div>
    <div class="contentSubBody center">
        <form class="messageForm">    
            <textarea name="message" class = "messageTextArea" placeholder = "reply message..."></textarea>
            <input type="hidden" name="replySubject" value = "<?php echo $data['contact_subject'];?>">
            <input type="hidden" name="replyEmail" value = "<?php echo $data['contact_email'];?>">
            <input type="hidden" name="replyName" value = "<?php echo $data['contact_name'];?>">
            <input type="hidden" name="replyId" value = "<?php echo $data['contact_id'];?>">
        </form>
        <span class = "classicBtn center" id = "replyBtn">reply</span>
    </div>
</div>
<?php
require "./component/footer.php";
?>