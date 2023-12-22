<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../assets/admin/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://kit.fontawesome.com/9cf4c83978.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/admin/script.js" defer></script>
</head>

<body>
    <div <?php if(isset($_COOKIE['admin_id'])){echo 'class="contentBody"';}?>>
    <div class="scrollToTopBtn">
        <span class="material-symbols-outlined">
            arrow_upward
        </span>
    </div>
    <div class="center">
        <div id="floatAlert">
            <i class="fa fa-solid fa-circle-check success"></i>
            <p></p>
        </div>
    </div>
    </div>