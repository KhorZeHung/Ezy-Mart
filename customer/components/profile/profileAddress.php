<div id="profileAddress">
    <div class="addressBody">
        <div class="listOfAddresses">
            <?php
                if(isset($addressInfo)){
                    foreach($addressInfo as $address){
                        echo '
                        <div class="listOfAddress" id = '.$address['address_id'].'>
                            <div>
                                <b class="address_name">'.$address['address_name'].'</b>
                                <p class="address">'.$address['address'].'</p>
                            </div>
                            <div>
                                <p class="editLink">Edit</p>
                                <p class="deleteLink">Delete</p>
                            </div>
                        </div>';
                    }
                } else{
                    echo "<div class = 'center' style = 'height:100px;'>add your first address here</div>";
                }
            ?>
        </div>
        <div class="center">
            <span class="material-symbols-outlined addAddress">
                add_circle
            </span>
        </div>
        <form class="addAddressForm" id="addAddressForm">
            <span class="material-symbols-outlined closeForm" id="closeAddAddressForm">
                close
            </span>
            <label for="address_name">Address Name</label>
            <input type="text" name="address_name" id="address_name">
            <label for="address">Address</label>
            <input type="text" name="address" id="address">
            <input type="hidden" name="address_id" id="address_id">
            <input type="hidden" name="addressSubmitType" id="addressSubmitType">
            <div id = "addressSubmit">submit</div>
        </form>
    </div>
</div>