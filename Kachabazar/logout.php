<?php
session_start();
// role is not set dont give me a error please
$role = $_SESSION['role'] ?? null;
session_unset(); //remove all session variables please.
session_destroy();
// Redirect based on saved role
if ($role === 'admin') {
    header("Location: admin_login.php");
} else {
    header("Location: login.php"); // default for customers/sellers
}
exit();
