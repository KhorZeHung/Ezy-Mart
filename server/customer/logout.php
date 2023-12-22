<?php
session_start();

setcookie("user_id", 1, time() - 60*60*24, "/");

session_unset(); // removes session variables
session_destroy(); // destroys the session

echo "logout successful";