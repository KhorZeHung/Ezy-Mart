<div id="loginModal">
    <span class="material-symbols-outlined closeForm" id="closeLoginSignUpForm">
        close
    </span>
    <div id="loginSignUpForm" class="loginFormWidth">
        <div class="formHeader">
            <p id="loginTitle" class="formTitle">Log-in</p>
            <p id="signupTitle" class="formTitle">Sign-up</p>
        </div>
        <div class="formBody">
            <form class="loginFormBody">
                <label for="loginEmail">E-mail</label>
                <input type="text" name="loginEmail" id="loginEmail" placeholder="E-mail">
                <label for="loginPassword">Password</label>
                <input type="password" name="loginPassword" id="loginPassword" placeholder="Password">
                <a href = "./password_manage_center.php?progress=0" id="forgotPassword">forgot password?</a>
                <div class="center">
                    <div class="loginSignUpBtn spinnerBtn" id="loginBtn">
                        login
                    </div>
                </div>
            </form>
            <form class="signUpFormBody">
                <div class="signUpFormSec">
                    <div>
                        <label for="signUpName">Name</label>
                        <input type="text" name="signUpName" id="signUpName" placeholder="Name">
                        <label for="signUpEmail">E-mail</label>
                        <input type="text" name="signUpEmail" id="signUpEmail" placeholder="E-mail">
                        <label for="signUpPNum">Phone Number</label>
                        <input type="text" name="signUpPNum" id="signUpPNum" placeholder="Phone Number">
                    </div>
                    <div>
                        <label for="signUpDAddress">Default Address</label>
                        <input type="text" name="signUpDAddress" id="signUpDAddress" placeholder="Default Address (optional)">
                        <label for="signUpPassword">Password <span class = reminder> * atleast 8 character</span></label>
                        <input type="password" name="signUpPassword" id="signUpPassword" placeholder="Password">
                        <label for="signUpRPassword">Repeat Password</label>
                        <input type="password" name="signUpRPassword" id="signUpRPassword" placeholder="Password">
                    </div>
                </div>
                <div class="center">
                    <div class="loginSignUpBtn spinnerBtn" id="signUpBtn">
                        sign-up
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>