<?php
require "./component/header.php";
if(isset($_COOKIE['admin_id'])){
    header("Location: ./contactUs.php");
    die();
}
?>
<div class="loginBody center">
    <form class="loginForm center" method="post">
        <h2>log-in</h2>
        <input type="text" name="loginEmail" placeholder="E-Mail" value = "admin@gmail.com">
        <input type="password" name="loginPassword" placeholder="Password" value ="123" />
        <div class="center">
            <div class="loginBtn center" id="loginBtn">
                login
            </div>
        </div>
    </form>
</div>
<?php
require "./component/footer.php";
?>