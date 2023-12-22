<?php
require "./component/header.php";
require "./component/sidebar.php";
require "../server/dataConnect.php";

$selectContentQuery = "SELECT * FROM slider_content;";
$html = '';
$contentUsHtml = '';

$stmt1 = $conn->prepare($selectContentQuery);
$stmt1->execute();

$result1 = $stmt1->get_result();


while ($data = $result1->fetch_assoc()) {
    $html .= '<div class="contentSliderImg center" id = "'.$data["contentID"].'">
    <span class="material-symbols-outlined delete">
        cancel
    </span>
    <img src="../assets/image/' . $data["contentLocation"] . '" alt="shoes">
    </div>';

    $contentUsHtml .= '
    <img src="../assets/image/' . $data["contentLocation"] . '" alt="Image" class="slider-image">';
}
?>
<div class="contentBody">
    <div class="contentSubBody">
        <h3>Slider content</h3>
        <p class="reminder">* only press update button if sequence of image is changed</p>
        <div class="contentSliderImgSec">
            <?php echo $html;?>
            <label class="custom-file-upload center">
                <input type="file" id="sliderContentFiles" name="sliderContentFiles[]" multiple>
                <span class="material-symbols-outlined addPhoto center green">
                    add_circle
                </span>
            </label>
        </div>
        <div class = "center">
            <div class="classicBtn updateBtn spinnerBtn" id = "updateContentSliderBtn" value = "update content" disabled>update content</div>
        </div>
    </div>

    <div class="contentSubBody">
        <h3>preview</h3>
        <div class="previewBody">
            <div class="slider">
                
                <a class="prev">&#10094;</a>
                <a class="next">&#10095;</a>
                
                <div class="slideAuto">
                </div>
                <?php echo $contentUsHtml;?>
            </div>
        </div>
    </div>
</div>

<div class="uploadStatus">
</div>
<?php
require "./component/footer.php";
?>