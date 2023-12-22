<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ez-mart</title>
    <link rel="stylesheet" href="../assets/customer/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://kit.fontawesome.com/9cf4c83978.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/customer/script.js"></script>
</head>

<body>
    <div class="scrollToTopBtn">
        <span class="material-symbols-outlined">
            arrow_upward
        </span>
    </div>
    <div class="center">
        <div id="floatAlert">
        </div>
    </div>
    <div class="headerSec">
        <div class="headerTop">
            <div class="logoCrop">
                <img src="../assets/image/logoWithMart.jpeg" class="logo" alt="logo">
            </div>

            <div class="searchBar">
                <input type="text" id="searchInput" placeholder="Search...">
                <span class="material-symbols-outlined" id="searchIcon">search</span>
                <div class="searchResult">
                </div>
            </div>

            <div class="userInteraction">
                <span class="material-symbols-outlined openForm">
                    account_circle
                    <?php if(isset($_COOKIE['user_id'])) {echo "<span></span>";} ?>
                </span>
                <span class="material-symbols-outlined" id="shoppingCart">
                    shopping_cart
                    <?php if(isset($_SESSION['cart']) && $_SESSION['cart'] != 0) {echo '<span id = "cartQuantity">'.$_SESSION['cart'] .'</span>';}?>
                </span>
            </div>

        </div>
        <div class="headerBottom">
            <div class="listOfCategories">
                <p class="headerCateogries <?php if(isset($_GET['cat']) && $_GET['cat'] == "All product") {echo "selectedCat";}?>">All product</p>
                <p class="headerCateogries <?php if(isset($_GET['cat']) && $_GET['cat'] == "Beverages") {echo "selectedCat";}?>">Beverages</p>
                <p class="headerCateogries <?php if(isset($_GET['cat']) && $_GET['cat'] == "Convenience foods") {echo "selectedCat";}?>">Convenience foods</p>
                <p class="headerCateogries <?php if(isset($_GET['cat']) && $_GET['cat'] == "Snacks") {echo "selectedCat";}?>">Snacks</p>
                <p class="headerCateogries <?php if(isset($_GET['cat']) && $_GET['cat'] == "Household Supplies") {echo "selectedCat";}?>">Household Supplies</p>
                <p class="headerCateogries <?php if(isset($_GET['cat']) && $_GET['cat'] == "Frozen foods") {echo "selectedCat";}?>">Frozen foods</p>
            </div>
        </div>
    </div>