<div id="profileAccount">
    <form action="./" class="profileForm">
        <div class="profileFormBody">
            <div>
                <label for="name">name</label>
                <input type="text" name="name" id="profileName" value = "<?php echo $userInfo['user_name']; ?>">
                <label for="phone">phone number</label>
                <input type="text" name="phone" id="phone" value = "<?php echo $userInfo['user_phone']; ?>">
            </div>
            <div>
                <label for="email">E-Mail</label>
                <input type="text" name="email" id="profileEmail" value = "<?php echo $userInfo['user_email']; ?>">
                <label for="profilePassword">Password</label>
                <div class="center" style = "width:100%;">
                    <a href="./password_manage_center.php?rp=1">change password</a>
                </div>
            </div>
        </div>
        <div class="center">
            <div name="profileFormUpdate" class="spinnerBtn" id="profileFormUpdateBtn">update</div>
        </div>
    </form>
</div>