<?php
require "./component/header.php";
require "./component/sidebar.php";
require "../server/function.php";

if(!isset($_COOKIE['admin_id'])){
    header("Location: ./login.php");
    die();
}else{
    include_once("../server/dataConnect.php");
    $selectQuery = "SELECT * FROM contact;";
    $result = $conn->query($selectQuery);
    $checkedRows = array();
    $uncheckRows = array();

    if($result->num_rows > 0){
        while($data = $result->fetch_assoc()){
            if($data['admin_id'] != null){
                $checkedRows[] = $data;
            }else{
                $uncheckRows[] = $data;
            }
        }
    }
}
?>
<div class="contentBody">
    <div class="contentSubBody">
        <h3>new incoming message</h3>
        <?php if(count($uncheckRows) > 0){
            ?>
            <p class = "reminder">* double click to select</p>
            <table class = 'contactTable'>
                <thead>
                    <th>
                        name
                    </th>
                    <th>
                        phone number
                    </th>
                    <th>
                        title
                    </th>
                    <th>
                        message
                    </th>
                </thead>
                <tbody>
                    <?php
                        foreach($uncheckRows as $uncheckRow){
                            echo "<tr id = ".$uncheckRow['contact_id'].">
                            <td>
                                ".cutString($uncheckRow['contact_name'], 15)."
                            </td>
                            <td>
                                ".$uncheckRow['contact_phone']."
                            </td>
                            <td>
                                ".cutString($uncheckRow['contact_subject'], 25)."
                            </td>
                            <td>
                                ".cutString($uncheckRow['contact_message'], 40)."
                            </td>
                        </tr>";
                        }
                    ?>
                </tbody>
            </table>
            
        <?php } else{echo "<p class = 'center' style = 'height:100px;'>no new message</p>";}?>
        
    </div>
    <div class="contentSubBody">
        <h3>history</h3>
        <div class="searchBar">
            <input type="text" placeholder="title, name, email" class = "contactSearchBar">
            <span class="material-symbols-outlined">
                search
            </span>
            <div class="searchResult" id = "contactSearchResult">
            </div>
        </div>
    <?php if(count($checkedRows) > 0){
        ?>
            <p class = "reminder">* double click to select</p>
            <table class = 'contactTable'>
                <thead>
                    <th>
                        name
                    </th>
                    <th>
                        date
                    </th>
                    <th>
                        title
                    </th>
                    <th>
                        message
                    </th>
                </thead>
                <tbody>
                    <?php
                        foreach($checkedRows as $checkedRow){
                            echo "<tr  id = ".$checkedRow['contact_id'].">
                            <td>
                                ".$checkedRow['contact_name']."
                            </td>
                            <td>
                                ".$checkedRow['contact_phone']."
                            </td>
                            <td>
                                ".$checkedRow['contact_subject']."
                            </td>
                            <td>
                            ".$checkedRow['contact_message']."
                            </td>
                        </tr>";
                        }
                    ?>
                </tbody>
            </table>
            
        <?php }
        else{echo "<p class = 'center' style = 'height:100px;'>no new message</p>";} ?>
    </div>
</div>
<?php
require "./component/footer.php";
?>