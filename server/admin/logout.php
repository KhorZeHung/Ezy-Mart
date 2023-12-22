<?php
setcookie("admin_id", 1, time() - 60*60*24, "/");

echo "logout successful";