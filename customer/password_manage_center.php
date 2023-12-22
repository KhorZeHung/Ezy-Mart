<?php
include "./components/header.php";
include "./components/loginSignUp.php";
if(!isset($_COOKIE['user_id']) && !isset($_GET['rp']) && !isset($_COOKIE['reset_password']) && !isset($_GET['progress'])){
    header("Location: error.php");
    die();
}
?>
<div class="contentBody">
    <div class="passwordManageSec">
        <?php 
        if(isset($_COOKIE['user_id']) && isset($_GET['rp'])){
            echo '
            <h3>reset password</h3>
            <form id="resetPasswordForm">
                    <label for="pin">current password</label>            
                    <input type="password" name="pin" placeholder = "current password">
                    <label for="new_password">new password  <span class = reminder> * atleast 8 character</span></label>            
                    <input type="password" name="new_password" placeholder = "new password">
                    <label for="confirm_password">repeat password</label>            
                    <input type="password" name="confirm_password" placeholder = "repeat new password">
                    <input type="hidden" name="resetPassword" value = "1">
                    <div class="center">
                        <div class="passwordManageFormBtn spinnerBtn" id="resetPasswordBtn">
                            reset
                        </div>
                    </div>
                </form>';
        }else if(!isset($_COOKIE['reset_password']) && !isset($_COOKIE['user_id'])){
            echo'
            <h3>renew password</h3>
            <p>please enter your email</p>
            <form id="resetPasswordEmailForm">            
                <input type="text" name="email" id="email" placeholder = "email">
                <input type="hidden" name="getPinNum" value = "1">
                <div class="center">
                    <div class="passwordManageFormBtn spinnerBtn" id="renewPasswordEmailBtn">
                        continue
                    </div>
                </div>
            </form>';
        }else if(isset($_COOKIE['reset_password']) && !isset($_COOKIE['user_id'])){
            $email = $_COOKIE['reset_password'];
            $repeatBlur = strpos($email,"@") - 2;
            $positionStart = 2;
            $blurEmail = str_replace(substr($email, 2, $repeatBlur), str_repeat("*", $repeatBlur), $email);
            echo '
            <h3>6-pin password</h3>
            <p>temporary password has send to <b>'.$blurEmail.'</b></p>
            <form id = "renewPasswordForm">
                <label for="pin">6-pin password</label>
                <input type="text" name="pin" id="tempPassword" min=0 max=999999>
                <label for="new_password">New password <span class = reminder> * atleast 8 character</span></label>
                <input type="password" name="new_password" id="newPassword">
                <label for="confirm_password">Repeat password</label>
                <input type="password" name="confirm_password" id="newRepeatPassword">
                <div class="center">
                    <div class="passwordManageFormBtn spinnerBtn" id="passwordUpdateBtn">
                        update password
                    </div>
                </div>
            </form>'; 
        }
        ?>
    </div>
</div>
<?php
include "./components/footer.php";
?>