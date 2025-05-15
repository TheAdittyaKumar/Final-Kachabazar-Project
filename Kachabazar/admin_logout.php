<?php
session_start(); //Start session
session_unset(); // its like emptying a box but we arent throwing it away. The box is session.
session_destroy(); //Here we BREAK the box.
header("Location: admin_login.php"); //redicter after box no more
exit();
?>
